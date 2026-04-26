<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $patients = Client::where('facility_id', $facilityId)
            ->latest()
            ->get();

        return view('facility.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('facility.patients.create');
    }

    public function store(Request $request)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
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
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'name' => trim($data['first_name'] . ' ' . $data['last_name']),
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
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $patient = Client::where('facility_id', $facilityId)->findOrFail($id);

        return view('facility.patients.show', compact('patient'));
    }

    public function edit($id)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $patient = Client::where('facility_id', $facilityId)->findOrFail($id);

        return view('facility.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $patient = Client::where('facility_id', $facilityId)->findOrFail($id);

        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
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

        $firstName = $data['first_name'] ?? $patient->first_name ?? null;
        $lastName = $data['last_name'] ?? $patient->last_name ?? null;

        $patient->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'name' => trim(($firstName ?? '') . ' ' . ($lastName ?? '')) ?: ($data['name'] ?? $patient->name),
            'date_of_birth' => $data['date_of_birth'] ?? $patient->date_of_birth,
            'gender' => $data['gender'] ?? $patient->gender,
            'room' => $data['room_number'] ?? $patient->room,
            'photo' => $data['photo'] ?? $patient->photo,

            'allergies' => $data['allergies'] ?? $patient->allergies,
            'psychiatrist' => $data['psychiatrist'] ?? $patient->psychiatrist,
            'cardiologist' => $data['cardiologist'] ?? $patient->cardiologist,
            'primary_care_provider' => $data['primary_care_provider'] ?? $patient->primary_care_provider,
            'pharmacy' => $data['pharmacy'] ?? $patient->pharmacy,
        ]);

        return redirect()
            ->route('facility.patients.index')
            ->with('success', 'Patient updated successfully.');
    }
}
