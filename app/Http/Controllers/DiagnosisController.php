<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Diagnosis;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    /**
     * Return the active facility context for the authenticated user.
     */
    private function currentFacilityId($user): ?int
    {
        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        return $facilityId !== null ? (int) $facilityId : null;
    }

    /**
     * Authorize access to a patient through direct provider ownership
     * or through the active facility context.
     */
    private function authorizeClientAccess(Client $client): void
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        if (
            $user->role === 'provider'
            && (int) $client->provider_id === (int) $user->id
        ) {
            return;
        }

        $facilityId = $this->currentFacilityId($user);

        if (
            $facilityId !== null
            && $client->facility_id !== null
            && (int) $client->facility_id === $facilityId
        ) {
            return;
        }

        /*
         * Preserve the previous unrestricted behavior for privileged users
         * who do not operate inside a facility context.
         */
        if ($user->role !== 'provider' && $facilityId === null) {
            return;
        }

        abort(403, 'Unauthorized.');
    }

    public function index()
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = $this->currentFacilityId($user);

        $diagnoses = Diagnosis::with(['client', 'user'])
            ->when($user->role === 'provider', function ($query) use ($user, $facilityId) {
                $query->whereHas('client', function ($clientQuery) use ($user, $facilityId) {
                    $clientQuery->where(function ($visibilityQuery) use ($user, $facilityId) {
                        $visibilityQuery->where('provider_id', $user->id);

                        if ($facilityId !== null) {
                            $visibilityQuery->orWhere('facility_id', $facilityId);
                        }
                    });
                });
            })
            ->when(
                $user->role !== 'provider' && $facilityId !== null,
                function ($query) use ($facilityId) {
                    $query->whereHas('client', function ($clientQuery) use ($facilityId) {
                        $clientQuery->where('facility_id', $facilityId);
                    });
                }
            )
            ->latest()
            ->get();

        return view('provider.diagnoses.index', compact('diagnoses'));
    }

    public function create(Client $client)
    {
        $user = auth()->user();

        $this->authorizeClientAccess($client);

        return view('diagnoses.create', compact('client'));
    }

    public function store(Request $request, Client $client)
    {
        $user = auth()->user();

        $this->authorizeClientAccess($client);

        $validated = $request->validate([
            'diagnosis_name' => ['required', 'string', 'max:255'],
            'icd_code'       => ['nullable', 'string', 'max:255'],
            'notes'          => ['nullable', 'string'],
            'status'         => ['required', 'string', 'max:255'],
        ]);

        $client->diagnoses()->create([
            'user_id'         => $user->id,
            'diagnosis_name'  => $validated['diagnosis_name'],
            'icd_code'        => $validated['icd_code'] ?? null,
            'notes'           => $validated['notes'] ?? null,
            'status'          => $validated['status'],
        ]);

        if ($user->role === 'provider') {
            return redirect()
                ->route('provider.patients.workspace', $client->id)
                ->with('success', 'Diagnosis added successfully.');
        }

        return redirect()->back()->with('success', 'Diagnosis added successfully.');
    }

    public function edit(Client $client, Diagnosis $diagnosis)
    {
        $user = auth()->user();

        $this->authorizeClientAccess($client);

        abort_if((int) $diagnosis->client_id !== (int) $client->id, 404);

        return view('diagnoses.edit', compact('client', 'diagnosis'));
    }

    public function update(Request $request, Client $client, Diagnosis $diagnosis)
    {
        $user = auth()->user();

        $this->authorizeClientAccess($client);

        abort_if((int) $diagnosis->client_id !== (int) $client->id, 404);

        $validated = $request->validate([
            'diagnosis_name' => ['required', 'string', 'max:255'],
            'icd_code'       => ['nullable', 'string', 'max:255'],
            'notes'          => ['nullable', 'string'],
            'status'         => ['required', 'string', 'max:255'],
        ]);

        $diagnosis->update([
            'diagnosis_name' => $validated['diagnosis_name'],
            'icd_code'       => $validated['icd_code'] ?? null,
            'notes'          => $validated['notes'] ?? null,
            'status'         => $validated['status'],
        ]);

        if ($user->role === 'provider') {
            return redirect()
                ->route('provider.patients.workspace', $client->id)
                ->with('success', 'Diagnosis updated successfully.');
        }

        return redirect()->back()->with('success', 'Diagnosis updated successfully.');
    }

    public function destroy(Client $client, Diagnosis $diagnosis)
    {
        $user = auth()->user();

        $this->authorizeClientAccess($client);

        abort_if((int) $diagnosis->client_id !== (int) $client->id, 404);

        $diagnosis->delete();

        if ($user->role === 'provider') {
            return redirect()
                ->route('provider.patients.workspace', $client->id)
                ->with('success', 'Diagnosis deleted successfully.');
        }

        return redirect()->back()->with('success', 'Diagnosis deleted successfully.');
    }
}
