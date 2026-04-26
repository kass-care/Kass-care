<?php

namespace App\Http\Controllers;

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
            ->when($facilityId, function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->findOrFail($visitId);

        $client = $visit->client;

        $clientName = $client
            ? trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''))
            : 'Unknown Client';

        if ($client && empty($clientName) && !empty($client->name)) {
            $clientName = $client->name;
        }

        $clientDob = $client->date_of_birth ?? $client->dob ?? null;

        $subjective = '';
        $objective = '';
        $assessment = '';
        $plan = '';

        $weight = null;
        $height = null;
        $bmi = null;

        $latestLog = $visit->careLogs
            ->sortByDesc('created_at')
            ->first();

        if ($latestLog) {
            if (!empty($latestLog->notes)) {
                $subjective = 'Caregiver reported: ' . $latestLog->notes;
            }

            if (!empty($latestLog->vitals) && is_array($latestLog->vitals)) {
                $vitals = $latestLog->vitals;

                $objectiveLines = ['Vitals:'];

                if (!empty($vitals['blood_pressure'])) {
                    $objectiveLines[] = '- BP: ' . $vitals['blood_pressure'];
                } elseif (!empty($vitals['bp'])) {
                    $objectiveLines[] = '- BP: ' . $vitals['bp'];
                }

                if (!empty($vitals['pulse'])) {
                    $objectiveLines[] = '- Pulse: ' . $vitals['pulse'];
                }

                if (!empty($vitals['temperature'])) {
                    $objectiveLines[] = '- Temp: ' . $vitals['temperature'];
                } elseif (!empty($vitals['temp'])) {
                    $objectiveLines[] = '- Temp: ' . $vitals['temp'];
                }

                if (!empty($vitals['oxygen'])) {
                    $objectiveLines[] = '- Oxygen: ' . $vitals['oxygen'];
                } elseif (!empty($vitals['oxygen_saturation'])) {
                    $objectiveLines[] = '- Oxygen: ' . $vitals['oxygen_saturation'];
                }

                if (!empty($vitals['weight'])) {
                    $objectiveLines[] = '- Weight: ' . $vitals['weight'];
                    $weight = $vitals['weight'];
                }

                if (!empty($vitals['height'])) {
                    $objectiveLines[] = '- Height: ' . $vitals['height'];
                    $height = $vitals['height'];
                }

                $objective = implode("\n", $objectiveLines);
            }

            $assessment = 'Patient evaluated based on caregiver observations and recorded vitals.';
        }

        $plan = 'Continue monitoring. Adjust care plan as clinically indicated.';

        return view('provider.notes.create', compact(
            'visit',
            'clientName',
            'clientDob',
            'subjective',
            'objective',
            'assessment',
            'plan',
            'weight',
            'height',
            'bmi'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $validated = $request->validate([
            'visit_id'    => ['required', 'exists:visits,id'],
            'subjective'  => ['nullable', 'string'],
            'objective'   => ['nullable', 'string'],
            'assessment'  => ['nullable', 'string'],
            'plan'        => ['nullable', 'string'],
        ]);

        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        $visit = Visit::with(['client', 'caregiver'])
            ->when($facilityId, function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->findOrFail($validated['visit_id']);

        $client = $visit->client;

        $clientName = $client
            ? trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''))
            : 'Unknown Client';

        if ($client && empty($clientName) && !empty($client->name)) {
            $clientName = $client->name;
        }

        $clientDob = $client->date_of_birth ?? $client->dob ?? null;

        $combinedNote = trim(
            "Client: " . $clientName . "\n" .
            "DOB: " . ($clientDob ?: 'N/A') . "\n\n" .
            "S: " . ($validated['subjective'] ?? '') . "\n\n" .
            "O: " . ($validated['objective'] ?? '') . "\n\n" .
            "A: " . ($validated['assessment'] ?? '') . "\n\n" .
            "P: " . ($validated['plan'] ?? '')
        );

        ProviderNote::updateOrCreate(
            ['visit_id' => $visit->id],
            [
                'client_id'   => $visit->client_id,
                'visit_id'    => $visit->id,
                'provider_id' => auth()->id(),
                'subjective'  => $validated['subjective'] ?? null,
                'objective'   => $validated['objective'] ?? null,
                'assessment'  => $validated['assessment'] ?? null,
                'plan'        => $validated['plan'] ?? null,
                'note'        => $combinedNote,
                'status'      => 'signed',
                'signed_at'   => now(),
            ]
        );

        return redirect()
            ->route('provider.notes.index')
            ->with('success', 'Provider SOAP note saved successfully.');
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
