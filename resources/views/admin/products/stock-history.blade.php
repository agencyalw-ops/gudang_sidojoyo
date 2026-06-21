@extends('layouts.app')

@section('content')
<div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h2 style="margin:0;">📜 History Stok Semua Produk</h2>

        <a href="/admin/products" class="btn btn-primary">
            📦 Daftar Produk
        </a>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <h4 style="margin-top: 0; margin-bottom: 1rem;">Filter</h4>
        <form method="GET" action="/admin/products/stock/history" 
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">
            
            <div class="form-group" style="margin-bottom: 0;">
                <label>Produk</label>
                <select name="product_id" class="form-control">
                    <option value="">-- Semua Produk --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" @if(request('product_id') == $product->id) selected @endif>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label>Tipe</label>
                <select name="type" class="form-control">
                    <option value="">-- Semua Tipe --</option>
                    <option value="in" @if(request('type') == 'in') selected @endif>Masuk (In)</option>
                    <option value="out" @if(request('type') == 'out') selected @endif>Terjual/Keluar (Out)</option>
                    <option value="adjust" @if(request('type') == 'adjust') selected @endif>Adjustment</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="/admin/products/stock/history" class="btn btn-secondary" style="text-decoration: none;">Reset</a>
            </div>
        </form>
    </div>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Produk</th>
                    <th>Tipe</th>
                    <th>Qty</th>
                    <th>Stock Awal</th>
                    <th>Stock Akhir</th>
                    <th>User</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($histories as $h)
                <tr>
                    <td style="font-size: 0.875rem; color: var(--text-muted);">
                        {{ $h->created_at->format('d/m/Y H:i:s') }}
                    </td>
                    <td>
                        <a href="/admin/products/{{ $h->product?->id }}/stock-history" style="text-decoration: none; color: var(--primary);">
                            {{ $h->product?->name }}
                        </a>
                    </td>

                    <td>
                        @if($h->type == 'in')
                            <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem; font-weight: bold;">
                                📥 Masuk
                            </span>
                        @elseif($h->type == 'out')
                            <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem; font-weight: bold;">
                                📤 Terjual
                            </span>
                        @else
                            <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem; font-weight: bold;">
                                🔧 Adjustment
                            </span>
                        @endif
                    </td>

                    <td style="font-weight: bold;">{{ $h->qty }} pcs</td>
                    <td>{{ $h->before_stock }} pcs</td>
                    <td style="font-weight: bold;">{{ $h->after_stock }} pcs</td>
                    <td>{{ $h->user_name }}</td>
                    <td style="font-size: 0.875rem; color: var(--text-muted);">{{ $h->note }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; color: var(--text-muted); padding: 2rem;">
                        📭 Belum ada history stok
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.5rem;">
        {{ $histories->links() }}
    </div>

</div>
@endsection