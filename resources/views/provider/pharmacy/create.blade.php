@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">💊 Create Prescription</h1>

    <form method="POST" action="{{ route('provider.pharmacy.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-1">Allergies</label>
            <textarea name="allergies"
                      class="w-full border rounded px-3 py-2"
                      placeholder="e.g. Penicillin, Sulfa, No known drug allergies">{{ old('allergies') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Client</label>
            <select name="client_id" required class="w-full border rounded px-3 py-2">
                <option value="">Select Client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Medication</label>
            <input list="medicationOptions"
                   type="text"
                   name="medication_name"
                   value="{{ old('medication_name') }}"
                   class="w-full border rounded px-3 py-2"
                   placeholder="Search or type medication name"
                   required>

            <datalist id="medicationOptions">
                <option value="Acetaminophen">
                <option value="Ibuprofen">
                <option value="Aspirin">
                <option value="Amoxicillin">
                <option value="Azithromycin">
                <option value="Cephalexin">
                <option value="Metformin">
                <option value="Lisinopril">
                <option value="Amlodipine">
                <option value="Losartan">
                <option value="Atorvastatin">
                <option value="Simvastatin">
                <option value="Omeprazole">
                <option value="Pantoprazole">
                <option value="Furosemide">
                <option value="Hydrochlorothiazide">
                <option value="Levothyroxine">
                <option value="Albuterol inhaler">
                <option value="Insulin">
                <option value="Gabapentin">
                <option value="Tramadol">
                <option value="Prednisone">
                <option value="Ondansetron">
                <option value="Loratadine">
                <option value="Cetirizine">
                <option value="Docusate sodium">
                <option value="Polyethylene glycol">
                <option value="Vitamin D">
                <option value="Multivitamin">
                <option value="Melatonin">
            </datalist>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Dosage</label>
            <input type="text"
                   name="dosage"
                   value="{{ old('dosage') }}"
                   class="w-full border rounded px-3 py-2"
                   placeholder="e.g. 500mg, 5ml, 10 units">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Frequency</label>
            <select name="frequency" class="w-full border rounded px-3 py-2">
                <option value="">Select Frequency</option>
                @foreach([
                    'Once daily',
                    'Twice daily',
                    'Three times daily',
                    'Four times daily',
                    'Every 4 hours',
                    'Every 6 hours',
                    'Every 8 hours',
                    'Every 12 hours',
                    'At bedtime',
                    'Before meals',
                    'After meals',
                    'PRN (as needed)'
                ] as $frequency)
                    <option value="{{ $frequency }}" {{ old('frequency') == $frequency ? 'selected' : '' }}>
                        {{ $frequency }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Quantity</label>
            <div class="grid grid-cols-2 gap-3">
                <input type="number"
                       name="quantity"
                       value="{{ old('quantity') }}"
                       class="w-full border rounded px-3 py-2"
                       placeholder="Amount">

                <select name="quantity_unit" class="w-full border rounded px-3 py-2">
                    <option value="">Select Unit</option>
                    @foreach(['mg', 'ml', 'units', 'tablets', 'capsules', 'drops', 'puffs', 'patches', 'bottles', 'vials'] as $unit)
                        <option value="{{ $unit }}" {{ old('quantity_unit') == $unit ? 'selected' : '' }}>
                            {{ $unit }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Refills</label>
            <input type="number"
                   name="refills"
                   value="{{ old('refills') }}"
                   class="w-full border rounded px-3 py-2"
                   placeholder="0">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Pharmacy Name</label>
            <input list="pharmacyOptions"
                   type="text"
                   name="pharmacy_name"
                   value="{{ old('pharmacy_name') }}"
                   class="w-full border rounded px-3 py-2"
                   placeholder="Search or type pharmacy name">

            <datalist id="pharmacyOptions">
                <option value="CVS Pharmacy">
                <option value="Walgreens">
                <option value="Rite Aid">
                <option value="Walmart Pharmacy">
                <option value="Costco Pharmacy">
                <option value="Safeway Pharmacy">
                <option value="Fred Meyer Pharmacy">
                <option value="Kroger Pharmacy">
                <option value="Albertsons Pharmacy">
                <option value="Target CVS Pharmacy">
            </datalist>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Pharmacy Phone</label>
            <input type="text"
                   name="pharmacy_phone"
                   value="{{ old('pharmacy_phone') }}"
                   class="w-full border rounded px-3 py-2"
                   placeholder="Pharmacy phone">
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
