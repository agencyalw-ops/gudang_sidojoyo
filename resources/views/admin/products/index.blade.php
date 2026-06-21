@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">📦 Kelola Produk</h2>
        <a href="/admin/products/create" class="btn btn-primary">+ Tambah Produk</a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>SKU</th>
                    <th>Harga</th>
                    <th>Pcs</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td style="font-weight: 500;">{{ $p->name }}</td>
                    <td><span class="badge" style="background: #334155;">{{ $p->sku }}</span></td>
                    <td style="color: var(--success); font-weight: bold;">Rp {{ number_format($p->price) }} /pcs</td>
                    <td>{{ $p->stock }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="/admin/products/{{ $p->id }}/edit" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            <form method="POST" action="/admin/products/{{ $p->id }}" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
