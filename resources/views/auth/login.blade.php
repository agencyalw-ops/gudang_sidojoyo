@extends('layouts.auth')

@section('content')

<style>
    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .login-card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .login-header .icon {
        font-size: 3.5rem;
        margin-bottom: 10px;
    }

    .login-header h2 {
        margin: 0;
        color: #0f172a;
    }

    .login-header p {
        color: #6b7280;
        margin-top: 8px;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #374151;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
        transition: 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
    }

    .btn {
        border: none;
        cursor: pointer;
        border-radius: 8px;
        padding: 12px;
        font-size: 15px;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-primary {
        width: 100%;
        background: #0f172a;
        color: white;
    }

    .btn-primary:hover {
        background: #1e293b;
    }

    .alert-error {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 14px;
    }
    
    /* RESPONSIVE */
    @media(max-width:480px){
        .login-wrapper{
            padding: 15px;
        }
        
        .login-card{
            max-width: 100%;
            padding: 1.5rem;
            border-radius: 12px;
        }
        
        .login-header{
            margin-bottom: 1.5rem;
        }
        
        .login-header .icon{
            font-size: 2.5rem;
            margin-bottom: 8px;
        }
        
        .login-header h2{
            font-size: 1.3rem;
        }
        
        .login-header p{
            font-size: 0.85rem;
        }
        
        .form-group label{
            font-size: 0.9rem;
            margin-bottom: 4px;
        }
        
        .form-control{
            padding: 10px;
            font-size: 16px;
            border-radius: 6px;
        }
        
        .btn{
            padding: 10px;
            font-size: 14px;
            border-radius: 6px;
        }
        
        .alert-error{
            padding: 10px;
            font-size: 13px;
        }
    }
</style>

<div class="login-wrapper">

    <div class="login-card">

        <div class="login-header">
            <div class="icon">📦</div>
            <h2>Gudang Sidojoyo</h2>
            <p>Sistem Manajemen Gudang</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="form-group">
                <label>Username</label>
                <input
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">
                🔐 Masuk
            </button>

        </form>

    </div>

</div>

@endsection

@section('extra_styles')
<style>
    @media(max-width:768px){
        .login-card{
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
    }
</style>
@endsection