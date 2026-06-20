@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">📜 History Transaksi Kasir</h2>
    </div>

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
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($transactions as $t)

                <tr style="{{ ($t->status ?? 'completed') == 'cancelled' ? 'opacity:.5;' : '' }}">

                    {{-- INVOICE --}}
                    <td>
                        <span class="badge" style="background:#334155;">
                            {{ $t->invoice }}
                        </span>
                    </td>

                    {{-- KASIR --}}
                    <td>
                        {{ $t->kasir_name }}
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if(($t->status ?? 'completed') == 'cancelled')
                            <span class="badge" style="background:red;">
                                Cancelled
                            </span>
                        @else
                            <span class="badge" style="background:green;">
                                Completed
                            </span>
                        @endif
                    </td>

                    {{-- TOTAL --}}
                    <td style="font-weight:bold; color:var(--success);">
                        Rp {{ number_format($t->total) }}
                    </td>

                    {{-- DETAIL ITEM --}}
                    <td>
                        @foreach($t->items as $item)
                            <div style="font-size:0.875rem; color:var(--text-muted); margin-bottom:2px;">
                                {{ $item->name }} (x{{ $item->qty }}) - Rp {{ number_format($item->subtotal) }}
                            </div>
                        @endforeach
                    </td>

                    {{-- BAYAR --}}
                    <td>
                        Rp {{ number_format($t->money) }}
                    </td>

                    {{-- KEMBALIAN --}}
                    <td>
                        Rp {{ number_format($t->change_money) }}
                    </td>

                    {{-- TANGGAL --}}
                    <td style="font-size:0.875rem; color:var(--text-muted);">
                        {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}
                    </td>

                    {{-- AKSI --}}
                    <td>

                        @if(($t->status ?? 'completed') == 'cancelled')

                            <span style="color:red; font-size:0.75rem;">
                                ✔ Already Cancelled
                            </span>

                        @else

                            <form method="POST"
                                  action="{{ route('transaction.cancel', $t->id) }}"
                                  onsubmit="return confirm('Cancel transaksi ini? Stok akan dikembalikan.')">

                                @csrf

                                <button
                                    class="btn btn-danger"
                                    style="padding:0.25rem 0.5rem;font-size:0.75rem;">

                                    Cancel

                                </button>

                            </form>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="9" style="text-align:center; color:var(--text-muted);">
                        Belum ada transaksi
                    </td>
                </tr>

                @endforelse

            </tbody>
        </table>
    </div>
</div>
@endsection