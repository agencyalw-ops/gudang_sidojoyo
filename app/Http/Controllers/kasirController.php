<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $products = DB::table('products')->get();

        $cart = session('cart_' . session('user_id'), []);

        $transactions = DB::table('transactions')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('kasir.dashboard', compact('products', 'cart', 'transactions'));
    }

    public function addToCart($id)
    {
        $cartKey = 'cart_' . session('user_id');

        $product = DB::table('products')->where('id', $id)->first();

        if (!$product || $product->stock <= 0) {
            return back()->with('error', 'Stok habis');
        }

        $cart = session()->get($cartKey, []);

        $price = (int) $product->price;

        if (isset($cart[$id])) {
            if ($cart[$id]['qty'] >= $product->stock) {
                return back()->with('error', 'Stok tidak cukup');
            }
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
            $total += (int)$item['price'] * (int)$item['qty'];
        }

        if ($money < $total) {
            return back()->with('error', 'Uang tidak cukup');
        }

        try {
            DB::beginTransaction();

            $invoice = 'INV-' . time();
            $change = max(0, $money - $total);

            // INSERT TRANSACTION
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
                    'price' => (int)$item['price'],
                    'subtotal' => (int)$item['price'] * (int)$item['qty']
                ]);

                DB::table('products')
                    ->where('id', $productId)
                    ->decrement('stock', $item['qty']);
            }

            DB::commit();

            session()->forget($cartKey);

            return redirect('/kasir')->with('success', 'Transaksi berhasil');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}