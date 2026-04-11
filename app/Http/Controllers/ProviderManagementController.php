<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProviderManagementController extends Controller
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

        if ($this->isAdmin() && $user && $user->facility_id) {
            return Facility::where('id', $user->facility_id)
                ->orderBy('name')
                ->get();
        }

        return collect();
    }

    private function providerQuery()
    {
        $query = User::where('role', 'provider')
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

    private function ensureProviderAccessible(User $provider): void
    {
        abort_unless($provider->role === 'provider', 404);

        $selectedFacilityId = session('facility_id');

        if ($this->isSuperAdmin()) {
            if ($selectedFacilityId && (int) $provider->facility_id !== (int) $selectedFacilityId) {
                abort(403, 'Unauthorized');
            }

            return;
        }

        if ($this->isAdmin() && (int) $provider->facility_id === (int) $this->currentUser()->facility_id) {
            return;
        }

        abort(403, 'Unauthorized');
    }

    public function index()
    {
        $providers = $this->providerQuery()->get();

        return view('admin.providers.index', compact('providers'));
    }

    public function create()
    {
        $facilities = $this->allowedFacilities();

        return view('admin.providers.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'facility_id' => ['nullable', 'exists:facilities,id'],
        ]);

        $facilityId = $this->resolvedFacilityId(
            $request->filled('facility_id') ? (int) $request->facility_id : null
        );

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'provider',
            'facility_id' => $facilityId,
        ]);

        return redirect()
            ->route('admin.providers.index')
            ->with('success', 'Provider created successfully.');
    }

    public function show(User $provider)
    {
        $this->ensureProviderAccessible($provider);

        return view('admin.providers.show', compact('provider'));
    }

    public function edit(User $provider)
    {
        $this->ensureProviderAccessible($provider);

        $facilities = $this->allowedFacilities();

        return view('admin.providers.edit', compact('provider', 'facilities'));
    }

    public function update(Request $request, User $provider)
    {
        $this->ensureProviderAccessible($provider);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $provider->id],
            'password' => ['nullable', 'string', 'min:6'],
            'facility_id' => ['nullable', 'exists:facilities,id'],
        ]);

        $facilityId = $this->resolvedFacilityId(
            $request->filled('facility_id')
                ? (int) $request->facility_id
                : (int) $provider->facility_id
        );

        $provider->name = $request->name;
        $provider->email = $request->email;
        $provider->facility_id = $facilityId;

        if ($request->filled('password')) {
            $provider->password = Hash::make($request->password);
        }

        $provider->save();

        return redirect()
            ->route('admin.providers.index')
            ->with('success', 'Provider updated successfully.');
    }

    public function destroy(User $provider)
    {
        $this->ensureProviderAccessible($provider);

        $provider->delete();

        return redirect()
            ->route('admin.providers.index')
            ->with('success', 'Provider deleted successfully.');
    }
}
