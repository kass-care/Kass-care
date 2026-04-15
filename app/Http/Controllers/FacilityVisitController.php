<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use App\Models\Facility;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;

class FacilityVisitController extends Controller
{
    public function index()
    {
        $facilityId = session('facility_id');

        if (!$facilityId) {
            return redirect()
                ->route('facility.admin.home')
                ->with('error', 'No facility selected.');
        }

        $facility = Facility::findOrFail($facilityId);

        $visits = Visit::where('facility_id', $facilityId)
            ->with(['client', 'caregiver', 'provider'])
            ->latest('visit_date')
            ->latest('id')
            ->get();

        $patients = Patient::where('facility_id', $facilityId)
            ->get()
            ->keyBy('id');

        $providers = User::where('facility_id', $facilityId)
            ->where('role', 'provider')
            ->orderBy('name')
            ->get()
            ->keyBy('id');

        $caregivers = Caregiver::where('facility_id', $facilityId)
            ->orderBy('name')
            ->get()
            ->keyBy('id');

        return view('facility.visits.index', compact(
            'facility',
            'visits',
            'patients',
            'providers',
            'caregivers'
        ));
    }

    public function create()
    {
        $facilityId = session('facility_id');

        if (!$facilityId) {
            return redirect()
                ->route('facility.admin.home')
                ->with('error', 'No facility selected.');
        }

        $facility = Facility::findOrFail($facilityId);

        $clients = Patient::where('facility_id', $facilityId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $providers = User::where('facility_id', $facilityId)
            ->where('role', 'provider')
            ->orderBy('name')
            ->get();

        $caregivers = Caregiver::where('facility_id', $facilityId)
            ->orderBy('name')
            ->get();

        return view('facility.visits.create', compact(
            'facility',
            'clients',
            'providers',
            'caregivers'
        ));
    }

    public function store(Request $request)
    {
        $facilityId = session('facility_id');

        if (!$facilityId) {
            return redirect()
                ->route('facility.admin.home')
                ->with('error', 'No facility selected.');
        }

        $validated = $request->validate([
            'client_id'    => ['required', 'exists:patients,id'],
            'provider_id'  => ['nullable', 'exists:users,id'],
            'caregiver_id' => ['nullable', 'exists:caregivers,id'],
            'visit_date'   => ['required', 'date'],
            'status'       => ['nullable', 'string'],
        ], [
            'client_id.required'  => 'Please select a client.',
            'visit_date.required' => 'Please choose a visit date.',
        ]);

        $patient = Patient::where('facility_id', $facilityId)
            ->findOrFail($validated['client_id']);

        $providerId = null;
        if (!empty($validated['provider_id'])) {
            $provider = User::where('facility_id', $facilityId)
                ->where('role', 'provider')
                ->findOrFail($validated['provider_id']);

            $providerId = $provider->id;
        }

        $caregiverId = null;
        if (!empty($validated['caregiver_id'])) {
            $caregiver = Caregiver::where('facility_id', $facilityId)
                ->findOrFail($validated['caregiver_id']);

            $caregiverId = $caregiver->id;
        }

        Visit::create([
            'facility_id'  => $facilityId,
            'client_id'    => $patient->id,
            'provider_id'  => $providerId,
            'caregiver_id' => $caregiverId,
            'visit_date'   => $validated['visit_date'],
            'status'       => $validated['status'] ?? 'scheduled',
        ]);

        return redirect()
            ->route('facility.visits.index')
            ->with('success', 'Visit scheduled successfully.');
    }

    public function show(Visit $visit)
    {
        $facilityId = session('facility_id');

        if (!$facilityId) {
            return redirect()
                ->route('facility.admin.home')
                ->with('error', 'No facility selected.');
        }

        abort_if((int) $visit->facility_id !== (int) $facilityId, 403, 'Unauthorized.');

        $visit->load(['client', 'provider', 'caregiver']);

        return view('facility.visits.show', compact('visit'));
    }
}
