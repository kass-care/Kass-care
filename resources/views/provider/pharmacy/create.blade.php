@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold mb-4">💊 Create Prescription</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('provider.pharmacy.store') }}" class="bg-white p-6 rounded shadow">
        @csrf

        <label class="block mb-1 font-medium">Client</label>
        <select name="client_id" class="w-full mb-4 border p-2 rounded">
            <option value="">Select Client</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
        </select>

        <label class="block mb-1 font-medium">Medication</label>
        <input type="text" name="medication_name" class="w-full mb-4 border p-2 rounded" placeholder="e.g. Amoxicillin">

        <label class="block mb-1 font-medium">Dosage</label>
        <input type="text" name="dosage" class="w-full mb-4 border p-2 rounded" placeholder="e.g. 500mg">

        <label class="block mb-1 font-medium">Frequency</label>
        <input type="text" name="frequency" class="w-full mb-4 border p-2 rounded" placeholder="e.g. Twice daily">

        <label class="block mb-1 font-medium">Quantity</label>
        <input type="number" name="quantity" class="w-full mb-4 border p-2 rounded">

        <label class="block mb-1 font-medium">Refills</label>
        <input type="number" name="refills" class="w-full mb-4 border p-2 rounded">

        <label class="block mb-1 font-medium">Pharmacy Name</label>
        <input type="text" name="pharmacy_name" class="w-full mb-4 border p-2 rounded">

        <label class="block mb-1 font-medium">Pharmacy Phone</label>
        <input type="text" name="pharmacy_phone" class="w-full mb-4 border p-2 rounded">

        <label class="block mb-1 font-medium">Pharmacy Fax</label>
        <input type="text" name="pharmacy_fax" class="w-full mb-4 border p-2 rounded">
        
       <label class="block mb-1 font-medium">Pharmacy Email</label>
<input type="email" name="pharmacy_email"
       class="w-full mb-4 border p-2 rounded"
       placeholder="pharmacy@example.com">
        <label class="block mb-1 font-medium">Instructions</label>
        <textarea name="instructions" class="w-full mb-4 border p-2 rounded" placeholder="Take after meals..."></textarea>

        <div class="flex justify-between">
            <a href="{{ route('provider.pharmacy.index') }}"
               class="bg-gray-300 px-4 py-2 rounded">
                Cancel
            </a>

            <button class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                Save Prescription
            </button>
        </div>
    </form>

</div>
@endsection
