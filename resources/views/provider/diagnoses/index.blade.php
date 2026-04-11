@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-indigo-600 font-bold">
                    KASS CARE
                </p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">
                    Provider Diagnoses
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    Review diagnoses across clients, including ICD codes, notes, and diagnosis status.
                </p>
            </div>

            <a href="{{ route('provider.dashboard') }}"
               class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                ← Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Total Diagnoses</p>
                <p class="mt-3 text-4xl font-bold text-indigo-600">{{ $diagnoses->count() }}</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Active</p>
                <p class="mt-3 text-4xl font-bold text-amber-500">
                    {{ $diagnoses->where('status', 'active')->count() }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Resolved / Chronic</p>
                <p class="mt-3 text-4xl font-bold text-green-600">
                    {{ $diagnoses->whereIn('status', ['resolved', 'chronic'])->count() }}
                </p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Client</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Diagnosis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">ICD Code</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Provider</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Created</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($diagnoses as $diagnosis)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    {{ $diagnosis->client->name ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-slate-700">
                                    {{ $diagnosis->diagnosis_name }}
                                </td>

                                <td class="px-6 py-4 text-slate-700">
                                    {{ $diagnosis->icd_code ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $status = strtolower($diagnosis->status ?? 'active');
                                    @endphp

                                    @if($status === 'active')
                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                            Active
                                        </span>
                                    @elseif($status === 'resolved')
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                            Resolved
                                        </span>
                                    @elseif($status === 'chronic')
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                            Chronic
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                                            {{ ucfirst($diagnosis->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-slate-700">
                                    {{ $diagnosis->user->name ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-slate-700 max-w-xs">
                                    {{ $diagnosis->notes ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-slate-700">
                                    {{ $diagnosis->created_at ? $diagnosis->created_at->format('M d, Y') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    No diagnoses found yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
