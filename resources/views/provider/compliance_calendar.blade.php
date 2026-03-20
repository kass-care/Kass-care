@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-7xl mx-auto">
        @php
            $groupedScheduledCycles = $cycles
                ->filter(fn($cycle) => $cycle->scheduled_for)
                ->groupBy(fn($cycle) => $cycle->scheduled_for->format('Y-m-d'));

            $dueCycles = $cycles->sortBy('next_due_at');
        @endphp

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Facility Cycle Calendar</h1>
                <p class="text-slate-600 mt-1">Scheduled and upcoming facility review dates.</p>
            </div>

            <a href="{{ route('provider.compliance') }}"
               class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                Back to Compliance
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="p-6 border-b bg-slate-50">
                    <h2 class="text-xl font-semibold text-slate-900">Scheduled Cycles</h2>
                    <p class="text-slate-500 mt-1">Facilities grouped by scheduled review date.</p>
                </div>

                <div class="p-6">
                    @forelse($groupedScheduledCycles as $date => $items)
                        <div class="mb-6">
                            <h3 class="mb-3 text-lg font-bold text-indigo-600">
                                {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                            </h3>

                            <div class="space-y-3">
                                @foreach($items as $cycle)
                                    <div class="flex items-start justify-between rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <div>
                                            <h4 class="text-base font-semibold text-slate-900">
                                                {{ $cycle->facility->name ?? 'N/A' }}
                                            </h4>
                                            <p class="text-sm text-slate-600 mt-1">
                                                Next Due: {{ $cycle->next_due_at ? $cycle->next_due_at->format('Y-m-d') : 'N/A' }}
                                            </p>
                                            <p class="text-sm text-slate-600">
                                                Priority: {{ ucfirst($cycle->priority) }}
                                            </p>
                                        </div>

                                        <a href="{{ route('provider.cycles.schedule', $cycle->id) }}"
                                           class="rounded-lg bg-blue-600 px-3 py-2 text-xs text-white hover:bg-blue-700">
                                            Edit
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-600">No scheduled cycles yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="p-6 border-b bg-slate-50">
                    <h2 class="text-xl font-semibold text-slate-900">Upcoming Due Cycles</h2>
                    <p class="text-slate-500 mt-1">Facilities ordered by next due date.</p>
                </div>

                <div class="p-6 space-y-4">
                    @forelse($dueCycles as $cycle)
                        @php
                            $status = $cycle->computed_status;
                            $statusLabel = ucwords(str_replace('_', ' ', $status));
                        @endphp

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">{{ $cycle->facility->name ?? 'N/A' }}</h3>
                                    <p class="text-sm text-slate-600 mt-1">
                                        Next Due: {{ $cycle->next_due_at ? $cycle->next_due_at->format('Y-m-d') : 'N/A' }}
                                    </p>
                                    <p class="text-sm text-slate-600">
                                        Scheduled: {{ $cycle->scheduled_for ? $cycle->scheduled_for->format('Y-m-d') : 'Not scheduled' }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    @if($status === 'overdue')
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">{{ $statusLabel }}</span>
                                    @elseif($status === 'due_soon')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">{{ $statusLabel }}</span>
                                    @elseif($status === 'due')
                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $statusLabel }}</span>
                                    @elseif($status === 'scheduled')
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $statusLabel }}</span>
                                    @elseif($status === 'completed')
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">{{ $statusLabel }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $statusLabel }}</span>
                                    @endif

                                    <div class="mt-3">
                                        <a href="{{ route('provider.cycles.schedule', $cycle->id) }}"
                                           class="rounded-lg bg-blue-600 px-3 py-2 text-xs text-white hover:bg-blue-700">
                                            Schedule
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-600">No cycles found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
