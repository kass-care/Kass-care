<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Client;

class ShiftController extends Controller
{
public function index()
{
    $user = auth()->user();

    abort_if(!$user, 403);

    $facilityId = session('facility_id') ?? $user->facility_id;

    $shifts = Shift::with(['caregiver', 'clients'])
        ->where('facility_id', $facilityId)
        ->latest('shift_date')
        ->latest()
        ->get();

    $caregivers = User::where('role', 'caregiver')
        ->where('facility_id', $facilityId)
        ->orderBy('name')
        ->get();

    $clients = Client::where('facility_id', $facilityId)
        ->orderBy('name')
        ->get();

    return view('facility.shifts.index', compact(
        'shifts',
        'caregivers',
        'clients'
    ));
}
public function store(Request $request)
{
    $user = auth()->user();

    abort_if(!$user, 403);

    $facilityId = session('facility_id') ?? $user->facility_id;

    $validated = $request->validate([
        'caregiver_id' => ['required', 'exists:users,id'],
        'client_ids' => ['nullable', 'array'],
        'client_ids.*' => ['exists:clients,id'],
        'shift_date' => ['required', 'date'],
        'shift_start' => ['nullable'],
        'shift_end' => ['nullable'],
        'duties' => ['nullable', 'array'],
        'special_instructions' => ['nullable', 'string'],
        'notes' => ['nullable', 'string'],
    ]);

    $shift = Shift::create([
        'facility_id' => $facilityId,
        'caregiver_id' => $validated['caregiver_id'],
        'shift_date' => $validated['shift_date'],
        'shift_start' => $validated['shift_start'] ?? null,
        'shift_end' => $validated['shift_end'] ?? null,
        'notes' => $validated['notes'] ?? null,
        'duties' => $validated['duties'] ?? [],
        'special_instructions' => $validated['special_instructions'] ?? null,
        'status' => 'scheduled',
        'created_by' => $user->id,
    ]);

    if (!empty($validated['client_ids'])) {
        $shift->clients()->sync($validated['client_ids']);
    }

    return back()->with('success', 'Shift scheduled successfully.');
}
}
