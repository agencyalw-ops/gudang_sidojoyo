<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Gudang Sidojoyo' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root{
            --primary:#3b82f6;
            --primary-soft:#e0f2fe;
            --bg:#f4f6fb;
            --sidebar:#ffffff;
            --border:#e5e7eb;
            --text:#0f172a;
            --muted:#6b7280;
            --danger:#ef4444;
            --success:#22c55e;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Segoe UI, sans-serif;
        }

        body{
            background:var(--bg);
            color:var(--text);
            display:flex;
            min-height:100vh;
        }

        /* SIDEBAR */
        .sidebar{
            width:260px;
            background:var(--sidebar);
            border-right:1px solid var(--border);
            display:flex;
            flex-direction:column;
            position:fixed;
            top:0;
            left:0;
            height:100vh;
            z-index:999;
        }

        .sidebar-header{
            padding:1.5rem;
            font-weight:800;
            font-size:1.25rem;
            color:var(--primary);
            border-bottom:1px solid var(--border);
            letter-spacing:0.5px;
        }

        .sidebar-user{
            padding:1rem 1.5rem;
            font-size:0.85rem;
            color:var(--muted);
            border-bottom:1px solid var(--border);
        }

        .sidebar-menu{
            flex:1;
            padding:1rem 0;
        }

        .sidebar-menu a{
            display:flex;
            align-items:center;
            gap:0.5rem;
            padding:0.75rem 1.5rem;
            text-decoration:none;
            color:var(--text);
            font-weight:500;
            transition:0.2s;
            border-left:3px solid transparent;
        }

        .sidebar-menu a:hover{
            background:#f8fafc;
            color:var(--primary);
        }

        .sidebar-menu a.active{
            background:var(--primary-soft);
            color:var(--primary);
            border-left:3px solid var(--primary);
        }

        .sidebar-footer{
            padding:1rem 1.5rem;
            border-top:1px solid var(--border);
        }

        .logout{
            display:block;
            text-align:center;
            padding:0.65rem;
            border-radius:0.6rem;
            text-decoration:none;
            font-weight:600;
            transition:0.2s;
        }

        .logout.danger{
            background:var(--danger);
            color:white;
        }

        .logout.primary{
            background:var(--primary);
            color:white;
        }

        .logout:hover{
            opacity:0.9;
        }

        /* MAIN */
        .main{
            margin-left:260px;
            width:100%;
            display:flex;
            flex-direction:column;
        }

        .topbar{
            background:white;
            padding:1rem 1.5rem;
            border-bottom:1px solid var(--border);
            font-weight:700;
            position:sticky;
            top:0;
        }

        .content{
            padding:2rem;
        }

        /* ALERT */
        .alert{
            padding:1rem;
            border-radius:0.6rem;
            margin-bottom:1rem;
        }

        .alert-success{
            background:#dcfce7;
            color:#166534;
        }

        .alert-error{
            background:#fee2e2;
            color:#991b1b;
        }

        /* RESPONSIVE */
        @media(max-width:768px){
            body{
                flex-direction:column;
            }
            
            .sidebar{
                position:fixed;
                top:0;
                left:-260px;
                height:100vh;
                transition:0.3s;
                z-index:999;
                overflow-y:auto;
            }
            
            .sidebar.active{
                left:0;
            }
            
            .sidebar-overlay{
                position:fixed;
                inset:0;
                background:rgba(0,0,0,0.4);
                display:none;
                z-index:998;
            }
            
            .sidebar-overlay.active{
                display:block;
            }
            
            .main{
                margin-left:0;
                width:100%;
            }
            
            .topbar{
                display:flex;
                align-items:center;
                gap:1rem;
                padding:1rem;
            }
            
            .menu-toggle{
                background:var(--primary);
                color:white;
                border:none;
                padding:0.5rem 0.7rem;
                border-radius:0.5rem;
                cursor:pointer;
                font-size:1.2rem;
                display:flex;
                align-items:center;
                justify-content:center;
            }
            
            .content{
                padding:1rem;
            }
            
            table{
                font-size:0.8rem;
            }
            
            th, td{
                padding:0.6rem;
            }
        }
        
        @media(max-width:480px){
            .topbar{
                padding:0.75rem;
                font-size:0.9rem;
            }
            
            .content{
                padding:0.75rem;
            }
            
            table{
                font-size:0.7rem;
            }
            
            th, td{
                padding:0.4rem;
            }
            
            .badge{
                padding:0.2rem 0.4rem;
                font-size:0.65rem;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-header">
            📦 Sidojoyo
        </div>

        @if(session('user_id'))
        <div class="sidebar-user">
            👋 {{ session('name') }}<br>
            <small>{{ ucfirst(session('role')) }}</small>
        </div>
        @endif

        <div class="sidebar-menu">

            @if(session('user_id'))

                @if(session('role') == 'admin')
                    <a href="/admin" class="{{ request()->is('admin') ? 'active' : '' }}">📊 Dashboard</a>
                    <a href="/admin/products" class="{{ request()->is('admin/products*') ? 'active' : '' }}">📦 Products</a>
                    <a href="/reports/kasir-history" class="{{ request()->is('reports/kasir-history') ? 'active' : '' }}">📜 History Kasir</a>
                    <a href="/reports/kasir-performance" class="{{ request()->is('reports/kasir-performance') ? 'active' : '' }}">📈 Kinerja Kasir</a>

                @elseif(session('role') == 'kasir')
                    <a href="/kasir" class="{{ request()->is('kasir') ? 'active' : '' }}">🛒 POS</a>

                @elseif(session('role') == 'owner')
                    <a href="/owner" class="{{ request()->is('owner') ? 'active' : '' }}">📊 Dashboard</a>
                    <a href="/admin/products">📦 Products</a>
                    <a href="/admin/users">👤 Users</a>
                    <a href="/reports/kasir-history">📜 History Kasir</a>
                    <a href="/reports/kasir-performance">📈 Kinerja Kasir</a>
                @endif

            @endif

        </div>

<div class="sidebar-footer">
    @if(session('user_id'))

        <form method="POST" action="/logout" style="margin:0;">
            @csrf
            <button type="submit" class="logout danger" style="width:100%;border:none;cursor:pointer;">
                Logout
            </button>
        </form>

    @else

        <a href="/login" class="logout primary">Login</a>

    @endif
</div>

    </aside>

    <!-- MAIN -->
    <div class="main">

        <div class="topbar">
            <button class="menu-toggle" id="menuToggle" onclick="toggleSidebar()" style="display:none;">☰</button>
            <span>{{ $title ?? 'Dashboard' }}</span>
        </div>

        <div class="content">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')

        </div>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        
        // Show menu toggle on mobile
        function updateMenuToggle() {
            const menuToggle = document.getElementById('menuToggle');
            if (window.innerWidth <= 768) {
                menuToggle.style.display = 'flex';
            } else {
                menuToggle.style.display = 'none';
                document.getElementById('sidebar').classList.remove('active');
                document.getElementById('sidebarOverlay').classList.remove('active');
            }
        }
        
        window.addEventListener('resize', updateMenuToggle);
        updateMenuToggle();
    </script>

</body>
</html>