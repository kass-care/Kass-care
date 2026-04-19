@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Compliance Dashboard</h1>

        <a href="{{ route('provider.dashboard') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-red-100 p-6 rounded-xl">
            <h2 class="text-lg font-bold text-red-700">Missed Visits</h2>
            <p class="text-3xl font-bold">{{ $missedVisits->count() }}</p>
        </div>

        <div class="bg-yellow-100 p-6 rounded-xl">
            <h2 class="text-lg font-bold text-yellow-700">Missing Care Logs</h2>
            <p class="text-3xl font-bold">{{ $missingCareLogs->count() }}</p>
        </div>

        <div class="bg-blue-100 p-6 rounded-xl">
            <h2 class="text-lg font-bold text-blue-700">Missing Provider Notes</h2>
            <p class="text-3xl font-bold">{{ $missingNotes->count() }}</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Problem Visits</h2>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Client</th>
                        <th class="py-2">Caregiver</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Issues</th>
                        <th class="py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($missedVisits as $visit)
                        <tr class="border-b">
                            <td class="py-2">{{ $visit->client->full_name ?? 'N/A' }}</td>
                            <td class="py-2">{{ $visit->caregiver->full_name ?? 'N/A' }}</td>
                            <td class="py-2 text-red-600 font-bold">Missed</td>
                            <td class="py-2">Visit missed</td>
                            <td class="py-2">
                                <span class="text-sm text-gray-500">Review schedule</span>
                            </td>
                        </tr>
                    @endforeach

                    @foreach($missingCareLogs as $visit)
                        <tr class="border-b">
                            <td class="py-2">{{ $visit->client->full_name ?? 'N/A' }}</td>
                            <td class="py-2">{{ $visit->caregiver->full_name ?? 'N/A' }}</td>
                            <td class="py-2">{{ ucfirst(strtolower($visit->status ?? 'Scheduled')) }}</td>
                            <td class="py-2 text-yellow-600">Missing Care Log</td>
                            <td class="py-2">
                                <a href="{{ route('provider.care-logs.create', ['visit_id' => $visit->id]) }}"
                                   class="inline-flex text-sm bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600">
                                    Add Care Log
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @foreach($missingNotes as $visit)
                        <tr class="border-b">
                            <td class="py-2">{{ $visit->client->full_name ?? 'N/A' }}</td>
                            <td class="py-2">{{ $visit->caregiver->full_name ?? 'N/A' }}</td>
                            <td class="py-2">{{ ucfirst(strtolower($visit->status ?? 'Completed')) }}</td>
                            <td class="py-2 text-blue-600">Missing Provider Note</td>
                            <td class="py-2">
                                <a href="{{ route('provider.notes.create', ['visit_id' => $visit->id]) }}"
                                   class="inline-flex text-sm bg-indigo-600 text-white px-3 py-1 rounded-lg hover:bg-indigo-700">
                                    Add Note
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if($missedVisits->count() === 0 && $missingCareLogs->count() === 0 && $missingNotes->count() === 0)
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">
                                No compliance issues found.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
