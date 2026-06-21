@extends('layouts.app')

@section('content')
<style>
    .form-card {
        max-width: 1000px;
        margin: auto;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .form-control {
        padding: 0.6rem;
        border-radius: 0.375rem;
        border: 1px solid #334155;
        background: #0f172a;
        color: white;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--primary);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .full {
        grid-column: span 3;
    }

    .actions {
        grid-column: span 3;
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .btn {
        padding: 0.6rem 1rem;
        border: none;
        border-radius: 0.375rem;
        cursor: pointer;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-secondary {
        background: #334155;
        color: white;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .full, .actions {
            grid-column: span 1;
        }
    }
</style>

<div class="card form-card">
    <h2 style="margin-top: 0;">➕ Tambah Product</h2>

    @if($errors->any())
        <div style="margin-bottom: 1rem;">
            @foreach($errors->all() as $error)
                <div style="color:red; font-size:0.875rem;">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/admin/products">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>Nama Product</label>
                <input type="text" name="name" class="form-control" placeholder="Nama product">
            </div>

            <div class="form-group">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" placeholder="SKU">
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="unit" class="form-control" placeholder="pcs / box / kg">
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="color: var(--text-muted);">Rp</span>
                    <input type="number" name="price" class="form-control" placeholder="Harga" style="flex: 1;">
                </div>
            </div>

            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" placeholder="Stock">
            </div>

            <div class="form-group full">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control" placeholder="Deskripsi product"></textarea>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="/admin/products" class="btn btn-secondary">Kembali</a>
            </div>

        </div>
    </form>
</div>
@endsection