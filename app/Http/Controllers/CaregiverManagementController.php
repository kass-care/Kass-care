<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use App\Models\Facility;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CaregiverManagementController extends Controller
{
    private function currentUser()
    {
        return auth()->user();
    }

    private function isSuperAdmin(): bool
    {
        $user = $this->currentUser();

        return $user && $user->role === 'super_admin';
    }

    private function isAdmin(): bool
    {
        $user = $this->currentUser();

        return $user && $user->role === 'admin';
    }

    private function allowedFacilities()
    {
        $selectedFacilityId = session('facility_id');
        $user = $this->currentUser();

        if ($this->isSuperAdmin()) {
            if ($selectedFacilityId) {
                return Facility::where('id', $selectedFacilityId)
                    ->orderBy('name')
                    ->get();
            }

            return Facility::orderBy('name')->get();
        }

        if ($this->isAdmin() && $user?->facility_id) {
            return Facility::where('id', $user->facility_id)
                ->orderBy('name')
                ->get();
        }

        return collect();
    }

    private function caregiverQuery()
    {
        $query = User::where('role', 'caregiver')
            ->with('facility')
            ->latest();

        $selectedFacilityId = session('facility_id');

        if ($this->isSuperAdmin()) {
            if ($selectedFacilityId) {
                return $query->where('facility_id', $selectedFacilityId);
            }

            return $query;
        }

        if ($this->isAdmin()) {
            return $query->where('facility_id', $this->currentUser()->facility_id);
        }

        abort(403, 'Unauthorized');
    }

    private function resolvedFacilityId(?int $requestedFacilityId = null): ?int
    {
        $selectedFacilityId = session('facility_id');

        if ($this->isSuperAdmin()) {
            return $selectedFacilityId ?: $requestedFacilityId;
        }

        if ($this->isAdmin()) {
            return $this->currentUser()->facility_id;
        }

        abort(403, 'Unauthorized');
    }

    private function ensureCaregiverAccessible(User $caregiver): void
    {
        abort_unless($caregiver->role === 'caregiver', 404);

        $selectedFacilityId = session('facility_id');

        if ($this->isSuperAdmin()) {
            if ($selectedFacilityId && (int) $caregiver->facility_id !== (int) $selectedFacilityId) {
                abort(403, 'Unauthorized');
            }

            return;
        }

        if ($this->isAdmin() && (int) $caregiver->facility_id === (int) $this->currentUser()->facility_id) {
            return;
        }

        abort(403, 'Unauthorized');
    }

    private function ensureFacilityAccessible(Facility $facility): void
    {
        if ($this->isSuperAdmin()) {
            $selectedFacilityId = session('facility_id');

            if ($selectedFacilityId && (int) $facility->id !== (int) $selectedFacilityId) {
                abort(403, 'Unauthorized');
            }

            return;
        }

        if ($this->isAdmin() && (int) $facility->id === (int) $this->currentUser()->facility_id) {
            return;
        }

        abort(403, 'Unauthorized');
    }

    private function resolveOrganizationId(Facility $facility): int
    {
        if (!empty($facility->organization_id)) {
            return (int) $facility->organization_id;
        }

        $organizationName = trim(($facility->name ?? 'Facility') . ' Organization');

        $organization = Organization::create([
            'name' => $organizationName,
        ]);

        $facility->organization_id = $organization->id;
        $facility->save();

        return (int) $organization->id;
    }

    private function syncCaregiverProfile(User $user, Facility $facility): Caregiver
    {
        $organizationId = $this->resolveOrganizationId($facility);

        $caregiver = Caregiver::where('user_id', $user->id)->first();

        if (!$caregiver) {
            $caregiver = Caregiver::where('email', $user->email)->first();
        }

        if (!$caregiver) {
            $caregiver = new Caregiver();
        }

        $caregiver->user_id = $user->id;
        $caregiver->facility_id = $facility->id;
        $caregiver->organization_id = $organizationId;
        $caregiver->name = $user->name;
        $caregiver->email = $user->email;
        $caregiver->phone = $user->phone;
        $caregiver->status = $caregiver->status ?: 'Active';
        $caregiver->save();

        return $caregiver;
    }

    public function index()
    {
        $caregivers = $this->caregiverQuery()->get();

        return view('admin.caregivers.index', compact('caregivers'));
    }

    public function create()
    {
        $facilities = $this->allowedFacilities();

        return view('admin.caregivers.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'facility_id' => ['nullable', 'exists:facilities,id'],
            'phone' => ['nullable', 'string', 'max:255'],
        ]);

        $facilityId = $this->resolvedFacilityId(
            $request->filled('facility_id') ? (int) $request->facility_id : null
        );

        abort_if(!$facilityId, 422, 'A facility is required before creating a caregiver.');

        $facility = Facility::findOrFail($facilityId);
        $this->ensureFacilityAccessible($facility);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'caregiver',
            'facility_id' => $facility->id,
            'phone' => $request->phone,
        ]);

        $this->syncCaregiverProfile($user, $facility);

        return redirect()
            ->route('admin.caregivers.index')
            ->with('success', 'Caregiver created successfully.');
    }

    public function show(User $caregiver)
    {
        $this->ensureCaregiverAccessible($caregiver);

        return view('admin.caregivers.show', compact('caregiver'));
    }

    public function edit(User $caregiver)
    {
        $this->ensureCaregiverAccessible($caregiver);

        $facilities = $this->allowedFacilities();

        return view('admin.caregivers.edit', compact('caregiver', 'facilities'));
    }

    public function update(Request $request, User $caregiver)
    {
        $this->ensureCaregiverAccessible($caregiver);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($caregiver->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'facility_id' => ['nullable', 'exists:facilities,id'],
            'phone' => ['nullable', 'string', 'max:255'],
        ]);

        $facilityId = $this->resolvedFacilityId(
            $request->filled('facility_id') ? (int) $request->facility_id : (int) $caregiver->facility_id
        );

        abort_if(!$facilityId, 422, 'A facility is required before updating a caregiver.');

        $facility = Facility::findOrFail($facilityId);
        $this->ensureFacilityAccessible($facility);

        $caregiver->name = $request->name;
        $caregiver->email = $request->email;
        $caregiver->facility_id = $facility->id;
        $caregiver->phone = $request->phone;

        if ($request->filled('password')) {
            $caregiver->password = Hash::make($request->password);
        }

        $caregiver->save();

        $this->syncCaregiverProfile($caregiver, $facility);

        return redirect()
            ->route('admin.caregivers.index')
            ->with('success', 'Caregiver updated successfully.');
    }

    public function destroy(User $caregiver)
    {
        $this->ensureCaregiverAccessible($caregiver);

        $profile = Caregiver::where('user_id', $caregiver->id)->first();

        if ($profile) {
            $profile->delete();
        }

        $caregiver->delete();

        return redirect()
            ->route('admin.caregivers.index')
            ->with('success', 'Caregiver deleted successfully.');
    }
}
