@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold">
                My Patients
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                {{ $patients->count() }} {{ \Illuminate\Support\Str::plural('patient', $patients->count()) }} visible
            </p>
        </div>

        <a href="{{ route('provider.patients.create') }}"
           class="px-4 py-2 rounded-lg bg-blue-600 text-white">
            + New Patient
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-gray-100">
            <tr>
                <th class="text-left p-4">MRN</th>
                <th class="text-left p-4">Patient</th>
                <th class="text-left p-4">Facility</th>
                <th class="text-left p-4">Provider</th>
                <th class="text-left p-4">Actions</th>
            </tr>
            </thead>

            <tbody>

            @forelse($patients as $patient)

                <tr class="border-t">

                    <td class="p-4">{{ $patient->mrn }}</td>

                    <td class="p-4">{{ $patient->name }}</td>

                    <td class="p-4">
                        @if($patient->facility)
                            <span class="font-medium text-gray-800">
                                {{ $patient->facility->name }}
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">
                                Independent
                            </span>
                        @endif
                    </td>

                    <td class="p-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span>
                                {{ optional($patient->provider)->name ?? 'Unassigned' }}
                            </span>

                            @if((int) $patient->provider_id === (int) auth()->id())
                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">
                                    Your Patient
                                </span>
                            @endif
                        </div>
                    </td>

                    <td class="p-4 space-x-3">

                        <a href="{{ route('provider.patients.workspace',$patient) }}">
                            Workspace
                        </a>

                        <a href="{{ route('provider.patients.summary',$patient) }}">
                            Summary
                        </a>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        No patients found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
