@extends('layouts.app', ['title' => '✏️ Edit Produk'])

@section('content')

<div class="card" style="max-width:900px;margin:auto;">

    <h2 style="margin-top:0;">✏️ Edit Produk</h2>
    <p style="color:#64748b;font-size:0.9rem;margin-bottom:1.5rem;">
        Update data produk dengan benar sebelum disimpan
    </p>

    {{-- ERROR --}}
    @if($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:1rem;border-radius:8px;margin-bottom:1rem;">
            @foreach($errors->all() as $error)
                <div style="font-size:0.875rem;">• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/admin/products/{{ $product->id }}">
        @csrf
        @method('PUT')

        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">

            <div>
                <label style="font-size:0.85rem;color:#64748b;">Nama Produk</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}">
            </div>

            <div>
                <label style="font-size:0.85rem;color:#64748b;">SKU</label>
                <input type="text" name="sku" class="form-control" value="{{ $product->sku }}">
            </div>

            <div>
                <label style="font-size:0.85rem;color:#64748b;">Harga / Pcs</label>
                <input type="number" name="price" class="form-control" value="{{ $product->price }}">
            </div>

            <div>
                <label style="font-size:0.85rem;color:#64748b;">Stock</label>
                <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">
            </div>

            <div style="display:flex;align-items:center;gap:0.5rem;">
                <input type="checkbox" name="is_active" {{ $product->is_active ? 'checked' : '' }}>
                <label style="margin:0;color:#64748b;font-size:0.9rem;">
                    Active Product
                </label>
            </div>

            <div style="grid-column:span 2;">
                <label style="font-size:0.85rem;color:#64748b;">Deskripsi</label>
                <textarea name="description" class="form-control" style="min-height:120px;">{{ $product->description }}</textarea>
            </div>

        </div>

        {{-- ACTION --}}
        <div style="display:flex;gap:0.5rem;margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">
                💾 Update Produk
            </button>

            <a href="/admin/products" class="btn btn-secondary">
                ← Kembali
            </a>
        </div>

    </form>

</div>

@endsection