@extends('layouts.app', ['title' => '➕ Tambah Produk Baru'])

@section('content')

<div class="card" style="max-width:900px;margin:auto;">

    <h2 style="margin-top:0;">➕ Tambah Produk</h2>
    <p style="color:#64748b;font-size:0.9rem;margin-bottom:1.5rem;">
        Isi data produk dengan lengkap sebelum disimpan
    </p>

    {{-- ERROR --}}
    @if($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:1rem;border-radius:8px;margin-bottom:1rem;">
            @foreach($errors->all() as $error)
                <div style="font-size:0.875rem;">• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/admin/products">
        @csrf

        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">

            <div>
                <label style="font-size:0.85rem;color:#64748b;">Nama Produk</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Beras Premium">
            </div>

            <div>
                <label style="font-size:0.85rem;color:#64748b;">SKU</label>
                <input type="text" name="sku" class="form-control" placeholder="Contoh: BR-001">
            </div>

            <div>
                <label style="font-size:0.85rem;color:#64748b;">Harga / Pcs</label>
                <input type="number" name="price" class="form-control" placeholder="0">
            </div>

            <div>
                <label style="font-size:0.85rem;color:#64748b;">Stock</label>
                <input type="number" name="stock" class="form-control" placeholder="0">
            </div>

            <div style="grid-column:span 2;">
                <label style="font-size:0.85rem;color:#64748b;">Deskripsi</label>
                <textarea name="description" class="form-control" placeholder="Deskripsi produk..." style="min-height:120px;"></textarea>
            </div>

        </div>

        {{-- ACTION --}}
        <div style="display:flex;gap:0.5rem;margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">
                💾 Simpan Produk
            </button>

            <a href="/admin/products" class="btn btn-secondary">
                ← Kembali
            </a>
        </div>

    </form>

</div>

@endsection