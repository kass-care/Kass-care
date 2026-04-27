@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs uppercase tracking-widest text-indigo-600 font-semibold">
                KASS CARE
            </p>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">
                Add Diagnosis
            </h1>
            <p class="text-sm text-gray-600 mt-2">
                Record ICD-ready diagnosis for {{ $client->name }}.
            </p>
        </div>

        <a href="{{ route('provider.patients.workspace', $client->id) }}"
           class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
            Back to Patient
        </a>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <form action="{{ route('diagnoses.store', $client) }}" method="POST" class="space-y-6">
            @csrf

            <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                <h2 class="text-lg font-bold text-slate-900">Clinical Diagnosis Entry</h2>
                <p class="text-sm text-slate-600 mt-1">
                    Select a common ICD-10 diagnosis or type the diagnosis and code manually.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Common Diagnosis Search
                </label>

                <select id="diagnosisPreset"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select common diagnosis</option>
                    <option value="I10|Essential hypertension">I10 — Essential hypertension</option>
                    <option value="E11.9|Type 2 diabetes mellitus without complications">E11.9 — Type 2 diabetes mellitus without complications</option>
                    <option value="E78.5|Hyperlipidemia, unspecified">E78.5 — Hyperlipidemia, unspecified</option>
                    <option value="J44.9|Chronic obstructive pulmonary disease, unspecified">J44.9 — COPD, unspecified</option>
                    <option value="J45.909|Unspecified asthma, uncomplicated">J45.909 — Asthma, uncomplicated</option>
                    <option value="I50.9|Heart failure, unspecified">I50.9 — Heart failure, unspecified</option>
                    <option value="N18.9|Chronic kidney disease, unspecified">N18.9 — Chronic kidney disease, unspecified</option>
                    <option value="F32.A|Depression, unspecified">F32.A — Depression, unspecified</option>
                    <option value="F41.9|Anxiety disorder, unspecified">F41.9 — Anxiety disorder, unspecified</option>
                    <option value="G30.9|Alzheimer disease, unspecified">G30.9 — Alzheimer disease, unspecified</option>
                    <option value="R26.81|Unsteadiness on feet">R26.81 — Unsteadiness on feet</option>
                    <option value="R29.6|Repeated falls">R29.6 — Repeated falls</option>
                    <option value="M19.90|Unspecified osteoarthritis, unspecified site">M19.90 — Osteoarthritis, unspecified</option>
                    <option value="G89.29|Other chronic pain">G89.29 — Other chronic pain</option>
                    <option value="K21.9|Gastro-esophageal reflux disease without esophagitis">K21.9 — GERD</option>
                    <option value="E03.9|Hypothyroidism, unspecified">E03.9 — Hypothyroidism</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Diagnosis Name
                </label>
                <input type="text"
                       id="diagnosis_name"
                       name="diagnosis_name"
                       value="{{ old('diagnosis_name') }}"
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="e.g. Essential hypertension"
                       required>
                @error('diagnosis_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    ICD-10 Code
                </label>
                <input type="text"
                       id="icd_code"
                       name="icd_code"
                       value="{{ old('icd_code') }}"
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="e.g. I10">
                @error('icd_code')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select name="status"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <option value="">Select status</option>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="chronic" {{ old('status') == 'chronic' ? 'selected' : '' }}>Chronic</option>
                    <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea name="notes"
                          rows="5"
                          class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Clinical context, source, or assistant/provider note...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit"
                        class="px-5 py-3 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    Save Diagnosis
                </button>

                <a href="{{ route('provider.patients.workspace', $client->id) }}"
                   class="px-5 py-3 rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('diagnosisPreset').addEventListener('change', function () {
    if (!this.value) return;

    const parts = this.value.split('|');
    document.getElementById('icd_code').value = parts[0] || '';
    document.getElementById('diagnosis_name').value = parts[1] || '';
});
</script>
@endsection
