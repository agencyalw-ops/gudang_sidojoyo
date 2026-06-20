<!DOCTYPE html>
<html>
<head>
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>

<body>

<div class="topbar">

    <h2>👑 Owner Dashboard</h2>

    <div>
        Selamat datang,
        <b>{{ session('name') }}</b>
    </div>

</div>

<div class="container">

    <!-- MENU -->

    <div class="menu">

        <a href="/admin/users">
            👥 Kelola User
        </a>

        <a href="/admin/users/create">
            ➕ Tambah User
        </a>

        <a href="/admin/products">
            📦 Kelola Product
        </a>

        <a href="/logout" class="logout">
            🚪 Logout
        </a>

    </div>

    <!-- STATISTIK -->

    @php

        $totalTransaksi = count($transactions);

        $totalPenjualan = collect($transactions)->sum('total');

    @endphp

    <div class="stats">

        <div class="stat-box">
            <p>Total Transaksi</p>
            <h2>{{ $totalTransaksi }}</h2>
        </div>

        <div class="stat-box">
            <p>Total Penjualan</p>
            <h2>
                Rp {{ number_format($totalPenjualan) }}
            </h2>
        </div>

    </div>

    <!-- HISTORY -->

    <div class="card">

        <h3>📜 History Transaksi</h3>

        <table>

            <tr>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Money</th>
                <th>Change</th>
                <th>Tanggal</th>
                <th>Detail</th>
            </tr>

            @forelse($transactions as $t)

            <tr>

                <td>{{ $t->invoice }}</td>

                <td>{{ $t->kasir_name }}</td>

                <td>
                    Rp {{ number_format($t->total) }}
                </td>

                <td>
                    Rp {{ number_format($t->money) }}
                </td>

                <td>
                    Rp {{ number_format($t->change_money) }}
                </td>

                <td>
                    {{ $t->created_at }}
                </td>

                <td>

                    <button
                        class="btn-detail"
                        onclick="toggleDetail({{ $t->id }})"
                    >
                        Lihat
                    </button>

                </td>

            </tr>

            <tr
                id="detail-{{ $t->id }}"
                class="detail-row"
                style="display:none;"
            >

                <td colspan="7">

                    <table
                        class="detail-table"
                        width="100%"
                    >

                        <tr>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>

                        @foreach($t->items as $item)

                        <tr>

                            <td>
                                {{ $item->name }}
                            </td>

                            <td>
                                {{ $item->qty }}
                            </td>

                            <td>
                                Rp {{ number_format($item->price) }}
                            </td>

                            <td>
                                Rp {{ number_format($item->subtotal) }}
                            </td>

                        </tr>

                        @endforeach

                    </table>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="7">
                    Belum ada transaksi
                </td>

            </tr>

            @endforelse

        </table>

    </div>

</div>

<script>

function toggleDetail(id)
{
    let row =
        document.getElementById(
            'detail-' + id
        );

    if(row.style.display === 'none')
    {
        row.style.display = 'table-row';
    }
    else
    {
        row.style.display = 'none';
    }
}

</script>

</body>
</html>