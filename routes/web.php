<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\VotingPeriodController;
use App\Http\Controllers\PanitiaAccountController;
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
    return view('public.jadwal', compact('period'));
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
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pemilih
    Route::middleware([RoleMiddleware::class.':pemilih'])->group(function () {
        Route::get('/dashboard/pemilih', function () {
            return view('dashboard.pemilih');
        })->name('pemilih.dashboard');

        Route::middleware([VotingValidator::class])->group(function () {
            Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
            Route::get('/voting/confirm/{id}', [VotingController::class, 'confirm'])->name('voting.confirm');
            Route::post('/voting/submit', [VotingController::class, 'submit'])->name('voting.submit');
        });
    });

    // Panitia
    Route::middleware([RoleMiddleware::class.':panitia'])->group(function () {
        Route::get('/panitia/dashboard', function () {
            $pemilihQuery = \App\Models\User::whereHas('role', function($q){ $q->where('name', 'pemilih'); });
            $totalPemilih = (clone $pemilihQuery)->count();
            $sudahMemilih = (clone $pemilihQuery)->sudahMemilih()->count();
            $belumMemilih = (clone $pemilihQuery)->belumMemilih()->count();
            return view('dashboard.panitia', compact('totalPemilih', 'sudahMemilih', 'belumMemilih'));
        })->name('panitia.dashboard');

        // Kandidat CRUD
        Route::get('/panitia/kandidat', [CandidateController::class, 'index'])->name('panitia.kandidat.index');
        Route::post('/panitia/kandidat', [CandidateController::class, 'store'])->name('panitia.kandidat.store');
        Route::delete('/panitia/kandidat/{id}', [CandidateController::class, 'destroy'])->name('panitia.kandidat.destroy');

        // DPT CRUD
        Route::get('/panitia/dpt', [VoterController::class, 'index'])->name('panitia.dpt.index');
        Route::post('/panitia/dpt', [VoterController::class, 'store'])->name('panitia.dpt.store');
        Route::delete('/panitia/dpt/{id}', [VoterController::class, 'destroy'])->name('panitia.dpt.destroy');
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
        
        Route::post('/admin/periode/toggle', function (\Illuminate\Http\Request $request) {
            $period = \App\Models\VotingPeriod::where('is_active', true)->first();
            if (!$period) {
                $period = \App\Models\VotingPeriod::latest('start_at')->first() ?? new \App\Models\VotingPeriod();
            }
            $period->is_active = !$period->is_active;
            $period->created_by = auth()->id();
            $period->save();
            return back()->with('success', 'Status periode voting berhasil diubah.');
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
