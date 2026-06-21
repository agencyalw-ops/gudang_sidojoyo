@extends('layouts.app')

@section('content')
<div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <div>
            <h2 style="margin:0;">📦 {{ $product->name }}</h2>
            <small style="color: var(--text-muted);">SKU: {{ $product->sku }} | Stock Saat Ini: {{ $product->stock }} pcs</small>
        </div>

        <a href="/admin/products" class="btn btn-primary">
            ← Kembali
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        @php
            $totalIn = collect($histories)->where('type', 'in')->sum('qty');
            $totalOut = collect($histories)->where('type', 'out')->sum('qty');
            $totalAdjust = collect($histories)->where('type', 'adjust')->sum('qty');
        @endphp

        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Total Masuk</div>
            <div style="font-size: 1.5rem; font-weight: bold; color: #22c55e;">
                {{ $totalIn }} pcs
            </div>
        </div>

        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Total Terjual</div>
            <div style="font-size: 1.5rem; font-weight: bold; color: #ef4444;">
                {{ $totalOut }} pcs
            </div>
        </div>

        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Total Adjustment</div>
            <div style="font-size: 1.5rem; font-weight: bold; color: #f59e0b;">
                {{ $totalAdjust }} pcs
            </div>
        </div>

        <div class="card" style="margin-bottom: 0;">
            <div style="color: var(--text-muted); font-size: 0.875rem;">Stock Sisa</div>
            <div style="font-size: 1.5rem; font-weight: bold;">
                {{ $product->stock }} pcs
            </div>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Tipe</th>
                    <th>Qty</th>
                    <th>Stock Awal</th>
                    <th>Stock Akhir</th>
                    <th>User</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($histories as $h)
                <tr>
                    <td style="font-size: 0.875rem; color: var(--text-muted);">
                        {{ $h->created_at->format('d/m/Y H:i:s') }}
                    </td>

                    <td>
                        @if($h->type == 'in')
                            <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem; font-weight: bold;">
                                📥 Masuk
                            </span>
                        @elseif($h->type == 'out')
                            <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem; font-weight: bold;">
                                📤 Terjual
                            </span>
                        @else
                            <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem; font-weight: bold;">
                                🔧 Adjustment
                            </span>
                        @endif
                    </td>

                    <td style="font-weight: bold;">{{ $h->qty }} pcs</td>
                    <td>{{ $h->before_stock }} pcs</td>
                    <td style="font-weight: bold;">{{ $h->after_stock }} pcs</td>
                    <td>{{ $h->user_name }}</td>
                    <td style="font-size: 0.875rem; color: var(--text-muted);">{{ $h->note }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color: var(--text-muted); padding: 2rem;">
                        📭 Belum ada history untuk produk ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.5rem;">
        {{ $histories->links() }}
    </div>

</div>
@endsection
