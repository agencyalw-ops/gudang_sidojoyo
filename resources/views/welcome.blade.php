<!DOCTYPE html>
<html>
<head>
    <title>Gudang Sidojoyo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body{
            margin:0;
            font-family:Arial,sans-serif;
            background:#f8fafc;
        }

        .container{
            max-width:1200px;
            margin:auto;
            padding:30px;
        }

        .btn{
            display:inline-block;
            padding:12px 20px;
            border-radius:8px;
            text-decoration:none;
        }

        .btn-primary{
            background:#0f172a;
            color:white;
        }

        .btn-secondary{
            background:#ef4444;
            color:white;
        }

        .card{
            background:white;
            border-radius:12px;
            padding:24px;
            box-shadow:0 2px 10px rgba(0,0,0,.05);
        }
    </style>
</head>
<body>

<div class="container">

    <div style="text-align:center;padding:3rem 1.5rem;">
        <h1 style="font-size:2.5rem;margin-bottom:.5rem;">
            📦 Gudang Sidojoyo
        </h1>

        <p style="font-size:1.1rem;color:#6b7280;max-width:600px;margin:auto auto 2rem;">
            Sistem manajemen pergudangan dan kasir terpadu untuk efisiensi bisnis Anda
        </p>

        @if(!session('user_id'))
            <a href="/login" class="btn btn-primary">
                🔐 Masuk ke Sistem
            </a>
        @else
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">

                @if(session('role') == 'owner')
                    <a href="/owner" class="btn btn-primary">
                        📊 Dashboard Owner
                    </a>
                @elseif(session('role') == 'admin')
                    <a href="/admin" class="btn btn-primary">
                        📊 Dashboard Admin
                    </a>
                @else
                    <a href="/kasir" class="btn btn-primary">
                        💳 Buka POS Kasir
                    </a>
                @endif

                <a href="/logout" class="btn btn-secondary">
                    Logout
                </a>

            </div>
        @endif
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:2rem;margin-top:3rem;">

        <div class="card">
            <div style="font-size:2.5rem;">📊</div>
            <h3>Laporan Real-time</h3>
            <p>Pantau penjualan dan performa bisnis kapan saja dengan laporan yang akurat.</p>
        </div>

        <div class="card">
            <div style="font-size:2.5rem;">📦</div>
            <h3>Manajemen Stok</h3>
            <p>Kelola inventaris dengan mudah dan pantau perubahan stok secara real-time.</p>
        </div>

        <div class="card">
            <div style="font-size:2.5rem;">💰</div>
            <h3>POS Kasir</h3>
            <p>Sistem kasir yang cepat dan responsif untuk mempercepat transaksi.</p>
        </div>

    </div>

</div>

</body>
</html>