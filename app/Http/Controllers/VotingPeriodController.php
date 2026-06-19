<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VotingPeriod;

class VotingPeriodController extends Controller
{
    public function index()
    {
        $periods = VotingPeriod::latest('start_at')->get();
        return view('dashboard.admin.periods', compact('periods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'period_name' => 'required|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        VotingPeriod::create([
            'period_name' => $request->period_name,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'is_active' => false,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Periode baru berhasil dibuat. Sistem akan tetap terkunci sampai Anda mengaktifkannya.');
    }
}
