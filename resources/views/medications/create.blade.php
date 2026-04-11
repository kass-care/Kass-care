@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <div class="bg-white shadow rounded-2xl p-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-widest text-indigo-600 font-semibold">
                    KASS CARE
                </p>
                <h1 class="text-3xl font-bold text-gray-900 mt-2">
                    Add Medication for {{ $client->name }}
                </h1>
                <p class="text-gray-500 mt-2">
                    Record a medication order for this client.
                </p>

            </div>
           <a href="{{ route('provider.patients.workspace', $client->id) }}"
   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
    Back to Patient Workspace
</a>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                <ul class="list-disc pl-5 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('medications.store', $client->id) }}">
            @csrf

            <div class="grid grid-cols-1 gap-6">

                <div>
                     <label class="block text-sm font-semibold text-gray-700 mb-2">
Medication Name
</label>

<input type="text"
    id="medicationSearch"
    name="medication_name"
    value="{{ old('medication_name') }}"
    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
    placeholder="Start typing medication name..."
    autocomplete="off"
    required>

<div id="medicationSuggestions"
     class="hidden border border-gray-200 rounded-lg mt-2 bg-white shadow-sm max-h-48 overflow-y-auto">
</div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Linked Diagnosis
                    </label>
                    <select name="diagnosis_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Diagnosis (optional)</option>

                        @foreach($diagnoses as $diagnosis)
                            <option value="{{ $diagnosis->id }}"
                                {{ old('diagnosis_id') == $diagnosis->id ? 'selected' : '' }}>
                                {{ $diagnosis->diagnosis_name }}{{ $diagnosis->icd_code ? ' (' . $diagnosis->icd_code . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Dose
                    </label>
                    <select name="dose"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Dose</option>
                        <option value="2.5 mg" {{ old('dose') == '2.5 mg' ? 'selected' : '' }}>2.5 mg</option>
                        <option value="5 mg" {{ old('dose') == '5 mg' ? 'selected' : '' }}>5 mg</option>
                        <option value="10 mg" {{ old('dose') == '10 mg' ? 'selected' : '' }}>10 mg</option>
                        <option value="20 mg" {{ old('dose') == '20 mg' ? 'selected' : '' }}>20 mg</option>
                        <option value="25 mg" {{ old('dose') == '25 mg' ? 'selected' : '' }}>25 mg</option>
                        <option value="50 mg" {{ old('dose') == '50 mg' ? 'selected' : '' }}>50 mg</option>
                        <option value="100 mg" {{ old('dose') == '100 mg' ? 'selected' : '' }}>100 mg</option>
                        <option value="250 mg" {{ old('dose') == '250 mg' ? 'selected' : '' }}>250 mg</option>
                        <option value="500 mg" {{ old('dose') == '500 mg' ? 'selected' : '' }}>500 mg</option>
                        <option value="1 tablet" {{ old('dose') == '1 tablet' ? 'selected' : '' }}>1 tablet</option>
                        <option value="2 tablets" {{ old('dose') == '2 tablets' ? 'selected' : '' }}>2 tablets</option>
                        <option value="5 mL" {{ old('dose') == '5 mL' ? 'selected' : '' }}>5 mL</option>
                        <option value="10 mL" {{ old('dose') == '10 mL' ? 'selected' : '' }}>10 mL</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Frequency
                    </label>
                    <select name="frequency"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Frequency</option>
                        <option value="Once daily" {{ old('frequency') == 'Once daily' ? 'selected' : '' }}>Once daily</option>
                        <option value="Twice daily" {{ old('frequency') == 'Twice daily' ? 'selected' : '' }}>Twice daily</option>
                        <option value="Three times daily" {{ old('frequency') == 'Three times daily' ? 'selected' : '' }}>Three times daily</option>
                        <option value="Every morning" {{ old('frequency') == 'Every morning' ? 'selected' : '' }}>Every morning</option>
                        <option value="Every evening" {{ old('frequency') == 'Every evening' ? 'selected' : '' }}>Every evening</option>
                        <option value="At bedtime" {{ old('frequency') == 'At bedtime' ? 'selected' : '' }}>At bedtime</option>
                        <option value="As needed" {{ old('frequency') == 'As needed' ? 'selected' : '' }}>As needed</option>
                        <option value="Every 4 hours" {{ old('frequency') == 'Every 4 hours' ? 'selected' : '' }}>Every 4 hours</option>
                        <option value="Every 6 hours" {{ old('frequency') == 'Every 6 hours' ? 'selected' : '' }}>Every 6 hours</option>
                        <option value="Every 8 hours" {{ old('frequency') == 'Every 8 hours' ? 'selected' : '' }}>Every 8 hours</option>
                        <option value="Weekly" {{ old('frequency') == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                    </select>
                </div>
                <div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
Medication Status
</label>

<select name="status"
class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">

<option value="active">Active</option>
<option value="paused">Paused</option>
<option value="discontinued">Discontinued</option>
<option value="completed">Completed</option>

</select>
</div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Instructions
                    </label>
                    <textarea name="instructions"
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Example: Take with food after breakfast">{{ old('instructions') }}</textarea>
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                        Save Medication
                    </button>

                    <a href="{{ route('provider.patients.workspace', $client->id) }}"
                       class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>

const medications = [
"Amoxicillin",
"Azithromycin",
"Lisinopril",
"Metformin",
"Atorvastatin",
"Omeprazole",
"Losartan",
"Amlodipine",
"Hydrochlorothiazide",
"Gabapentin",
"Levothyroxine",
"Sertraline",
"Furosemide",
"Prednisone",
"Albuterol",
"Insulin",
"Aspirin",
"Ibuprofen",
"Acetaminophen"
];

const searchInput = document.getElementById("medicationSearch");
const suggestionBox = document.getElementById("medicationSuggestions");

searchInput.addEventListener("input", function(){

let value = this.value.toLowerCase();
suggestionBox.innerHTML = "";

if(value.length < 2){
suggestionBox.classList.add("hidden");
return;
}

let results = medications.filter(med =>
med.toLowerCase().includes(value)
);

results.forEach(med => {

let option = document.createElement("div");

option.className =
"px-4 py-2 hover:bg-indigo-50 cursor-pointer text-sm";

option.innerText = med;

option.onclick = function(){
searchInput.value = med;
suggestionBox.classList.add("hidden");
};

suggestionBox.appendChild(option);

});

suggestionBox.classList.remove("hidden");

});

</script>
@endsection
