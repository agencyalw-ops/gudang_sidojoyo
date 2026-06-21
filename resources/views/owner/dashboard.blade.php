@extends('layouts.app', ['title' => '📊 Dashboard - Owner'])

@section('content')

<style>
    .dashboard-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:1.5rem;
        gap:1rem;
    }

    .dashboard-header h2{
        margin:0;
        font-size:1.5rem;
        color:#0f172a;
    }

    .dashboard-subtitle{
        margin-top:4px;
        color:#64748b;
        font-size:.9rem;
    }

    .stats-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:1rem;
        margin-bottom:1.5rem;
    }

    .stat-card{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:.75rem;
        padding:1.25rem;
        transition:.2s;
    }

    .stat-card:hover{
        transform:translateY(-2px);
        box-shadow:0 6px 18px rgba(0,0,0,.05);
    }

    .stat-label{
        color:#64748b;
        font-size:.85rem;
        margin-bottom:.5rem;
    }

    .stat-value{
        font-size:1.9rem;
        font-weight:700;
        color:#0f172a;
    }

    .stat-success{
        color:#16a34a;
    }

    table{
        width:100%;
        border-collapse:separate;
        border-spacing:0;
        background:white;
        border:1px solid #e5e7eb;
        border-radius:.75rem;
        overflow:hidden;
    }

    thead{
        background:#f8fafc;
    }

    th{
        padding:.9rem;
        text-align:left;
        font-size:.75rem;
        text-transform:uppercase;
        color:#64748b;
        border-bottom:1px solid #e5e7eb;
    }

    td{
        padding:.9rem;
        border-bottom:1px solid #f1f5f9;
        vertical-align:top;
        font-size:.9rem;
        color:#0f172a;
    }

    tr:hover{
        background:#f9fafb;
    }

    .badge{
        display:inline-block;
        padding:.25rem .65rem;
        border-radius:999px;
        font-size:.75rem;
        font-weight:600;
    }

    .badge-invoice{
        background:#eef2ff;
        color:#3730a3;
    }

    .badge-success{
        background:#dcfce7;
        color:#166534;
    }

    .badge-danger{
        background:#fee2e2;
        color:#991b1b;
    }

    .item-box{
        font-size:.8rem;
        color:#475569;
        margin-bottom:2px;
    }

    .total{
        font-weight:700;
        color:#16a34a;
    }

    .muted{
        color:#64748b;
        font-size:.85rem;
    }

    .btn-danger{
        background:#ef4444;
        color:white;
        border:none;
        padding:.4rem .75rem;
        border-radius:.4rem;
        cursor:pointer;
        font-size:.75rem;
        transition:.2s;
    }

    .btn-danger:hover{
        background:#dc2626;
    }

    .section-card{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:.75rem;
        padding:1.25rem;
    }

    .section-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:1rem;
    }

    .section-header h3{
        margin:0;
        font-size:1.1rem;
    }

    @media(max-width:768px){
        .dashboard-header{
            flex-direction:column;
            align-items:flex-start;
        }
    }
</style>

<div class="dashboard-header">


<div>
    <h2>📊 Dashboard Owner</h2>

</div>



</div>

<div class="stats-grid">


<div class="stat-card">
    <div class="stat-label">Total Transaksi</div>
    <div class="stat-value">
        {{ number_format($transactions->count()) }}
    </div>
</div>

<div class="stat-card">
    <div class="stat-label">Total Pendapatan</div>
    <div class="stat-value stat-success">
        Rp {{ number_format($transactions->sum('total')) }}
    </div>
</div>

<div class="stat-card">
    <div class="stat-label">Rata-rata Transaksi</div>
    <div class="stat-value">
        Rp {{ number_format($transactions->avg('total') ?? 0) }}
    </div>
</div>


</div>

<div class="section-card">


<div class="section-header">
    <h3>📜 Riwayat Penjualan</h3>
</div>

<div style="overflow-x:auto;">

    <table>

        <thead>
            <tr>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Status</th>
                <th>Total</th>
                <th>Detail Barang</th>
                <th>Bayar</th>
                <th>Kembalian</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

        @forelse($transactions as $t)

            <tr style="{{ ($t->status ?? 'completed') == 'cancelled' ? 'opacity:.55;' : '' }}">

                <td>
                    <span class="badge badge-invoice">
                        {{ $t->invoice }}
                    </span>
                </td>

                <td>
                    {{ $t->kasir_name }}
                </td>

                <td>
                    @if(($t->status ?? 'completed') == 'cancelled')
                        <span class="badge badge-danger">
                            Cancelled
                        </span>
                    @else
                        <span class="badge badge-success">
                            Success
                        </span>
                    @endif
                </td>

                <td class="total">
                    Rp {{ number_format($t->total) }}
                </td>

                <td>
                    @foreach($t->items as $item)
                        <div class="item-box">
                            {{ $item->name }}
                            (x{{ $item->qty }})
                            • Rp {{ number_format($item->subtotal) }}
                        </div>
                    @endforeach
                </td>

                <td>
                    Rp {{ number_format($t->money) }}
                </td>

                <td>
                    Rp {{ number_format($t->change_money) }}
                </td>

                <td class="muted">
                    {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}
                </td>

                <td>

                    @if(($t->status ?? 'completed') == 'cancelled')

                        <span class="muted">
                            ✔ Cancelled
                        </span>

                    @else

                        <form method="POST"
                              action="{{ route('transaction.cancel', $t->id) }}"
                              onsubmit="return confirm('Cancel transaksi ini? Stok akan dikembalikan.')">

                            @csrf

                            <button type="submit" class="btn-danger">
                                Cancel
                            </button>

                        </form>

                    @endif

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="9"
                    style="text-align:center;padding:2rem;color:#64748b;">
                    📭 Belum ada transaksi
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>


</div>

@endsection
