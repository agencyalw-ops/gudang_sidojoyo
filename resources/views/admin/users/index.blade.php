@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">👥 Kelola Pengguna</h2>
        <a href="/admin/users/create" class="btn btn-primary">+ Tambah Pengguna</a>
    </div>

    <h3 style="margin-top: 2rem;">🛠 Administrator</h3>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $user)
                <tr>
                    <td style="font-weight: 500;">{{ $user->name }}</td>
                    <td><span class="badge" style="background: #334155;">{{ $user->username }}</span></td>
                    <td><span class="badge" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa;">{{ ucfirst($user->role) }}</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            <form method="POST" action="/admin/users/{{ $user->id }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h3 style="margin-top: 2rem;">🧾 Kasir</h3>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kasirs as $user)
                <tr>
                    <td style="font-weight: 500;">{{ $user->name }}</td>
                    <td><span class="badge" style="background: #334155;">{{ $user->username }}</span></td>
                    <td><span class="badge" style="background: rgba(34, 197, 94, 0.2); color: #4ade80;">{{ ucfirst($user->role) }}</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            <form method="POST" action="/admin/users/{{ $user->id }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
