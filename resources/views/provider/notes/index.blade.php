@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-300 bg-green-100 px-4 py-3 text-green-900 font-medium shadow">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-300 bg-red-100 px-4 py-3 text-red-900 font-medium shadow">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-indigo-800 rounded-3xl shadow-2xl p-8 mb-8 border border-indigo-900">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-widest text-indigo-200 font-bold mb-2">
                        KASS CARE
                    </p>
                    <h1 class="text-4xl font-extrabold text-white">
                        Provider Notes
                    </h1>
                    <p class="text-indigo-100 mt-2 text-base">
                        Review saved provider documentation by visit.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('provider.dashboard') }}"
                       class="inline-flex items-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-800 shadow hover:bg-indigo-50 transition">
                        ← Back to Dashboard
                    </a>

                    <a href="{{ route('provider.calendar') }}"
                       class="inline-flex items-center rounded-xl bg-yellow-400 px-5 py-3 text-sm font-bold text-slate-900 shadow hover:bg-yellow-300 transition">
                        Back to Schedule
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl overflow-hidden border border-gray-200">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Visit Notes Queue</h2>
                    <p class="text-sm text-gray-500 mt-1">Saved provider notes linked to visits.</p>
                </div>

                <span class="bg-indigo-100 text-indigo-700 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $notes->count() }} Total
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Visit</th>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Caregiver</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Note Preview</th>
                            <th class="px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($notes as $note)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    #{{ $note->visit->id ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $note->visit->client->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $note->visit->caregiver->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $note->visit->visit_date ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 max-w-sm">
                                    <div class="truncate text-gray-700">
                                        {{ $note->note }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('provider.notes.show', $note->id) }}"
                                       class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                                        View Note
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    No provider notes found yet.
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
