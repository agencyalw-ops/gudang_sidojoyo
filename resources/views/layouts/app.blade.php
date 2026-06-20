<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Gudang Sidojoyo' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* Global CSS Improvements */
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --dark: #0f172a;
            --card-bg: #1e293b;
            --text-main: #ffffff;
            --text-muted: #cbd5e1;
            --danger: #ef4444;
            --success: #22c55e;
        }

        body {
            background-color: var(--dark);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: var(--card-bg);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #334155;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .card {
            background: var(--card-bg);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-danger { background: var(--danger); color: white; }
        .btn-success { background: var(--success); color: white; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th {
            text-align: left;
            padding: 0.75rem;
            background: #334155;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        td {
            padding: 0.75rem;
            border-bottom: 1px solid #334155;
        }

        tr:hover {
            background: rgba(255,255,255,0.02);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            padding: 0.625rem;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            color: white;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
        }

        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .alert-success { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
        .alert-error { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="navbar-brand">Sidojoyo</a>
        <div class="nav-links">
            @if(session('user_id'))
                <span>👋 {{ session('name') }} ({{ ucfirst(session('role')) }})</span>
                @if(session('role') == 'admin')
                    <a href="/admin">Dashboard</a>
                    <a href="/admin/products">Products</a>
                    <a href="/admin/users">Users</a>
                @elseif(session('role') == 'kasir')
                    <a href="/kasir">POS</a>
                @elseif(session('role') == 'owner')
                    <a href="/owner">Dashboard</a>
                @endif
                <a href="/logout" class="btn btn-danger">Logout</a>
            @else
                <a href="/login">Login</a>
            @endif
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
