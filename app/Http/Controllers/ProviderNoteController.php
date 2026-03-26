<?php

namespace App\Http\Controllers;

use App\Models\ProviderNote;
use App\Models\Visit;
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
        $visitId = $request->get('visit_id');

        if (!$visitId) {
            return redirect()
                ->route('provider.calendar')
                ->with('error', 'No visit was selected for note creation.');
        }

        $visit = Visit::with(['client', 'caregiver'])->findOrFail($visitId);

        return view('provider.notes.create', compact('visit'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visit_id' => ['required', 'exists:visits,id'],
            'note' => ['required', 'string'],
        ]);

        $visit = Visit::with(['client', 'caregiver'])->findOrFail($validated['visit_id']);

        ProviderNote::updateOrCreate(
            ['visit_id' => $visit->id],
            [
                'client_id'   => $visit->client_id,
                'visit_id'    => $visit->id,
                'provider_id' => auth()->id(),
                'note'        => $validated['note'],
                'status'      => 'signed',
                'signed_at'   => now(),
            ]
        );

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
