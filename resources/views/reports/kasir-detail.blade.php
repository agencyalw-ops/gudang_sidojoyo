@extends('layouts.app', ['title' => '📊 Detail Kinerja Kasir'])

@section('content')

<div style="margin-bottom:2rem;">

    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
        <a href="/reports/kasir-performance?month={{ $month }}&year={{ $year }}"
           class="btn btn-primary"
           style="text-decoration:none;">
            ← Kembali
        </a>

        <h2 style="margin:0;">
            📊 Detail Kinerja Kasir: {{ $kasirName }}
        </h2>
    </div>

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:1rem;
    ">

        <div class="card" style="margin:0;">
            <div style="font-size:0.8rem;color:var(--text-muted);">Total Transaksi</div>
            <div style="font-size:1.6rem;font-weight:bold;">{{ $totalTransactions }}</div>
        </div>

        <div class="card" style="margin:0;">
            <div style="font-size:0.8rem;color:var(--text-muted);">Total Penjualan</div>
            <div style="font-size:1.6rem;font-weight:bold;color:var(--success);">
                Rp {{ number_format($totalSales) }}
            </div>
        </div>

        <div class="card" style="margin:0;">
            <div style="font-size:0.8rem;color:var(--text-muted);">Rata-rata Transaksi</div>
            <div style="font-size:1.6rem;font-weight:bold;">
                Rp {{ number_format($averageTransaction) }}
            </div>
        </div>

        <div class="card" style="margin:0;">
            <div style="font-size:0.8rem;color:var(--text-muted);">Total Item Terjual</div>
            <div style="font-size:1.6rem;font-weight:bold;">
                {{ $totalItems }} pcs
            </div>
        </div>

    </div>
</div>


<div class="card">

    <h3 style="margin:0 0 1rem 0;">📋 Riwayat Transaksi</h3>

    <div style="overflow-x:auto;">

        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Detail Barang</th>
                    <th>Bayar</th>
                    <th>Kembalian</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>

            <tbody>

            @forelse($transactions as $t)
                <tr>

                    <td>
                        <span class="badge" style="background:#e2e8f0;color:#0f172a;">
                            {{ $t->invoice }}
                        </span>
                    </td>

                    <td style="font-weight:600;color:var(--success);">
                        Rp {{ number_format($t->total) }}
                    </td>

                    <td>
                        @foreach($t->items as $item)
                            <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:2px;">
                                {{ $item->name }} (x{{ $item->qty }}) = Rp {{ number_format($item->subtotal) }}
                            </div>
                        @endforeach
                    </td>

                    <td>Rp {{ number_format($t->money) }}</td>
                    <td>Rp {{ number_format($t->change_money) }}</td>

                    {{-- STATUS --}}
                    <td>
                        @if($t->status === 'cancelled')
                            <span style="background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;">
                                Cancelled
                            </span>
                        @else
                            <span style="background:#dcfce7;color:#166534;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;">
                                Success
                            </span>
                        @endif
                    </td>

                    <td style="font-size:0.85rem;color:var(--text-muted);">
                        {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">
                        Belum ada transaksi
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>

    </div>

</div>

@endsection