<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;

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

        $providers = User::where('role', 'provider')
            ->where('facility_id', $facilityId)
            ->orderBy('name')
            ->get();

        $availableProviders = User::where('role', 'provider')
            ->where(function ($query) use ($facilityId) {
                $query->whereNull('facility_id')
                    ->orWhere('facility_id', '!=', $facilityId);
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

        $validated = $request->validate([
            'provider_id' => ['required', 'exists:users,id'],
        ], [
            'provider_id.required' => 'Please select a provider.',
            'provider_id.exists'   => 'The selected provider is invalid.',
        ]);

        $provider = User::where('role', 'provider')
            ->findOrFail($validated['provider_id']);

        $provider->facility_id = $facility->id;

        if (empty($provider->organization_id) && !empty($facility->organization_id)) {
            $provider->organization_id = $facility->organization_id;
        }

        $provider->save();

        return redirect()
            ->route('facility.providers.index')
            ->with('success', 'Provider assigned successfully.');
    }
}
