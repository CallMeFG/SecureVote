<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\OtpToken;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:20|unique:users',
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@mahasiswa.pcr.ac.id')) {
                        $fail('Email harus menggunakan domain @mahasiswa.pcr.ac.id.');
                    }
                },
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(), // Menghindari password umum/bocor (Kombinatorika & Ruang Sampel)
            ],
        ], [
            'password.regex' => 'Password harus mengandung minimal satu huruf besar, huruf kecil, angka, dan simbol.',
            'password.uncompromised' => 'Kata sandi ini telah ditemukan dalam daftar kebocoran data publik (data leak). Untuk keamanan, silakan gunakan kombinasi password yang lebih unik.',
        ]);

        $rolePemilih = Role::where('name', 'pemilih')->first();

        $user = User::create([
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $rolePemilih->id,
            'is_active' => true, // Langsung aktif
            'is_voted' => false,
        ]);

        $this->logActivity($user->id, 'register', 'Pendaftaran akun baru sukses', 'E');

        // Alur Registrasi -> OTP -> Dashboard
        $this->generateOtp($user);
        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.verify.form')->with('success', 'Registrasi berhasil! Silakan selesaikan verifikasi keamanan OTP.');
    }

    public function login(Request $request)
    {
        if ($request->has('tamu')) {
            return redirect()->route('public.beranda');
        }

        $request->validate([
            'nim' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('nim', $request->nim)->first();

        // ¬p
        if (!$user) {
            $this->logActivity(null, 'login_attempt', 'NIM tidak ditemukan', '¬p');
            return back()->withErrors(['nim' => 'NIM tidak ditemukan.']);
        }

        // p ∧ ¬e (Validasi Himpunan Mahasiswa PCR)
        if ($user->role->name === 'pemilih' && !str_ends_with($user->email, '@mahasiswa.pcr.ac.id')) {
            $this->logActivity($user->id, 'login_attempt', 'Domain email tidak valid', 'p ∧ ¬e');
            return back()->withErrors(['nim' => 'Akses ditolak: Hanya Himpunan Mahasiswa PCR (@mahasiswa.pcr.ac.id) yang diizinkan.']);
        }

        // p ∧ e ∧ ¬q
        if (!Hash::check($request->password, $user->password)) {
            $this->logActivity($user->id, 'login_attempt', 'Password salah', 'p ∧ e ∧ ¬q');
            return back()->withErrors(['password' => 'Password salah.']);
        }

        // p ∧ e ∧ q ∧ ¬r
        if (!$user->is_active) {
            $this->logActivity($user->id, 'login_attempt', 'Akun nonaktif', 'p ∧ e ∧ q ∧ ¬r');
            return back()->withErrors(['nim' => 'Akun nonaktif.']);
        }

        // p ∧ e ∧ q ∧ r (TRUE)
        $this->logActivity($user->id, 'login_attempt', 'Autentikasi awal berhasil', 'p ∧ e ∧ q ∧ r');
        $this->generateOtp($user);
        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.verify.form');
    }

    private function generateOtp(User $user)
    {
        OtpToken::where('user_id', $user->id)->delete();
        $numericToken = random_int(100000, 999999);

        OtpToken::create([
            'user_id' => $user->id,
            'token' => (string) $numericToken,
            'expires_at' => Carbon::now()->addMinutes(5),
            'is_used' => false,
        ]);
        
        session()->flash('debug_otp', $numericToken); // Simulate SMS/Email
    }

    public function showOtpVerify()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp_verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['token' => 'required|size:6']);

        $userId = session('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        $otp = OtpToken::where('user_id', $userId)
            ->where('token', $request->token)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        // ¬s
        if (!$otp) {
            $this->logActivity($user->id, 'otp_verify', 'OTP salah/kedaluwarsa', 'p ∧ q ∧ r ∧ ¬s');
            return back()->withErrors(['token' => 'OTP salah atau kedaluwarsa.']);
        }

        // s = TRUE
        $otp->update(['is_used' => true]);
        Auth::login($user);
        session()->forget('otp_user_id');

        $this->logActivity($user->id, 'otp_verify', 'Sesi aktif penuh', 'p ∧ q ∧ r ∧ s');

        if ($user->role->name === 'pemilih') {
            return redirect()->route('pemilih.dashboard');
        } elseif ($user->role->name === 'panitia') {
            return redirect()->route('panitia.dashboard');
        } elseif ($user->role->name === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    private function logActivity($userId, $action, $desc, $propState)
    {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $desc,
            'proposition_state' => $propState
        ]);
    }
}
