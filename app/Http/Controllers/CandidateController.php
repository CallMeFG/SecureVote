<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\VotingPeriod;

class CandidateController extends Controller
{
    public function index()
    {
        $period = VotingPeriod::where('is_active', true)->first();
        if (!$period) {
            $period = VotingPeriod::latest('start_at')->first();
        }
        $candidates = $period ? $period->candidates : collect();
        return view('dashboard.panitia.candidates', compact('candidates', 'period'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'vice_nim' => 'nullable|string|max:255',
            'vice_name' => 'nullable|string|max:255',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'photo_url' => 'nullable|url',
        ]);

        $period = VotingPeriod::where('is_active', true)->first();
        if (!$period) {
            return back()->with('error', 'Gagal: Tidak ada periode voting yang aktif.');
        }

        Candidate::create(array_merge($request->all(), ['voting_period_id' => $period->id]));

        return back()->with('success', 'Kandidat berhasil ditambahkan ke periode aktif!');
    }

    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->delete();
        return back()->with('success', 'Kandidat berhasil dihapus.');
    }
}
