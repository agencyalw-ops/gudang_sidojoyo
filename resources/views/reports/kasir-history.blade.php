@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="margin-top: 0;">📋 Riwayat Transaksi Kasir</h2>

    <div class="card" style="margin-bottom: 1.5rem;">
        <h4 style="margin-top: 0; margin-bottom: 1rem;">Filter</h4>

        <form method="GET" action="/reports/kasir-history"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">

            <div class="form-group" style="margin-bottom: 0;">
                <label>Kasir</label>
                <select name="kasir_name" class="form-control">
                    <option value="">-- Semua Kasir --</option>
                    @foreach($cashiers as $cashier)
                        <option value="{{ $cashier }}" @if(request('kasir_name') == $cashier) selected @endif>
                            {{ $cashier }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Filter</button>
                <a href="/reports/kasir-history" class="btn btn-primary" style="flex: 1; text-decoration: none;">Reset</a>
            </div>
        </form>
    </div>

    @if(request('kasir_name'))
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            @php
                $filteredTransactions = $transactions->where('kasir_name', request('kasir_name'));
                $totalSales = $filteredTransactions->sum('total');
                $totalCount = $filteredTransactions->count();
                $avgSales = $totalCount > 0 ? round($totalSales / $totalCount) : 0;
            @endphp

            <div class="card" style="margin-bottom: 0;">
                <div style="color: var(--text-muted); font-size: 0.875rem;">Total Transaksi</div>
                <div style="font-size: 2rem; font-weight: bold;">{{ $totalCount }}</div>
            </div>

            <div class="card" style="margin-bottom: 0;">
                <div style="color: var(--text-muted); font-size: 0.875rem;">Total Penjualan</div>
                <div style="font-size: 2rem; font-weight: bold; color: var(--success);">
                    Rp {{ number_format($totalSales) }}
                </div>
            </div>

            <div class="card" style="margin-bottom: 0;">
                <div style="color: var(--text-muted); font-size: 0.875rem;">Rata-rata Transaksi</div>
                <div style="font-size: 2rem; font-weight: bold;">
                    Rp {{ number_format($avgSales) }}
                </div>
            </div>
        </div>
    @endif
</div>

<div class="card">
    <h3 style="margin-top: 0; margin-bottom: 1.5rem;">📊 Daftar Transaksi</h3>

    <div style="overflow-x: auto;">
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
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $t)
                <tr style="{{ $t->status === 'cancelled' ? 'opacity:0.5;' : '' }}">

                    <td>
                        <span class="badge" style="background: #334155;">
                            {{ $t->invoice }}
                        </span>
                    </td>

                    <td>{{ $t->kasir_name }}</td>

                    <td>
                        @if($t->status === 'cancelled')
                            <span class="badge" style="background: red;">Cancelled</span>
                        @else
                            <span class="badge" style="background: green;">Completed</span>
                        @endif
                    </td>

                    <td style="font-weight: bold; color: var(--success);">
                        Rp {{ number_format($t->total) }}
                    </td>

                    <td>
                        @foreach($t->items as $item)
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                {{ $item->name }} (x{{ $item->qty }})
                            </div>
                        @endforeach
                    </td>

                    <td>Rp {{ number_format($t->money) }}</td>
                    <td>Rp {{ number_format($t->change_money) }}</td>

                    <td style="font-size: 0.875rem; color: var(--text-muted);">
                        {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}
                    </td>

                    <td>
                        @if($t->status !== 'cancelled')
                            <form method="POST" action="/transaction/{{ $t->id }}/cancel">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Cancel transaksi ini? Stock akan dikembalikan')"
                                    style="background:red;color:white;border:none;padding:5px 10px;border-radius:5px;cursor:pointer;">
                                    Cancel
                                </button>
                            </form>
                        @else
                            <small style="color:red;">✔ Cancelled</small>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--text-muted);">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>
@endsection