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

            Caregiver::create([
                'organization_id' => $facility->organization_id,
                'facility_id' => $facility->id,
                'user_id' => $user->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'status' => $validated['status'] ?? 'Active',
            ]);
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

                if ($user) {
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
                $user = User::find($userId);

                if ($user && $user->role === 'caregiver') {
                    $user->delete();
                }
            }
        });

        return redirect()
            ->route('facility.caregivers.index')
            ->with('success', 'Caregiver deleted successfully.');
    }
}
