<form method="POST" action="/login">
    @csrf

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <div class="input-group">
        <label>Username</label>
        <input type="text" name="username">
    </div>

    <div class="input-group">
        <label>Password</label>
        <input type="password" name="password">
    </div>

    <button type="submit">Login</button>
</form>