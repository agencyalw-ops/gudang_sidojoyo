<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
</head>
<body>

<h1>➕ Create User</h1>

@if($errors->any())
    <ul>
        @foreach($errors->all() as $error)
            <li style="color:red">{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="/admin/users">
    @csrf

    <label>Nama</label><br>
    <input type="text" name="name"><br><br>

    <label>Username</label><br>
    <input type="text" name="username"><br><br>

    <label>Password</label><br>
    <input type="password" name="password"><br><br>

    <label>Role</label><br>
    <select name="role">
        <option value="admin">Admin</option>
        <option value="kasir">kasir</option>
    </select><br><br>

    <button type="submit">Simpan</button>
</form>

<br>
<a href="/admin/users">⬅ Kembali</a>

</body>
</html>