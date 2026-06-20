@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
        <a href="/reports/kasir-performance?month={{ $month }}&year={{ $year }}" class="btn btn-primary" style="text-decoration: none;">← Kembali</a>
        <h2 style="margin: 0;">📊 Detail Kinerja Kasir: {{ $kasirName }}</h2>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Total Transaksi</div>
            <div style="font-size: 2rem; font-weight: bold;">{{ $totalTransactions }}</div>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Total Penjualan</div>
            <div style="font-size: 2rem; font-weight: bold; color: var(--success);">Rp {{ number_format($totalSales) }}</div>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Rata-rata Transaksi</div>
            <div style="font-size: 2rem; font-weight: bold;">Rp {{ number_format($averageTransaction) }}</div>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Total Item Terjual</div>
            <div style="font-size: 2rem; font-weight: bold;">{{ $totalItems }} pcs</div>
        </div>
    </div>
</div>

<div class="card">
    <h3 style="margin-top: 0; margin-bottom: 1.5rem;">📋 Riwayat Transaksi</h3>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Detail Barang</th>
                    <th>Bayar</th>
                    <th>Kembalian</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td><span class="badge" style="background: #334155;">{{ $t->invoice }}</span></td>
                    <td style="font-weight: bold; color: var(--success);">Rp {{ number_format($t->total) }}</td>
                    <td>
                        @foreach($t->items as $item)
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                {{ $item->name }} (x{{ $item->qty }}) = Rp {{ number_format($item->subtotal) }}
                            </div>
                        @endforeach
                    </td>
                    <td>Rp {{ number_format($t->money) }}</td>
                    <td>Rp {{ number_format($t->change_money) }}</td>
                    <td style="font-size: 0.875rem; color: var(--text-muted);">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted);">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
