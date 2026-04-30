@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900">Compliance Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Review missed visits, missing care logs, and missing provider notes.
                </p>
            </div>

            <a href="{{ route('provider.dashboard') }}"
               class="rounded-xl bg-slate-700 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800">
                Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="rounded-2xl bg-red-100 p-6 border border-red-200">
                <h2 class="text-lg font-bold text-red-700">Missed Visits</h2>
                <p class="mt-2 text-4xl font-black text-red-900">{{ $missedVisits->count() }}</p>
            </div>

            <div class="rounded-2xl bg-yellow-100 p-6 border border-yellow-200">
                <h2 class="text-lg font-bold text-yellow-700">Missing Care Logs</h2>
                <p class="mt-2 text-4xl font-black text-yellow-900">{{ $missingCareLogs->count() }}</p>
            </div>

            <div class="rounded-2xl bg-blue-100 p-6 border border-blue-200">
                <h2 class="text-lg font-bold text-blue-700">Missing Provider Notes</h2>
                <p class="mt-2 text-4xl font-black text-blue-900">{{ $missingNotes->count() }}</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-200 overflow-hidden">
            <div class="border-b border-slate-200 p-6">
                <h2 class="text-xl font-bold text-slate-900">Problem Visits</h2>
                <p class="mt-1 text-sm text-slate-500">Items that need documentation or review.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="px-6 py-4">Visit</th>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Caregiver</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Issue</th>
                            <th class="px-6 py-4">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @foreach($missedVisits as $visit)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-bold">#{{ $visit->id }}</td>
                                <td class="px-6 py-4">{{ $visit->client?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $visit->caregiver?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-red-600 font-bold">Missed</td>
                                <td class="px-6 py-4">Visit missed</td>
                                <td class="px-6 py-4 text-slate-500">Review schedule</td>
                            </tr>
                        @endforeach

                        @foreach($missingCareLogs as $visit)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-bold">#{{ $visit->id }}</td>
                                <td class="px-6 py-4">{{ $visit->client?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $visit->caregiver?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ ucfirst(strtolower($visit->status ?? 'Scheduled')) }}</td>
                                <td class="px-6 py-4 text-yellow-700 font-semibold">Missing Care Log</td>
                                <td class="px-6 py-4">
                                    @if(Route::has('care-logs.create'))
                                        <a href="{{ route('care-logs.create', ['visit_id' => $visit->id]) }}"
                                           class="inline-flex rounded-lg bg-yellow-500 px-3 py-2 text-xs font-bold text-white hover:bg-yellow-600">
                                            Add Care Log
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-500">Care log route missing</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @foreach($missingNotes as $visit)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-bold">#{{ $visit->id }}</td>
                                <td class="px-6 py-4">{{ $visit->client?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $visit->caregiver?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ ucfirst(strtolower($visit->status ?? 'Completed')) }}</td>
                                <td class="px-6 py-4 text-blue-700 font-semibold">Missing Provider Note</td>
                                <td class="px-6 py-4">
                                    @if(Route::has('provider.notes.create'))
                                        <a href="{{ route('provider.notes.create', ['visit_id' => $visit->id]) }}"
                                           class="inline-flex rounded-lg bg-indigo-600 px-3 py-2 text-xs font-bold text-white hover:bg-indigo-700">
                                            Add Note
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-500">Note route missing</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if($missedVisits->count() === 0 && $missingCareLogs->count() === 0 && $missingNotes->count() === 0)
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                    No compliance issues found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
