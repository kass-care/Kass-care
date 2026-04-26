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

    <!-- MAIN CARD -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 p-8 space-y-8">

        <!-- ✅ PATIENT SECTION WITH PHOTO -->
        <div class="flex items-center gap-4 border border-slate-800 rounded-2xl p-5 bg-slate-950/60">
            <img
                src="{{ $visit->client?->photo
                    ? asset('storage/' . $visit->client->photo)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($visit->client->name ?? 'Patient') }}"
                class="h-16 w-16 rounded-full object-cover border border-slate-600"
                alt="Patient photo"
            >

            <div>
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

        <!-- NEXT BUILD HOOK -->
        <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-950/40 p-6">
            <h2 class="text-xl font-semibold mb-2">Next Build Hook</h2>
            <p class="text-slate-300">
                This visit workspace is now connected.
                Next we wire:
                caregiver charting, vitals, provider notes,
                visit completion, and medication follow-up.
            </p>
        </div>

    </div>
</div>

</body>
</html>
