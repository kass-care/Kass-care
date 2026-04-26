@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-indigo-800 rounded-3xl shadow-2xl p-8 mb-8 border border-indigo-900">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-widest text-indigo-200 font-bold mb-2">KASS CARE</p>
                    <h1 class="text-3xl font-extrabold text-white">Visit Clinical Note</h1>
                    <p class="text-indigo-100 mt-2">Add provider documentation for this visit.</p>
                </div>

                <a href="{{ route('provider.notes.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow hover:bg-indigo-50">
                    Back to Notes
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

            <div class="mb-8 rounded-2xl bg-slate-50 border border-slate-200 p-6">
                <h2 class="text-xl font-black text-slate-900 mb-4">Patient Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <p><strong>Visit ID:</strong> {{ $visit->id }}</p>
                    <p><strong>Client:</strong> {{ $clientName ?? 'N/A' }}</p>
                    <p><strong>Date of Birth:</strong> {{ $clientDob ? \Carbon\Carbon::parse($clientDob)->format('m/d/Y') : 'N/A' }}</p>
                    <p><strong>Caregiver:</strong> {{ $visit->caregiver->name ?? 'N/A' }}</p>
                    <p><strong>Date:</strong> {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('m/d/Y') : 'N/A' }}</p>
                </div>
            </div>

            <form action="{{ route('provider.notes.store') }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="visit_id" value="{{ $visit->id }}">

                <div class="bg-indigo-50 rounded-2xl border border-indigo-200 p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Clinical Measurements</h3>
                    <p class="text-sm text-slate-600 mb-6">Vitals and measurements charted during this provider visit.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Blood Pressure</label>
                            <input type="text" id="blood_pressure" class="w-full rounded-xl border border-gray-300 px-4 py-3" placeholder="e.g. 120/80">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pulse</label>
                            <input type="text" id="pulse" class="w-full rounded-xl border border-gray-300 px-4 py-3" placeholder="e.g. 78">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Oxygen</label>
                            <input type="text" id="oxygen" class="w-full rounded-xl border border-gray-300 px-4 py-3" placeholder="e.g. 98%">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Temperature</label>
                            <input type="text" id="temperature" class="w-full rounded-xl border border-gray-300 px-4 py-3" placeholder="e.g. 98.6">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                            <input type="number" step="0.01" id="weight" class="w-full rounded-xl border border-gray-300 px-4 py-3" placeholder="e.g. 70">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Height (cm)</label>
                            <input type="number" step="0.01" id="height" class="w-full rounded-xl border border-gray-300 px-4 py-3" placeholder="e.g. 175">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">BMI</label>
                            <input type="text" id="bmi" readonly class="w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" placeholder="Auto-calculated">
                        </div>
                    </div>

                    <p id="bmiStatus" class="mt-4 text-xs font-semibold bg-white inline-flex rounded-full px-3 py-1">
                        BMI status will appear here
                    </p>
                </div>

                <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Care Logs / Visit Observations</h3>
                    <p class="text-sm text-slate-600 mb-6">Provider can document care observations separately from objective findings.</p>

                    <textarea id="care_logs"
                              rows="5"
                              class="w-full rounded-xl border border-gray-300 px-4 py-3"
                              placeholder="Care observations, ADLs, appetite, mood, pain, caregiver concerns..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Subjective</label>
                    <textarea name="subjective" rows="5" class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('subjective', $subjective ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Objective</label>
                    <textarea name="objective" rows="6" class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('objective', $objective ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment</label>
                    <textarea name="assessment" rows="5" class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('assessment', $assessment ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Plan</label>
                    <textarea name="plan" rows="5" class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('plan', $plan ?? '') }}</textarea>
                </div>

                <button type="submit"
                        class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold shadow hover:bg-indigo-700">
                    Save Note
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function buildObjective() {
    const bp = document.getElementById('blood_pressure').value;
    const pulse = document.getElementById('pulse').value;
    const oxygen = document.getElementById('oxygen').value;
    const temp = document.getElementById('temperature').value;
    const weight = document.getElementById('weight').value;
    const height = document.getElementById('height').value;
    const bmi = document.getElementById('bmi').value;
    const careLogs = document.getElementById('care_logs').value;
    const objective = document.querySelector('[name=objective]');

    let text = '';

    text += "Clinical Measurements:\n";
    if (bp) text += "- BP: " + bp + "\n";
    if (pulse) text += "- Pulse: " + pulse + "\n";
    if (oxygen) text += "- Oxygen: " + oxygen + "\n";
    if (temp) text += "- Temperature: " + temp + "\n";
    if (weight) text += "- Weight: " + weight + " kg\n";
    if (height) text += "- Height: " + height + " cm\n";
    if (bmi) text += "- BMI: " + bmi + "\n";

    if (careLogs) {
        text += "\nCare Logs / Visit Observations:\n" + careLogs + "\n";
    }

    if (!objective.value.includes('Clinical Measurements:')) {
    objective.value = text + "\n\n" + objective.value;

}

function calculateBMI() {
    const weight = document.getElementById('weight').value;
    const height = document.getElementById('height').value;
    const bmiInput = document.getElementById('bmi');
    const status = document.getElementById('bmiStatus');

    if (!weight || !height) {
        buildObjective();
        return;
    }

    const h = height / 100;
    const bmi = (weight / (h * h)).toFixed(2);

    bmiInput.value = bmi;

    let label = '';
    if (bmi < 18.5) label = 'Underweight';
    else if (bmi < 25) label = 'Normal';
    else if (bmi < 30) label = 'Overweight';
    else label = 'Obese';

    status.innerHTML = 'BMI: ' + bmi + ' (' + label + ')';

    buildObjective();
}

['blood_pressure', 'pulse', 'oxygen', 'temperature', 'weight', 'height', 'care_logs'].forEach(function(id) {
    document.getElementById(id).addEventListener('input', calculateBMI);
});
</script>
@endsection
