@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Facility Provider Cycles</h1>
                <p class="text-slate-600 mt-1">Manage 60-day provider review cycles by facility.</p>
            </div>

            <a href="{{ route('admin.facility-provider-cycles.create') }}"
               class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                + New Cycle
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-800 p-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            @if($cycles->count())
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Facility</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Provider</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Interval</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Next Due</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Scheduled</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Priority</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cycles as $cycle)
                                <tr class="border-t border-slate-200">
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $cycle->facility->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $cycle->provider->name ?? $cycle->provider->email ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $cycle->review_interval_days }} days</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $cycle->next_due_at ? $cycle->next_due_at->format('Y-m-d') : 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $cycle->scheduled_for ? $cycle->scheduled_for->format('Y-m-d') : 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ ucwords(str_replace('_', ' ', $cycle->status)) }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ ucfirst($cycle->priority) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex gap-3">
                                            <a href="{{ route('admin.facility-provider-cycles.edit', $cycle->id) }}"
                                               class="text-indigo-600 hover:underline">Edit</a>

                                            <form method="POST" action="{{ route('admin.facility-provider-cycles.destroy', $cycle->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Delete this cycle?')"
                                                        class="text-red-600 hover:underline">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-slate-600">
                    No facility provider cycles found.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
