<div class="transactions-card">

    <div class="transactions-header">
        <h3>📜 transactions Transaksi</h3>
    </div>

    <table class="transactions-table">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Money</th>
                <th>Kembalian</th>
                <th>Tanggal</th>
            </tr>
        </thead>

        <tbody>

        @forelse($transactions as $t)

        <tr>
            <td>{{ $t->invoice }}</td>

            <td>
                {{ $t->kasir_name ?? $t->kasir_name }}
            </td>

            <td>
                Rp {{ number_format($t->total) }}
            </td>

            <td>
                Rp {{ number_format($t->money ?? 0) }}
            </td>

            <td>
                Rp {{ number_format($t->change_money ?? 0) }}
            </td>

            <td>
                {{ date('d-m-Y H:i', strtotime($t->created_at)) }}
            </td>

        </tr>

        @empty

        <tr>
            <td colspan="7" align="center">
                Belum ada transaksi
            </td>
        </tr>

        @endforelse

        </tbody>
    </table>

</div>

<style>
.transactions-card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
}

.transactions-header{
    margin-bottom:15px;
}

.transactions-table{
    width:100%;
    border-collapse:collapse;
}

.transactions-table th{
    background:#f3f4f6;
    padding:12px;
    text-align:left;
}

.transactions-table td{
    padding:12px;
    border-bottom:1px solid #eee;
}

.transactions-table tr:hover{
    background:#fafafa;
}
</style>