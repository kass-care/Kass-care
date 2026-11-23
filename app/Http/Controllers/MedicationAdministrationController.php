<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\MedicationAdministration;
use Illuminate\Http\Request;

class MedicationAdministrationController extends Controller
{
    public function store(Request $request, Medication $medication)
    {
        $data = $request->validate([
            'status' => ['required', 'in:given,missed,refused,held,prn'],
            'scheduled_time' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        MedicationAdministration::create([
            'medication_id' => $medication->id,
            'client_id' => $medication->client_id,
            'caregiver_id' => auth()->id(),
            'administration_date' => now()->toDateString(),
            'scheduled_time' => $data['scheduled_time'] ?? null,
            'administered_at' => in_array($data['status'], ['given', 'prn']) ? now() : null,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'Medication administration recorded successfully.');
    }
}
