@extends('layouts.app', ['title' => '📦 History Stok Produk'])

@section('content')
<div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <div>
            <h2 style="margin:0;">📦 {{ $product->name }}</h2>
            <small style="color:#64748b;">
                SKU: {{ $product->sku }} | Stock Saat Ini: <b>{{ $product->stock }} pcs</b>
            </small>
        </div>

        <a href="/admin/products" class="btn btn-primary">
            ← Kembali
        </a>
    </div>

    {{-- SUMMARY --}}
    @php
        $totalIn = collect($histories)->where('type', 'in')->sum('qty');
        $totalOut = collect($histories)->where('type', 'out')->sum('qty');
        $totalAdjust = collect($histories)->where('type', 'adjust')->sum('qty');
    @endphp

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
        gap:1rem;
        margin-bottom:2rem;
    ">

        <div class="card" style="padding:1rem;">
            <div style="color:#64748b;font-size:0.85rem;">Total Masuk</div>
            <div style="font-size:1.4rem;font-weight:700;color:#16a34a;">
                {{ $totalIn }} pcs
            </div>
        </div>

        <div class="card" style="padding:1rem;">
            <div style="color:#64748b;font-size:0.85rem;">Total Terjual</div>
            <div style="font-size:1.4rem;font-weight:700;color:#dc2626;">
                {{ $totalOut }} pcs
            </div>
        </div>

        <div class="card" style="padding:1rem;">
            <div style="color:#64748b;font-size:0.85rem;">Adjustment</div>
            <div style="font-size:1.4rem;font-weight:700;color:#f59e0b;">
                {{ $totalAdjust }} pcs
            </div>
        </div>

        <div class="card" style="padding:1rem;">
            <div style="color:#64748b;font-size:0.85rem;">Stock Sisa</div>
            <div style="font-size:1.4rem;font-weight:700;">
                {{ $product->stock }} pcs
            </div>
        </div>

    </div>

    {{-- TABLE --}}
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

                    <td style="font-size:0.85rem;color:#64748b;">
                        {{ $h->created_at->format('d/m/Y H:i:s') }}
                    </td>

                    <td>
                        @if($h->type == 'in')
                            <span style="background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;">
                                📥 Masuk
                            </span>
                        @elseif($h->type == 'out')
                            <span style="background:#fee2e2;color:#991b1b;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;">
                                📤 Terjual
                            </span>
                        @else
                            <span style="background:#fef3c7;color:#92400e;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;">
                                🔧 Adjustment
                            </span>
                        @endif
                    </td>

                    <td style="font-weight:600;">{{ $h->qty }} pcs</td>
                    <td>{{ $h->before_stock }} pcs</td>
                    <td style="font-weight:700;">{{ $h->after_stock }} pcs</td>
                    <td>{{ $h->user_name }}</td>
                    <td style="color:#64748b;font-size:0.85rem;">
                        {{ $h->note }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:#94a3b8;padding:2rem;">
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