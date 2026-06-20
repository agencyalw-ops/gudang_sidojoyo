<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    $user = DB::table('users')
        ->where('username', $request->username)
        ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Username / Password salah');
    }

    session([
        'user_id' => $user->id,
        'name' => $user->name,
        'role' => $user->role,
    ]);

    // Redirect berdasarkan role
    switch ($user->role) {
        case 'owner':
            return redirect('/owner');

        case 'admin':
            return redirect('/admin');

        case 'kasir':
            return redirect('/kasir');

        default:
            return redirect('/login')
                ->with('error', 'Role tidak dikenali');
    }
}

   
    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}