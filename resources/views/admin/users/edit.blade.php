<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            background: #1e293b;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        label {
            font-size: 0.9rem;
            color: #94a3b8;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #334155;
            background: #0f172a;
            color: #e2e8f0;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #3b82f6;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-update {
            background: #22c55e;
            color: white;
        }

        .btn-update:hover {
            background: #16a34a;
        }

        .back {
            display: inline-block;
            margin-top: 15px;
            color: #60a5fa;
            text-decoration: none;
        }

        .back:hover {
            text-decoration: underline;
        }

        .error-box {
            background: #7f1d1d;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error-box li {
            color: #fecaca;
        }

        small {
            color: #64748b;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>✏️ Edit User</h1>

    @if($errors->any())
        <div class="error-box">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/admin/users/{{ $user->id }}">
        @csrf
        @method('PUT')

        <label>Nama</label>
        <input type="text" name="name" value="{{ $user->name }}">

        <label>Username</label>
        <input type="text" name="username" value="{{ $user->username }}">

        <label>Password (opsional)</label>
        <input type="password" name="password">
        <small>Kosongkan jika tidak ingin ganti password</small>

        <label>Role</label>
        <select name="role">
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
        </select>

        <button type="submit" class="btn-update">Update User</button>
    </form>

    <a href="/admin/users" class="back">⬅ Kembali</a>

</div>

</body>
</html>