@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">💊 Create Prescription</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('provider.pharmacy.store') }}" class="bg-white p-6 rounded shadow">
        @csrf

        {{-- Favorite Prescriptions --}}
        <div class="mb-6">
            <label class="block mb-2 font-semibold">Favorite Prescriptions</label>

            <div class="flex flex-wrap gap-2">
                <button
                    type="button"
                    class="favorite-rx px-3 py-2 rounded-lg bg-indigo-100 text-indigo-700 text-sm"
                    data-medication="Metformin 500mg Tablet"
                    data-dosage="500mg"
                    data-frequency="Twice daily"
                    data-quantity="60"
                    data-refills="2"
                    data-instructions="Take by mouth twice daily with meals"
                >
                    Metformin 500mg BID
                </button>

                <button
                    type="button"
                    class="favorite-rx px-3 py-2 rounded-lg bg-indigo-100 text-indigo-700 text-sm"
                    data-medication="Amoxicillin 500mg Capsule"
                    data-dosage="500mg"
                    data-frequency="Three times daily"
                    data-quantity="21"
                    data-refills="0"
                    data-instructions="Take by mouth three times daily for 7 days"
                >
                    Amoxicillin 500mg TID
                </button>

                <button
                    type="button"
                    class="favorite-rx px-3 py-2 rounded-lg bg-indigo-100 text-indigo-700 text-sm"
                    data-medication="Ibuprofen 600mg Tablet"
                    data-dosage="600mg"
                    data-frequency="Every 6 hours as needed"
                    data-quantity="30"
                    data-refills="1"
                    data-instructions="Take by mouth every 6 hours as needed for pain"
                >
                    Ibuprofen 600mg PRN
                </button>
            </div>
        </div>

        <label class="block mb-1 font-medium">Client</label>
        <select name="client_id" class="w-full mb-4 border p-2 rounded" required>
            <option value="">Select Client</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>

        <label class="block mb-1 font-medium">Medication</label>
        <input
            type="text"
            name="medication_name"
            list="medication-options"
            value="{{ old('medication_name') }}"
            placeholder="Type medication name..."
            class="w-full mb-4 border border-gray-300 rounded-lg px-3 py-2"
            required
        >

        <datalist id="medication-options">
            <option value="Amoxicillin 125mg/5ml Suspension">
            <option value="Amoxicillin 250mg Capsule">
            <option value="Amoxicillin 500mg Capsule">
            <option value="Amoxicillin-Clavulanate 500mg/125mg Tablet">
            <option value="Amoxicillin-Clavulanate 875mg/125mg Tablet">
            <option value="Azithromycin 250mg Tablet">
            <option value="Azithromycin 500mg Tablet">
            <option value="Cephalexin 250mg Capsule">
            <option value="Cephalexin 500mg Capsule">
            <option value="Ciprofloxacin 500mg Tablet">
            <option value="Doxycycline 100mg Capsule">
            <option value="Ibuprofen 200mg Tablet">
            <option value="Ibuprofen 400mg Tablet">
            <option value="Ibuprofen 600mg Tablet">
            <option value="Ibuprofen 800mg Tablet">
            <option value="Lisinopril 5mg Tablet">
            <option value="Lisinopril 10mg Tablet">
            <option value="Lisinopril 20mg Tablet">
            <option value="Losartan 25mg Tablet">
            <option value="Losartan 50mg Tablet">
            <option value="Losartan 100mg Tablet">
            <option value="Metformin 500mg Tablet">
            <option value="Metformin 850mg Tablet">
            <option value="Metformin 1000mg Tablet">
            <option value="Omeprazole 20mg Capsule">
            <option value="Omeprazole 40mg Capsule">
            <option value="Prednisone 10mg Tablet">
            <option value="Prednisone 20mg Tablet">
            <option value="Albuterol HFA Inhaler">
            <option value="Amlodipine 5mg Tablet">
            <option value="Amlodipine 10mg Tablet">
        </datalist>

        <label class="block mb-1 font-medium">Dosage</label>
        <input
            type="text"
            name="dosage"
            value="{{ old('dosage') }}"
            class="w-full mb-4 border p-2 rounded"
            placeholder="e.g. 500mg"
        >

        <label class="block mb-1 font-medium">Frequency</label>
        <input
            type="text"
            name="frequency"
            value="{{ old('frequency') }}"
            class="w-full mb-4 border p-2 rounded"
            placeholder="e.g. Twice daily"
        >

        <label class="block mb-1 font-medium">Quantity</label>
        <input
            type="number"
            name="quantity"
            value="{{ old('quantity') }}"
            class="w-full mb-4 border p-2 rounded"
        >

        <label class="block mb-1 font-medium">Refills</label>
        <input
            type="number"
            name="refills"
            value="{{ old('refills') }}"
            class="w-full mb-4 border p-2 rounded"
        >

        <label class="block mb-1 font-medium">Pharmacy Name</label>
        <select name="pharmacy_name" class="w-full mb-4 border rounded-lg px-3 py-2">
            <option value="">Select Pharmacy</option>

            <option
                value="CVS Pharmacy"
                data-phone="8007467287"
                data-email="cvs@pharmacy.com"
                data-fax="8007467288"
                {{ old('pharmacy_name') == 'CVS Pharmacy' ? 'selected' : '' }}
            >
                CVS Pharmacy
            </option>

            <option
                value="Walgreens"
                data-phone="8009254733"
                data-email="walgreens@pharmacy.com"
                data-fax="8009254734"
                {{ old('pharmacy_name') == 'Walgreens' ? 'selected' : '' }}
            >
                Walgreens
            </option>

            <option
                value="Rite Aid"
                data-phone="8007483243"
                data-email="riteaid@pharmacy.com"
                data-fax="8007483244"
                {{ old('pharmacy_name') == 'Rite Aid' ? 'selected' : '' }}
            >
                Rite Aid
            </option>
        </select>

        <label class="block mb-1 font-medium">Pharmacy Phone</label>
        <input
            type="text"
            name="pharmacy_phone"
            value="{{ old('pharmacy_phone') }}"
            class="w-full mb-4 border p-2 rounded"
        >

        <label class="block mb-1 font-medium">Pharmacy Fax</label>
        <input
            type="text"
            name="pharmacy_fax"
            value="{{ old('pharmacy_fax') }}"
            class="w-full mb-4 border p-2 rounded"
        >

        <label class="block mb-1 font-medium">Pharmacy Email</label>
        <input
            type="email"
            name="pharmacy_email"
            value="{{ old('pharmacy_email') }}"
            class="w-full mb-4 border p-2 rounded"
            placeholder="pharmacy@example.com"
        >

        <label class="block mb-1 font-medium">Instructions</label>
        <textarea
            name="instructions"
            class="w-full mb-4 border p-2 rounded"
            placeholder="Take after meals..."
        >{{ old('instructions') }}</textarea>

        <div class="flex justify-between">
            <a href="{{ route('provider.pharmacy.index') }}" class="bg-gray-300 px-4 py-2 rounded">
                Cancel
            </a>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                Save Prescription
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pharmacySelect = document.querySelector('[name="pharmacy_name"]');
    const medicationInput = document.querySelector('[name="medication_name"]');
    const dosageInput = document.querySelector('[name="dosage"]');
    const frequencyInput = document.querySelector('[name="frequency"]');
    const quantityInput = document.querySelector('[name="quantity"]');
    const refillsInput = document.querySelector('[name="refills"]');
    const instructionsInput = document.querySelector('[name="instructions"]');

    if (pharmacySelect) {
        pharmacySelect.addEventListener('change', function () {
            const option = this.options[this.selectedIndex];

            document.querySelector('[name="pharmacy_phone"]').value = option.dataset.phone || '';
            document.querySelector('[name="pharmacy_email"]').value = option.dataset.email || '';
            document.querySelector('[name="pharmacy_fax"]').value = option.dataset.fax || '';
        });

        pharmacySelect.dispatchEvent(new Event('change'));
    }

    if (medicationInput && dosageInput) {
        medicationInput.addEventListener('input', function () {
            const medication = this.value || '';
            const match = medication.match(/(\d+\s?mg(?:\/\d+\s?ml)?)/i);

            if (match) {
                dosageInput.value = match[1];
            }
        });
    }

    document.querySelectorAll('.favorite-rx').forEach(function (button) {
        button.addEventListener('click', function () {
            if (medicationInput) medicationInput.value = this.dataset.medication || '';
            if (dosageInput) dosageInput.value = this.dataset.dosage || '';
            if (frequencyInput) frequencyInput.value = this.dataset.frequency || '';
            if (quantityInput) quantityInput.value = this.dataset.quantity || '';
            if (refillsInput) refillsInput.value = this.dataset.refills || '';
            if (instructionsInput) instructionsInput.value = this.dataset.instructions || '';
        });
    });
});
</script>
@endsection
