@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-indigo-600 font-semibold mb-2">
                Clinical History Engine
            </p>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ $client->name }} Timeline
            </h1>
            <p class="text-gray-500 mt-2">
                Complete patient history across visits, care logs, provider notes, diagnoses, and medications.
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('provider.patients.workspace', $client->id) }}"
               class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                Back to Patient Workspace
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-xl bg-gray-50 p-4">
                <div class="text-sm text-gray-500">Patient</div>
                <div class="text-xl font-bold text-gray-900 mt-1">{{ $client->name }}</div>
            </div>

            <div class="rounded-xl bg-gray-50 p-4">
                <div class="text-sm text-gray-500">Phone</div>
                <div class="text-xl font-bold text-gray-900 mt-1">{{ $client->phone ?? 'N/A' }}</div>
            </div>

            <div class="rounded-xl bg-gray-50 p-4">
                <div class="text-sm text-gray-500">Timeline Events</div>
                <div class="text-xl font-bold text-gray-900 mt-1">{{ $timeline->count() }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Patient Timeline</h2>

        @if($timeline->count())
            <div class="space-y-5">
                @foreach($timeline as $item)
                    @php
                        $badge = match($item['color']) {
                            'blue' => 'bg-blue-100 text-blue-700',
                            'indigo' => 'bg-indigo-100 text-indigo-700',
                            'green' => 'bg-green-100 text-green-700',
                            'red' => 'bg-red-100 text-red-700',
                            'purple' => 'bg-purple-100 text-purple-700',
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp

                    <div class="relative pl-8 border-l-2 border-gray-200">
                        <div class="absolute left-[-9px] top-2 w-4 h-4 rounded-full bg-indigo-600"></div>

                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div>
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $badge }}">
                                        {{ $item['type'] }}
                                    </span>

                                    <h3 class="text-lg font-bold text-gray-900 mt-3">
                                        {{ $item['title'] }}
                                    </h3>

                                    <p class="text-gray-600 mt-2">
                                        {{ $item['description'] }}
                                    </p>
                                </div>

                                <div class="text-sm text-gray-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item['time'])->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-8 text-center text-gray-400">
                No timeline activity found for this patient.
            </div>
        @endif
    </div>
</div>
@endsection
