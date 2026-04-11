@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-8">

    <h1 class="text-2xl font-bold text-slate-800 mb-6">
        New Care Log
    </h1>

    @if($visits->count() == 0)
        <div class="rounded-xl border border-yellow-200 bg-yellow-50 px-5 py-4 text-yellow-900">
            <div class="font-semibold">No assigned visits available for care logging yet.</div>
            <div class="text-sm mt-1">Once a visit is assigned to this caregiver, it will appear here.</div>
        </div>
    @else

<form method="POST" action="{{ route('caregiver.care-logs.store') }}" class="space-y-8 bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
@csrf


<!-- VISIT -->
<div>
<label class="block text-sm font-semibold text-slate-700 mb-2">
Visit
</label>

<select name="visit_id"
        required
        class="w-full rounded-lg border border-slate-300 px-4 py-2">

@foreach($visits as $visit)

<option value="{{ $visit->id }}">
{{ $visit->client->first_name ?? 'Client' }} — {{ $visit->visit_date }}
</option>

@endforeach

</select>
</div>


<!-- ADL CHARTING -->
<div>

<h2 class="text-lg font-semibold text-slate-800 mb-4">
ADL Charting
</h2>

<div class="grid md:grid-cols-2 gap-4">


<!-- ADL STATUS -->
<div>
<label class="block text-sm text-slate-700 mb-1">
ADL Status
</label>

<select name="adl_status" class="w-full border border-slate-300 rounded-lg px-3 py-2">
<option value="">Select</option>
<option>Independent</option>
<option>Needs Assistance</option>
<option>Dependent</option>
</select>
</div>


<!-- BATHROOM -->
<div>
<label class="block text-sm text-slate-700 mb-1">
Bathroom Assistance
</label>

<select name="bathroom_assistance" class="w-full border border-slate-300 rounded-lg px-3 py-2">
<option value="">Select</option>
<option>Independent</option>
<option>Supervision</option>
<option>Full Assistance</option>
</select>
</div>


<!-- MOBILITY -->
<div>
<label class="block text-sm text-slate-700 mb-1">
Mobility Support
</label>

<select name="mobility_support" class="w-full border border-slate-300 rounded-lg px-3 py-2">
<option value="">Select</option>
<option>Independent</option>
<option>Walker</option>
<option>Wheelchair</option>
<option>Full Assistance</option>
</select>
</div>


<!-- MEALS -->
<div>
<label class="block text-sm text-slate-700 mb-1">
Meal Intake
</label>

<select name="meal_notes" class="w-full border border-slate-300 rounded-lg px-3 py-2">
<option value="">Select</option>
<option>100%</option>
<option>75%</option>
<option>50%</option>
<option>25%</option>
<option>Refused</option>
</select>
</div>


</div>
</div>



<!-- MEDICATION -->
<div>

<label class="block text-sm text-slate-700 mb-1">
Medication Notes
</label>

<textarea name="medication_notes"
          rows="2"
          class="w-full border border-slate-300 rounded-lg px-3 py-2"
          placeholder="Medication notes"></textarea>

</div>



<!-- CHARTING NOTES -->
<div>

<label class="block text-sm text-slate-700 mb-1">
Charting Notes
</label>

<textarea name="charting_notes"
          rows="3"
          class="w-full border border-slate-300 rounded-lg px-3 py-2"
          placeholder="Additional charting notes"></textarea>

</div>



<!-- VITALS -->
<div>
<h2 class="text-lg font-semibold text-slate-800 mb-4">
Vitals
</h2>

<div class="grid md:grid-cols-3 gap-4">

<!-- BLOOD PRESSURE -->
<div>
<label class="block text-sm text-slate-700 mb-1">
Blood Pressure
</label>
<input type="text"
name="blood_pressure"
placeholder="e.g. 120/80"
class="w-full border border-slate-300 rounded-lg px-3 py-2">
</div>

<!-- PULSE -->
<div>
<label class="block text-sm text-slate-700 mb-1">
Pulse
</label>
<input type="text"
name="pulse"
placeholder="e.g. 72"
class="w-full border border-slate-300 rounded-lg px-3 py-2">
</div>

<!-- TEMPERATURE -->
<div>
<label class="block text-sm text-slate-700 mb-1">
Temperature
</label>
<input type="text"
name="temperature"
placeholder="e.g. 98.6"
class="w-full border border-slate-300 rounded-lg px-3 py-2">
</div>

<!-- RESPIRATORY RATE -->
<div>
<label class="block text-sm text-slate-700 mb-1">
Respiratory Rate
</label>
<input type="text"
name="respiratory_rate"
placeholder="e.g. 16"
class="w-full border border-slate-300 rounded-lg px-3 py-2">
</div>

<!-- OXYGEN SATURATION -->
<div>
<label class="block text-sm text-slate-700 mb-1">
Oxygen Saturation
</label>
<input type="text"
name="oxygen_saturation"
placeholder="e.g. 98%"
class="w-full border border-slate-300 rounded-lg px-3 py-2">
</div>
<!-- WEIGHT -->
<div>
<label class="block text-sm text-slate-700 mb-1">
Weight (lb)
</label>
<input type="number"
step="0.1"
name="weight_lb"
placeholder="e.g. 154"
class="w-full border border-slate-300 rounded-lg px-3 py-2">
</div>

<!-- BLOOD SUGAR -->
<div>
<label class="block text-sm text-slate-700 mb-1">
Blood Sugar
</label>
<input type="text"
name="blood_sugar"
placeholder="e.g. 110"
class="w-full border border-slate-300 rounded-lg px-3 py-2">
</div>

</div>
</div>


<!-- BUTTONS -->
<div class="flex gap-3 pt-4">

<button type="submit"
class="rounded-lg bg-indigo-600 text-white px-6 py-2 font-semibold hover:bg-indigo-700">
Save Care Log
</button>

<a href="{{ route('caregiver.care-logs.index') }}"
class="rounded-lg bg-slate-200 px-6 py-2 font-semibold text-slate-700">
Cancel
</a>

</div>


</form>

@endif

</div>

@endsection
