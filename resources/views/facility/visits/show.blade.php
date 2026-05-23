<!DOCTYPE html>
<html>
<head>
    <title>Open Visit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white min-h-screen">

<div class="max-w-5xl mx-auto px-6 py-10">

    <!-- HEADER -->
    <div class="flex items-start justify-between gap-4 mb-8">
        <div>
            <p class="text-xs text-indigo-400 uppercase tracking-widest">Kass Care</p>
            <h1 class="text-4xl font-bold">Visit Workspace</h1>
            <p class="text-slate-400 mt-2">Review this scheduled facility visit.</p>
        </div>

        <a href="{{ route('facility.visits.index') }}"
           class="bg-white text-slate-900 px-6 py-3 rounded-xl font-semibold">
            Back to Facility Visits
        </a>
    </div>
             @if($visit->client)
<div class="sticky top-0 z-40 mb-6 rounded-3xl border border-indigo-500/20 bg-slate-950/95 p-5 shadow-2xl">
    <div class="flex items-center gap-4">

        @if($visit->client->photo)
            <img
                src="{{ asset('storage/' . $visit->client->photo) }}"
                class="h-20 w-20 rounded-2xl object-cover border-2 border-indigo-500"
            >
        @else
            <div class="h-20 w-20 rounded-2xl bg-slate-800 flex items-center justify-center text-3xl font-black text-indigo-300">
                {{ strtoupper(substr($visit->client->name ?? 'P',0,1)) }}
            </div>
        @endif

        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-indigo-300">
                Active Patient
            </p>

            <h2 class="text-2xl font-black text-white">
                {{ $visit->client->name }}
            </h2>

            <div class="mt-2 flex flex-wrap gap-2 text-xs text-slate-300">

                @if($visit->client->date_of_birth)
                    <span class="rounded-full bg-slate-800 px-3 py-1">
                        DOB:
                        {{ \Carbon\Carbon::parse($visit->client->date_of_birth)->format('M d, Y') }}
                    </span>

                    <span class="rounded-full bg-slate-800 px-3 py-1">
                        Age:
                        {{ \Carbon\Carbon::parse($visit->client->date_of_birth)->age }}
                    </span>
                @endif

                <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-emerald-300">
                    Visit #{{ $visit->id }}
                </span>

            </div>
        </div>
    </div>
</div>
@endif
    <!-- MAIN CARD -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 p-8 space-y-8">

                    Patient Workspace
                </p>

                <h2 class="text-3xl font-black text-white">
                    {{ $visit->client->name }}
                </h2>

                <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-slate-300">

                    @if($visit->client->dob)
                        <span class="rounded-full bg-slate-800 px-3 py-1">
                            DOB:
                            {{ \Carbon\Carbon::parse($visit->client->dob)->format('M d, Y') }}
                        </span>

                        <span class="rounded-full bg-slate-800 px-3 py-1">
                            Age:
                            {{ \Carbon\Carbon::parse($visit->client->dob)->age }}
                        </span>
                    @endif

                    @if($visit->client->gender)
                        <span class="rounded-full bg-slate-800 px-3 py-1">
                            {{ $visit->client->gender }}
                        </span>
                    @endif

                    <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-emerald-300">
                        {{ ucfirst($visit->status) }}
                    </span>

                </div>
            </div>
        </div>

        {{-- Right Side --}}
        <div class="flex flex-wrap gap-3">

            <div class="rounded-2xl bg-slate-900 px-5 py-3 border border-slate-800">
                <p class="text-xs uppercase text-slate-400">
                    Visit Date
                </p>

                <p class="text-lg font-bold text-white">
                    {{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-900 px-5 py-3 border border-slate-800">
                <p class="text-xs uppercase text-slate-400">
                    Visit ID
                </p>

                <p class="text-lg font-bold text-white">
                    #{{ $visit->id }}
                </p>
            </div>

        </div>

    </div>
</div>
                <p class="text-xs uppercase tracking-widest text-slate-400">Patient</p>
                <p class="text-xl font-bold">
                    {{ $visit->client->name
                        ?? trim(($visit->client->first_name ?? '') . ' ' . ($visit->client->last_name ?? ''))
                        ?: 'Patient not found' }}
                </p>
            </div>
        </div>

        <!-- GRID DETAILS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Provider -->
            <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-5">
                <p class="text-xs uppercase tracking-widest text-slate-400">Provider</p>
                <p class="mt-2 text-xl font-semibold">
                    {{ $visit->provider->name ?? 'Unassigned' }}
                </p>
            </div>

            <!-- Caregiver -->
            <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-5">
                <p class="text-xs uppercase tracking-widest text-slate-400">Caregiver</p>
                <p class="mt-2 text-xl font-semibold">
                    {{ $visit->caregiver->name ?? 'Unassigned' }}
                </p>
            </div>

            <!-- Visit Date -->
            <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-5">
                <p class="text-xs uppercase tracking-widest text-slate-400">Visit Date</p>
                <p class="mt-2 text-xl font-semibold">
                    {{ \Carbon\Carbon::parse($visit->visit_date)->format('M j, Y') }}
                </p>
            </div>

            <!-- Status -->
            <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-5">
                <p class="text-xs uppercase tracking-widest text-slate-400">Status</p>
                <p class="mt-2 text-xl font-semibold text-emerald-400">
                    {{ ucfirst($visit->status) }}
                </p>
            </div>

            <!-- Visit ID -->
            <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-5">
                <p class="text-xs uppercase tracking-widest text-slate-400">Visit ID</p>
                <p class="mt-2 text-xl font-semibold">
                    {{ $visit->id }}
                </p>
            </div>

        </div>

    </div>
</div>

</body>
</html>
