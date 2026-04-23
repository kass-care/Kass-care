<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class FacilityCaregiverController extends Controller
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

        // Permanent self-heal:
        // if caregiver users exist for this facility without caregiver profiles,
        // rebuild the missing caregiver profile rows automatically.
        $this->syncFacilityCaregiverProfiles($facility);

        $caregivers = Caregiver::where('facility_id', $facilityId)
            ->latest()
            ->get();

        return view('facility.caregivers.index', compact('facility', 'caregivers'));
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

        // Self-heal before loading create page
        $this->syncFacilityCaregiverProfiles($facility);

        return view('facility.caregivers.create', compact('facility'));
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:caregivers,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:50'],
        ], [
            'name.required' => 'Caregiver name is required.',
            'email.required' => 'Caregiver email is required.',
            'email.email' => 'Please enter a valid caregiver email.',
            'email.unique' => 'That email is already in use.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        DB::transaction(function () use ($validated, $facility) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'caregiver',
                'facility_id' => $facility->id,
                'organization_id' => $facility->organization_id,
            ]);

            Caregiver::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'organization_id' => $facility->organization_id,
                    'facility_id' => $facility->id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'status' => $validated['status'] ?? 'Active',
                ]
            );
        });

        return redirect()
            ->route('facility.caregivers.index')
            ->with('success', 'Caregiver created successfully.');
    }

    public function edit(Caregiver $caregiver)
    {
        $facilityId = session('facility_id');

        if (!$facilityId) {
            return redirect()
                ->route('facility.admin.home')
                ->with('error', 'No facility selected.');
        }

        abort_if((int) $caregiver->facility_id !== (int) $facilityId, 403, 'Unauthorized.');

        $facility = Facility::findOrFail($facilityId);

        return view('facility.caregivers.edit', compact('facility', 'caregiver'));
    }

    public function update(Request $request, Caregiver $caregiver)
    {
        $facilityId = session('facility_id');

        if (!$facilityId) {
            return redirect()
                ->route('facility.admin.home')
                ->with('error', 'No facility selected.');
        }

        abort_if((int) $caregiver->facility_id !== (int) $facilityId, 403, 'Unauthorized.');

        $facility = Facility::findOrFail($facilityId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('caregivers', 'email')->ignore($caregiver->id),
                Rule::unique('users', 'email')->ignore($caregiver->user_id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated, $caregiver, $facility) {
            $caregiver->update([
                'organization_id' => $facility->organization_id,
                'facility_id' => $facility->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'status' => $validated['status'] ?? ($caregiver->status ?: 'Active'),
            ]);

            if ($caregiver->user_id) {
                $user = User::find($caregiver->user_id);
            } else {
                $user = null;
            }

            if (!$user) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password'] ?? 'TempPass123!'),
                    'role' => 'caregiver',
                    'facility_id' => $facility->id,
                    'organization_id' => $facility->organization_id,
                ]);

                $caregiver->user_id = $user->id;
                $caregiver->save();
            } else {
                $user->name = $validated['name'];
                $user->email = $validated['email'];
                $user->role = 'caregiver';
                $user->facility_id = $facility->id;
                $user->organization_id = $facility->organization_id;

                if (!empty($validated['password'])) {
                    $user->password = Hash::make($validated['password']);
                }

                $user->save();
            }
        });

        return redirect()
            ->route('facility.caregivers.index')
            ->with('success', 'Caregiver updated successfully.');
    }

    public function destroy(Caregiver $caregiver)
    {
        $facilityId = session('facility_id');

        if (!$facilityId) {
            return redirect()
                ->route('facility.admin.home')
                ->with('error', 'No facility selected.');
        }

        abort_if((int) $caregiver->facility_id !== (int) $facilityId, 403, 'Unauthorized.');

        DB::transaction(function () use ($caregiver) {
            $userId = $caregiver->user_id;

            $caregiver->delete();

            if ($userId) {
                User::where('id', $userId)->delete();
            }
        });

        return redirect()
            ->route('facility.caregivers.index')
            ->with('success', 'Caregiver deleted successfully.');
    }

    /**
     * Permanent self-heal for facilities:
     * if caregiver users exist without matching caregiver profile rows,
     * auto-create the missing caregiver profiles.
     */
    private function syncFacilityCaregiverProfiles(Facility $facility): void
    {
        $caregiverUsers = User::where('role', 'caregiver')
            ->where('facility_id', $facility->id)
            ->get();

        foreach ($caregiverUsers as $user) {
            Caregiver::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'organization_id' => $facility->organization_id,
                    'facility_id' => $facility->id,
                    'name' => $user->name ?: 'Unnamed Caregiver',
                    'email' => $user->email,
                    'phone' => $user->phone ?? null,
                    'status' => 'Active',
                ]
            );
        }
    }
}
