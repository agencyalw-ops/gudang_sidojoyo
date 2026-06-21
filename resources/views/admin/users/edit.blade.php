@extends('layouts.app', ['title' => '✏️ Edit Pengguna'])

@section('content')

<div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <div>
            <h2 style="margin:0;">✏️ Edit Pengguna</h2>
            <small style="color:var(--muted);">
                Perbarui data pengguna sistem
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

    <form method="POST" action="/admin/users/{{ $user->id }}">
        @csrf
        @method('PUT')

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
                    value="{{ old('name', $user->name) }}"
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
                    value="{{ old('username', $user->username) }}"
                    class="form-control"
                    placeholder="Masukkan username">
            </div>

            <div>
                <label style="display:block;margin-bottom:.5rem;font-weight:500;">
                    Role
                </label>

                <select name="role" class="form-control">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                        👑 Admin
                    </option>

                    <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>
                        🧾 Kasir
                    </option>
                </select>
            </div>

            <div style="grid-column:1/-1;">
                <label style="display:block;margin-bottom:.5rem;font-weight:500;">
                    Password Baru
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Kosongkan jika tidak ingin mengganti password">

                <small style="color:var(--muted);">
                    Kosongkan jika password tidak ingin diubah.
                </small>
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
                💾 Update Pengguna
            </button>

            <a href="/admin/users" class="btn btn-secondary">
                Batal
            </a>

        </div>

    </form>

</div>

@endsection