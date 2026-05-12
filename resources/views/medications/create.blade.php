@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-10">
    <div class="mx-auto max-w-5xl px-4">

        <div class="mb-8 rounded-3xl bg-slate-950 p-7 text-white shadow-2xl border border-indigo-500">
            <p class="text-xs uppercase tracking-[0.35em] text-cyan-300 font-black">KASSCARE Medication Order</p>
            <h1 class="mt-3 text-4xl font-black">Add Medication for {{ $client->name }}</h1>
            <p class="mt-3 text-slate-200 font-semibold">
                Create a medication order and configure the eMAR pass schedule.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-800 font-semibold">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('medications.store', $client->id) }}" class="rounded-3xl bg-white p-8 shadow-xl border border-slate-200">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div class="md:col-span-2">
                    <label class="block text-sm font-black text-slate-700 mb-2">Medication Name</label>
                    <input id="medicationSearch" type="text" name="medication_name" value="{{ old('medication_name') }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 font-semibold focus:ring-2 focus:ring-indigo-500"
                           placeholder="Start typing medication name..." autocomplete="off" required>
                    <div id="medicationSuggestions" class="hidden border border-slate-200 rounded-2xl mt-2 bg-white shadow max-h-48 overflow-y-auto"></div>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Linked Diagnosis</label>
                    <select name="diagnosis_id" class="w-full rounded-2xl border border-slate-300 px-4 py-3 font-semibold">
                        <option value="">Select Diagnosis (optional)</option>
                        @foreach($diagnoses as $diagnosis)
                            <option value="{{ $diagnosis->id }}" {{ old('diagnosis_id') == $diagnosis->id ? 'selected' : '' }}>
                                {{ $diagnosis->diagnosis_name }}{{ $diagnosis->icd_code ? ' (' . $diagnosis->icd_code . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Dose</label>
                    <input type="text" name="dose" value="{{ old('dose') }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 font-semibold"
                           placeholder="Example: 500 mg, 1 tablet, 10 mL">
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Route</label>
                    <select name="route" class="w-full rounded-2xl border border-slate-300 px-4 py-3 font-semibold">
                        <option value="">Select Route</option>
                        @foreach(['PO / Oral','Topical','Subcutaneous','Inhalation','Eye Drops','Ear Drops','Nasal','Rectal','IM Injection','Other'] as $route)
                            <option value="{{ $route }}" {{ old('route') == $route ? 'selected' : '' }}>{{ $route }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Frequency</label>
                    <select name="frequency" class="w-full rounded-2xl border border-slate-300 px-4 py-3 font-semibold">
                        <option value="">Select Frequency</option>
                        @foreach(['Once daily','Twice daily','Three times daily','Every morning','Every evening','At bedtime','As needed','Every 4 hours','Every 6 hours','Every 8 hours','Weekly'] as $frequency)
                            <option value="{{ $frequency }}" {{ old('frequency') == $frequency ? 'selected' : '' }}>{{ $frequency }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 rounded-3xl border border-emerald-200 bg-emerald-50 p-5">
                    <label class="block text-sm font-black text-emerald-900 mb-3">eMAR Pass Times</label>

                    <div class="grid grid-cols-2 gap-3 md:grid-cols-5">
                        @foreach(['Morning','Noon','Evening','Bedtime','PRN'] as $time)
                            <label class="flex items-center gap-2 rounded-2xl bg-white px-4 py-3 font-black text-slate-700 border border-emerald-100 shadow-sm">
                                <input type="checkbox" name="emar_times[]" value="{{ $time }}"
                                    class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                    {{ in_array($time, old('emar_times', [])) ? 'checked' : '' }}>
                                {{ $time }}
                            </label>
                        @endforeach
                    </div>

                    <p class="mt-3 text-sm font-semibold text-emerald-800">
                        These are the times caregivers will see in the eMAR signing screen.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 font-semibold">
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 font-semibold">
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Medication Status</label>
                    <select name="status" class="w-full rounded-2xl border border-slate-300 px-4 py-3 font-semibold">
                        <option value="active">Active</option>
                        <option value="paused">Paused</option>
                        <option value="discontinued">Discontinued</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div class="flex items-center rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3">
                    <label class="flex items-center gap-3 font-black text-amber-900">
                        <input type="checkbox" name="is_prn" value="1"
                               class="rounded border-amber-300 text-amber-600 focus:ring-amber-500"
                               {{ old('is_prn') ? 'checked' : '' }}>
                        PRN / As Needed Medication
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-black text-slate-700 mb-2">Instructions / SIG</label>
                    <textarea name="instructions" rows="4"
                              class="w-full rounded-2xl border border-slate-300 px-4 py-3 font-semibold"
                              placeholder="Example: Take with food after breakfast">{{ old('instructions') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-3 md:flex-row md:items-center">
            <div class="mt-10 flex flex-col gap-4 border-t border-slate-200 pt-6 md:flex-row md:items-center md:justify-between">

    <a href="{{ route('provider.patients.workspace', $client->id) }}"
       class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-4 text-sm font-black text-slate-700 shadow-sm transition hover:bg-slate-100">
        Cancel
    </a>

    <button
        type="submit"
        class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 px-8 py-4 text-base font-black text-white shadow-2xl transition duration-200 hover:scale-[1.02] hover:from-indigo-700 hover:via-blue-700 hover:to-cyan-600"
    >
        💊 Submit Medication for Provider Review
    </button>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const medications = [
    "Amoxicillin","Azithromycin","Lisinopril","Metformin","Atorvastatin",
    "Omeprazole","Losartan","Amlodipine","Hydrochlorothiazide","Gabapentin",
    "Levothyroxine","Sertraline","Furosemide","Prednisone","Albuterol",
    "Insulin","Aspirin","Ibuprofen","Acetaminophen"
];

const searchInput = document.getElementById("medicationSearch");
const suggestionBox = document.getElementById("medicationSuggestions");

searchInput.addEventListener("input", function () {
    let value = this.value.toLowerCase();
    suggestionBox.innerHTML = "";

    if (value.length < 2) {
        suggestionBox.classList.add("hidden");
        return;
    }

    medications
        .filter(med => med.toLowerCase().includes(value))
        .forEach(med => {
            let option = document.createElement("div");
            option.className = "px-4 py-2 hover:bg-indigo-50 cursor-pointer text-sm font-semibold";
            option.innerText = med;
            option.onclick = function () {
                searchInput.value = med;
                suggestionBox.classList.add("hidden");
            };
            suggestionBox.appendChild(option);
        });

    suggestionBox.classList.remove("hidden");
});
</script>
@endsection
