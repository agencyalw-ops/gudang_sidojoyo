@extends('layouts.app')

@section('content')
<style>
    .pos-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }

    .product-card {
        background: #334155;
        padding: 1rem;
        border-radius: 0.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #334155;
    }

    .qty-btn {
        background: #475569;
        color: white;
        border: none;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        cursor: pointer;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        color: white;
        font-weight: bold;
    }

    .success {
        background: #22c55e;
    }

    .cancelled {
        background: #ef4444;
    }
</style>

<div class="pos-grid">

    {{-- PRODUCTS --}}
    <div class="products-section">
        <div class="card">
            <h3 style="margin-top: 0;">📦 Daftar Produk</h3>

            <div class="product-grid">
                @foreach($products as $p)
                <div class="product-card">
                    <div>
                        <div style="font-weight: bold;">{{ $p->name }}</div>
                        <div style="color: var(--success); font-size: 0.875rem;">
                            Rp {{ number_format($p->price) }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            Stok: {{ $p->stock }}
                        </div>
                    </div>

                    <form method="POST" action="/kasir/cart/add/{{ $p->id }}" style="margin-top: 0.75rem;">
                        @csrf
                        <button class="btn btn-primary" style="width: 100%; font-size: 0.75rem;">
                            Tambah
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- CART --}}
    <div class="cart-section">
        <div class="card">
            <h3 style="margin-top: 0;">🛒 Keranjang</h3>

            @php $total = 0; @endphp

            <div style="max-height: 400px; overflow-y: auto;">
                @forelse($cart as $id => $c)

                    @php $total += $c['price'] * $c['qty']; @endphp

                    <div class="cart-item">
                        <div>
                            <div style="font-weight: 500;">{{ $c['name'] }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                Rp {{ number_format($c['price']) }}
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <form method="POST" action="/kasir/cart/min/{{ $id }}">
                                @csrf
                                <button class="qty-btn">-</button>
                            </form>

                            <span>{{ $c['qty'] }}</span>

                            <form method="POST" action="/kasir/cart/add/{{ $id }}">
                                @csrf
                                <button class="qty-btn">+</button>
                            </form>
                        </div>
                    </div>

                @empty
                    <p style="text-align:center;color:var(--text-muted);">
                        Keranjang kosong
                    </p>
                @endforelse
            </div>

            @if($total > 0)
            <div style="margin-top: 1.5rem; border-top: 2px solid #334155; padding-top: 1rem;">

                <div style="display:flex;justify-content:space-between;font-size:1.25rem;font-weight:bold;margin-bottom:1rem;">
                    <span>Total:</span>
                    <span style="color:var(--success);">
                        Rp {{ number_format($total) }}
                    </span>
                </div>

                <form method="POST" action="/kasir/checkout">
                    @csrf

<div class="form-group">
	                        <label>Bayar (Tunai)</label>
	                        <div style="display: flex; align-items: center; gap: 0.5rem;">
	                            <span style="color: var(--text-muted);">Rp</span>
	                            <input type="number" name="money" class="form-control" placeholder="Jumlah uang" required style="flex: 1;">
	                        </div>
	                    </div>

                    <button class="btn btn-success" style="width:100%;margin-bottom:0.5rem;">
                        Proses Checkout
                    </button>
                </form>

                <form method="POST" action="/kasir/cart/clear">
                    @csrf
                    <button class="btn btn-danger" style="width:100%;font-size:0.75rem;">
                        Kosongkan Keranjang
                    </button>
                </form>

            </div>
            @endif
        </div>
    </div>
</div>

{{-- TRANSAKSI TERAKHIR --}}
<div class="card" style="margin-top: 1.5rem;">
    <h3>🕒 Transaksi Terakhir</h3>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Kembali</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td>{{ $t->invoice }}</td>

                    <td style="color:var(--success);font-weight:bold;">
                        Rp {{ number_format($t->total) }}
                    </td>

                    <td>Rp {{ number_format($t->money) }}</td>

                    <td>Rp {{ number_format($t->change_money) }}</td>

                    {{-- STATUS ONLY 2 STATE --}}
                    <td>
                        @if($t->status === 'cancelled')
                            <span class="status-badge cancelled">Cancelled</span>
                        @else
                            <span class="status-badge success">Success</span>
                        @endif
                    </td>

                    <td style="font-size:0.875rem;color:var(--text-muted);">
                        {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--text-muted);">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection