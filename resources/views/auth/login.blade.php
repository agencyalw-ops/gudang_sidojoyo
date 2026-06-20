@extends('layouts.app')

@section('content')
<div style="max-width: 400px; margin: 4rem auto;">
    <div class="card">
        <h2 style="text-align: center; margin-bottom: 2rem;">Login Sidojoyo</h2>
        
        <form method="POST" action="/login">
            @csrf
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Enter username">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter password">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 0.75rem;">Login</button>
        </form>
    </div>
</div>
@endsection
