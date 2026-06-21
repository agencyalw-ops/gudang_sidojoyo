<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductStockHistory;

class KasirController extends Controller
{
    public function index()
    {
        $products = DB::table('products')
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->get();

        $cartKey = 'cart_' . session('user_id');
        $cart = session($cartKey, []);

        // CLEAN CART
        $productIds = $products->pluck('id')->toArray();

        foreach ($cart as $id => $item) {
            if (!in_array($id, $productIds)) {
                unset($cart[$id]);
            }
        }

        session()->put($cartKey, $cart);

        $transactions = DB::table('transactions')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('kasir.dashboard', compact('products', 'cart', 'transactions'));
    }

    public function addToCart($id)
    {
        $cartKey = 'cart_' . session('user_id');

        $product = DB::table('products')
            ->where('id', $id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->first();

        if (!$product || $product->stock <= 0) {
            return back()->with('error', 'Stok habis / produk tidak tersedia');
        }

        $cart = session()->get($cartKey, []);
        $price = (int) $product->price;

        $currentQty = $cart[$id]['qty'] ?? 0;

        if (($currentQty + 1) > $product->stock) {
            return back()->with('error', 'Stok tidak cukup');
        }

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'price' => $price,
                'qty' => 1
            ];
        }

        session()->put($cartKey, $cart);

        return back();
    }

    // =========================
    // FIXED: SET QTY MANUAL
    // =========================
    public function setQty(Request $request, $id)
    {
        $cartKey = 'cart_' . session('user_id');
        $cart = session($cartKey, []);

        $product = DB::table('products')->where('id', $id)->first();

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        // SUPPORT JSON + FORM
        $qty = $request->input('qty');

        if ($request->isJson()) {
            $qty = $request->json('qty');
        }

        $qty = (int) $qty;

        if ($qty < 1) {
            unset($cart[$id]);
            session()->put($cartKey, $cart);

            return response()->json(['message' => 'Item dihapus']);
        }

        if ($qty > $product->stock) {
            return response()->json(['message' => 'Stok tidak cukup'], 422);
        }

        $cart[$id] = [
            'name' => $product->name,
            'price' => (int) $product->price,
            'qty' => $qty
        ];

        session()->put($cartKey, $cart);

        return response()->json([
            'message' => 'OK',
            'qty' => $qty,
            'subtotal' => $qty * (int) $product->price
        ]);
    }

    public function decreaseQty($id)
    {
        $cartKey = 'cart_' . session('user_id');
        $cart = session()->get($cartKey, []);

        if (isset($cart[$id])) {
            $cart[$id]['qty']--;

            if ($cart[$id]['qty'] <= 0) {
                unset($cart[$id]);
            }
        }

        session()->put($cartKey, $cart);

        return back();
    }

    public function removeItem($id)
    {
        $cartKey = 'cart_' . session('user_id');
        $cart = session()->get($cartKey, []);

        unset($cart[$id]);

        session()->put($cartKey, $cart);

        return back();
    }

    public function clearCart()
    {
        session()->forget('cart_' . session('user_id'));
        return back()->with('success', 'Cart dikosongkan');
    }

    public function checkout(Request $request)
    {
        $cartKey = 'cart_' . session('user_id');
        $cart = session($cartKey, []);

        if (empty($cart)) {
            return back()->with('error', 'Cart kosong');
        }

        $money = (int) $request->money;
        $total = 0;

        foreach ($cart as $item) {
            $total += (int) $item['price'] * (int) $item['qty'];
        }

        if ($money < $total) {
            return back()->with('error', 'Uang tidak cukup');
        }

        try {
            DB::beginTransaction();

            $invoice = 'INV-' . time();
            $change = max(0, $money - $total);

            $transaction_id = DB::table('transactions')->insertGetId([
                'invoice' => $invoice,
                'kasir_name' => session('name'),
                'total' => $total,
                'money' => $money,
                'change_money' => $change,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($cart as $productId => $item) {

                DB::table('transaction_items')->insert([
                    'transaction_id' => $transaction_id,
                    'product_id' => $productId,
                    'qty' => $item['qty'],
                    'price' => (int) $item['price'],
                    'subtotal' => (int) $item['price'] * (int) $item['qty']
                ]);

                $product = DB::table('products')->where('id', $productId)->first();

                if ($product) {
                    $beforeStock = (int) $product->stock;
                    $afterStock = $beforeStock - (int) $item['qty'];

                    DB::table('products')
                        ->where('id', $productId)
                        ->decrement('stock', $item['qty']);

                    ProductStockHistory::create([
                        'product_id' => $productId,
                        'type' => 'out',
                        'qty' => (int) $item['qty'],
                        'before_stock' => $beforeStock,
                        'after_stock' => $afterStock,
                        'note' => 'Sold - Invoice: ' . $invoice,
                        'user_name' => session('name') ?? 'Kasir'
                    ]);
                }
            }

            DB::commit();
            session()->forget($cartKey);

            return redirect("/transaction/$transaction_id/receipt");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $query = DB::table('transactions');

        if ($request->filled('status')) {
            $status = $request->status;

            if ($status === 'success') {
                $query->where('status', '!=', 'cancelled');
            } elseif ($status === 'cancelled') {
                $query->where('status', 'cancelled');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        $allTransactions = DB::table('transactions')->get();
        $total_count = $allTransactions->count();
        $success_count = $allTransactions->where('status', '!=', 'cancelled')->count();
        $cancelled_count = $allTransactions->where('status', 'cancelled')->count();
        $total_sales = $allTransactions->where('status', '!=', 'cancelled')->sum('total');

        return view('kasir.history', compact(
            'transactions',
            'total_count',
            'success_count',
            'cancelled_count',
            'total_sales'
        ));
    }

    public function receipt($id)
    {
        $transaction = DB::table('transactions')
            ->where('id', $id)
            ->first();

        $items = DB::table('transaction_items')
            ->where('transaction_id', $id)
            ->get();

        return view('kasir.receipt', compact('transaction', 'items'));
    }
}