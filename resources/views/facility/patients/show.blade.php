@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">
        Patient Profile
    </h1>

    <div class="bg-white shadow rounded-lg p-6 space-y-4">

        <div>
            <strong>Name:</strong>
            {{ $patient->first_name }} {{ $patient->last_name }}
        </div>

        <div>
            <strong>Date of Birth:</strong>
            {{ $patient->dob ?? 'Not provided' }}
        </div>

        <div>
            <strong>Facility ID:</strong>
            {{ $patient->facility_id }}
        </div>

        <div>
            <strong>Patient ID:</strong>
            {{ $patient->id }}
        </div>

    </div>

    <div class="mt-6">
        <a href="{{ route('facility.patients.index') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded">
            Back to Patients
        </a>
    </div>

</div>

@endsection
