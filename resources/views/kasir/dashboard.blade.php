@extends('layouts.kasir-layout', ['title' => 'POS Kasir - Checkout'])

@section('content')

<style>
    .pos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .search-box {
        margin-bottom: 1rem;
    }

    .search-box input {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        outline: none;
        font-size: 14px;
    }

    .pos-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 1rem;
        align-items: start;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.8rem;
    }
    
    @media(max-width:1024px){
        .pos-grid{
            grid-template-columns: 1fr 300px;
        }
        
        .product-grid{
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }
    
    @media(max-width:768px){
        .pos-grid{
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .product-grid{
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.6rem;
        }
        
        .product-card{
            min-height: 120px;
            padding: 0.75rem;
            font-size: 0.8rem;
        }
        
        .product-title{
            font-size: 0.9rem;
        }
        
        .search-box input{
            font-size: 16px;
            padding: 0.75rem;
        }
        
        .cart-item{
            font-size: 0.75rem;
            padding: 0.4rem;
        }
        
        .qty-btn{
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .btn-checkout,
        .btn-clear{
            padding: 0.6rem;
            font-size: 0.9rem;
        }
    }
    
    @media(max-width:480px){
        .pos-header{
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .pos-header h3{
            font-size: 1.1rem;
        }
        
        .product-grid{
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.5rem;
        }
        
        .product-card{
            min-height: 110px;
            padding: 0.6rem;
            font-size: 0.75rem;
        }
        
        .product-title{
            font-size: 0.8rem;
        }
        
        .product-price{
            font-size: 0.75rem;
        }
        
        .product-stock{
            font-size: 0.65rem;
        }
        
        .total-section{
            padding: 0.6rem;
            font-size: 0.85rem;
        }
    }

    @media(max-width:320px){
        .product-grid{
            grid-template-columns: 1fr;
        }
        .product-card{
            min-height: auto;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .product-card button{
            width: auto !important;
            margin-top: 0 !important;
            padding: 5px 10px !important;
        }
        .cart-item{
            flex-direction: column;
            gap: 8px;
        }
        .cart-item > div:last-child{
            align-items: flex-start !important;
        }
        .qty-input{
            width: 50px !important;
        }
    }

    .product-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        padding: 1rem;
        border-radius: 0.8rem;
        font-size: 0.9rem;
        transition: 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 140px;
        cursor: pointer;
    }

    .product-card:hover {
        transform: scale(1.02);
        border-color: #0f172a;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    .product-card.disabled {
        opacity: 0.4;
        pointer-events: none;
    }

    .product-title { font-weight: bold; font-size: 1rem; }
    .product-price { margin-top: 0.3rem; font-size: 0.9rem; }
    .product-stock { font-size: 0.75rem; margin-top: 0.3rem; opacity: 0.7; }

    .cart-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem;
        border-bottom: 1px solid #eee;
        font-size: 0.85rem;
    }

    .qty-btn {
        padding: 0.3rem 0.6rem;
        border: none;
        background: #e5e7eb;
        border-radius: 0.25rem;
        cursor: pointer;
    }

    .total-section {
        background: #fff;
        border: 1px solid #ddd;
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-top: 0.75rem;
    }

    .btn-checkout {
        width: 100%;
        padding: 0.7rem;
        margin-top: 0.5rem;
        background: #22c55e;
        color: white;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
    }

    .btn-clear {
        width: 100%;
        padding: 0.6rem;
        margin-top: 0.5rem;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
    }
</style>

<div class="pos-header">
    <h3 style="margin:0;">POS Kasir</h3>
</div>

{{-- SEARCH --}}
<div class="search-box">
    <input type="text" id="searchProduct" placeholder="Cari produk... 🔍">
</div>

<div class="pos-grid">

    {{-- PRODUCTS --}}
    <div class="card">
        <h3>Produk</h3>

        <div class="product-grid">

            @foreach($products as $p)

            <form method="POST" action="/kasir/cart/add/{{ $p->id }}">
                @csrf

                <div class="product-card {{ $p->stock <= 0 ? 'disabled' : '' }}"
                     onclick="if(!this.classList.contains('disabled')) this.closest('form').submit()">

                    <div class="product-title">{{ $p->name }}</div>

                    <div class="product-price">
                        Rp {{ number_format($p->price) }}
                    </div>

                    <div class="product-stock">
                        {{ $p->stock > 0 ? $p->stock.' pcs' : 'Habis' }}
                    </div>

                    <button type="submit"
                        onclick="event.stopPropagation()"
                        style="
                            margin-top:10px;
                            width:100%;
                            padding:8px;
                            border:none;
                            border-radius:8px;
                            background:#0f172a;
                            color:white;
                            font-size:12px;
                            font-weight:600;
                        ">
                        + Tambah
                    </button>

                </div>

            </form>

            @endforeach

        </div>
    </div>

    {{-- CART --}}
    <div class="card">
        <h3>Keranjang</h3>

        @php $total = 0; @endphp

        @forelse($cart as $id => $c)
            @php $subtotal = $c['price'] * $c['qty']; $total += $subtotal; @endphp

            <div class="cart-item"
                 data-id="{{ $id }}"
                 data-price="{{ $c['price'] }}">

                <div>
                    <b>{{ $c['name'] }}</b><br>
                    <small>Rp {{ number_format($c['price']) }}</small>
                </div>

                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">

                    <div style="display:flex;gap:5px;align-items:center;">

                        {{-- MIN --}}
                        <form method="POST" action="/kasir/cart/min/{{ $id }}">
                            @csrf
                            <button class="qty-btn">-</button>
                        </form>

                        {{-- QTY INPUT --}}
                        <input type="number"
                               value="{{ $c['qty'] }}"
                               min="1"
                               class="qty-input"
                               onchange="updateQty(this, {{ $id }})"
                               style="
                                    width:60px;
                                    padding:3px;
                                    border:1px solid #ccc;
                                    border-radius:6px;
                                    text-align:center;
                               ">

                        {{-- PLUS --}}
                        <form method="POST" action="/kasir/cart/add/{{ $id }}">
                            @csrf
                            <button class="qty-btn">+</button>
                        </form>

                    </div>

                    <small class="subtotal-text" style="opacity:0.7;">
                        Subtotal: Rp {{ number_format($subtotal) }}
                    </small>

                </div>

            </div>

        @empty
            <p style="color:#666;">Keranjang kosong</p>
        @endforelse

        {{-- TOTAL --}}
        <div class="total-section">
            <div style="display:flex;justify-content:space-between;">
                <span>Total</span>
                <b id="grandTotal">Rp {{ number_format($total) }}</b>
            </div>

            <form method="POST" action="/kasir/checkout">
                @csrf
                <input type="number"
                       name="money"
                       placeholder="Bayar"
                       required
                       style="width:100%;padding:0.6rem;margin-top:0.5rem;border:1px solid #ccc;border-radius:0.5rem;">

                <button class="btn-checkout">Checkout</button>
            </form>

            <form method="POST" action="/kasir/cart/clear">
                @csrf
                <button class="btn-clear">Clear</button>
            </form>
        </div>
    </div>

</div>

{{-- SCRIPT --}}
<script>
document.getElementById('searchProduct').addEventListener('keyup', function () {
    let keyword = this.value.toLowerCase();
    let products = document.querySelectorAll('.product-card');

    products.forEach(card => {
        let name = card.innerText.toLowerCase();
        card.style.display = name.includes(keyword) ? 'flex' : 'none';
    });
});

function updateQty(input, id) {
    let cartItem = input.closest('.cart-item');
    let price = parseInt(cartItem.dataset.price);
    let qty = parseInt(input.value);

    if (qty < 1 || isNaN(qty)) {
        qty = 1;
        input.value = 1;
    }

    let subtotal = price * qty;

    cartItem.querySelector('.subtotal-text').innerText =
        "Subtotal: Rp " + subtotal.toLocaleString('id-ID');

    let total = 0;

    document.querySelectorAll('.cart-item').forEach(item => {
        let p = parseInt(item.dataset.price);
        let q = item.querySelector('.qty-input').value;
        total += p * q;
    });

    document.getElementById('grandTotal').innerText =
        "Rp " + total.toLocaleString('id-ID');

    fetch(`/kasir/cart/set/${id}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ qty: qty })
    });
}
</script>

@endsection