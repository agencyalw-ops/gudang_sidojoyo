<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
{
    $admins = DB::table('users')->where('role', 'admin')->get();
    $kasirs = DB::table('users')->where('role', 'kasir')->get();

    return view('admin.users.index', compact('admins', 'kasirs'));
}

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'role' => 'required'
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/users')->with('success', 'User berhasil dibuat');
    }

    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'role' => 'required'
        ]);

        DB::table('users')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'username' => $request->username,
                'role' => $request->role,
                'updated_at' => now(),
            ]);

        if ($request->password) {
            DB::table('users')
                ->where('id', $id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);
        }

        return redirect('/admin/users')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect('/admin/users')->with('success', 'User berhasil dihapus');
    }
}