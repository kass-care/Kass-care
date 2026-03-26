@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Care Logs</h1>
                <p class="text-slate-600 mt-2">Saved caregiver documentation and ADL charting.</p>
            </div>

            <a href="{{ route('caregiver.care-logs.create') }}"
               class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                + New Care Log
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Visit</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Client</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Caregiver</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($careLogs as $careLog)
                        <tr>
                            <td class="px-6 py-4 text-sm text-slate-700">#{{ $careLog->visit->id ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $careLog->visit->client->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $careLog->visit->caregiver->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $careLog->created_at?->format('M d, Y h:i A') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('caregiver.care-logs.show', $careLog) }}"
                                   class="text-indigo-600 hover:text-indigo-800 font-medium">
                                    View Log
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                No care logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
