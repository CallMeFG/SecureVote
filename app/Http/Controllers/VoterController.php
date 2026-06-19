<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class VoterController extends Controller
{
    public function index()
    {
        $role = Role::where('name', 'pemilih')->first();
        $voters = User::where('role_id', $role->id)->latest()->get();
        return view('dashboard.panitia.voters', compact('voters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:255|unique:users,nim',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $role = Role::where('name', 'pemilih')->first();

        User::create([
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'), // Default password
            'role_id' => $role->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        return back()->with('success', 'Pemilih berhasil ditambahkan dengan kata sandi default: password123');
    }

    public function destroy($id)
    {
        $voter = User::findOrFail($id);
        $voter->delete();
        return back()->with('success', 'Data pemilih berhasil dihapus.');
    }
}
