<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'POS Kasir - Gudang Sidojoyo' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root {
            --primary: #3b82f6;
            --dark: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
        }

        /* TOPBAR */
        .kasir-topbar {
            background: white;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e5e7eb;
        }

        .kasir-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .burger {
            background: var(--dark);
            color: white;
            border: none;
            padding: 0.5rem 0.7rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: -260px;
            width: 240px;
            height: 100%;
            background: var(--dark);
            color: white;
            padding: 1rem;
            transition: 0.3s;
            z-index: 999;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 0.75rem 0;
            text-decoration: none;
        }

        .sidebar a:hover {
            opacity: 0.7;
        }

        /* LOGOUT STYLE */
        .logout-btn {
            width: 100%;
            margin-top: 1rem;
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 600;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            display: none;
            z-index: 998;
        }

        .overlay.active {
            display: block;
        }

        /* CONTENT */
        .kasir-content {
            padding: 1.5rem;
        }
        
        /* RESPONSIVE */
        @media(max-width:768px){
            .kasir-topbar{
                padding: 0.75rem 1rem;
                gap: 0.5rem;
            }
            
            .kasir-brand{
                gap: 0.75rem;
            }
            
            .kasir-brand strong{
                font-size: 0.95rem;
            }
            
            .kasir-brand small{
                font-size: 0.75rem;
            }
            
            .kasir-topbar > div:last-child{
                font-size: 0.9rem;
            }
            
            .kasir-content{
                padding: 1rem;
            }
        }
        
        @media(max-width:480px){
            .kasir-topbar{
                padding: 0.6rem 0.75rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .kasir-brand{
                width: 100%;
                gap: 0.5rem;
            }
            
            .kasir-brand strong{
                font-size: 0.85rem;
            }
            
            .kasir-brand small{
                font-size: 0.7rem;
            }
            
            .kasir-topbar > div:last-child{
                font-size: 0.8rem;
                width: 100%;
            }
            
            .kasir-content{
                padding: 0.75rem;
            }
            
            .sidebar{
                width: 200px;
            }
        }
    </style>
</head>

<body>

    {{-- SIDEBAR --}}
    <div class="sidebar" id="sidebar">
        <h3 style="margin-bottom:1rem;">Menu</h3>

        <a href="/kasir">💳 Checkout</a>
        <a href="/kasir/history">📊 History Transaksi</a>

        {{-- LOGOUT DI SINI --}}
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="logout-btn">
                🚪 Logout
            </button>
        </form>
    </div>

    <div class="overlay" id="overlay" onclick="toggleMenu()"></div>

    {{-- TOPBAR --}}
    <div class="kasir-topbar">

        <div class="kasir-brand">
            <button class="burger" onclick="toggleMenu()">☰</button>

            <div>
                <strong style="color:#0f172a;">POS Kasir</strong><br>
                <small style="color:#6b7280;">Gudang Sidojoyo</small>
            </div>
        </div>

        <div>
            <strong>{{ session('name') }}</strong>
        </div>

    </div>

    {{-- CONTENT --}}
    <div class="kasir-content">
        @yield('content')
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }
    </script>

</body>
</html>