<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CaregiverController extends Controller
{
    public function index()
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $caregivers = User::where('role', 'caregiver')
            ->where('facility_id', $facilityId)
            ->latest()
            ->get();

        return view('facility.caregivers.index', compact('caregivers'));
    }

    public function create()
    {
        return view('facility.caregivers.create');
    }

    public function store(Request $request)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'caregiver',
            'facility_id' => $facilityId,
        ]);

        return redirect()
            ->route('facility.caregivers.index')
            ->with('success', 'Caregiver created successfully.');
    }

    public function edit($id)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $caregiver = User::where('role', 'caregiver')
            ->where('facility_id', $facilityId)
            ->findOrFail($id);

        return view('facility.caregivers.edit', compact('caregiver'));
    }

    public function update(Request $request, $id)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $caregiver = User::where('role', 'caregiver')
            ->where('facility_id', $facilityId)
            ->findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $caregiver->id],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $caregiver->name = $request->name;
        $caregiver->email = $request->email;

        if ($request->filled('password')) {
            $caregiver->password = Hash::make($request->password);
        }

        $caregiver->save();

        return redirect()
            ->route('facility.caregivers.index')
            ->with('success', 'Caregiver updated successfully.');
    }

    public function destroy($id)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $caregiver = User::where('role', 'caregiver')
            ->where('facility_id', $facilityId)
            ->findOrFail($id);

        $caregiver->delete();

        return redirect()
            ->route('facility.caregivers.index')
            ->with('success', 'Caregiver deleted successfully.');
    }
}
