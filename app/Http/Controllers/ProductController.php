<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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

        Product::create($request->all());

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

    $product->update([
        'name' => $request->name,
        'sku' => $request->sku,
        'price' => $request->price,
        'stock' => $request->stock,
        'unit' => $request->unit,
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
    
}