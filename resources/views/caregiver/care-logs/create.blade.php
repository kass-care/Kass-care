@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900">New Care Log</h1>
            <p class="text-slate-600 mt-2">Chart ADLs, vitals, and caregiver notes for the selected visit.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
            <form action="{{ route('caregiver.care-logs.store') }}" method="POST" class="space-y-8">
                @csrf

                <div>
                    <label for="visit_id" class="block text-sm font-bold text-slate-700 mb-2">Select Visit</label>
                    <select name="visit_id" id="visit_id" required
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Choose visit</option>
                        @foreach($visits as $visit)
                            <option value="{{ $visit->id }}"
                                {{ old('visit_id', optional($selectedVisit)->id) == $visit->id ? 'selected' : '' }}>
                                Visit #{{ $visit->id }} — Client: {{ $visit->client->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <h2 class="text-xl font-black text-slate-900 mb-4">ADL Charting</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">ADL Status</label>
                            <select name="adl_status" class="w-full rounded-xl border-slate-300">
                                <option value="">Select status</option>
                                <option value="Independent">Independent</option>
                                <option value="Assisted">Assisted</option>
                                <option value="Dependent">Dependent</option>
                                <option value="Refused">Refused</option>
                                <option value="Not Done">Not Done</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Bathroom Assistance</label>
                            <select name="bathroom_assistance" class="w-full rounded-xl border-slate-300">
                                <option value="">Select assistance</option>
                                <option value="Independent">Independent</option>
                                <option value="Standby Assist">Standby Assist</option>
                                <option value="One-person Assist">One-person Assist</option>
                                <option value="Two-person Assist">Two-person Assist</option>
                                <option value="Full Assist">Full Assist</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Mobility Support</label>
                            <select name="mobility_support" class="w-full rounded-xl border-slate-300">
                                <option value="">Select mobility support</option>
                                <option value="Ambulates independently">Ambulates independently</option>
                                <option value="Walker">Walker</option>
                                <option value="Wheelchair">Wheelchair</option>
                                <option value="Transfer assist">Transfer assist</option>
                                <option value="Bedbound">Bedbound</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Meal Notes</label>
                            <input type="text" name="meal_notes" value="{{ old('meal_notes') }}"
                                   class="w-full rounded-xl border-slate-300"
                                   placeholder="Ate well, poor appetite, refused meal...">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Medication Notes</label>
                            <textarea name="medication_notes" rows="3"
                                      class="w-full rounded-xl border-slate-300"
                                      placeholder="Medication taken, refused, delayed, needs follow-up...">{{ old('medication_notes') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Charting Notes</label>
                            <textarea name="charting_notes" rows="3"
                                      class="w-full rounded-xl border-slate-300"
                                      placeholder="General ADL/charting notes...">{{ old('charting_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-black text-slate-900 mb-4">Vitals</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <input name="blood_pressure" value="{{ old('blood_pressure') }}" placeholder="Blood Pressure e.g. 120/80" class="rounded-xl border-slate-300">
                        <input name="pulse" value="{{ old('pulse') }}" placeholder="Pulse" class="rounded-xl border-slate-300">
                        <input name="temperature" value="{{ old('temperature') }}" placeholder="Temperature" class="rounded-xl border-slate-300">
                        <input name="respiratory_rate" value="{{ old('respiratory_rate') }}" placeholder="Respiratory Rate" class="rounded-xl border-slate-300">
                        <input name="oxygen_saturation" value="{{ old('oxygen_saturation') }}" placeholder="Oxygen Saturation" class="rounded-xl border-slate-300">
                        <input name="blood_sugar" value="{{ old('blood_sugar') }}" placeholder="Blood Sugar" class="rounded-xl border-slate-300">
                        <input name="weight" value="{{ old('weight') }}" placeholder="Weight lb" class="rounded-xl border-slate-300">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Care Notes</label>
                    <textarea name="care_notes" rows="5"
                              class="w-full rounded-xl border-slate-300"
                              placeholder="Write care notes here...">{{ old('care_notes') }}</textarea>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit"
                            class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white hover:bg-indigo-700">
                        Save Care Log
                    </button>

                    <a href="{{ route('caregiver.care-logs.index') }}"
                       class="inline-flex items-center rounded-xl border border-slate-300 px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
