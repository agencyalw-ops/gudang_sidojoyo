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
        min-height: 100px;
        resize: vertical;
    }

    .full {
        grid-column: span 3;
    }

    .half {
        grid-column: span 2;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-muted);
        font-size: 0.875rem;
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

        .full, .half, .actions {
            grid-column: span 1;
        }
    }
</style>

<div class="card form-card">
    <h2 style="margin-top: 0;">✏️ Edit Product</h2>

    @if($errors->any())
        <div style="margin-bottom: 1rem;">
            @foreach($errors->all() as $error)
                <div style="color:red; font-size:0.875rem;">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/admin/products/{{ $product->id }}">
        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}">
            </div>

            <div class="form-group">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" value="{{ $product->sku }}">
            </div>

            <div class="form-group">
                <label>Harga per Pcs (Rp)</label>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="color: var(--text-muted);">Rp</span>
                    <input type="number" name="price" class="form-control" value="{{ $product->price }}" style="flex: 1;">
                </div>
            </div>

            <div class="form-group">
                <label>Stok (Pcs)</label>
                <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" name="is_active" {{ $product->is_active ? 'checked' : '' }}>
                <label style="margin:0;">Active Product</label>
            </div>

            <div class="form-group full">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control">{{ $product->description }}</textarea>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="/admin/products" class="btn btn-secondary">Kembali</a>
            </div>

        </div>
    </form>
</div>
@endsection