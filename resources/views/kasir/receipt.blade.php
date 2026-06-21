<!DOCTYPE html>
<html>
<head>
    <title>Struk</title>

    <style>
        body {
            font-family: monospace;
            width: 300px;
            margin: auto;
            padding: 10px;
        }

        .center { text-align: center; }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        table {
            width: 100%;
            font-size: 12px;
        }

        @media print {
            button { display: none; }
        }
    </style>
</head>

<body onload="window.print()">

<div class="center">
    <h3>📦 Gudang Sidojoyo</h3>
    <small>Struk Pembelian</small>
</div>

<div class="line"></div>

<p>Invoice : {{ $transaction->invoice }}</p>
<p>Kasir : {{ $transaction->kasir_name }}</p>
<p>Waktu : {{ $transaction->created_at }}</p>

<div class="line"></div>

<table>
@foreach($items as $item)
    <tr>
        <td>{{ $item->product_id }}</td>
        <td>x{{ $item->qty }}</td>
        <td style="text-align:right;">
            Rp{{ number_format($item->subtotal) }}
        </td>
    </tr>
@endforeach
</table>

<div class="line"></div>

<p>Total : Rp {{ number_format($transaction->total) }}</p>
<p>Bayar : Rp {{ number_format($transaction->money) }}</p>
<p>Kembali : Rp {{ number_format($transaction->change_money) }}</p>

<div class="line"></div>

<div class="center">
    <p>Terima kasih 🙏</p>
</div>

<button onclick="window.print()">Print Ulang</button>

<script>
    window.onafterprint = function () {
        window.location.href = "/kasir";
    }
</script>

</body>
</html>