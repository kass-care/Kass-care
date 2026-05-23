@props([
    'client' => null,
    'visit' => null,
])

@php
    $name = $client->name
        ?? trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''))
        ?: 'Patient';

    $photo = $client->photo ?? null;
    $dob = $client->dob ?? $client->date_of_birth ?? null;
@endphp

@if($client)
    <div class="sticky top-0 z-40 mb-6 rounded-3xl border border-indigo-500/20 bg-slate-950/95 p-5 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-4">
                <div class="h-20 w-20 overflow-hidden rounded-2xl border-2 border-indigo-500 bg-slate-900">
                    @if($photo)
                        <img src="{{ asset('storage/' . $photo) }}" class="h-full w-full object-cover" alt="Patient photo">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-3xl font-black text-indigo-300">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-indigo-300">Active Patient</p>
                    <h2 class="text-2xl font-black text-white">{{ $name }}</h2>

                    <div class="mt-2 flex flex-wrap gap-2 text-xs text-slate-300">
                        @if($dob)
                            <span class="rounded-full bg-slate-800 px-3 py-1">
                                DOB: {{ \Carbon\Carbon::parse($dob)->format('M d, Y') }}
                            </span>
                            <span class="rounded-full bg-slate-800 px-3 py-1">
                                Age: {{ \Carbon\Carbon::parse($dob)->age }}
                            </span>
                        @endif

                        @if($visit)
                            <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-emerald-300">
                                Visit #{{ $visit->id }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
