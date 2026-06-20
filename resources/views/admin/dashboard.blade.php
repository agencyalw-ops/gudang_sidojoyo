<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

<h1>Welcome Admin 👋</h1>

<p>Nama: {{ session('admin_name') }}</p>

<a href="/logout">Logout</a>

<br><br>

<a href="/admin/products">
    Kelola Product
</a>

<hr>

@if(session('success'))
<p style="color:green">
    {{ session('success') }}
</p>
@endif

@if(session('error'))
<p style="color:red">
    {{ session('error') }}
</p>
@endif

<h2>📜 History Transaksi Kasir</h2>

<table border="1" cellpadding="10" width="100%">

<tr>
    <th>Invoice</th>
    <th>Kasir</th>
    <th>Total</th>
    <th>Barang Keluar</th>
    <th>Money</th>
    <th>Change</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>

@foreach($transactions as $t)

<tr>

    <td>{{ $t->invoice }}</td>

    <td>{{ $t->kasir_name }}</td>

    <td>
        Rp {{ number_format($t->total) }}
    </td>

    <td>

        @foreach($t->items as $item)

            <div style="margin-bottom:5px">

                {{ $item->name }}

                |

                Qty :
                {{ $item->qty }}

                |

                Rp
                {{ number_format($item->subtotal) }}

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
        {{ $t->created_at }}
    </td>

    <td>

        <form
            method="POST"
            action="/admin/transactions/delete/{{ $t->id }}"
            style="display:inline"
            onsubmit="return confirm('Hapus transaksi dan kembalikan stok?')"
        >

            @csrf

            <button
                style="
                    background:red;
                    color:white;
                    border:none;
                    padding:8px 12px;
                    cursor:pointer;
                "
            >
                Hapus
            </button>

        </form>

    </td>

</tr>

@endforeach

</table>
</body>