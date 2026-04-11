<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Diagnosis;
use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function create(Client $client)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        if ($facilityId) {
            abort_if((int) $client->facility_id !== (int) $facilityId, 403, 'Unauthorized.');
        }

        $diagnoses = Diagnosis::where('client_id', $client->id)
            ->latest()
            ->get();

        return view('medications.create', compact('client', 'diagnoses'));
    }

    public function store(Request $request, Client $client)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        if ($facilityId) {
            abort_if((int) $client->facility_id !== (int) $facilityId, 403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'medication_name' => ['required', 'string', 'max:255'],
            'diagnosis_id'    => ['nullable', 'exists:diagnoses,id'],
            'dose'            => ['nullable', 'string', 'max:255'],
            'frequency'       => ['nullable', 'string', 'max:255'],
            'status'          => ['nullable', 'string', 'max:50'],
            'instructions'    => ['nullable', 'string'],
        ]);

        if (!empty($validated['diagnosis_id'])) {
            $diagnosisBelongsToClient = Diagnosis::where('id', $validated['diagnosis_id'])
                ->where('client_id', $client->id)
                ->exists();

            abort_if(!$diagnosisBelongsToClient, 403, 'Diagnosis does not belong to this patient.');
        }

        Medication::create([
            'client_id'       => $client->id,
            'diagnosis_id'    => $validated['diagnosis_id'] ?? null,
            'medication_name' => $validated['medication_name'],
            'dose'            => $validated['dose'] ?? null,
            'frequency'       => $validated['frequency'] ?? null,
            'status'          => $validated['status'] ?? 'active',
            'instructions'    => $validated['instructions'] ?? null,
            'prescribed_by'   => auth()->id(),
        ]);

        if ($user->role === 'provider') {
            return redirect()
                ->route('provider.patients.workspace', $client->id)
                ->with('success', 'Medication added successfully.');
        }

        return redirect()
            ->back()
            ->with('success', 'Medication added successfully.');
    }
}
