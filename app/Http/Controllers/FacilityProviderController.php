<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityProviderController extends Controller
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

        $providers = User::query()
            ->where('role', 'provider')
            ->whereIn('id', function ($query) use ($facilityId) {
                $query->select('provider_id')
                    ->from('facility_provider')
                    ->where('facility_id', $facilityId);
            })
            ->orderBy('name')
            ->get();

        $assignedProviderIds = $providers->pluck('id')->toArray();

        $availableProviders = User::query()
            ->where('role', 'provider')
            ->when(count($assignedProviderIds) > 0, function ($query) use ($assignedProviderIds) {
                $query->whereNotIn('id', $assignedProviderIds);
            })
            ->orderBy('name')
            ->get();

        return view('facility.providers.index', compact(
            'facility',
            'providers',
            'availableProviders'
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

        $facility = Facility::findOrFail($facilityId);

        /*
        |--------------------------------------------------------------------------
        | Option 1: Assign existing provider from dropdown
        |--------------------------------------------------------------------------
        */
        if ($request->filled('provider_id')) {
            $request->validate([
                'provider_id' => ['required', 'exists:users,id'],
            ]);

            $provider = User::where('role', 'provider')
                ->findOrFail($request->provider_id);

            $this->linkProviderToFacility($facility->id, $provider->id);

            return redirect()
                ->route('providers.index')
                ->with('success', 'Provider assigned successfully.');
        }

        /*
        |--------------------------------------------------------------------------
        | Option 2: Link provider by email
        |--------------------------------------------------------------------------
        */
        if ($request->filled('provider_email')) {
            $request->validate([
                'provider_email' => ['required', 'email'],
            ]);

            $provider = User::where('role', 'provider')
                ->where('email', $request->provider_email)
                ->first();

            if (!$provider) {
                return redirect()
                    ->route('providers.index')
                    ->with('error', 'Provider not found. Ask the provider to register first at /register-provider, then link them here.');
            }

            $this->linkProviderToFacility($facility->id, $provider->id);

            return redirect()
                ->route('providers.index')
                ->with('success', 'Provider linked successfully by email.');
        }

        return redirect()
            ->route('providers.index')
            ->withErrors([
                'provider' => 'Please select a provider or enter a provider email.',
            ]);
    }

    private function linkProviderToFacility(int $facilityId, int $providerId): void
    {
        DB::table('facility_provider')->updateOrInsert(
            [
                'facility_id' => $facilityId,
                'provider_id' => $providerId,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
