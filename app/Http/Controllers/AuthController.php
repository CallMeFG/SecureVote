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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use App\Mail\OtpMail;

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
                    if (app()->environment('production') && !str_ends_with($value, '@mahasiswa.pcr.ac.id')) {
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
        session(['otp_last_sent_at' => time()]);

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
        if (app()->environment('production') && $user->role->name === 'pemilih' && !str_ends_with($user->email, '@mahasiswa.pcr.ac.id')) {
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

        // Cek Trusted Device Cookie (Bypass OTP)
        $trustedCookie = Cookie::get('trusted_device_' . $user->id);
        if ($trustedCookie === 'trusted') {
            Auth::login($user);
            $this->logActivity($user->id, 'login_success', 'Login berhasil (Trusted Device Bypass)', 'bypass_otp');

            if ($user->role->name === 'pemilih') return redirect()->route('pemilih.dashboard');
            if ($user->role->name === 'panitia') return redirect()->route('panitia.dashboard');
            if ($user->role->name === 'admin') return redirect()->route('admin.dashboard');
            return redirect('/');
        }

        $this->generateOtp($user);
        session(['otp_user_id' => $user->id]);
        session(['otp_last_sent_at' => time()]);

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
        
        try {
            Mail::to($user->email)->send(new OtpMail($user, (string) $numericToken));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
        }
        
        // session()->flash('debug_otp', $numericToken); // Simulate SMS/Email (Dinonaktifkan karena email riil sudah aktif)
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

        // Set Trusted Device Cookie berlaku 30 hari (43200 menit)
        Cookie::queue('trusted_device_' . $user->id, 'trusted', 43200);

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

    public function resendOtp(Request $request)
    {
        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $lastSent = session('otp_last_sent_at');
        // Jika nilai session masih berupa objek/string lama, is_numeric akan mengabaikan blok ini
        if ($lastSent && is_numeric($lastSent) && (time() - $lastSent) < 30) {
            return back()->with('error', 'Harap tunggu 30 detik sebelum meminta OTP baru.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $this->generateOtp($user);
        session(['otp_last_sent_at' => time()]);

        return back()->with('success', 'Kode OTP baru telah berhasil dikirimkan ke email Anda.');
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
