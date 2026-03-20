<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\ProviderNote;
use Illuminate\Http\Request;

class ProviderNoteController extends Controller
{
    public function index()
    {
        $notes = ProviderNote::with(['visit.client', 'visit.caregiver'])
            ->latest()
            ->get();

        return view('provider.notes.index', compact('notes'));
    }

    public function create(Request $request)
    {
        $visits = Visit::with(['client', 'caregiver'])
            ->orderBy('visit_date', 'asc')
            ->get();

        $selectedVisit = $request->get('visit_id');

        return view('provider.notes.create', compact('visits', 'selectedVisit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'note' => 'required|string',
            'status' => 'nullable|string|max:100',
        ]);

        ProviderNote::create([
            'visit_id' => $request->visit_id,
            'provider_id' => auth()->id(),
            'note' => $request->note,
            'status' => $request->status ?? 'Open',
        ]);

        return redirect()
            ->route('provider.notes.index')
            ->with('success', 'Provider note saved successfully.');
    }

    public function show(ProviderNote $providerNote)
    {
        $providerNote->load(['visit.client', 'visit.caregiver']);

        return view('provider.notes.show', compact('providerNote'));
    }
}
