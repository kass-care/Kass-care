<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use App\Models\Client;
use App\Models\Facility;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DutyTemplate;

class FacilityVisitController extends Controller
{
    private function facilityId()
    {
        $user = auth()->user();

        return session('facility_id') ?? ($user->facility_id ?? null);
    }

    public function index()
    {
        $facilityId = $this->facilityId();

        abort_if(!$facilityId, 403, 'No facility context.');

        $facility = Facility::findOrFail($facilityId);

        $visits = Visit::with(['client', 'caregiver', 'provider'])
            ->where('facility_id', $facilityId)
            ->latest()
            ->get();

        $clients = Client::where('facility_id', $facilityId)->orderBy('name')->get();

       $caregivers = User::where('role', 'caregiver')
    ->where('facility_id', $facilityId)
    ->orderBy('name')
    ->get();

        $providerIds = DB::table('facility_provider')
            ->where('facility_id', $facilityId)
            ->pluck('provider_id');

        $providers = User::where('role', 'provider')
    ->where(function ($query) use ($facilityId, $providerIds) {
        $query->where('facility_id', $facilityId)
              ->orWhereIn('id', $providerIds);
    })
    ->orderBy('name')
    ->get();

        return view('facility.visits.index', compact(
            'facility',
            'visits',
            'clients',
            'providers',
            'caregivers',
        ));
    }

    public function create()
    {
        $facilityId = $this->facilityId();

        abort_if(!$facilityId, 403, 'No facility context.');

        $facility = Facility::findOrFail($facilityId);

        $clients = Client::where('facility_id', $facilityId)->orderBy('name')->get();

        $caregivers = User::where('role', 'caregiver')
    ->where('facility_id', $facilityId)
    ->orderBy('name')
    ->get();

        $providerIds = DB::table('facility_provider')
            ->where('facility_id', $facilityId)
            ->pluck('provider_id');

        $providers = User::where('role', 'provider')
    ->where(function ($query) use ($facilityId, $providerIds) {
        $query->where('facility_id', $facilityId)
              ->orWhereIn('id', $providerIds);
    })
    ->orderBy('name')
    ->get();
$dutyTemplates = DutyTemplate::whereNull('facility_id')
    ->orWhere('facility_id', $facilityId)
    ->orderBy('title')
    ->get();

        return view('facility.visits.create', compact(
            'facility',
            'clients',
            'caregivers',
            'providers',
            'dutyTemplates'
        ));
    }

    public function store(Request $request)
    {
        $facilityId = $this->facilityId();

        abort_if(!$facilityId, 403, 'No facility context.');
 $validated = $request->validate([
    'client_id' => ['required', 'exists:clients,id'],
    'provider_id' => ['nullable', 'exists:users,id'],
     'caregiver_ids' => ['required', 'array', 'min:1'],
'caregiver_ids.*' => ['exists:users,id'],
'duties' => ['nullable', 'string'],
    'visit_date' => ['required', 'date'],
    'status' => ['required', 'string'],
]);
$caregivers = User::whereIn('id', $validated['caregiver_ids'])
    ->where('role', 'caregiver')
    ->where('facility_id', $facilityId)
    ->get();

abort_if($caregivers->count() !== count($validated['caregiver_ids']), 422, 'Invalid caregiver selection.');

        $client = Client::findOrFail($validated['client_id']);
        

        abort_if((int) $client->facility_id !== (int) $facilityId, 403, 'Client not in this facility.');

        $visit = Visit::create([
    'client_id' => $client->id,
    'caregiver_id' => $caregivers->first()->id,
    'provider_id' => $validated['provider_id'] ?? null,
    'visit_date' => $validated['visit_date'],
    'status' => $validated['status'],
    'activity' => $validated['activity'] ?? 'Visit',
    'duties' => $validated['duties'] ?? null,
    'facility_id' => $facilityId,
]);

$visit->caregivers()->sync($caregivers->pluck('id')->toArray());
        return redirect()
            ->route('facility.visits.index')
            ->with('success', 'Visit created successfully!');
    }

    public function show(Visit $visit)
    {
        $this->authorizeVisit($visit);

        return view('facility.visits.show', compact('visit'));
    }

    public function edit(Visit $visit)
    {
        $this->authorizeVisit($visit);

        $facilityId = $visit->facility_id;
        $facility = Facility::findOrFail($facilityId);

        $clients = Client::where('facility_id', $facilityId)->orderBy('name')->get();
        $caregivers = User::where('role', 'caregiver')
    ->where('facility_id', $facilityId)
    ->orderBy('name')
    ->get();

        $providerIds = DB::table('facility_provider')
            ->where('facility_id', $facilityId)
            ->pluck('provider_id');

        $providers = User::whereIn('id', $providerIds)->orderBy('name')->get();

        return view('facility.visits.edit', compact(
            'visit',
            'facility',
            'clients',
            'caregivers',
            'providers'
        ));
    }

    public function update(Request $request, Visit $visit)
    {
        $this->authorizeVisit($visit);

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'caregiver_id' => ['required', 'exists:users,id'],
            'provider_id' => ['nullable', 'exists:users,id'],
            'visit_date' => ['required', 'date'],
            'status' => ['required', 'string', 'max:50'],
            'activity' => ['nullable', 'string', 'max:255'],
        ]);

        $client = Client::findOrFail($validated['client_id']);
        $caregiver = User::where('role', 'caregiver')->findOrFail($validated['caregiver_id']);

        abort_if((int) $client->facility_id !== (int) $visit->facility_id, 403);

        $visit->update([
            'client_id' => $client->id,
            'caregiver_id' => $caregiver->id,
            'provider_id' => $validated['provider_id'] ?? null,
            'visit_date' => $validated['visit_date'],
            'status' => $validated['status'],
            'activity' => $validated['activity'] ?? $visit->activity,
        ]);

        return redirect()
            ->route('facility.visits.index')
            ->with('success', 'Visit updated successfully!');
    }

    public function destroy(Visit $visit)
    {
        $this->authorizeVisit($visit);

        $visit->delete();

        return redirect()
            ->route('facility.visits.index')
            ->with('success', 'Visit deleted successfully!');
    }

    private function authorizeVisit(Visit $visit): void
    {
        $facilityId = $this->facilityId();

        abort_if(!$facilityId, 403, 'No facility context.');

        abort_if(
            (int) $visit->facility_id !== (int) $facilityId,
            403,
            'Unauthorized visit access.'
        );
    }
}
