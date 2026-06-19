<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\ActivityLog;
use Illuminate\Support\Str;

class VotingController extends Controller
{
    public function index()
    {
        $period = \App\Models\VotingPeriod::where('is_active', true)->first();
        $candidates = $period ? $period->candidates : collect();
        return view('voting.index', compact('candidates'));
    }

    public function confirm($id)
    {
        $period = \App\Models\VotingPeriod::where('is_active', true)->first();
        $candidate = Candidate::findOrFail($id);
        
        if (!$period || $candidate->voting_period_id !== $period->id) {
            return redirect()->route('voting.index')->with('error', 'Kandidat tidak valid atau periode tidak aktif.');
        }

        return view('voting.confirm', compact('candidate', 'period'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        $user = Auth::user();

        try {
            DB::transaction(function () use ($user, $request) {
                // Lock row to prevent race condition (menjamin V_h ∩ V_b = ∅)
                $lockedUser = DB::table('users')->where('id', $user->id)->lockForUpdate()->first();
                
                if ($lockedUser->is_voted) {
                    throw new \Exception('Sudah memilih');
                }

                // Enkripsi suara (AES-256-CBC)
                $encryptedChoice = Crypt::encryptString($request->candidate_id);

                // Token Voting (Kombinatorika 36^8)
                $votingToken = $this->generateVotingToken();

                $period = \App\Models\VotingPeriod::where('is_active', true)->first();
                if (!$period) {
                    throw new \Exception('Periode voting belum aktif.');
                }

                Vote::create([
                    'user_id' => $user->id,
                    'voting_period_id' => $period->id,
                    'candidate_id' => $request->candidate_id,
                    'encrypted_choice' => $encryptedChoice,
                    'voting_token' => $votingToken,
                    'voted_at' => now(),
                ]);

                // Update status v = TRUE
                DB::table('users')->where('id', $user->id)->update(['is_voted' => true]);
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'vote_cast',
                    'description' => 'Suara berhasil dicatat dengan token ' . $votingToken,
                    'proposition_state' => 'p ∧ q ∧ r ∧ s ∧ ¬v ∧ t',
                ]);
            });

            return redirect()->route('pemilih.dashboard')->with('success', 'Suara Anda berhasil dicatat!');
        } catch (\Exception $e) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'access_denied',
                'description' => 'Gagal mencatat suara: ' . $e->getMessage(),
                'proposition_state' => 'Error/Race Condition',
            ]);
            return redirect()->route('error.access_denied')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function generateVotingToken()
    {
        do {
            $token = strtoupper(Str::random(8));
        } while (Vote::where('voting_token', $token)->exists());

        return $token;
    }
}
