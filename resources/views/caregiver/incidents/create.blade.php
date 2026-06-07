@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('caregiver.incidents.index') }}"
               class="text-sm font-bold text-red-700 hover:text-red-900">
                ← Back to Incident Reports
            </a>

            <a href="{{ route('caregiver.dashboard') }}"
               class="text-sm font-bold text-slate-600 hover:text-slate-900">
                Dashboard
            </a>
        </div>

        <div class="overflow-hidden rounded-3xl border border-red-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-red-800 via-rose-700 to-slate-900 px-8 py-6 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-red-100">
                    KassCare Safety & Incident Center
                </p>

                <h1 class="mt-2 text-3xl font-black">
                    🚨 New Incident Report
                </h1>

                <p class="mt-2 text-sm text-red-100">
                    Document falls, medication errors, injuries, behavior concerns, hospital transfers, and other safety events.
                </p>
            </div>

            <form method="POST" action="{{ route('caregiver.incidents.store') }}" class="space-y-6 p-8">
                @csrf

                @if($errors->any())
                    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
                        Please review the form and complete all required fields.
                    </div>
                @endif

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Resident / Client</label>
                    <select name="client_id"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                        <option value="">Select resident if applicable</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name ?? trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? '')) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Incident Type</label>
                        <select name="incident_type" required
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                            <option value="">Select incident type</option>
                            <option value="Fall">Fall</option>
                            <option value="Medication Error">Medication Error</option>
                            <option value="Injury">Injury</option>
                            <option value="Behavior Incident">Behavior Incident</option>
                            <option value="Elopement / Wandering">Elopement / Wandering</option>
                            <option value="Hospital Transfer">Hospital Transfer</option>
                            <option value="Abuse / Neglect Concern">Abuse / Neglect Concern</option>
                            <option value="Clinical Change">Clinical Change</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Severity</label>
                        <select name="severity" required
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                            <option value="">Select severity</option>
                            <option value="Low">Low</option>
                            <option value="Moderate">Moderate</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Location</label>
                        <input type="text"
                               name="location"
                               value="{{ old('location') }}"
                               placeholder="Bathroom, bedroom, hallway, dining room..."
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Incident Date / Time</label>
                        <input type="datetime-local"
                               name="incident_datetime"
                               value="{{ old('incident_datetime') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Witnesses</label>
                    <input type="text"
                           name="witnesses"
                           value="{{ old('witnesses') }}"
                           placeholder="Names of witnesses, if any"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Immediate Action Taken</label>
                    <textarea name="immediate_action"
                              rows="3"
                              class="w-full rounded-2xl border border-slate-300 px-4 py-3"
                              placeholder="Assisted resident, checked vitals, notified supervisor, called 911...">{{ old('immediate_action') }}</textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Incident Narrative</label>
                    <textarea name="description"
                              rows="7"
                              required
                              class="w-full rounded-2xl border border-slate-300 px-4 py-3"
                              placeholder="Describe exactly what happened.">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <label class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm font-bold text-slate-700">
                        <input type="checkbox" name="provider_notified" value="1" class="mr-2">
                        Provider Notified
                    </label>

                    <label class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm font-bold text-slate-700">
                        <input type="checkbox" name="family_notified" value="1" class="mr-2">
                        Family Notified
                    </label>

                    <label class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm font-bold text-slate-700">
                        <input type="checkbox" name="ems_called" value="1" class="mr-2">
                        EMS Called
                    </label>

                    <label class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm font-bold text-slate-700">
                        <input type="checkbox" name="hospital_transfer" value="1" class="mr-2">
                        Hospital Transfer
                    </label>
                </div>

                <button type="submit"
                        class="rounded-2xl bg-red-700 px-6 py-3 font-black text-white shadow hover:bg-red-800">
                    Save Incident Report
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
