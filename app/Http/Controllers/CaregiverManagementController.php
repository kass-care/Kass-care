<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CaregiverManagementController extends Controller
{
    public function index()
    {
        $caregivers = User::where('role', 'caregiver')
            ->with('facility')
            ->latest()
            ->get();

        return view('admin.caregivers.index', compact('caregivers'));
    }

    public function create()
    {
        $facilities = Facility::orderBy('name')->get();

        return view('admin.caregivers.create', compact('facilities'));
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
            'role' => 'caregiver',
            'facility_id' => $request->facility_id,
        ]);

        return redirect()
            ->route('admin.caregivers.index')
            ->with('success', 'Caregiver created successfully.');
    }

    public function edit(User $caregiver)
    {
        abort_unless($caregiver->role === 'caregiver', 404);

        $facilities = Facility::orderBy('name')->get();

        return view('admin.caregivers.edit', compact('caregiver', 'facilities'));
    }

    public function update(Request $request, User $caregiver)
    {
        abort_unless($caregiver->role === 'caregiver', 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $caregiver->id],
            'password' => ['nullable', 'string', 'min:6'],
            'facility_id' => ['nullable', 'exists:facilities,id'],
        ]);

        $caregiver->name = $request->name;
        $caregiver->email = $request->email;
        $caregiver->facility_id = $request->facility_id;

        if ($request->filled('password')) {
            $caregiver->password = Hash::make($request->password);
        }

        $caregiver->save();

        return redirect()
            ->route('admin.caregivers.index')
            ->with('success', 'Caregiver updated successfully.');
    }

    public function destroy(User $caregiver)
    {
        abort_unless($caregiver->role === 'caregiver', 404);

        $caregiver->delete();

        return redirect()
            ->route('admin.caregivers.index')
            ->with('success', 'Caregiver deleted successfully.');
    }
}
