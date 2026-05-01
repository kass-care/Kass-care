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

        $validated = $request->validate(
            [
                'provider_id' => ['required', 'exists:users,id'],
            ],
            [
                'provider_id.required' => 'Please select a provider.',
                'provider_id.exists' => 'The selected provider is invalid.',
            ]
        );

        $provider = User::where('role', 'provider')
            ->findOrFail($validated['provider_id']);

        DB::table('facility_provider')->updateOrInsert(
            [
                'facility_id' => $facility->id,
                'provider_id' => $provider->id,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return redirect()
            ->route('providers.index')
            ->with('success', 'Provider assigned successfully.');
    }
}
