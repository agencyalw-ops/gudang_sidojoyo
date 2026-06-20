<!DOCTYPE html>
<html>
<head>
    <title>Kasir POS</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

<!-- POPUP -->
@if(session('success'))
<div class="popup success" id="popup">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="popup error" id="popup">{{ session('error') }}</div>
@endif

<script>
    window.onload = () => {
        let p = document.getElementById("popup");
        if (p) {
            p.style.display = "block";
            setTimeout(() => p.style.display = "none", 3000);
        }
    }
</script>

<div class="topbar">
    <div>🧾 Kasir POS</div>
    <div style="display:flex;align-items:center;gap:15px;">
        <span>👤 {{ session('name') }}</span>

        <a href="/logout" class="logout-btn">
            Logout
        </a>
    </div>
</div>

<div class="container">

    <!-- PRODUCT -->
    <div class="products">
        <h3>Produk</h3>

        <div class="product-grid">
            @foreach($products as $p)
            <div class="card">
                <b>{{ $p->name }}</b><br>
                Rp {{ number_format($p->price) }}<br>
                Stock: {{ $p->stock }}

                <form method="POST" action="/kasir/cart/add/{{ $p->id }}">
                    @csrf
                    <button class="btn">Tambah</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>

    <!-- CART -->
    <div class="cart">
        <h3>Cart</h3>

        @php $total = 0; @endphp

        @foreach($cart as $id => $c)
            @php $total += $c['price'] * $c['qty']; @endphp

            <div>
                <b>{{ $c['name'] }}</b><br>

                <form method="POST" action="/kasir/cart/min/{{ $id }}">
                    @csrf <button>-</button>
                </form>

                {{ $c['qty'] }}

                <form method="POST" action="/kasir/cart/add/{{ $id }}">
                    @csrf <button>+</button>
                </form>
            </div>
        @endforeach

        <hr>

        <h3>Total: Rp {{ number_format($total) }}</h3>

        <form method="POST" action="/kasir/checkout">
            @csrf
            <input type="number" name="money" placeholder="Uang customer" required>
            <button class="btn">Bayar</button>
        </form>

        <form method="POST" action="/kasir/cart/clear">
            @csrf
            <button class="btn danger">Clear</button>
        </form>
    </div>
</div>

<!-- transactions -->
<x-transactions-table :transactions="$transactions" />

</body>
</html>