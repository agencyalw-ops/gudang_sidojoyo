@extends('layouts.app', ['title' => '📜 History Transaksi - Admin'])

@section('content')

<style>
    .history-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:1rem;
    }

    .history-header h2{
        font-size:1.25rem;
        color:#0f172a;
    }

    table{
        width:100%;
        border-collapse:separate;
        border-spacing:0;
        background:white;
        border:1px solid #e5e7eb;
        border-radius:0.75rem;
        overflow:hidden;
    }

    thead{
        background:#f8fafc;
    }

    th{
        padding:0.9rem;
        text-align:left;
        font-size:0.75rem;
        text-transform:uppercase;
        color:#64748b;
        border-bottom:1px solid #e5e7eb;
    }

    td{
        padding:0.9rem;
        border-bottom:1px solid #f1f5f9;
        vertical-align:top;
        font-size:0.9rem;
        color:#0f172a;
    }

    tr:hover{
        background:#f9fafb;
    }

    .badge{
        display:inline-block;
        padding:0.25rem 0.6rem;
        border-radius:999px;
        font-size:0.75rem;
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
        font-size:0.8rem;
        color:#475569;
        margin-bottom:0.2rem;
    }

    .total{
        font-weight:700;
        color:#16a34a;
    }

    .btn-danger{
        background:#ef4444;
        color:white;
        border:none;
        padding:0.35rem 0.6rem;
        border-radius:0.4rem;
        cursor:pointer;
        font-size:0.75rem;
        transition:.2s;
    }

    .btn-danger:hover{
        background:#dc2626;
    }

    .muted{
        color:#64748b;
        font-size:0.85rem;
    }

</style>

<div class="card">

    <div class="history-header">
        <h2>📜 History Transaksi Kasir</h2>
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

                <tr style="{{ ($t->status ?? 'completed') == 'cancelled' ? 'opacity:0.55;' : '' }}">

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
                            <span class="badge badge-danger">Cancelled</span>
                        @else
                            <span class="badge badge-success">Completed</span>
                        @endif
                    </td>

                    <td class="total">
                        Rp {{ number_format($t->total) }}
                    </td>

                    <td>
                        @foreach($t->items as $item)
                            <div class="item-box">
                                {{ $item->name }} (x{{ $item->qty }})
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

                            <span class="muted">Already Cancelled</span>

                        @else

                            <form method="POST"
                                  action="{{ route('transaction.cancel', $t->id) }}"
                                  onsubmit="return confirm('Cancel transaksi ini? Stock akan dikembalikan.')">

                                @csrf

                                <button class="btn-danger">
                                    Cancel
                                </button>

                            </form>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="9" style="text-align:center; padding:2rem; color:#64748b;">
                        Belum ada transaksi
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection