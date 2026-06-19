<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class PanitiaAccountController extends Controller
{
    public function index()
    {
        $role = Role::where('name', 'panitia')->first();
        $panitias = User::where('role_id', $role->id)->latest()->get();
        return view('dashboard.admin.panitia', compact('panitias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:255|unique:users,nim',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $role = Role::where('name', 'panitia')->first();

        User::create([
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        return back()->with('success', 'Akun Panitia KPU berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $panitia = User::findOrFail($id);
        $panitia->delete();
        return back()->with('success', 'Akun panitia berhasil dihapus.');
    }
}
