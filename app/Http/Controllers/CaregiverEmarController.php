<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\EmarAdministration;
use App\Models\Medication;
use Illuminate\Http\Request;

class CaregiverEmarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;

        $clients = Client::where('facility_id', $facilityId)
            ->with(['medications' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('medication_name');
            }])
            ->orderBy('name')
            ->get();

        $today = now()->toDateString();

        $administrations = EmarAdministration::where('facility_id', $facilityId)
            ->whereDate('scheduled_date', $today)
            ->get()
            ->groupBy(fn ($row) => $row->medication_id . '|' . ($row->scheduled_time ?? ''));

        return view('caregiver.emar.index', compact(
            'clients',
            'today',
            'administrations'
        ));
    }

    public function administer(Request $request, Medication $medication)
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;

        abort_if((int) $medication->client->facility_id !== (int) $facilityId, 403);

        $validated = $request->validate([
            'scheduled_time' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:given,refused,held,missed,side_effects'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        EmarAdministration::updateOrCreate(
            [
                'facility_id' => $facilityId,
                'client_id' => $medication->client_id,
                'medication_id' => $medication->id,
                'scheduled_date' => now()->toDateString(),
                'scheduled_time' => $validated['scheduled_time'] ?? null,
            ],
            [
                'caregiver_id' => $user->id,
                'status' => $validated['status'],
                'administered_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return back()->with('success', 'Medication administration recorded.');
    }
}
