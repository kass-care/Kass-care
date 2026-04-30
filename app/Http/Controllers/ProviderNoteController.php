<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\ProviderNote;
use App\Models\Visit;
use Illuminate\Http\Request;

class ProviderNoteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        $notes = ProviderNote::with(['visit.client', 'visit.caregiver'])
            ->when($facilityId, function ($query) use ($facilityId) {
                $query->whereHas('visit', function ($visitQuery) use ($facilityId) {
                    $visitQuery->where('facility_id', $facilityId);
                });
            })
            ->latest()
            ->get();

        return view('provider.notes.index', compact('notes'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $visitId = $request->get('visit_id');

        if (!$visitId) {
            return redirect()
                ->route('provider.calendar')
                ->with('error', 'No visit was selected for note creation.');
        }

        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        $visit = Visit::with(['client', 'caregiver', 'careLogs'])
            ->when($facilityId, fn ($query) => $query->where('facility_id', $facilityId))
            ->findOrFail($visitId);

        $client = $visit->client;

        $clientName = $client?->name ?? 'Unknown Client';
        $clientDob = $client?->date_of_birth ?? null;

        $subjective = '';
        $objective = '';
        $assessment = 'Patient evaluated based on caregiver observations and recorded vitals.';
        $plan = 'Continue monitoring. Adjust care plan as clinically indicated.';

        $latestLog = $visit->careLogs->sortByDesc('created_at')->first();

        if ($latestLog && !empty($latestLog->notes)) {
            $subjective = 'Caregiver reported: ' . $latestLog->notes;
        }

        return view('provider.notes.create', compact(
            'visit',
            'clientName',
            'clientDob',
            'subjective',
            'objective',
            'assessment',
            'plan'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');
        
        $validated = $request->validate([
    'visit_id'          => ['required', 'exists:visits,id'],
    'chief_complaint'   => ['nullable', 'string'], // ✅ ADD THIS
    'subjective'        => ['nullable', 'string'],
    'objective'         => ['nullable', 'string'],
    'assessment'        => ['nullable', 'string'],
    'plan'              => ['nullable', 'string'],

    'weight'            => ['nullable', 'numeric'],
    'height'            => ['nullable', 'numeric'],
    'bmi'               => ['nullable', 'numeric'],

    'screening_items'   => ['nullable', 'array'],
    'screening_items.*' => ['nullable', 'string'],
    'screening_other'   => ['nullable', 'string'],
]);

        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        $visit = Visit::with(['client', 'caregiver'])
            ->when($facilityId, fn ($query) => $query->where('facility_id', $facilityId))
            ->findOrFail($validated['visit_id']);

        $client = $visit->client;

        $clientName = $client?->name ?? 'Unknown Client';
        $clientDob = $client?->date_of_birth ?? null;

        if ($client && (!empty($validated['weight']) || !empty($validated['height']))) {
            $client->update([
                'weight' => $validated['weight'] ?? $client->weight,
                'height' => $validated['height'] ?? $client->height,
            ]);
        }

        $screeningItems = $validated['screening_items'] ?? [];
        $screeningOther = $validated['screening_other'] ?? null;

        $screeningText = '';

        if (!empty($screeningItems) || !empty($screeningOther)) {
            $screeningText .= "\n\nAdult Screening & Immunization Review:\n";

            foreach ($screeningItems as $item) {
                $screeningText .= "- {$item}\n";
            }

            if (!empty($screeningOther)) {
                $screeningText .= "- Other: {$screeningOther}\n";
            }
        }

        $objective = trim(($validated['objective'] ?? '') . $screeningText);

        $combinedNote = trim(
            "Client: " . $clientName . "\n" .
            "DOB: " . ($clientDob ?: 'N/A') . "\n\n" .
            "S: " . ($validated['subjective'] ?? '') . "\n\n" .
            "O: " . $objective . "\n\n" .
            "A: " . ($validated['assessment'] ?? '') . "\n\n" .
            "P: " . ($validated['plan'] ?? '')
        );

 $providerNote = ProviderNote::updateOrCreate(
    ['visit_id' => $visit->id],
    [
        'client_id'        => $visit->client_id,
        'visit_id'         => $visit->id,
        'provider_id'      => $user->id,
        'chief_complaint'  => $validated['chief_complaint'] ?? null, // ✅ ADD
        'subjective'       => $validated['subjective'] ?? null,
        'objective'        => $objective,
        'assessment'       => $validated['assessment'] ?? null,
        'plan'             => $validated['plan'] ?? null,
        'note'             => $combinedNote,
        'status'           => 'signed',
        'signed_at'        => now(),
    ]
);        

        $this->createScreeningAlerts($visit, $providerNote, $screeningItems, $screeningOther);

        return redirect()
            ->route('provider.notes.index')
            ->with('success', 'Provider SOAP note saved successfully.');
    }

    private function createScreeningAlerts(
        Visit $visit,
        ProviderNote $providerNote,
        array $screeningItems,
        ?string $screeningOther
    ): void {
        if (empty($screeningItems) && empty($screeningOther)) {
            return;
        }

        Alert::withoutGlobalScopes()
            ->where('provider_note_id', $providerNote->id)
            ->where('type', 'preventive_screening')
            ->delete();

        foreach ($screeningItems as $item) {
            Alert::create([
                'organization_id'   => $visit->organization_id ?: 1,
                'facility_id'       => $visit->facility_id,
                'client_id'         => $visit->client_id,
                'visit_id'          => $visit->id,
                'caregiver_id'      => $visit->caregiver_id,
                'provider_id'       => $providerNote->provider_id,
                'provider_note_id'  => $providerNote->id,
                'type'              => 'preventive_screening',
                'title'             => $item,
                'severity'          => 'info',
                'message'           => 'Preventive screening or immunization item reviewed/flagged: ' . $item,
                'resolved'          => false,
            ]);
        }

        if (!empty($screeningOther)) {
            Alert::create([
                'organization_id'   => $visit->organization_id ?: 1,
                'facility_id'       => $visit->facility_id,
                'client_id'         => $visit->client_id,
                'visit_id'          => $visit->id,
                'caregiver_id'      => $visit->caregiver_id,
                'provider_id'       => $providerNote->provider_id,
                'provider_note_id'  => $providerNote->id,
                'type'              => 'preventive_screening',
                'title'             => 'Other preventive care note',
                'severity'          => 'info',
                'message'           => $screeningOther,
                'resolved'          => false,
            ]);
        }
    }

 public function edit(ProviderNote $providerNote)
{
    $user = auth()->user();
    abort_if(!$user, 403, 'Unauthorized.');

    $providerNote->load(['visit.client', 'visit.caregiver']);

    $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

    if ($facilityId && (int) ($providerNote->visit->facility_id ?? 0) !== (int) $facilityId) {
        abort(403, 'Unauthorized.');
    }

    return view('provider.notes.edit', compact('providerNote'));
}

public function update(Request $request, ProviderNote $providerNote)
{
    $user = auth()->user();
    abort_if(!$user, 403, 'Unauthorized.');

    $providerNote->load(['visit.client']);

    $validated = $request->validate([
        'chief_complaint' => ['nullable', 'string'],
        'subjective' => ['nullable', 'string'],
        'objective' => ['nullable', 'string'],
        'assessment' => ['nullable', 'string'],
        'plan' => ['nullable', 'string'],
    ]);

    $client = $providerNote->visit?->client;

    $clientName = $client?->name ?? 'Unknown Client';
    $clientDob = $client?->date_of_birth ?? 'N/A';

    $combinedNote = trim(
        "Client: " . $clientName . "\n" .
        "DOB: " . $clientDob . "\n\n" .
        "S: " . ($validated['subjective'] ?? '') . "\n\n" .
        "O: " . ($validated['objective'] ?? '') . "\n\n" .
        "A: " . ($validated['assessment'] ?? '') . "\n\n" .
        "P: " . ($validated['plan'] ?? '')
    );

$providerNote->update([
    'chief_complaint' => $validated['chief_complaint'] ?? null,
    'subjective' => $validated['subjective'] ?? null,
    'objective' => $validated['objective'] ?? null,
    'assessment' => $validated['assessment'] ?? null,
    'plan' => $validated['plan'] ?? null,
    'note' => $combinedNote,
]);
    return redirect()
        ->route('provider.notes.show', $providerNote->id)
        ->with('success', 'Note updated successfully.');
}
    public function show(ProviderNote $providerNote)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $providerNote->load(['visit.client', 'visit.caregiver']);

        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        if ($facilityId && (int) ($providerNote->visit->facility_id ?? 0) !== (int) $facilityId) {
            abort(403, 'Unauthorized.');
        }

        return view('provider.notes.show', compact('providerNote'));
    }
}
