@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">📜 History Transaksi Kasir</h2>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Kasir</th>
                    <th>Total</th>
                    <th>Detail Barang</th>
                    <th>Bayar</th>
                    <th>Kembalian</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
                <tr>
                    <td><span class="badge" style="background: #334155;">{{ $t->invoice }}</span></td>
                    <td>{{ $t->kasir_name }}</td>
                    <td style="font-weight: bold; color: var(--success);">Rp {{ number_format($t->total) }}</td>
                    <td>
                        @foreach($t->items as $item)
                            <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 2px;">
                                {{ $item->name }} ({{ $item->qty }}) - Rp {{ number_format($item->subtotal) }}
                            </div>
                        @endforeach
                    </td>
                    <td>Rp {{ number_format($t->money) }}</td>
                    <td>Rp {{ number_format($t->change_money) }}</td>
                    <td style="font-size: 0.875rem; color: var(--text-muted);">{{ $t->created_at }}</td>
                    <td>
                        <form method="POST" action="/admin/transactions/delete/{{ $t->id }}" onsubmit="return confirm('Hapus transaksi dan kembalikan stok?')">
                            @csrf
                            <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
