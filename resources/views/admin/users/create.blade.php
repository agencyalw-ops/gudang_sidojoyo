@extends('layouts.app')

@section('content')
<style>
    .form-card {
        max-width: 800px;
        margin: auto;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .form-control {
        padding: 0.6rem;
        border-radius: 0.375rem;
        border: 1px solid #334155;
        background: #0f172a;
        color: white;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--primary);
    }

    .full {
        grid-column: span 3;
    }

    .actions {
        grid-column: span 3;
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .btn {
        padding: 0.6rem 1rem;
        border: none;
        border-radius: 0.375rem;
        cursor: pointer;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-secondary {
        background: #334155;
        color: white;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .full, .actions {
            grid-column: span 1;
        }
    }
</style>

<div class="card form-card">
    <h2 style="margin-top: 0;">➕ Create User</h2>

    @if($errors->any())
        <div style="margin-bottom: 1rem;">
            @foreach($errors->all() as $error)
                <div style="color:red; font-size:0.875rem;">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/admin/users">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" placeholder="Nama lengkap">
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Username login">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                </select>
            </div>

            <div class="form-group full">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password user">
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="/admin/users" class="btn btn-secondary">Kembali</a>
            </div>

        </div>
    </form>
</div>
@endsection