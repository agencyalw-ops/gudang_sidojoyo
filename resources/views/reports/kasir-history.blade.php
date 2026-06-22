@extends('layouts.app', ['title' => '📋 Riwayat Transaksi Kasir'])

@section('content')

<style>
    .kasir-header {
        margin-bottom: 1.5rem;
    }
    .kasir-header h2 {
        margin: 0;
    }
    .kasir-header p {
        color: #64748b;
        font-size: 0.9rem;
    }

    .filter-card {
        margin-bottom: 1.5rem;
    }
    .filter-card h4 {
        margin: 0 0 1rem 0;
    }
    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }
    .filter-label {
        font-size: 0.85rem;
        color: #64748b;
    }
    .filter-actions {
        display: flex;
        gap: 0.5rem;
        align-items: end;
    }
    .btn-flex {
        flex: 1;
        text-align: center;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .summary-card {
        padding: 1rem;
    }
    .summary-label {
        color: #64748b;
        font-size: 0.85rem;
    }
    .summary-value {
        font-size: 1.6rem;
        font-weight: 700;
    }
    .summary-value.text-success {
        color: #16a34a;
    }

    .table-title {
        margin: 0 0 1rem 0;
    }
    .table-responsive {
        overflow-x: auto;
    }

    .row-cancelled {
        opacity: 0.5;
    }

    .badge {
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 12px;
    }
    .badge-invoice {
        background: #334155;
        color: white;
    }
    .badge-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }
    .badge-completed {
        background: #dcfce7;
        color: #166534;
    }

    .text-total {
        font-weight: 700;
        color: #16a34a;
    }
    .item-line {
        font-size: 0.8rem;
        color: #64748b;
    }
    .text-muted-sm {
        font-size: 0.75rem;
        color: #94a3b8;
    }
    
    /* RESPONSIVE */
    @media(max-width:768px){
        .kasir-header h2{
            font-size:1.1rem;
        }
        
        .filter-form{
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .filter-actions{
            flex-direction: row;
            width: 100%;
        }
        
        .summary-grid{
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        
        .summary-value{
            font-size: 1.25rem;
        }
        
        table{
            font-size: 0.8rem;
        }
        
        th, td{
            padding: 0.6rem;
        }
        
        .badge{
            padding: 2px 6px;
            font-size: 11px;
        }
    }
    
    @media(max-width:480px){
        .summary-grid{
            grid-template-columns: 1fr;
        }
        
        .summary-card{
            padding: 0.75rem;
        }
        
        table{
            font-size: 0.7rem;
        }
        
        th, td{
            padding: 0.4rem;
        }
        
        .item-line{
            font-size: 0.7rem;
        }
    }
        font-size: 0.85rem;
        color: #64748b;
    }
    .btn-sm {
        padding: 4px 10px;
        font-size: 12px;
    }
    .text-cancelled-label {
        color: #dc2626;
        font-size: 12px;
    }
    .empty-state {
        text-align: center;
        color: #94a3b8;
        padding: 2rem;
    }
</style>

<div class="kasir-header">
    <h2>📋 Riwayat Transaksi Kasir</h2>
    <p>Monitoring semua transaksi kasir</p>
</div>

{{-- FILTER --}}
<div class="card filter-card">
    <h4>🔍 Filter Data</h4>

    <form method="GET" action="/reports/kasir-history" class="filter-form">

        <div>
            <label class="filter-label">Kasir</label>
            <select name="kasir_name" class="form-control">
                <option value="">Semua Kasir</option>
                @foreach($cashiers as $cashier)
                    <option value="{{ $cashier }}" @selected(request('kasir_name') == $cashier)>
                        {{ $cashier }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="filter-label">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>

        <div>
            <label class="filter-label">Tanggal Akhir</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary btn-flex">
                Filter
            </button>

            <a href="/reports/kasir-history" class="btn btn-secondary btn-flex">
                Reset
            </a>
        </div>

    </form>
</div>

{{-- SUMMARY --}}
@if(request('kasir_name'))
    @php
        $filteredTransactions = $transactions->where('kasir_name', request('kasir_name'));
        $totalSales = $filteredTransactions->sum('total');
        $totalCount = $filteredTransactions->count();
        $avgSales = $totalCount > 0 ? round($totalSales / $totalCount) : 0;
    @endphp

    <div class="summary-grid">

        <div class="card summary-card">
            <div class="summary-label">Total Transaksi</div>
            <div class="summary-value">
                {{ $totalCount }}
            </div>
        </div>

        <div class="card summary-card">
            <div class="summary-label">Total Penjualan</div>
            <div class="summary-value text-success">
                Rp {{ number_format($totalSales) }}
            </div>
        </div>

        <div class="card summary-card">
            <div class="summary-label">Rata-rata</div>
            <div class="summary-value">
                Rp {{ number_format($avgSales) }}
            </div>
        </div>

    </div>
@endif

{{-- TABLE --}}
<div class="card">
    <h3 class="table-title">📊 Data Transaksi</h3>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Kasir</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Item</th>
                    <th>Bayar</th>
                    <th>Kembali</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $t)
                <tr class="{{ $t->status === 'cancelled' ? 'row-cancelled' : '' }}">

                    <td>
                        <span class="badge badge-invoice">
                            {{ $t->invoice }}
                        </span>
                    </td>

                    <td>{{ $t->kasir_name }}</td>

                    <td>
                        @if($t->status === 'cancelled')
                            <span class="badge badge-cancelled">
                                Cancelled
                            </span>
                        @else
                            <span class="badge badge-completed">
                                Completed
                            </span>
                        @endif
                    </td>

                    <td class="text-total">
                        Rp {{ number_format($t->total) }}
                    </td>

                    <td>
                        @foreach($t->items as $item)
                            <div class="item-line">
                                {{ $item->name }} (x{{ $item->qty }})
                            </div>
                        @endforeach
                    </td>

                    <td>Rp {{ number_format($t->money) }}</td>
                    <td>Rp {{ number_format($t->change_money) }}</td>

                    <td class="text-muted-sm">
                        {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}
                    </td>

                    <td>
                        @if($t->status !== 'cancelled')
                            <form method="POST" action="/transaction/{{ $t->id }}/cancel">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Cancel transaksi ini? Stock akan kembali')"
                                    class="btn btn-danger btn-sm">
                                    Cancel
                                </button>
                            </form>
                        @else
                            <span class="text-cancelled-label">✔ Cancelled</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="9" class="empty-state">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection