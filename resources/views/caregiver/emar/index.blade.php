@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-8">
    <div class="mx-auto max-w-7xl px-4">

        {{-- HERO --}}
        <div class="mb-8 rounded-[2rem] bg-slate-950 p-7 text-white shadow-2xl border border-emerald-500">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-emerald-300 font-black">
                        KASSCARE eMAR
                    </p>

                    <h1 class="mt-3 text-4xl font-black tracking-tight text-white">
                        Medication Administration Record
                    </h1>

                    <p class="mt-3 max-w-3xl text-base font-semibold text-slate-200">
                        Digital medication pass workflow for caregivers — real-time signing, timestamps, refusal tracking, and audit-ready documentation.
                    </p>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <span class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-black text-white">
                            📅 {{ \Carbon\Carbon::parse($today)->format('M d, Y') }}
                        </span>

                        <span class="rounded-full bg-cyan-600 px-4 py-2 text-sm font-black text-white">
                            👤 {{ auth()->user()->name ?? 'Caregiver' }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('caregiver.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-black text-slate-950 shadow-lg hover:bg-emerald-50">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-300 bg-emerald-100 px-6 py-4 text-emerald-900 font-black shadow-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- LEGEND --}}
        <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-5">
            <div class="rounded-2xl bg-white p-5 shadow border border-emerald-200">
                <p class="text-xs font-black uppercase text-slate-500">Given</p>
                <p class="mt-1 text-xl font-black text-emerald-700">✅ Signed</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow border border-amber-200">
                <p class="text-xs font-black uppercase text-slate-500">Refused</p>
                <p class="mt-1 text-xl font-black text-amber-700">⚠ Refused</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow border border-orange-200">
                <p class="text-xs font-black uppercase text-slate-500">Held</p>
                <p class="mt-1 text-xl font-black text-orange-700">⏸ Held</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow border border-red-200">
                <p class="text-xs font-black uppercase text-slate-500">Missed</p>
                <p class="mt-1 text-xl font-black text-red-700">🚫 Missed</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow border border-slate-200">
                <p class="text-xs font-black uppercase text-slate-500">Pending</p>
                <p class="mt-1 text-xl font-black text-slate-700">⏳ Pending</p>
            </div>
        </div>

        {{-- CLIENTS --}}
        <div class="space-y-8">
            @forelse($clients as $client)
                <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl">

                    {{-- PATIENT HEADER --}}
                    <div class="bg-slate-900 px-6 py-5 text-white">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.25em] text-emerald-300">
                                    Patient Medication Pass
                                </p>

                                <h2 class="mt-2 text-2xl font-black text-white">
                                    {{ $client->name ?? trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? '')) ?: 'Unnamed Client' }}
                                </h2>

                                <p class="mt-1 text-sm font-semibold text-slate-300">
                                    Room: {{ $client->room ?? 'N/A' }}
                                    @if(!empty($client->date_of_birth))
                                        · DOB: {{ \Carbon\Carbon::parse($client->date_of_birth)->format('m/d/Y') }}
                                    @endif
                                </p>
                            </div>

                            <div class="rounded-2xl bg-emerald-600 px-5 py-3 text-center shadow">
                                <p class="text-xs font-black uppercase text-emerald-100">Active Meds</p>
                                <p class="text-3xl font-black text-white">{{ $client->medications->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @forelse($client->medications as $medication)
                            @php
                                $times = $medication->emar_times ?: ['Morning'];
                                if (!is_array($times)) {
                                    $times = ['Morning'];
                                }
                            @endphp

                            {{-- MEDICATION CARD --}}
                            <div class="mb-6 rounded-[1.6rem] border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div>
                                        <h3 class="text-2xl font-black text-slate-950">
                                            💊 {{ $medication->medication_name }}
                                        </h3>

                                        <p class="mt-2 text-sm font-black text-slate-700">
                                            {{ $medication->dose ?? 'Dose not recorded' }}
                                            @if($medication->route)
                                                · {{ $medication->route }}
                                            @endif
                                            @if($medication->frequency)
                                                · {{ $medication->frequency }}
                                            @endif
                                        </p>

                                        @if($medication->instructions)
                                            <p class="mt-3 rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm border border-slate-200">
                                                📝 {{ $medication->instructions }}
                                            </p>
                                        @endif
                                    </div>

                                    @if($medication->is_prn)
                                        <span class="rounded-full bg-amber-200 px-4 py-2 text-xs font-black text-amber-900">
                                            PRN / AS NEEDED
                                        </span>
                                    @endif
                                </div>

                                {{-- TIME / SIGNING CARDS --}}
                                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                                    @foreach($times as $time)
                                        @php
                                            $key = $medication->id . '|' . $time;
                                            $record = optional($administrations->get($key))->first();

                                            $statusClass = 'bg-slate-200 text-slate-700';

                                            if ($record) {
                                                $statusClass = match($record->status) {
                                                    'given' => 'bg-emerald-200 text-emerald-900',
                                                    'refused' => 'bg-amber-200 text-amber-900',
                                                    'held' => 'bg-orange-200 text-orange-900',
                                                    'missed' => 'bg-red-200 text-red-900',
                                                    'side_effects' => 'bg-purple-200 text-purple-900',
                                                    default => 'bg-slate-200 text-slate-700',
                                                };
                                            }
                                        @endphp

                                        <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-md">
                                            <div class="flex items-center justify-between gap-3">
                                                <p class="text-xl font-black text-slate-900">
                                                    {{ $time }}
                                                </p>

                                                @if($record)
                                                    <span class="rounded-full px-3 py-1 text-xs font-black {{ $statusClass }}">
                                                        {{ strtoupper(str_replace('_', ' ', $record->status)) }}
                                                    </span>
                                                @else
                                                    <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-black text-slate-700">
                                                        PENDING
                                                    </span>
                                                @endif
                                            </div>

                                            @if($record)
                                                <div class="mt-3 rounded-2xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700">
                                                    Signed: {{ optional($record->administered_at)->format('g:i A') }}
                                                    @if($record->caregiver)
                                                        by {{ $record->caregiver->name }}
                                                    @endif
                                                </div>

                                                @if($record->notes)
                                                    <p class="mt-2 text-xs font-semibold text-slate-600">
                                                        Note: {{ $record->notes }}
                                                    </p>
                                                @endif
                                            @endif

                                            <form method="POST" action="{{ route('caregiver.emar.administer', $medication->id) }}" class="mt-4 space-y-3">
                                                @csrf

                                                <input type="hidden" name="scheduled_time" value="{{ $time }}">

                                                <select name="status"
                                                        required
                                                        class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-black text-slate-800 focus:border-emerald-500 focus:ring-emerald-500">
                                                    <option value="given">✅ Given</option>
                                                    <option value="refused">⚠ Refused</option>
                                                    <option value="held">⏸ Held</option>
                                                    <option value="missed">🚫 Missed</option>
                                                    <option value="side_effects">🤢 Side Effects</option>
                                                </select>

                                                <textarea
                                                    name="notes"
                                                    rows="2"
                                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-500 focus:ring-emerald-500"
                                                    placeholder="Optional medication note"></textarea>

                                                <button class="w-full rounded-2xl bg-emerald-700 px-5 py-3 text-base font-black text-white shadow-lg hover:bg-emerald-800">
                                                    ✍️ Sign Medication
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border-2 border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                                <p class="text-lg font-black text-slate-800">
                                    No active medications recorded for this client.
                                </p>
                                <p class="mt-2 text-sm font-semibold text-slate-500">
                                    Once a provider adds active medications, they will appear here for caregiver signing.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="rounded-[2rem] bg-white p-10 text-center shadow-xl border border-slate-200">
                    <p class="text-2xl font-black text-slate-800">No clients found for this facility.</p>
                    <p class="mt-2 text-slate-500">Assign clients to this facility to begin eMAR medication tracking.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
