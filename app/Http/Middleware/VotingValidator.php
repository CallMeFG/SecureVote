<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\VotingPeriod;
use App\Models\ActivityLog;

class VotingValidator
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // v: sudah memilih?
        if ($user->is_voted) {
            $this->logActivity($user->id, 'access_denied', 'Ditolak: Sudah memilih (anti double-vote)', 'p ∧ q ∧ r ∧ s ∧ v');
            return redirect()->route('error.access_denied')->with('error', 'Akses Ditolak: Anda sudah menggunakan hak pilih Anda.');
        }

        // t: periode aktif?
        $period = VotingPeriod::where('is_active', true)->first();
        if (!$period) {
            $this->logActivity($user->id, 'access_denied', 'Ditolak: Di luar periode voting', 'p ∧ q ∧ r ∧ s ∧ ¬v ∧ ¬t');
            return redirect()->route('error.access_denied')->with('error', 'Akses Ditolak: Saat ini bukan periode voting.');
        }

        // c: apakah kandidat? (Kandidat tidak boleh memilih)
        $isCandidate = \App\Models\Candidate::where('voting_period_id', $period->id)
            ->where(function ($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('vice_email', $user->email);
            })->exists();

        if ($isCandidate) {
            $this->logActivity($user->id, 'access_denied', 'Ditolak: Kandidat tidak diizinkan memilih', 'p ∧ q ∧ r ∧ s ∧ ¬v ∧ t ∧ c');
            return redirect()->route('error.access_denied')->with('error', 'Akses Ditolak: Sebagai kandidat, Anda tidak diizinkan untuk memberikan suara.');
        }

        return $next($request);
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
