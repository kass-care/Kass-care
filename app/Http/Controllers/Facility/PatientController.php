<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    private function facilityId()
    {
        $user = auth()->user();

        if ($user && $user->role === 'admin') {
            return $user->facility_id;
        }

        return session('facility_id') ?? $user?->facility_id;
    }

    public function index()
    {
        $facilityId = $this->facilityId();

        $patients = Client::withoutGlobalScopes()
            ->where('facility_id', $facilityId)
            ->orderByDesc('id')
            ->get();

        return view('facility.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('facility.patients.create');
    }

    public function store(Request $request)
    {
        $facilityId = $this->facilityId();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'room_number' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'allergies' => ['nullable', 'string'],
            'psychiatrist' => ['nullable', 'string', 'max:255'],
            'cardiologist' => ['nullable', 'string', 'max:255'],
            'primary_care_provider' => ['nullable', 'string', 'max:255'],
            'pharmacy' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('clients', 'public');
        }

        Client::create([
            'name' => $data['name'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'room' => $data['room_number'] ?? null,
            'facility_id' => $facilityId,
            'photo' => $data['photo'] ?? null,
            'allergies' => $data['allergies'] ?? null,
            'psychiatrist' => $data['psychiatrist'] ?? null,
            'cardiologist' => $data['cardiologist'] ?? null,
            'primary_care_provider' => $data['primary_care_provider'] ?? null,
            'pharmacy' => $data['pharmacy'] ?? null,
        ]);

        return redirect()
            ->route('facility.patients.index')
            ->with('success', 'Patient created successfully.');
    }

    public function show($id)
    {
        $facilityId = $this->facilityId();

        $patient = Client::withoutGlobalScopes()
            ->where('facility_id', $facilityId)
            ->findOrFail($id);

        return view('facility.patients.show', compact('patient'));
    }

    public function edit($id)
    {
        $facilityId = $this->facilityId();

        $patient = Client::withoutGlobalScopes()
            ->where('facility_id', $facilityId)
            ->findOrFail($id);

        return view('facility.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $facilityId = $this->facilityId();

        $patient = Client::withoutGlobalScopes()
            ->where('facility_id', $facilityId)
            ->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'room_number' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'allergies' => ['nullable', 'string'],
            'psychiatrist' => ['nullable', 'string', 'max:255'],
            'cardiologist' => ['nullable', 'string', 'max:255'],
            'primary_care_provider' => ['nullable', 'string', 'max:255'],
            'pharmacy' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('clients', 'public');
        }

        $patient->update([
            'name' => $data['name'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'room' => $data['room_number'] ?? null,
            'photo' => $data['photo'] ?? $patient->photo,
            'allergies' => $data['allergies'] ?? null,
            'psychiatrist' => $data['psychiatrist'] ?? null,
            'cardiologist' => $data['cardiologist'] ?? null,
            'primary_care_provider' => $data['primary_care_provider'] ?? null,
            'pharmacy' => $data['pharmacy'] ?? null,
        ]);

        return redirect()
            ->route('facility.patients.index')
            ->with('success', 'Patient updated successfully.');
    }
}
