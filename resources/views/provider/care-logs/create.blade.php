@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">
        New Care Log
    </h1>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
            <div class="font-semibold mb-2">Please fix the following:</div>
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($visits->count() === 0)
        <div class="rounded-xl border border-yellow-200 bg-yellow-50 px-5 py-4 text-yellow-900">
            <div class="font-semibold">No facility visits available for care logging yet.</div>
            <div class="text-sm mt-1">
                Once a visit exists in the selected facility context, it will appear here.
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('provider.care-logs.store') }}"
              class="space-y-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Visit
                </label>
                <select name="visit_id"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2">
                    @foreach($visits as $visit)
                        <option value="{{ $visit->id }}"
                            {{ old('visit_id', optional($selectedVisit)->id) == $visit->id ? 'selected' : '' }}>
                            {{ $visit->client->first_name ?? 'Client' }} — {{ $visit->visit_date }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-slate-800 mb-4">ADL Charting</h2>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-slate-700 mb-1">ADL Status</label>
                        <select name="adl_status" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                            <option value="">Select</option>
                            <option value="Independent" {{ old('adl_status') === 'Independent' ? 'selected' : '' }}>Independent</option>
                            <option value="Needs Assistance" {{ old('adl_status') === 'Needs Assistance' ? 'selected' : '' }}>Needs Assistance</option>
                            <option value="Dependent" {{ old('adl_status') === 'Dependent' ? 'selected' : '' }}>Dependent</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Bathroom Assistance</label>
                        <select name="bathroom_assistance" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                            <option value="">Select</option>
                            <option value="Independent" {{ old('bathroom_assistance') === 'Independent' ? 'selected' : '' }}>Independent</option>
                            <option value="Supervision" {{ old('bathroom_assistance') === 'Supervision' ? 'selected' : '' }}>Supervision</option>
                            <option value="Full Assistance" {{ old('bathroom_assistance') === 'Full Assistance' ? 'selected' : '' }}>Full Assistance</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Mobility Support</label>
                        <select name="mobility_support" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                            <option value="">Select</option>
                            <option value="Independent" {{ old('mobility_support') === 'Independent' ? 'selected' : '' }}>Independent</option>
                            <option value="Walker" {{ old('mobility_support') === 'Walker' ? 'selected' : '' }}>Walker</option>
                            <option value="Wheelchair" {{ old('mobility_support') === 'Wheelchair' ? 'selected' : '' }}>Wheelchair</option>
                            <option value="Full Assistance" {{ old('mobility_support') === 'Full Assistance' ? 'selected' : '' }}>Full Assistance</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Meal Intake</label>
                        <select name="meal_notes" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                            <option value="">Select</option>
                            <option value="100%" {{ old('meal_notes') === '100%' ? 'selected' : '' }}>100%</option>
                            <option value="75%" {{ old('meal_notes') === '75%' ? 'selected' : '' }}>75%</option>
                            <option value="50%" {{ old('meal_notes') === '50%' ? 'selected' : '' }}>50%</option>
                            <option value="25%" {{ old('meal_notes') === '25%' ? 'selected' : '' }}>25%</option>
                            <option value="Refused" {{ old('meal_notes') === 'Refused' ? 'selected' : '' }}>Refused</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm text-slate-700 mb-1">Medication Notes</label>
                    <textarea name="medication_notes"
                              rows="2"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2"
                              placeholder="Medication notes">{{ old('medication_notes') }}</textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-sm text-slate-700 mb-1">Charting Notes</label>
                    <textarea name="charting_notes"
                              rows="3"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2"
                              placeholder="Additional charting notes">{{ old('charting_notes') }}</textarea>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-slate-800 mb-4">Vitals</h2>

                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Blood Pressure</label>
                        <input type="text"
                               name="blood_pressure"
                               value="{{ old('blood_pressure') }}"
                               placeholder="e.g. 120/80"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Pulse</label>
                        <input type="text"
                               name="pulse"
                               value="{{ old('pulse') }}"
                               placeholder="e.g. 72"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Temperature</label>
                        <input type="text"
                               name="temperature"
                               value="{{ old('temperature') }}"
                               placeholder="e.g. 98.6"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Respiratory Rate</label>
                        <input type="text"
                               name="respiratory_rate"
                               value="{{ old('respiratory_rate') }}"
                               placeholder="e.g. 16"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Oxygen Saturation</label>
                        <input type="text"
                               name="oxygen_saturation"
                               value="{{ old('oxygen_saturation') }}"
                               placeholder="e.g. 98%"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Weight (lb)</label>
                        <input type="number"
                               step="0.1"
                               name="weight_lb"
                               value="{{ old('weight_lb') }}"
                               placeholder="e.g. 154"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Blood Sugar</label>
                        <input type="text"
                               name="blood_sugar"
                               value="{{ old('blood_sugar') }}"
                               placeholder="e.g. 110"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Check In Time</label>
                        <input type="text"
                               name="check_in_time"
                               value="{{ old('check_in_time') }}"
                               placeholder="e.g. 08:15 AM"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Check Out Time</label>
                        <input type="text"
                               name="check_out_time"
                               value="{{ old('check_out_time') }}"
                               placeholder="e.g. 10:45 AM"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Latitude</label>
                        <input type="text"
                               name="latitude"
                               value="{{ old('latitude') }}"
                               placeholder="Optional"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-slate-700 mb-1">Longitude</label>
                        <input type="text"
                               name="longitude"
                               value="{{ old('longitude') }}"
                               placeholder="Optional"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit"
                        class="rounded-lg bg-indigo-600 px-6 py-2 font-semibold text-white hover:bg-indigo-700">
                    Save Care Log
                </button>

                <a href="{{ route('provider.care-logs.index') }}"
                   class="rounded-lg bg-slate-200 px-6 py-2 font-semibold text-slate-700">
                    Cancel
                </a>
            </div>
        </form>
    @endif
</div>
@endsection
