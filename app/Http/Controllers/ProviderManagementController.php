<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProviderManagementController extends Controller
{
    public function index()
    {
        $providers = User::where('role', 'provider')
            ->with('facility')
            ->latest()
            ->get();

        return view('admin.providers.index', compact('providers'));
    }

    public function create()
    {
        $facilities = Facility::orderBy('name')->get();

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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'provider',
            'facility_id' => $request->facility_id,
        ]);

        return redirect()
            ->route('admin.providers.index')
            ->with('success', 'Provider created successfully.');
    }

    public function edit(User $provider)
    {
        abort_unless($provider->role === 'provider', 404);

        $facilities = Facility::orderBy('name')->get();

        return view('admin.providers.edit', compact('provider', 'facilities'));
    }

    public function update(Request $request, User $provider)
    {
        abort_unless($provider->role === 'provider', 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $provider->id],
            'password' => ['nullable', 'string', 'min:6'],
            'facility_id' => ['nullable', 'exists:facilities,id'],
        ]);

        $provider->name = $request->name;
        $provider->email = $request->email;
        $provider->facility_id = $request->facility_id;

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
        abort_unless($provider->role === 'provider', 404);

        $provider->delete();

        return redirect()
            ->route('admin.providers.index')
            ->with('success', 'Provider deleted successfully.');
    }
}
