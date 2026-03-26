<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\CareLog;
use Illuminate\Http\Request;

class CareLogController extends Controller
{
    public function index()
    {
        $careLogs = CareLog::with(['visit.client', 'visit.caregiver'])
            ->latest()
            ->get();

        return view('caregiver.care-logs.index', compact('careLogs'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        $visits = Visit::with(['client', 'caregiver'])
            ->when(!empty($user->facility_id), function ($query) use ($user) {
                $query->where('facility_id', $user->facility_id);
            })
            ->orderBy('visit_date', 'asc')
            ->get();

        $selectedVisit = $request->get('visit_id');

        return view('caregiver.care-logs.create', compact('visits', 'selectedVisit'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visit_id' => ['required', 'exists:visits,id'],
            'notes' => ['nullable', 'string'],
            'adls' => ['nullable', 'array'],
            'vitals' => ['nullable', 'array'],
        ]);

        $visit = Visit::findOrFail($validated['visit_id']);

        CareLog::create([
            'visit_id' => $visit->id,
            'caregiver_id' => $visit->caregiver_id,
            'notes' => $validated['notes'] ?? null,
            'adls' => $validated['adls'] ?? null,
            'vitals' => $validated['vitals'] ?? null,
        ]);

        if (auth()->user()->role === 'provider') {
            return redirect()
                ->route('provider.compliance')
                ->with('success', 'Care log saved successfully.');
        }

        return redirect()
            ->route('caregiver.care-logs.index')
            ->with('success', 'Care log saved successfully.');
    }

    public function show(CareLog $careLog)
    {
        $careLog->load(['visit.client', 'visit.caregiver']);

        return view('caregiver.care-logs.show', compact('careLog'));
    }
}
