<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $patients = Patient::where('facility_id', $facilityId)
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

        $request->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender'        => ['nullable', 'string', 'max:50'],
            'room_number'   => ['nullable', 'string', 'max:255'],
        ]);

        Patient::create([
            'facility_id'   => $facilityId,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'gender'        => $request->gender,
            'room_number'   => $request->room_number,
            'status'        => 'active',
        ]);

        return redirect()
            ->route('facility.patients.index')
            ->with('success', 'Patient created successfully.');
    }

    public function show($id)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $patient = Patient::where('facility_id', $facilityId)->findOrFail($id);

        return view('facility.patients.show', compact('patient'));
    }

    public function edit($id)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $patient = Patient::where('facility_id', $facilityId)->findOrFail($id);

        return view('facility.patients.edit', compact('patient'));
    }
}
