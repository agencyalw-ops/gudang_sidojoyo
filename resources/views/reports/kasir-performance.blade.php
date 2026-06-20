@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="margin-top: 0;">📈 Laporan Kinerja Kasir Bulanan</h2>
    
    <div class="card" style="margin-bottom: 1.5rem;">
        <h4 style="margin-top: 0; margin-bottom: 1rem;">Pilih Bulan</h4>
        <form method="GET" action="/reports/kasir-performance" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Bulan</label>
                <select name="month" class="form-control">
                    @php
                        $months = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" @if($month == $m) selected @endif>
                            {{ $months[$m] }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label>Tahun</label>
                <select name="year" class="form-control">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" @if($year == $y) selected @endif>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Tampilkan</button>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Total Penjualan</div>
            <div style="font-size: 2rem; font-weight: bold; color: var(--success);">Rp {{ number_format($totalSales) }}</div>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Total Transaksi</div>
            <div style="font-size: 2rem; font-weight: bold;">{{ $totalTransactions }}</div>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Rata-rata Transaksi</div>
            <div style="font-size: 2rem; font-weight: bold;">Rp {{ number_format($averageSales) }}</div>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Jumlah Kasir</div>
            <div style="font-size: 2rem; font-weight: bold;">{{ count($cashierPerformance) }}</div>
        </div>
    </div>
</div>

<div class="card">
    <h3 style="margin-top: 0; margin-bottom: 1.5rem;">👥 Performa Kasir</h3>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Nama Kasir</th>
                    <th>Total Transaksi</th>
                    <th>Total Penjualan</th>
                    <th>Rata-rata Transaksi</th>
                    <th>Total Item</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cashierPerformance as $rank => $performance)
                <tr>
                    <td style="font-weight: bold; color: var(--primary);">#{{ $rank + 1 }}</td>
                    <td style="font-weight: 600;">{{ $performance['kasir_name'] }}</td>
                    <td style="text-align: center;">{{ $performance['total_transactions'] }}</td>
                    <td style="font-weight: bold; color: var(--success);">Rp {{ number_format($performance['total_sales']) }}</td>
                    <td>Rp {{ number_format($performance['average_transaction']) }}</td>
                    <td style="text-align: center;">{{ $performance['total_items'] }} pcs</td>
                    <td>
                        <a href="/reports/kasir-detail/{{ urlencode($performance['kasir_name']) }}?month={{ $month }}&year={{ $year }}" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; text-decoration: none;">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted);">Belum ada data kasir untuk bulan ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
