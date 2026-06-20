<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
</head>
<body>

<h1>👥 User Management</h1>

<a href="/admin/users/create">➕ Tambah User</a>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<hr>

<h2>🛠 Admin</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Nama</th>
        <th>Username</th>
        <th>Role</th>
        <th>Action</th>
    </tr>

    @foreach($admins as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->role }}</td>
        <td>
            <a href="/admin/users/{{ $user->id }}/edit">Edit</a>

            <form method="POST" action="/admin/users/{{ $user->id }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<hr>

<h2>🧾 kasir</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Nama</th>
        <th>Username</th>
        <th>Role</th>
        <th>Action</th>
    </tr>

    @foreach($kasirs as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->role }}</td>
        <td>
            <a href="/admin/users/{{ $user->id }}/edit">Edit</a>

            <form method="POST" action="/admin/users/{{ $user->id }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

</body>
</html>