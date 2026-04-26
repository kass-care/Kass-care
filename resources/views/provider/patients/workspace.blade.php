@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="sticky top-0 z-40 mb-6 rounded-3xl border border-indigo-200 bg-white/95 p-5 shadow-lg backdrop-blur">
    <div class="flex items-center gap-4">
       <img
    src="{{ optional($patient)->photo
        ? asset('storage/' . $patient->photo)
        : 'https://ui-avatars.com/api/?name=' . urlencode($patient->name ?? 'Patient') }}"
    class="h-16 w-16 rounded-full object-cover border border-indigo-200 shadow"
>
            alt="Patient photo"
        >

        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">
                Patient Workspace
            </p>

            <h1 class="text-2xl font-black text-slate-900">
                {{ $patient->name
                    ?? trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''))
                    ?: 'Patient' }}
            </h1>

            <p class="text-sm text-slate-500">
                DOB:
                {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') : 'N/A' }}
                · Age:
                {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age : 'N/A' }}
                · Room:
                {{ $patient->room ?? 'N/A' }}
            </p>
        </div>
    </div>
</div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8 rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-500 px-6 py-8 text-white shadow-xl">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-100">KASS CARE</p>
                    <h1 class="mt-3 text-3xl font-bold">{{ $patientName }}</h1>
                    <p class="mt-2 text-sm text-indigo-100">

                        Provider Clinical Workspace
                    </p>

                    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur">
                            <div class="text-xs uppercase tracking-wide text-indigo-100">Date of Birth</div>
                            <div class="mt-1 text-sm font-semibold text-white">
                                {{ $patient->date_of_birth ?? $patient->dob ?? 'Not recorded' }}
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur">
                            <div class="text-xs uppercase tracking-wide text-indigo-100">Facility</div>
                            <div class="mt-1 text-sm font-semibold text-white">
                                {{ optional($patient->facility)->name ?? 'Current facility context' }}
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur">
                            <div class="text-xs uppercase tracking-wide text-indigo-100">Visits</div>
                            <div class="mt-1 text-sm font-semibold text-white">
                                {{ $visits->count() }}
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur">
                            <div class="text-xs uppercase tracking-wide text-indigo-100">Clinical Alerts</div>
                            <div class="mt-1 text-sm font-semibold text-white">
                                {{ $alertCount }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('provider.dashboard') }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow hover:bg-indigo-50">
                        Back to Dashboard
                    </a>

                    <a href="{{ route('provider.notes.create') }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-indigo-900/40 px-5 py-3 text-sm font-semibold text-white ring-1 ring-white/30 hover:bg-indigo-900/60">
                        New Provider Note
                    </a>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            <div class="space-y-6 lg:col-span-2">

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Clinical Snapshot</h2>
                            <p class="mt-1 text-sm text-slate-500">Most recent vitals and care documentation.</p>
                        </div>

                        @if($alertCount > 0)
                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                {{ $alertCount }} alert{{ $alertCount > 1 ? 's' : '' }}
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                Stable
                            </span>
                        @endif
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Blood Pressure</div>
                            <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['blood_pressure'] ?? 'N/A' }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Pulse</div>
                            <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['pulse'] ?? 'N/A' }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Temperature</div>
                            <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['temperature'] ?? 'N/A' }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Respiratory Rate</div>
                            <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['respiratory_rate'] ?? 'N/A' }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Oxygen Saturation</div>
                            <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['oxygen_saturation'] ?? 'N/A' }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Weight (lb)</div>
                            <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['weight'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-xl font-bold text-slate-900">ADL and Care Summary</h2>
                    <p class="mt-1 text-sm text-slate-500">Most recent caregiver documentation.</p>

                    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">ADL Status</div>
                            <div class="mt-2 font-semibold text-slate-900">{{ $latestAdls['adl_status'] ?? 'N/A' }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Bathroom Assistance</div>
                            <div class="mt-2 font-semibold text-slate-900">{{ $latestAdls['bathroom_assistance'] ?? 'N/A' }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Mobility Support</div>
                            <div class="mt-2 font-semibold text-slate-900">{{ $latestAdls['mobility_support'] ?? 'N/A' }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Meal Notes</div>
                            <div class="mt-2 font-semibold text-slate-900">{{ $latestAdls['meal_notes'] ?? 'N/A' }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4 sm:col-span-2">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Medication Notes</div>
                            <div class="mt-2 font-semibold text-slate-900">{{ $latestAdls['medication_notes'] ?? 'N/A' }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4 sm:col-span-2">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Charting Notes</div>
                            <div class="mt-2 font-semibold text-slate-900">{{ $latestAdls['charting_notes'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Visit History</h2>
                            <p class="mt-1 text-sm text-slate-500">Recent scheduled and completed visits.</p>
                        </div>
                        <span class="text-sm font-semibold text-indigo-600">{{ $visits->count() }} visit(s)</span>
                    </div>

                    <div class="mt-6 space-y-4">
                        @forelse($visits->take(10) as $visit)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">
                                            Visit #{{ $visit->id }}
                                        </div>
                                        <div class="mt-1 text-sm text-slate-600">
                                            Date: {{ $visit->visit_date ?? 'N/A' }}
                                        </div>
                                        <div class="mt-1 text-sm text-slate-600">
                                            Caregiver: {{ optional($visit->caregiver)->name ?? 'Not linked' }}
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ ucfirst(str_replace('_', ' ', $visit->status ?? 'scheduled')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-slate-500">
                                No visit history yet.
                            </div>
                        @endforelse


                    </div>
                </div>
            </div>

            <div class="space-y-6">

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                   <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Facility Messages</h2>
            <p class="mt-1 text-sm text-slate-500">
                Communication between facility staff and provider for this patient.
            </p>
        </div>

        <span class="text-sm font-semibold text-indigo-600">
            {{ $providerMessages->count() }} message(s)
        </span>
    </div>

    <div class="mt-6 space-y-4">
        @forelse($providerMessages->take(10) as $msg)
            <div class="rounded-2xl border border-slate-200 p-4">
                
                <div class="flex items-center justify-between">
                    <div class="text-sm font-bold text-slate-900">
                        {{ $msg->subject ?? 'Message' }}
                    </div>

                    <span class="text-xs font-semibold px-3 py-1 rounded-full
                        {{ $msg->priority === 'urgent' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700' }}">
                        {{ ucfirst($msg->priority ?? 'normal') }}
                    </span>
                </div>

                <div class="mt-2 text-sm text-slate-600">
                    From: {{ $msg->facility->name ?? 'Facility' }}
                    • {{ optional($msg->created_at)->diffForHumans() }}
                </div>

                <div class="mt-3 rounded-xl bg-slate-50 p-3 text-sm text-slate-800">
                    {{ $msg->message }}
                </div>

                @if($msg->provider_reply)
                    <div class="mt-3 rounded-xl bg-green-50 border border-green-200 p-3 text-sm text-slate-800">
                        <span class="block text-xs font-bold text-green-600 mb-1">
                            Your Reply
                        </span>
                        {{ $msg->provider_reply }}
                    </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('provider.messages.show', $msg) }}"
                       class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                        Open →
                    </a>
                </div>

            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-slate-500">
                No messages for this patient yet.
            </div>
        @endforelse
    </div>
</div>
                    <h2 class="text-lg font-bold text-slate-900">Patient Profile</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <div class="text-xs uppercase tracking-wide text-slate-500">Allergies</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">
                                {{ $patient->allergies ?? 'Not recorded' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs uppercase tracking-wide text-slate-500">Medical History</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">
                                {{ $patient->medical_history ?? 'Not recorded' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs uppercase tracking-wide text-slate-500">Family History</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">
                                {{ $patient->family_history ?? 'Not recorded' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs uppercase tracking-wide text-slate-500">Social History</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">
                                {{ $patient->social_history ?? 'Not recorded' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs uppercase tracking-wide text-slate-500">Chief Complaint</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">
                                {{ $patient->chief_complaint ?? 'Not recorded' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-indigo-800 p-6 text-white shadow-xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-200">KASS CARE</p>
                    <h3 class="mt-3 text-2xl font-bold">Provider Clinical Workspace</h3>
                    <p class="mt-3 text-sm leading-6 text-indigo-100">
                        Clean patient review, fast clinical decisions, and safer documentation for providers.
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
