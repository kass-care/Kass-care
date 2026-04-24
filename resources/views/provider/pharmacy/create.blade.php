@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto p-6">

<h1 class="text-2xl font-bold mb-6">💊 Create Prescription</h1>

<form method="POST" action="{{ route('provider.pharmacy.store') }}" class="space-y-4">

@csrf

<div>
<label class="block text-sm font-semibold mb-1">Allergies</label>
<textarea name="allergies" class="w-full border rounded px-3 py-2"
placeholder="e.g. Penicillin, Sulfa, No known drug allergies"></textarea>
</div>

<div>
<label class="block text-sm font-semibold mb-1">Client</label>

<select name="client_id" required
class="w-full border rounded px-3 py-2">

<option value="">Select Client</option>

@foreach($clients as $client)
<option value="{{ $client->id }}">
{{ $client->name }}
</option>
@endforeach

</select>

</div>

<div>
<label class="block text-sm font-semibold mb-1">Medication</label>
<input type="text" name="medication_name"
class="w-full border rounded px-3 py-2"
placeholder="Medication name" required>
</div>

<div>
<label class="block text-sm font-semibold mb-1">Dosage</label>
<input type="text" name="dosage"
class="w-full border rounded px-3 py-2"
placeholder="e.g. 500mg">
</div>

<div>
    <label class="block text-sm font-semibold mb-1">Frequency</label>
    <select name="frequency" class="w-full border rounded px-3 py-2">
        <option value="">Select frequency</option>
        <option value="Once daily">Once daily</option>
        <option value="Twice daily">Twice daily</option>
        <option value="Three times daily">Three times daily</option>
        <option value="Four times daily">Four times daily</option>
        <option value="Every 4 hours">Every 4 hours</option>
        <option value="Every 6 hours">Every 6 hours</option>
        <option value="Every 8 hours">Every 8 hours</option>
        <option value="Every 12 hours">Every 12 hours</option>
        <option value="As needed">As needed</option>
        <option value="At bedtime">At bedtime</option>
        <option value="Before meals">Before meals</option>
        <option value="After meals">After meals</option>
    </select>
</div>

<div>
    <label class="block text-sm font-semibold mb-1">Quantity</label>
    <div class="grid grid-cols-2 gap-3">
        <input type="number" name="quantity" class="w-full border rounded px-3 py-2" placeholder="Amount">

        <select name="quantity_unit" class="w-full border rounded px-3 py-2">
            <option value="">Select unit</option>
            <option value="tablet(s)">Tablet(s)</option>
            <option value="capsule(s)">Capsule(s)</option>
            <option value="mL">mL</option>
            <option value="mg">mg</option>
            <option value="mcg">mcg</option>
            <option value="unit(s)">Unit(s)</option>
            <option value="drop(s)">Drop(s)</option>
            <option value="patch(es)">Patch(es)</option>
            <option value="injection(s)">Injection(s)</option>
            <option value="bottle(s)">Bottle(s)</option>
            <option value="tube(s)">Tube(s)</option>
        </select>
    </div>
</div>

<div>
<label class="block text-sm font-semibold mb-1">Refills</label>
<input type="number" name="refills"
class="w-full border rounded px-3 py-2">
</div>

<div>
<label class="block text-sm font-semibold mb-1">Pharmacy Name</label>
<input type="text" name="pharmacy_name"
class="w-full border rounded px-3 py-2">
</div>

<div>
<label class="block text-sm font-semibold mb-1">Pharmacy Phone</label>
<input type="text" name="pharmacy_phone"
class="w-full border rounded px-3 py-2">
</div>

<div class="pt-4">

<button type="submit"
class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">

Create Prescription

</button>

</div>

</form>

</div>

@endsection
