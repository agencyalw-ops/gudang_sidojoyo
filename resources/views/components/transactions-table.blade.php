<div class="card">
    <h3 style="margin-top:0;margin-bottom:1rem;">
        📊 Riwayat Transaksi
    </h3>

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
                    <th>Waktu</th>

                    @if($showAction ?? false)
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>

            @forelse($transactions as $t)

                <tr style="{{ $t->status == 'cancelled' ? 'opacity:.6;' : '' }}">

                    <td>
                        <span class="badge">
                            {{ $t->invoice }}
                        </span>
                    </td>

                    <td>
                        {{ $t->kasir_name }}
                    </td>

                    <td>

                        @if($t->status == 'cancelled')
                            <span class="badge bg-danger">
                                Cancelled
                            </span>
                        @else
                            <span class="badge bg-success">
                                Completed
                            </span>
                        @endif

                    </td>

                    <td>
                        Rp {{ number_format($t->total) }}
                    </td>

                    <td>
                        @foreach($t->items as $item)
                            <div style="font-size:.8rem;">
                                {{ $item->name }}
                                (x{{ $item->qty }})
                            </div>
                        @endforeach
                    </td>

                    <td>
                        Rp {{ number_format($t->money) }}
                    </td>

                    <td>
                        Rp {{ number_format($t->change_money) }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}
                    </td>

                    @if($showAction ?? false)
                    <td>

                        @if($t->status != 'cancelled')

                            <form method="POST"
                                  action="{{ route('transaction.cancel',$t->id) }}">

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Cancel transaksi ini? Stok akan dikembalikan.')">

                                    Cancel

                                </button>

                            </form>

                        @else

                            <small style="color:red">
                                Sudah Cancel
                            </small>

                        @endif

                    </td>
                    @endif

                </tr>

            @empty

                <tr>
                    <td colspan="9" style="text-align:center;">
                        Belum ada transaksi
                    </td>
                </tr>

            @endforelse

            </tbody>
        </table>
    </div>
</div>