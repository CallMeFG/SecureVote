<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VotingController;
use App\Http\Middleware\VotingValidator;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    return view('public.beranda');
})->name('public.beranda');

Route::get('/kandidat', function () {
    $candidates = App\Models\Candidate::all();
    return view('public.kandidat', compact('candidates'));
})->name('public.kandidat');

Route::get('/jadwal', function () {
    $period = App\Models\VotingPeriod::first();
    return view('public.jadwal', compact('period'));
})->name('public.jadwal');

Route::get('/hasil', function () {
    // Only if period is closed
    $period = App\Models\VotingPeriod::first();
    if ($period && $period->is_active) {
        return redirect('/')->with('error', 'Pemilihan belum ditutup.');
    }
    // Calculate results (dekripsi batch)
    $candidates = App\Models\Candidate::with('votes')->get();
    $results = [];
    foreach ($candidates as $candidate) {
        $count = 0;
        foreach (\App\Models\Vote::all() as $vote) {
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

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/otp/verify', [AuthController::class, 'showOtpVerify'])->name('otp.verify.form');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pemilih
    Route::middleware([RoleMiddleware::class.':pemilih'])->group(function () {
        Route::get('/dashboard/pemilih', function () {
            return view('dashboard.pemilih');
        })->name('pemilih.dashboard');

        Route::middleware([VotingValidator::class])->group(function () {
            Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
            Route::post('/voting/submit', [VotingController::class, 'submit'])->name('voting.submit');
        });
    });

    // Panitia
    Route::middleware([RoleMiddleware::class.':panitia'])->group(function () {
        Route::get('/panitia/dashboard', function () {
            $totalPemilih = \App\Models\User::whereHas('role', function($q){ $q->where('name', 'pemilih'); })->count();
            $sudahMemilih = \App\Models\User::sudahMemilih()->count(); // V_h
            $belumMemilih = \App\Models\User::belumMemilih()->count(); // V_b
            return view('dashboard.panitia', compact('totalPemilih', 'sudahMemilih', 'belumMemilih'));
        })->name('panitia.dashboard');
    });

    // Admin
    Route::middleware([RoleMiddleware::class.':admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            $logs = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();
            $period = \App\Models\VotingPeriod::first();
            return view('dashboard.admin', compact('logs', 'period'));
        })->name('admin.dashboard');
        
        Route::post('/admin/periode/toggle', function (\Illuminate\Http\Request $request) {
            $period = \App\Models\VotingPeriod::first() ?? new \App\Models\VotingPeriod();
            $period->is_active = !$period->is_active;
            $period->created_by = auth()->id();
            $period->save();
            return back()->with('success', 'Status periode voting berhasil diubah.');
        })->name('admin.periode.toggle');
    });
});

Route::get('/error/access-denied', function () {
    return view('errors.access_denied');
})->name('error.access_denied');
