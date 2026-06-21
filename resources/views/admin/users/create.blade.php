@extends('layouts.app', ['title' => '➕ Tambah Pengguna'])

@section('content')

<div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <div>
            <h2 style="margin:0;">➕ Tambah Pengguna Baru</h2>
            <small style="color:var(--muted);">
                Buat akun Admin atau Kasir baru
            </small>
        </div>

        <a href="/admin/users" class="btn btn-secondary">
            ← Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <strong>Terjadi Kesalahan:</strong>
            <ul style="margin-top:.5rem;padding-left:1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/admin/users">
        @csrf

        <div style="
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:1rem;
        ">

            <div>
                <label style="display:block;margin-bottom:.5rem;font-weight:500;">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control"
                    placeholder="Masukkan nama lengkap">
            </div>

            <div>
                <label style="display:block;margin-bottom:.5rem;font-weight:500;">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    class="form-control"
                    placeholder="Masukkan username">
            </div>

            <div>
                <label style="display:block;margin-bottom:.5rem;font-weight:500;">
                    Role
                </label>

                <select name="role" class="form-control">
                    <option value="admin">👑 Admin</option>
                    <option value="kasir">🧾 Kasir</option>
                </select>
            </div>

            <div style="grid-column:1/-1;">
                <label style="display:block;margin-bottom:.5rem;font-weight:500;">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password">
            </div>

        </div>

        <div style="
            display:flex;
            gap:.75rem;
            margin-top:1.5rem;
            border-top:1px solid #e5e7eb;
            padding-top:1rem;
        ">

            <button type="submit" class="btn btn-primary">
                💾 Simpan Pengguna
            </button>

            <a href="/admin/users" class="btn btn-secondary">
                Batal
            </a>

        </div>

    </form>

</div>

@endsection