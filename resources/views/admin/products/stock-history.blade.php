@extends('layouts.app')

@section('content')
<div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h2 style="margin:0;">📜 History Pcs Produk</h2>

        <a href="/admin/products" class="btn btn-primary">
            📦 Daftar Produk
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Tipe</th>
                    <th>Qty</th>
                    <th>Pcs Awal</th>
                    <th>Pcs Akhir</th>
                    <th>User</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($histories as $h)
                <tr>
                    <td>{{ $h->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $h->product->name }}</td>

                    <td>
                        @if($h->type == 'in')
                            <span style="color:#22c55e;">Pcs Masuk</span>
                        @elseif($h->type == 'out')
                            <span style="color:#ef4444;">Pcs Keluar</span>
                        @else
                            <span style="color:#f59e0b;">Adjustment</span>
                        @endif
                    </td>

                    <td>{{ $h->qty }}</td>
                    <td>{{ $h->before_stock }}</td>
                    <td>{{ $h->after_stock }}</td>
                    <td>{{ $h->user_name }}</td>
                    <td>{{ $h->note }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;">
                        Belum ada history pcs
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">
        {{ $histories->links() }}
    </div>

</div>
@endsection