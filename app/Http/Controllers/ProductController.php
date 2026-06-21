<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStockHistory;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'sku' => 'required|unique:products',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'stock' => $request->stock, // sudah dianggap pcs
        ]);

        // Track stock history - pcs masuk
        ProductStockHistory::create([
            'product_id' => $product->id,
            'type' => 'in',
            'qty' => $product->stock,
            'before_stock' => 0,
            'after_stock' => $product->stock,
            'note' => 'Initial stock',
            'user_name' => session('name') ?? 'System'
        ]);

        return redirect('/admin/products')->with('success', 'Product berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'sku' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::findOrFail($id);
        $oldStock = $product->stock;
        $newStock = (int)$request->stock;

        // Track stock history if stock changes
        if ($oldStock != $newStock) {
            $type = $newStock > $oldStock ? 'in' : 'out';
            $qty = abs($newStock - $oldStock);

            ProductStockHistory::create([
                'product_id' => $product->id,
                'type' => $type,
                'qty' => $qty,
                'before_stock' => $oldStock,
                'after_stock' => $newStock,
                'note' => 'Manual adjustment',
                'user_name' => session('name') ?? 'System'
            ]);
        }

        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'stock' => $newStock, // tetap pcs
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect('/admin/products')->with('success', 'Product berhasil diupdate');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect('/admin/products')->with('success', 'Product berhasil dihapus');
    }

    /**
     * Show stock history for all products
     */
    public function stockHistory(Request $request)
    {
        $query = ProductStockHistory::with('product')
            ->orderBy('created_at', 'desc');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $histories = $query->paginate(50);
        $products = Product::get();

        return view('admin.products.stock-history', compact('histories', 'products'));
    }

    /**
     * Show stock history for specific product
     */
    public function productStockHistory($id)
    {
        $product = Product::findOrFail($id);
        $histories = ProductStockHistory::where('product_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.products.product-stock-history', compact('product', 'histories'));
    }
}