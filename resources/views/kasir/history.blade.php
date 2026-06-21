@extends('layouts.kasir-layout', ['title' => 'History Transaksi'])

@section('content')
<style>
    .history-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .filters-section {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    }

    .filters-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group label {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.85rem;
    }

    .filter-input {
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.9rem;
        color: #0f172a;
        background: white;
    }

    .filter-input:focus {
        outline: none;
        border-color: #0f172a;
        box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
    }

    .btn-filter {
        padding: 0.75rem 1.5rem;
        background: #0f172a;
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-filter:hover {
        background: #1e293b;
        transform: translateY(-1px);
    }

    .btn-reset {
        padding: 0.75rem 1.5rem;
        background: #e5e7eb;
        color: #0f172a;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-reset:hover {
        background: #d1d5db;
    }

    .transactions-table-container {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        overflow-x: auto;
    }

    .transactions-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .transactions-table thead {
        background: #f3f4f6;
    }

    .transactions-table th {
        text-align: left;
        padding: 1rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e5e7eb;
    }

    .transactions-table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        color: #0f172a;
    }

    .transactions-table tbody tr:hover {
        background: #f9fafb;
    }

    .status-badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.success {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .amount-success {
        color: #22c55e;
        font-weight: bold;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
    }

    .pagination a, .pagination span {
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        color: #0f172a;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: #f3f4f6;
    }

    .pagination .active {
        background: #0f172a;
        color: white;
        border-color: #0f172a;
    }

    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        padding: 1rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        text-align: center;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
    }

    .stat-value.success {
        color: #22c55e;
    }

    .stat-value.danger {
        color: #ef4444;
    }
</style>

<div class="history-container">
    {{-- HEADER --}}
    <div style="margin-bottom: 2rem;">
        <h2 style="color: #0f172a; font-size: 1.875rem; margin-bottom: 0.5rem;">📊 History Transaksi</h2>
        <p style="color: #6b7280; font-size: 0.95rem;">Riwayat semua transaksi kasir Anda</p>
    </div>

    {{-- STATS --}}
    <div class="stats-section">
        <div class="stat-card">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $total_count }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Transaksi Sukses</div>
            <div class="stat-value success">{{ $success_count }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Dibatalkan</div>
            <div class="stat-value danger">{{ $cancelled_count }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Penjualan</div>
            <div class="stat-value success">Rp {{ number_format($total_sales) }}</div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="filters-section">
        <form method="GET" action="/kasir/history" style="margin: 0;">
            <div class="filters-row">
                <div class="filter-group">
                    <label for="status-filter">Status</label>
                    <select name="status" id="status-filter" class="filter-input">
                        <option value="">Semua Status</option>
                        <option value="success" @if(request('status') === 'success') selected @endif>Sukses</option>
                        <option value="cancelled" @if(request('status') === 'cancelled') selected @endif>Dibatalkan</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date-from">Dari Tanggal</label>
                    <input type="date" name="date_from" id="date-from" class="filter-input" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group">
                    <label for="date-to">Ke Tanggal</label>
                    <input type="date" name="date_to" id="date-to" class="filter-input" value="{{ request('date_to') }}">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn-filter">🔍 Filter</button>
                    <a href="/kasir/history" class="btn-reset" style="text-decoration: none; text-align: center;">↺ Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- TRANSACTIONS TABLE --}}
    <div class="transactions-table-container">
        @if($transactions->count() > 0)
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Kembalian</th>
                        <th>Status</th>
                        <th>Tanggal & Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $t)
                    <tr>
                        <td><strong>{{ $t->invoice }}</strong></td>
                        <td class="amount-success">Rp {{ number_format($t->total) }}</td>
                        <td>Rp {{ number_format($t->money) }}</td>
                        <td>Rp {{ number_format($t->change_money) }}</td>
                        <td>
                            @if($t->status === 'cancelled')
                                <span class="status-badge cancelled">❌ Dibatalkan</span>
                            @else
                                <span class="status-badge success">✓ Sukses</span>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; color: #6b7280;">
                            {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i:s') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- PAGINATION --}}
            @if($transactions->hasPages())
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if($transactions->onFirstPage())
                    <span style="opacity: 0.5; cursor: not-allowed;">← Sebelumnya</span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}">← Sebelumnya</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    @if($page == $transactions->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}">Selanjutnya →</a>
                @else
                    <span style="opacity: 0.5; cursor: not-allowed;">Selanjutnya →</span>
                @endif
            </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p>Tidak ada transaksi yang ditemukan</p>
            </div>
        @endif
    </div>
</div>

@endsection
