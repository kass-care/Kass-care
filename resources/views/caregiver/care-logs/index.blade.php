@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Care Logs</h1>
            <p class="text-slate-600 mt-1">Only logs linked to your assigned visits.</p>
        </div>

        <a href="{{ route('provider.care-logs.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-semibold">
            + New Care Log
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-2xl overflow-hidden border border-slate-200">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Log ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Visit</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Caregiver</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($careLogs as $careLog)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-800">#{{ $careLog->id }}</td>
                        <td class="px-6 py-4 text-sm text-slate-800">
                            {{ $careLog->visit_id ? '#' . $careLog->visit_id : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-800">
                            {{ optional(optional($careLog->visit)->client)->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-800">
                            {{ optional(optional($careLog->visit)->caregiver)->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ optional($careLog->created_at)->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                           @php
    $viewRoute = match(auth()->user()->role) {
        'caregiver' => route('caregiver.care-logs.show', $careLog->id),
        'provider', 'admin', 'super_admin' => route('provider.care-logs.show', $careLog->id),
        default => '#',
    };
@endphp

<a href="{{ $viewRoute }}" class="text-indigo-600 font-semibold hover:text-indigo-800">
    View Log
</a>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            No care logs found for this caregiver yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
