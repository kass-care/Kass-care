@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Create Care Log</h1>
            <p class="text-slate-600 mt-2">Document visit care details, ADL support, vitals, and care notes.</p>
        </div>

        @php
            $currentUser = auth()->user();
            $isCaregiver = auth()->check() && $currentUser->role === 'caregiver';
            $hasVisits = isset($visits) && $visits->count() > 0;
        @endphp

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <p class="font-semibold mb-2">Please fix the following:</p>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($isCaregiver && !$hasVisits)
            <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-6 py-5 text-amber-900">
                <p class="font-semibold text-xl">No assigned visits available for care logging yet.</p>
                <p class="mt-2 text-sm">
                    Once a visit is assigned to this caregiver, it will appear here and the client/caregiver details will auto-fill.
                </p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow p-8">
            <form method="POST" action="{{ auth()->check() && auth()->user()->role === 'provider' ? route('provider.carelogs.store') : route('caregiver.care-logs.store') }}">
                @csrf

                @if($isCaregiver)
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Visit</label>
                                 <select
    name="visit_id"
    id="visit_id"
    class="w-full rounded-lg border border-slate-300 {{ !$hasVisits ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : '' }}"
    {{ $hasVisits ? 'required' : 'disabled' }}
>
    <option value="">{{ $hasVisits ? 'Select visit' : 'No assigned visits available' }}</option>
    @foreach($visits as $visit)
        <option
            value="{{ $visit->id }}"
            {{ old('visit_id', optional($selectedVisit)->id) == $visit->id ? 'selected' : '' }}
        >
            Visit #{{ $visit->id }}
            — {{ optional($visit->client)->name ?? 'No Client' }}
            — {{ $visit->visit_date ?? 'No Date' }}
        </option>
    @endforeach
</select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Client</label>
                                <input
                                    type="text"
                                    id="client_display"
                                    class="w-full rounded-lg border border-slate-300 bg-slate-100"
                                    value="{{ optional(optional($selectedVisit)->client)->name }}"
                                    readonly
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Caregiver</label>
                                <input
                                    type="text"
                                    id="caregiver_display"
                                    class="w-full rounded-lg border border-slate-300 bg-slate-100"
                                    value="{{ optional(optional($selectedVisit)->caregiver)->name }}"
                                    readonly
                                >
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Client</label>
                            <select name="client_id" id="client_id" class="w-full rounded-lg border border-slate-300">
                                <option value="">Select client</option>
                                @foreach($clients as $client)
                                    <option
                                        value="{{ $client->id }}"
                                        {{ old('client_id', optional(optional($selectedVisit)->client)->id) == $client->id ? 'selected' : '' }}
                                    >
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Caregiver</label>
                            <select name="caregiver_id" id="caregiver_id" class="w-full rounded-lg border border-slate-300">
                                <option value="">Select caregiver</option>
                                @foreach($caregivers as $caregiver)
                                    <option
                                        value="{{ $caregiver->id }}"
                                        {{ old('caregiver_id', optional(optional($selectedVisit)->caregiver)->id) == $caregiver->id ? 'selected' : '' }}
                                    >
                                        {{ $caregiver->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Visit</label>
                        <select name="visit_id" id="visit_id" class="w-full rounded-lg border border-slate-300" required>
                            <option value="">Select visit</option>
                            @foreach($visits as $visit)
                                <option
                                    value="{{ $visit->id }}"
                                    {{ old('visit_id', optional($selectedVisit)->id) == $visit->id ? 'selected' : '' }}
                                >
                                    Visit #{{ $visit->id }}
                                    — {{ optional($visit->client)->name ?? 'No Client' }}
                                    — {{ $visit->visit_date ?? 'No Date' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="mt-10">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">ADL Charting</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">ADL Status</label>
                            <select name="adl_status" class="w-full rounded-lg border border-slate-300">
                                <option value="">Select status</option>
                                @foreach (['Independent', 'Assisted', 'Completed', 'Partial Assist', 'Declined', 'Not Done'] as $option)
                                    <option value="{{ $option }}" {{ old('adl_status') === $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Bathroom Assistance</label>
                            <select name="bathroom_assistance" class="w-full rounded-lg border border-slate-300">
                                <option value="">Select status</option>
                                @foreach (['Independent', 'Standby Assist', 'One Person Assist', 'Two Person Assist', 'Scheduled Support'] as $option)
                                    <option value="{{ $option }}" {{ old('bathroom_assistance') === $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Mobility Support</label>
                            <select name="mobility_support" class="w-full rounded-lg border border-slate-300">
                                <option value="">Select status</option>
                                @foreach (['Independent', 'Walker', 'Wheelchair', 'Cane', 'One Person Assist', 'Bedbound'] as $option)
                                    <option value="{{ $option }}" {{ old('mobility_support') === $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Meal Notes</label>
                            <select name="meal_notes" class="w-full rounded-lg border border-slate-300">
                                <option value="">Select meal intake</option>
                                @foreach (['100%', '75%', '50%', '25%', 'Declined'] as $option)
                                    <option value="{{ $option }}" {{ old('meal_notes') === $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Medication Notes</label>
                            <select name="medication_notes" class="w-full rounded-lg border border-slate-300">
                                <option value="">Select medication status</option>
                                @foreach (['Given', 'Observed', 'Refused', 'Reminder Given', 'Not Scheduled'] as $option)
                                    <option value="{{ $option }}" {{ old('medication_notes') === $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Charting Notes</label>
                            <textarea
                                name="charting_notes"
                                rows="3"
                                class="w-full rounded-lg border border-slate-300"
                                placeholder="Additional ADL charting notes"
                            >{{ old('charting_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Care Notes</label>
                    <textarea
                        name="notes"
                        rows="4"
                        class="w-full rounded-lg border border-slate-300"
                        placeholder="Write care summary, observations, and notes"
                    >{{ old('notes') }}</textarea>
                </div>

                <div class="mt-10">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Vitals</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Blood Pressure</label>
                            <input type="text" name="vitals[blood_pressure]" value="{{ old('vitals.blood_pressure') }}" class="w-full rounded-lg border border-slate-300" placeholder="e.g. 120/80">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pulse</label>
                            <input type="text" name="vitals[pulse]" value="{{ old('vitals.pulse') }}" class="w-full rounded-lg border border-slate-300" placeholder="e.g. 72">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Temperature</label>
                            <input type="text" name="vitals[temperature]" value="{{ old('vitals.temperature') }}" class="w-full rounded-lg border border-slate-300" placeholder="e.g. 98.6">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Respiratory Rate</label>
                            <input type="text" name="vitals[respiratory_rate]" value="{{ old('vitals.respiratory_rate') }}" class="w-full rounded-lg border border-slate-300" placeholder="e.g. 16">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Oxygen Saturation</label>
                            <input type="text" name="vitals[oxygen_saturation]" value="{{ old('vitals.oxygen_saturation') }}" class="w-full rounded-lg border border-slate-300" placeholder="e.g. 98%">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Weight</label>
                            <input type="text" name="vitals[weight]" value="{{ old('vitals.weight') }}" class="w-full rounded-lg border border-slate-300" placeholder="e.g. 145 lb">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Blood Sugar</label>
                            <input type="text" name="vitals[blood_sugar]" value="{{ old('vitals.blood_sugar') }}" class="w-full rounded-lg border border-slate-300" placeholder="e.g. 110">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Check In Time <span class="text-slate-400">(optional)</span></label>
                        <input type="datetime-local" name="check_in_time" value="{{ old('check_in_time') }}" class="w-full rounded-lg border border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Check Out Time <span class="text-slate-400">(optional)</span></label>
                        <input type="datetime-local" name="check_out_time" value="{{ old('check_out_time') }}" class="w-full rounded-lg border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Latitude <span class="text-slate-400">(optional)</span></label>
                        <input type="text" name="latitude" value="{{ old('latitude') }}" class="w-full rounded-lg border border-slate-300" placeholder="e.g. 45.5231">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Longitude <span class="text-slate-400">(optional)</span></label>
                        <input type="text" name="longitude" value="{{ old('longitude') }}" class="w-full rounded-lg border border-slate-300" placeholder="e.g. -122.6765">
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                        Save Care Log
                    </button>

                    <a
                        href="{{ auth()->check() && auth()->user()->role === 'provider' ? route('provider.calendar') : route('caregiver.dashboard') }}"
                        class="bg-slate-200 text-slate-800 px-6 py-3 rounded-lg hover:bg-slate-300 font-semibold"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@if($isCaregiver)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const visitSelect = document.getElementById('visit_id');
    const clientDisplay = document.getElementById('client_display');
    const caregiverDisplay = document.getElementById('caregiver_display');

    if (!visitSelect || !clientDisplay || !caregiverDisplay) return;

    const visitMap = {!! json_encode(
        $visits->mapWithKeys(function ($visit) {
            return [
                $visit->id => [
                    'client_name' => optional($visit->client)->name,
                    'caregiver_name' => optional($visit->caregiver)->name,
                ],
            ];
        })->toArray()
    ) !!};

    function syncCaregiverVisitDetails() {
        const visitId = visitSelect.value;

        if (!visitId || !visitMap[visitId]) {
            clientDisplay.value = '';
            caregiverDisplay.value = '';
            return;
        }

        clientDisplay.value = visitMap[visitId].client_name ?? '';
        caregiverDisplay.value = visitMap[visitId].caregiver_name ?? '';
    }

    visitSelect.addEventListener('change', syncCaregiverVisitDetails);

    if (visitSelect.value) {
        syncCaregiverVisitDetails();
    }
});
</script>
@else
<script>
document.addEventListener('DOMContentLoaded', function () {
    const visitSelect = document.getElementById('visit_id');
    const clientSelect = document.getElementById('client_id');
    const caregiverSelect = document.getElementById('caregiver_id');

    if (!visitSelect || !clientSelect || !caregiverSelect) return;

    const visitMap = {!! json_encode(
        $visits->mapWithKeys(function ($visit) {
            return [
                $visit->id => [
                    'client_id' => $visit->client_id,
                    'caregiver_id' => $visit->caregiver_id,
                ],
            ];
        })->toArray()
    ) !!};

    function syncFromVisit() {
        const visitId = visitSelect.value;
        if (!visitId || !visitMap[visitId]) return;

        clientSelect.value = visitMap[visitId].client_id ?? '';
        caregiverSelect.value = visitMap[visitId].caregiver_id ?? '';
    }

    visitSelect.addEventListener('change', syncFromVisit);

    if (visitSelect.value) {
        syncFromVisit();
    }
});
</script>
@endif
@endsection
