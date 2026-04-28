@extends('layouts.app')

@section('content')
@php
    $latestVisit = $patient->visits->last() ?? $visits->first() ?? null;
    $communications = \App\Models\Communication::where('client_id', $patient->id)->latest()->get();
@endphp

<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="rounded-3xl border border-indigo-200 bg-white p-5 shadow-lg">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <img
                        src="{{ optional($patient)->photo ? asset('storage/' . $patient->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($patient->name ?? 'Patient') }}"
                        class="h-16 w-16 rounded-full object-cover border border-indigo-200 shadow"
                        alt="Patient photo"
                    >

                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Patient Workspace</p>
                        <h1 class="text-2xl font-black text-slate-900">{{ $patientName }}</h1>
                        <p class="text-sm text-slate-500">
                            DOB: {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') : 'N/A' }}
                            · Age: {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age : 'N/A' }}
                            · Room: {{ $patient->room ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    @if($latestVisit && Route::has('provider.notes.create'))
                        <a href="{{ route('provider.notes.create', ['visit_id' => $latestVisit->id]) }}"
                           class="rounded-xl bg-indigo-600 hover:bg-indigo-700 px-5 py-3 text-sm font-semibold text-white shadow">
                            📝 Create Provider Note
                        </a>
                    @endif

                    @if(Route::has('provider.pharmacy.create'))
                        <a href="{{ route('provider.pharmacy.create', ['client_id' => $patient->id, 'mode' => 'refill']) }}"
                           class="rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow">
                            🔁 Refill Medication
                        </a>
                    @endif

                    @if(Route::has('diagnoses.create'))
                        <a href="{{ route('diagnoses.create', $patient->id) }}"
                           class="rounded-xl bg-purple-600 hover:bg-purple-700 px-5 py-3 text-sm font-semibold text-white shadow">
                            🧾 Add Diagnosis
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-500 px-6 py-8 text-white shadow-xl">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-100">KASS CARE</p>
                    <h1 class="mt-3 text-3xl font-bold">{{ $patientName }}</h1>
                    <p class="mt-2 text-sm text-indigo-100">Provider Clinical Workspace</p>

                    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-2xl bg-white/10 px-4 py-3">
                            <div class="text-xs uppercase tracking-wide text-indigo-100">Date of Birth</div>
                            <div class="mt-1 text-sm font-semibold">{{ $patient->date_of_birth ?? 'Not recorded' }}</div>
                        </div>

                        <div class="rounded-2xl bg-white/10 px-4 py-3">
                            <div class="text-xs uppercase tracking-wide text-indigo-100">Facility</div>
                            <div class="mt-1 text-sm font-semibold">{{ optional($patient->facility)->name ?? 'Current facility context' }}</div>
                        </div>

                        <div class="rounded-2xl bg-white/10 px-4 py-3">
                            <div class="text-xs uppercase tracking-wide text-indigo-100">Visits</div>
                            <div class="mt-1 text-sm font-semibold">{{ $visits->count() }}</div>
                        </div>

                        <div class="rounded-2xl bg-white/10 px-4 py-3">
                            <div class="text-xs uppercase tracking-wide text-indigo-100">Clinical Alerts</div>
                            <div class="mt-1 text-sm font-semibold">{{ $alertCount }}</div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('provider.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow hover:bg-indigo-50">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Clinical Snapshot</h2>
                    <p class="mt-1 text-sm text-slate-500">Most recent vitals, diagnosis, and care documentation.</p>
                </div>

                @if($alertCount > 0)
                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                        {{ $alertCount }} alert{{ $alertCount > 1 ? 's' : '' }}
                    </span>
                @else
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Stable
                    </span>
                @endif
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Blood Pressure</div>
                    <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['blood_pressure'] ?? $latestVitals['bp'] ?? 'N/A' }}</div>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Pulse</div>
                    <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['pulse'] ?? 'N/A' }}</div>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Temperature</div>
                    <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['temperature'] ?? $latestVitals['temp'] ?? 'N/A' }}</div>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Respiratory Rate</div>
                    <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['respiratory_rate'] ?? 'N/A' }}</div>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Oxygen Saturation</div>
                    <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['oxygen_saturation'] ?? $latestVitals['oxygen'] ?? 'N/A' }}</div>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Weight (lb)</div>
                    <div class="mt-2 text-xl font-bold text-slate-900">{{ $latestVitals['weight'] ?? $patient->weight ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="mt-6 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Active ICD Diagnoses</h3>
                        <p class="text-sm text-slate-600">
                            Updates automatically when provider or assistant adds/updates diagnosis.
                        </p>
                    </div>

                    @if(Route::has('diagnoses.create'))
                        <a href="{{ route('diagnoses.create', $patient->id) }}"
                           class="inline-flex items-center rounded-xl bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                            🧾 Add Diagnosis
                        </a>
                    @endif
                </div>

                <div class="space-y-3">
                    @forelse(($patient->diagnoses ?? collect())->whereIn('status', ['active', 'chronic']) as $diagnosis)
                        <div class="rounded-xl bg-white border border-indigo-100 p-4">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                <div>
                                    <p class="font-bold text-slate-900">
                                        {{ $diagnosis->diagnosis_name ?? 'Diagnosis' }}
                                    </p>

                                    @if(!empty($diagnosis->icd_code))
                                        <span class="mt-2 inline-flex items-center rounded-full bg-indigo-100 px-2 py-1 text-xs font-bold text-indigo-700">
                                            ICD-10: {{ $diagnosis->icd_code }}
                                        </span>
                                    @endif
                                </div>

                                <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-bold
                                    {{ ($diagnosis->status ?? '') === 'chronic'
                                        ? 'bg-orange-100 text-orange-700'
                                        : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ ucfirst($diagnosis->status ?? 'active') }}
                                </span>
                            </div>

                            @if(!empty($diagnosis->notes))
                                <p class="mt-3 text-sm text-slate-600">{{ $diagnosis->notes }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-indigo-200 bg-white p-5 text-sm text-slate-500">
                            No active ICD-coded diagnoses recorded yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-xl font-bold text-slate-900">ADL and Care Summary</h2>
                    <p class="mt-1 text-sm text-slate-500">Most recent caregiver documentation.</p>

                    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach([
                            'adl_status' => 'ADL Status',
                            'bathroom_assistance' => 'Bathroom Assistance',
                            'mobility_support' => 'Mobility Support',
                            'meal_notes' => 'Meal Notes',
                            'medication_notes' => 'Medication Notes',
                            'charting_notes' => 'Charting Notes',
                        ] as $key => $label)
                            <div class="rounded-2xl bg-slate-50 p-4 {{ in_array($key, ['medication_notes', 'charting_notes']) ? 'sm:col-span-2' : '' }}">
                                <div class="text-xs uppercase tracking-wide text-slate-500">{{ $label }}</div>
                                <div class="mt-2 font-semibold text-slate-900">{{ $latestAdls[$key] ?? 'N/A' }}</div>
                            </div>
                        @endforeach
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
                                        <div class="text-sm font-bold text-slate-900">Visit #{{ $visit->id }}</div>
                                        <div class="mt-1 text-sm text-slate-600">Date: {{ $visit->visit_date ?? 'N/A' }}</div>
                                        <div class="mt-1 text-sm text-slate-600">Caregiver: {{ optional($visit->caregiver)->name ?? 'Not linked' }}</div>
                                    </div>

                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ ucfirst(str_replace('_', ' ', $visit->status ?? 'scheduled')) }}
                                    </span>
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
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Facility Communication</h2>

                    <form method="POST" action="{{ route('provider.communications.store') }}" class="space-y-4">
                        @csrf

                        <input type="hidden" name="client_id" value="{{ $patient->id }}">

                        <select name="type" class="w-full border rounded-xl px-3 py-2">
                            <option value="call">📞 Call</option>
                            <option value="fax">📠 Fax</option>
                            <option value="email">📧 Email</option>
                        </select>

                        <input type="text" name="recipient"
                               placeholder="Facility / Pharmacy Email"
                               class="w-full border rounded-xl px-3 py-2">

                        <input type="text" name="subject"
                               placeholder="Subject"
                               class="w-full border rounded-xl px-3 py-2">

                        <textarea name="message" rows="3"
                                  class="w-full border rounded-xl px-3 py-2"
                                  placeholder="What was communicated..."></textarea>

                        <button class="w-full bg-indigo-600 text-white px-5 py-2 rounded-xl font-semibold hover:bg-indigo-700">
                            Log Communication
                        </button>
                    </form>
                </div>
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-xl font-bold text-slate-900">Facility Messages</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Communication between facility staff and provider for this patient.
                    </p>

                    <div class="mt-6 space-y-4">
                        @forelse($providerMessages->take(10) as $msg)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <div class="text-sm font-bold text-slate-900">{{ $msg->subject ?? 'Message' }}</div>
                                <div class="mt-2 text-sm text-slate-600">
                                    From: {{ $msg->facility->name ?? 'Facility' }}
                                    • {{ optional($msg->created_at)->diffForHumans() }}
                                </div>
                                <div class="mt-3 rounded-xl bg-slate-50 p-3 text-sm text-slate-800">
                                    {{ $msg->message }}
                                </div>

                                @if(Route::has('provider.messages.show'))
                                    <div class="mt-3">
                                        <a href="{{ route('provider.messages.show', $msg) }}"
                                           class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                            Open →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-slate-500">
                                No messages for this patient yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-bold text-slate-900">Patient Profile</h2>

                    <div class="mt-5 space-y-4">
                        @foreach([
                            'Allergies' => $patient->allergies ?? null,
                            'Medical History' => $patient->medical_history ?? null,
                            'Family History' => $patient->family_history ?? null,
                            'Social History' => $patient->social_history ?? null,
                            'Chief Complaint' => $patient->chief_complaint ?? null,
                        ] as $label => $value)
                            <div>
                                <div class="text-xs uppercase tracking-wide text-slate-500">{{ $label }}</div>
                                <div class="mt-1 text-sm font-medium text-slate-900">{{ $value ?: 'Not recorded' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
