@extends('layouts.app', ['title' => '👥 Kelola Pengguna'])

@section('content')

<style>
    .page-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:1.5rem;
    }

    .page-header h2{
        margin:0;
    }

    .stats-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:1rem;
        margin-bottom:1.5rem;
    }

    .stat-card{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:.75rem;
        padding:1.25rem;
    }

    .stat-label{
        color:#64748b;
        font-size:.85rem;
        margin-bottom:.5rem;
    }

    .stat-value{
        font-size:1.8rem;
        font-weight:700;
    }

    .section-title{
        margin:1.5rem 0 1rem;
        font-size:1.1rem;
        font-weight:600;
    }

    table{
        width:100%;
        border-collapse:separate;
        border-spacing:0;
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:.75rem;
        overflow:hidden;
    }

    thead{
        background:#f8fafc;
    }

    th{
        padding:.9rem;
        text-align:left;
        font-size:.75rem;
        text-transform:uppercase;
        color:#64748b;
        border-bottom:1px solid #e5e7eb;
    }

    td{
        padding:.9rem;
        border-bottom:1px solid #f1f5f9;
        vertical-align:middle;
    }

    tr:hover{
        background:#f9fafb;
    }

    .badge{
        display:inline-block;
        padding:.3rem .7rem;
        border-radius:999px;
        font-size:.75rem;
        font-weight:600;
    }

    .badge-user{
        background:#eef2ff;
        color:#3730a3;
    }

    .badge-admin{
        background:#dbeafe;
        color:#1d4ed8;
    }

    .badge-kasir{
        background:#dcfce7;
        color:#166534;
    }

    .action-group{
        display:flex;
        gap:.5rem;
    }

    .btn-sm{
        padding:.35rem .7rem;
        border:none;
        border-radius:.4rem;
        cursor:pointer;
        font-size:.75rem;
        text-decoration:none;
    }

    .btn-edit{
        background:#3b82f6;
        color:white;
    }

    .btn-delete{
        background:#ef4444;
        color:white;
    }

    .btn-delete:hover{
        background:#dc2626;
    }

    .btn-edit:hover{
        background:#2563eb;
    }
</style>

<div class="page-header">
    <div>
        <h2>👥 Kelola Pengguna</h2>
        <p style="color:#64748b;margin-top:4px;">
            Manajemen akun administrator dan kasir
        </p>
    </div>


<a href="/admin/users/create" class="btn btn-primary">
    + Tambah Pengguna
</a>


</div>

<div class="stats-grid">


<div class="stat-card">
    <div class="stat-label">Total Pengguna</div>
    <div class="stat-value">
        {{ $admins->count() + $kasirs->count() }}
    </div>
</div>

<div class="stat-card">
    <div class="stat-label">Administrator</div>
    <div class="stat-value" style="color:#2563eb;">
        {{ $admins->count() }}
    </div>
</div>

<div class="stat-card">
    <div class="stat-label">Kasir</div>
    <div class="stat-value" style="color:#16a34a;">
        {{ $kasirs->count() }}
    </div>
</div>


</div>

<div class="card">


<div class="section-title">
    🛠 Administrator
</div>

<div style="overflow-x:auto;">
    <table>

        <thead>
            <tr>
                <th>Nama</th>
                <th>Username</th>
                <th>Role</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>

        <tbody>

        @forelse($admins as $user)

            <tr>

                <td style="font-weight:600;">
                    {{ $user->name }}
                </td>

                <td>
                    <span class="badge badge-user">
                        {{ $user->username }}
                    </span>
                </td>

                <td>
                    <span class="badge badge-admin">
                        Administrator
                    </span>
                </td>

                <td>

                    <div class="action-group">

                        <a href="/admin/users/{{ $user->id }}/edit"
                           class="btn-sm btn-edit">
                            Edit
                        </a>

                        <form method="POST"
                              action="/admin/users/{{ $user->id }}"
                              onsubmit="return confirm('Hapus pengguna ini?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn-sm btn-delete">
                                Hapus
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="4" style="text-align:center;padding:2rem;color:#64748b;">
                    Belum ada administrator
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>
</div>

<div class="section-title">
    🧾 Kasir
</div>

<div style="overflow-x:auto;">
    <table>

        <thead>
            <tr>
                <th>Nama</th>
                <th>Username</th>
                <th>Role</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>

        <tbody>

        @forelse($kasirs as $user)

            <tr>

                <td style="font-weight:600;">
                    {{ $user->name }}
                </td>

                <td>
                    <span class="badge badge-user">
                        {{ $user->username }}
                    </span>
                </td>

                <td>
                    <span class="badge badge-kasir">
                        Kasir
                    </span>
                </td>

                <td>

                    <div class="action-group">

                        <a href="/admin/users/{{ $user->id }}/edit"
                           class="btn-sm btn-edit">
                            Edit
                        </a>

                        <form method="POST"
                              action="/admin/users/{{ $user->id }}"
                              onsubmit="return confirm('Hapus pengguna ini?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn-sm btn-delete">
                                Hapus
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="4" style="text-align:center;padding:2rem;color:#64748b;">
                    Belum ada kasir
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>
</div>


</div>

@endsection
