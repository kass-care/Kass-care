@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-6">
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tight text-slate-900">
                    Care Logs
                </h1>
                <p class="text-slate-500 mt-2 text-sm">
                    Monitor caregiver documentation, client notes, and daily care updates.
                </p>
            </div>

            <a href="{{ route('care-logs.create') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">
                + Add Care Log
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 shadow-sm">
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-indigo-800 p-6 text-white shadow-xl">
                <p class="text-xs uppercase tracking-[0.25em] text-indigo-100">Total Care Logs</p>
                <h2 class="mt-4 text-4xl font-black">{{ $careLogs->count() }}</h2>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">With Caregiver</p>
                <h2 class="mt-4 text-3xl font-black text-slate-900">
                    {{ $careLogs->filter(fn($log) => !empty($log->caregiver_id))->count() }}
                </h2>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">With Notes</p>
                <h2 class="mt-4 text-3xl font-black text-slate-900">
                    {{ $careLogs->filter(fn($log) => !empty($log->notes))->count() }}
                </h2>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Today</p>
                <h2 class="mt-4 text-3xl font-black text-slate-900">
                    {{ $careLogs->filter(fn($log) => optional($log->created_at)->isToday())->count() }}
                </h2>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/40">
            @if($careLogs->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">#</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Caregiver</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Notes</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @foreach($careLogs as $log)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm font-semibold text-slate-500">
                                        {{ $log->id }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-100 text-sm font-black uppercase text-indigo-700">
                                                {{ strtoupper(substr($log->client->name ?? 'C', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">
                                                    {{ $log->client->name ?? 'Unknown Client' }}
                                                </p>
                                                <p class="text-xs text-slate-400">
                                                    Client Record
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-700">
                                        {{ $log->caregiver->name ?? 'Unassigned' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ optional($log->created_at)->format('M d, Y h:i A') }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-700 max-w-md">
                                        <div class="line-clamp-2">
                                            {{ $log->notes ?? 'No notes added.' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('care-logs.show', $log->id) }}"
                                               class="rounded-xl bg-sky-100 px-3 py-2 text-xs font-bold uppercase tracking-wider text-sky-700 hover:bg-sky-200 transition">
                                                View
                                            </a>

                                            <a href="{{ route('care-logs.edit', $log->id) }}"
                                               class="rounded-xl bg-amber-100 px-3 py-2 text-xs font-bold uppercase tracking-wider text-amber-700 hover:bg-amber-200 transition">
                                                Edit
                                            </a>

                                            <form action="{{ route('care-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Delete this care log?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-xl bg-red-100 px-3 py-2 text-xs font-bold uppercase tracking-wider text-red-700 hover:bg-red-200 transition">
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
                <div class="px-8 py-16 text-center">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-indigo-100 text-3xl text-indigo-600">
                        📝
                    </div>
                    <h2 class="text-2xl font-black text-slate-900">No care logs yet</h2>
                    <p class="mt-2 text-slate-500">
                        Start documenting care activity by creating your first care log.
                    </p>
                    <a href="{{ route('care-logs.create') }}"
                       class="mt-6 inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">
                        + Add First Care Log
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
