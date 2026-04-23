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
<input type="text" name="frequency"
class="w-full border rounded px-3 py-2"
placeholder="e.g. Twice daily">
</div>

<div>
<label class="block text-sm font-semibold mb-1">Quantity</label>
<input type="number" name="quantity"
class="w-full border rounded px-3 py-2">
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
