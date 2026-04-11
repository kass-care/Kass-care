@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">
    <div class="mb-8 rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500 font-semibold">
                    KASS Care Audit Layer
                </p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">
                    System Audit Logs
                </h1>
                <p class="mt-2 text-slate-600">
                    Review platform activity, user actions, patient access, and workflow events.
                </p>
            </div>

            <form method="GET" action="{{ route('admin.activity.index') }}" class="flex gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Search user, role, action, patient, IP..."
                    class="w-80 rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Search
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-semibold">Total Logs</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['total'] ?? 0 }}</h2>
            <p class="mt-1 text-sm text-slate-500">All recorded activity</p>
        </div>

        <div class="rounded-2xl bg-white border border-emerald-200 shadow-sm p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 font-semibold">Today</p>
            <h2 class="mt-2 text-3xl font-bold text-emerald-700">{{ $stats['today'] ?? 0 }}</h2>
            <p class="mt-1 text-sm text-emerald-600">Events logged today</p>
        </div>

        <div class="rounded-2xl bg-white border border-indigo-200 shadow-sm p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-indigo-600 font-semibold">Admin Actions</p>
            <h2 class="mt-2 text-3xl font-bold text-indigo-700">{{ $stats['admins'] ?? 0 }}</h2>
            <p class="mt-1 text-sm text-indigo-600">Admin-side actions</p>
        </div>

        <div class="rounded-2xl bg-white border border-amber-200 shadow-sm p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-amber-600 font-semibold">Provider Actions</p>
            <h2 class="mt-2 text-3xl font-bold text-amber-700">{{ $stats['providers'] ?? 0 }}</h2>
            <p class="mt-1 text-sm text-amber-600">Provider-side actions</p>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-600">
                        <th class="px-4 py-3 font-semibold">When</th>
                        <th class="px-4 py-3 font-semibold">User</th>
                        <th class="px-4 py-3 font-semibold">Role</th>
                        <th class="px-4 py-3 font-semibold">Action</th>
                        <th class="px-4 py-3 font-semibold">Target</th>
                        <th class="px-4 py-3 font-semibold">Patient</th>
                        <th class="px-4 py-3 font-semibold">Description</th>
                        <th class="px-4 py-3 font-semibold">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($activities as $activity)
                        <tr class="align-top hover:bg-slate-50">
                            <td class="px-4 py-4 whitespace-nowrap text-slate-600">
                                <div>{{ optional($activity->created_at)->format('M d, Y') }}</div>
                                <div class="text-xs text-slate-400">
                                    {{ optional($activity->created_at)->format('h:i A') }}
                                </div>
                            </td>

                            <td class="px-4 py-4 text-slate-900 font-medium">
                                {{ $activity->user_name ?? 'System' }}
                            </td>

                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ $activity->user_role ?? 'system' }}
                                </span>
                            </td>

                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                    {{ $activity->action }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-slate-600">
                                <div>{{ $activity->target_type ?? '-' }}</div>
                                <div class="text-xs text-slate-400">
                                    ID: {{ $activity->target_id ?? '-' }}
                                </div>
                            </td>

                            <td class="px-4 py-4 text-slate-600">
                                <div>{{ $activity->client_name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">
                                    ID: {{ $activity->client_id ?? '-' }}
                                </div>
                            </td>

                            <td class="px-4 py-4 text-slate-600 max-w-xs">
                                {{ $activity->description ?? '-' }}
                            </td>

                            <td class="px-4 py-4 text-slate-500 whitespace-nowrap">
                                {{ $activity->ip_address ?? '-' }}
                            </td>
                        </tr>

                        @if(!empty($activity->meta))
                            <tr class="bg-slate-50">
                                <td colspan="8" class="px-4 py-3">
                                    <div class="rounded-2xl bg-slate-900 text-slate-100 p-4 text-xs overflow-x-auto">
<pre>{{ json_encode($activity->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-slate-500">
                                No audit activity recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($activities, 'links'))
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
