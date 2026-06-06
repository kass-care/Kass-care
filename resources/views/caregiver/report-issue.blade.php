@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <a href="{{ route('caregiver.visits') }}"
               class="text-sm font-bold text-emerald-700 hover:text-emerald-900">
                ← Back to Visits
            </a>
        </div>

        <div class="overflow-hidden rounded-3xl border border-red-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-red-700 to-rose-600 px-8 py-6 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-red-100">
                    Caregiver Escalation
                </p>

                <h1 class="mt-2 text-3xl font-black">
                    Report Clinical Issue
                </h1>

                <p class="mt-2 text-sm text-red-100">
                    Send an urgent message to the provider for this visit.
                </p>
            </div>

            <div class="p-8">
                <div class="mb-6 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="text-sm text-slate-600">
                        Visit #{{ $visit->id }}
                    </div>

                    <div class="mt-1 text-xl font-black text-slate-900">
                        {{ $visit->client->name ?? 'Unknown Patient' }}
                    </div>

                    <div class="mt-1 text-sm text-slate-500">
                        Facility: {{ $visit->facility->name ?? 'N/A' }}
                    </div>
                </div>

                   
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">
                                Incident Type
                            </label>

                            <select name="incident_type"
                                    required
                                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-red-500">
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
                            <label class="mb-2 block text-sm font-bold text-slate-700">
                                Severity
                            </label>

                            <select name="severity"
                                    required
                                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-red-500">
                                <option value="">Select severity</option>
                                <option value="Low">Low</option>
                                <option value="Moderate">Moderate</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">
                            Location of Incident
                        </label>

                        <input type="text"
                               name="incident_location"
                               value="{{ old('incident_location') }}"
                               placeholder="Example: bedroom, bathroom, hallway, dining room"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-red-500">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">
                            Witnesses
                        </label>

                        <input type="text"
                               name="witnesses"
                               value="{{ old('witnesses') }}"
                               placeholder="Names of witnesses, if any"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-red-500">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">
                            Immediate Action Taken
                        </label>

                        <textarea name="immediate_action"
                                  rows="3"
                                  class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-red-500"
                                  placeholder="Example: assisted resident, checked vitals, notified supervisor, called 911, monitored condition">{{ old('immediate_action') }}</textarea>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">
                            Incident Narrative
                        </label>

                        <textarea name="message"
                                  rows="7"
                                  required
                                  class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-red-500"
                                  placeholder="Describe what happened, symptoms, vitals, behavior change, medication issue, fall details, or other concern.">{{ old('message') }}</textarea>
                    </div>                    <button type="submit"
                            class="rounded-2xl bg-red-600 px-6 py-3 font-bold text-white shadow hover:bg-red-700">
                        Send Urgent Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
