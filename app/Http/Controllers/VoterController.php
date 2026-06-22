<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class VoterController extends Controller
{
    public function index()
    {
        $role = Role::where('name', 'pemilih')->first();
        $voters = User::where('role_id', $role->id)->latest()->get();
        return view('dashboard.panitia.voters', compact('voters'));
    }

    // Penambahan DPT oleh panitia telah dinonaktifkan demi keamanan.

    public function destroy($id)
    {
        $voter = User::findOrFail($id);
        $voter->delete();
        return back()->with('success', 'Data pemilih berhasil dihapus.');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            // Store new photo
            $path = $request->file('photo')->store('profiles', 'public');
            
            $user->photo_path = $path;
            $user->save();

            return back()->with('success', 'Foto profil berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal mengunggah foto profil.');
    }
}
