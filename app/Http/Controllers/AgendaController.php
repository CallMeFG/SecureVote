<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::orderBy('start_date', 'desc')->get();
        return view('dashboard.panitia_agenda', compact('agendas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ]);

        Agenda::create($request->all());

        return back()->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ]);

        $agenda = Agenda::findOrFail($id);
        
        // Handle checkbox
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        $agenda->update($data);

        return back()->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Agenda::findOrFail($id)->delete();
        return back()->with('success', 'Agenda berhasil dihapus.');
    }
}
