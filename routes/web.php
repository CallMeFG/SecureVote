<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\VotingPeriodController;
use App\Http\Controllers\PanitiaAccountController;
use App\Http\Controllers\AgendaController;
use App\Http\Middleware\VotingValidator;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    return view('public.beranda');
})->name('public.beranda');

Route::get('/kandidat', function () {
    $period = App\Models\VotingPeriod::where('is_active', true)->first();
    if (!$period) {
        $period = App\Models\VotingPeriod::latest('start_at')->first();
    }
    $candidates = $period ? $period->candidates : collect();
    return view('public.kandidat', compact('candidates', 'period'));
})->name('public.kandidat');

Route::get('/jadwal', function () {
    $period = App\Models\VotingPeriod::where('is_active', true)->first();
    if (!$period) {
        $period = App\Models\VotingPeriod::latest('start_at')->first();
    }
    $agendas = App\Models\Agenda::where('is_active', true)->orderBy('start_date', 'asc')->get();
    return view('public.jadwal', compact('period', 'agendas'));
})->name('public.jadwal');

Route::get('/hasil', function () {
    $period = App\Models\VotingPeriod::where('is_active', true)->first();
    if ($period) {
        return redirect('/')->with('info', 'Pemilihan masih berlangsung. Akses rekapitulasi dikunci hingga periode ditutup untuk menjaga kerahasiaan.');
    }
    
    // Get the latest completed period
    $period = App\Models\VotingPeriod::where('is_active', false)->latest('end_at')->first();
    $candidates = $period ? $period->candidates()->with('votes')->get() : collect();
    
    $results = [];
    foreach ($candidates as $candidate) {
        $count = 0;
        foreach ($candidate->votes as $vote) {
            if (\Illuminate\Support\Facades\Crypt::decryptString($vote->encrypted_choice) == $candidate->id) {
                $count++;
            }
        }
        $results[] = [
            'candidate' => $candidate,
            'votes' => $count
        ];
    }
    return view('public.hasil', compact('results'));
})->name('public.hasil');

Route::get('/riwayat', function () {
    $periods = App\Models\VotingPeriod::where('is_active', false)->orderBy('end_at', 'desc')->get();
    return view('public.riwayat', compact('periods'));
})->name('public.riwayat');

Route::get('/riwayat/{id}', function ($id) {
    $period = App\Models\VotingPeriod::findOrFail($id);
    if ($period->is_active) {
        return redirect()->route('public.riwayat')->with('error', 'Periode masih aktif.');
    }
    
    $candidates = $period->candidates()->with('votes')->get();
    $results = [];
    foreach ($candidates as $candidate) {
        $count = 0;
        foreach ($candidate->votes as $vote) {
            if (\Illuminate\Support\Facades\Crypt::decryptString($vote->encrypted_choice) == $candidate->id) {
                $count++;
            }
        }
        $results[] = [
            'candidate' => $candidate,
            'votes' => $count
        ];
    }
    
    // Sort descending by votes
    usort($results, function($a, $b) {
        return $b['votes'] <=> $a['votes'];
    });
    
    return view('public.riwayat_detail', compact('period', 'results'));
})->name('public.riwayat.detail');

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // OTP berada sebelum Auth login selesai, jadi masih terhitung Guest
    Route::get('/otp/verify', [AuthController::class, 'showOtpVerify'])->name('otp.verify.form');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');

    // Registrasi Pemilih (Himpunan Mahasiswa PCR)
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pemilih
    Route::middleware([RoleMiddleware::class.':pemilih'])->group(function () {
        Route::get('/dashboard/pemilih', function () {
            return view('dashboard.pemilih');
        })->name('pemilih.dashboard');

        Route::post('/dashboard/pemilih/profile', [VoterController::class, 'updateProfile'])->name('pemilih.profile.update');

        Route::middleware([VotingValidator::class])->group(function () {
            Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
            Route::get('/voting/confirm/{id}', [VotingController::class, 'confirm'])->name('voting.confirm');
            Route::post('/voting/submit', [VotingController::class, 'submit'])->name('voting.submit');
        });
    });

    // Panitia
    Route::middleware([RoleMiddleware::class.':panitia'])->group(function () {
        Route::get('/panitia', function () {
            $role = \App\Models\Role::where('name', 'pemilih')->first();
            $totalPemilih = \App\Models\User::where('role_id', $role->id)->count();
            $sudahMemilih = \App\Models\User::where('role_id', $role->id)->where('is_voted', true)->count();
            $belumMemilih = $totalPemilih - $sudahMemilih;
            
            $panitiaRole = \App\Models\Role::where('name', 'panitia')->first();
            $panitias = \App\Models\User::where('role_id', $panitiaRole->id)->get();
            
            return view('dashboard.panitia', compact('totalPemilih', 'sudahMemilih', 'belumMemilih', 'panitias'));
        })->name('panitia.dashboard');

        // Kandidat CRUD
        Route::get('/panitia/kandidat', [CandidateController::class, 'index'])->name('panitia.kandidat.index');
        Route::post('/panitia/kandidat', [CandidateController::class, 'store'])->name('panitia.kandidat.store');
        Route::delete('/panitia/kandidat/{id}', [CandidateController::class, 'destroy'])->name('panitia.kandidat.destroy');

        // DPT
        Route::get('/panitia/dpt', [\App\Http\Controllers\VoterController::class, 'index'])->name('panitia.dpt.index');
        Route::delete('/panitia/dpt/{id}', [\App\Http\Controllers\VoterController::class, 'destroy'])->name('panitia.dpt.destroy');

        // Agenda CRUD
        Route::get('/panitia/agenda', [AgendaController::class, 'index'])->name('panitia.agenda.index');
        Route::post('/panitia/agenda', [AgendaController::class, 'store'])->name('panitia.agenda.store');
        Route::put('/panitia/agenda/{id}', [AgendaController::class, 'update'])->name('panitia.agenda.update');
        Route::delete('/panitia/agenda/{id}', [AgendaController::class, 'destroy'])->name('panitia.agenda.destroy');
    });

    // Admin
    Route::middleware([RoleMiddleware::class.':admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            $logs = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();
            $period = \App\Models\VotingPeriod::where('is_active', true)->first();
            if (!$period) {
                $period = \App\Models\VotingPeriod::latest('start_at')->first();
            }
            return view('dashboard.admin', compact('logs', 'period'));
        })->name('admin.dashboard');
        
        Route::post('/admin/periode/toggle/{id}', function (\Illuminate\Http\Request $request, $id) {
            $period = \App\Models\VotingPeriod::findOrFail($id);
            
            if (!$period->is_active) {
                return back()->with('error', 'Gagal! Periode yang sudah ditutup tidak dapat dibuka kembali secara manual.');
            }
            
            $period->is_active = false;
            $period->save();
            return back()->with('success', 'Periode pemilihan berhasil dikunci secara permanen.');
        })->name('admin.periode.toggle');

        // Periode CRUD
        Route::get('/admin/periode', [VotingPeriodController::class, 'index'])->name('admin.periode.index');
        Route::post('/admin/periode', [VotingPeriodController::class, 'store'])->name('admin.periode.store');

        // Panitia CRUD
        Route::get('/admin/panitia', [PanitiaAccountController::class, 'index'])->name('admin.panitia.index');
        Route::post('/admin/panitia', [PanitiaAccountController::class, 'store'])->name('admin.panitia.store');
        Route::delete('/admin/panitia/{id}', [PanitiaAccountController::class, 'destroy'])->name('admin.panitia.destroy');
    });
});

Route::get('/error/access-denied', function () {
    return view('errors.access_denied');
})->name('error.access_denied');
