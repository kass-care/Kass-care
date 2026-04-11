@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Facility Patients
            </h1>
            <p class="text-gray-500">
                Manage patients for this facility
            </p>
        </div>

        <a href="{{ route('facility.patients.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-semibold">
           + Add Patient
        </a>
    </div>


    @php
        $patients = \App\Models\Patient::where(
            'facility_id',
            session('facility_id') ?? auth()->user()->facility_id
        )->latest()->get();
    @endphp


    <div class="bg-white shadow rounded-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr class="text-left text-sm text-gray-600">
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Room</th>
                    <th class="px-6 py-3">Gender</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($patients as $patient)

                <tr class="border-t">

                    <td class="px-6 py-4 font-semibold">
                        {{ $patient->first_name }} {{ $patient->last_name }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $patient->room_number ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $patient->gender ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                            {{ $patient->status }}
                        </span>
                    </td>

                    <td class="px-6 py-4 space-x-3">

                        <a href="{{ route('facility.patients.show', $patient->id) }}"
                           class="text-indigo-600 font-semibold text-sm">
                           View
                        </a>

                        <a href="{{ route('facility.patients.edit', $patient->id) }}"
                           class="text-blue-600 font-semibold text-sm">
                           Edit
                        </a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                        No patients registered yet.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
