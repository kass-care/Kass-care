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
                   class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">
                    Back to Notes
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

            <div class="mb-8 rounded-2xl bg-slate-50 border border-slate-200 p-6">
                <h2 class="text-xl font-black text-slate-900 mb-4">Patient Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <p><strong>Visit ID:</strong> {{ $visit->id }}</p>
                    <p><strong>Client:</strong> {{ $clientName ?? ($visit->client->name ?? 'N/A') }}</p>
                    <p>
                        <strong>Date of Birth:</strong>
                        {{ !empty($clientDob) ? \Carbon\Carbon::parse($clientDob)->format('m/d/Y') : 'N/A' }}
                    </p>
                    <p><strong>Caregiver:</strong> {{ $visit->caregiver->name ?? 'N/A' }}</p>
                    <p>
                        <strong>Date:</strong>
                        {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('m/d/Y') : 'N/A' }}
                    </p>
                    <p><strong>MRN:</strong> {{ $visit->client->mrn ?? 'N/A' }}</p>
                </div>
            </div>

            <form action="{{ route('provider.notes.store') }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="visit_id" value="{{ $visit->id }}">

                {{-- Clinical Measurements --}}
                <div class="bg-indigo-50 rounded-2xl border border-indigo-200 p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Clinical Measurements</h3>
                    <p class="text-sm text-slate-600 mb-6">
                        Vitals and measurements charted during this provider visit.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Blood Pressure</label>
                            <input type="text" id="blood_pressure" name="blood_pressure"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-3"
                                   placeholder="120/80">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pulse</label>
                            <input type="text" id="pulse" name="pulse"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-3"
                                   placeholder="78">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Oxygen</label>
                            <input type="text" id="oxygen" name="oxygen"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-3"
                                   placeholder="98">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Temperature</label>
                            <input type="text" id="temperature" name="temperature"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-3"
                                   placeholder="98.6">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (lb)</label>
                            <input type="number" step="0.1" id="weight" name="weight"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-3"
                                   placeholder="180">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Height (inches)</label>
                            <input type="number" step="0.1" id="height" name="height"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-3"
                                   placeholder="70">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">BMI</label>
                            <input type="text" id="bmi" name="bmi" readonly
                                   class="w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3"
                                   placeholder="Auto-calculated">
                        </div>
                    </div>

                    <p id="bmiStatus" class="mt-4 text-xs font-semibold bg-white inline-flex rounded-full px-3 py-1">
                        BMI status will appear here
                    </p>
                </div>

                {{-- Adult Screening --}}
                <div class="bg-purple-50 rounded-2xl border border-purple-200 p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">
                        Adult Screening & Immunization Review
                    </h3>
                    <p class="text-sm text-slate-600 mb-5">
                        Select items reviewed or needed. Add anything not listed below.
                    </p>

                    @php
                        $screeningOptions = [
                            'Cholesterol screening',
                            'Diabetes screening',
                            'Cervical cancer screening',
                            'Breast cancer screening',
                            'Colorectal cancer screening',
                            'Lung cancer screening',
                            'Osteoporosis screening',
                            'AAA screening',
                            'HIV screening',
                            'Hepatitis B/C screening',
                            'STI screening',
                            'Skin check',
                            'Prostate cancer discussion',
                            'Genetic/family history screening',
                            'Depression screening',
                            'Mini-Cog / cognition screening',
                            'Fall screening',
                            'COVID-19 vaccine',
                            'Influenza vaccine',
                            'Tdap / Td booster',
                            'Shingles vaccine',
                            'Pneumococcal vaccine',
                            'Hepatitis A/B vaccine',
                            'HPV vaccine',
                            'MMR / Varicella immunity',
                            'Meningococcal vaccine',
                            'RSV vaccine review',
                        ];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($screeningOptions as $option)
                            <label class="flex items-center gap-3 rounded-xl bg-white border border-purple-100 px-4 py-3 text-sm font-semibold text-slate-700">
                                <input type="checkbox"
                                       name="screening_items[]"
                                       value="{{ $option }}"
                                       class="screening-item rounded border-slate-300 text-purple-600">
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Other screening / immunization notes
                        </label>
                        <textarea name="screening_other"
                                  id="screening_other"
                                  rows="4"
                                  class="w-full rounded-xl border border-purple-200 px-4 py-3"
                                  placeholder="Write any additional screening, vaccine, risk factor, or provider note here...">{{ old('screening_other') }}</textarea>
                    </div>
                </div>

                {{-- Care Logs --}}
                <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Care Logs / Visit Observations</h3>
                    <p class="text-sm text-slate-600 mb-6">
                        Provider can document care observations separately from objective findings.
                    </p>

                    <textarea id="care_logs"
                              rows="5"
                              class="w-full rounded-xl border border-gray-300 px-4 py-3"
                              placeholder="Care observations, ADLs, appetite, mood, pain, caregiver concerns..."></textarea>
                </div>
                        <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Chief Complaint
    </label>
    <textarea name="chief_complaint" rows="3"
              class="w-full rounded-xl border border-gray-300 px-4 py-3"
              placeholder="Why is the patient being seen today?">{{ old('chief_complaint') }}</textarea>
</div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Subjective</label>
                    <textarea name="subjective" rows="5"
                              class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('subjective') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Objective</label>
                    <textarea name="objective" rows="6"
                              class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('objective') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment</label>
                    <textarea name="assessment" rows="5"
                              class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('assessment') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Plan</label>
                    <textarea name="plan" rows="5"
                              class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('plan') }}</textarea>
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
function getValue(id) {
    const el = document.getElementById(id);
    return el ? el.value : '';
}

function getBmiStatus(bmi) {
    if (bmi < 18.5) return 'Underweight';
    if (bmi < 25) return 'Normal';
    if (bmi < 30) return 'Overweight';
    return 'Obese';
}

function getSelectedScreenings() {
    const items = document.querySelectorAll('.screening-item:checked');
    return Array.from(items).map(item => item.value);
}

function calculateBMI() {
    const weight = parseFloat(getValue('weight'));
    const height = parseFloat(getValue('height'));
    const bmiInput = document.getElementById('bmi');
    const status = document.getElementById('bmiStatus');

    if (weight > 0 && height > 0) {
        const bmi = ((weight / (height * height)) * 703).toFixed(1);
        const label = getBmiStatus(parseFloat(bmi));

        bmiInput.value = bmi;
        status.innerHTML = 'BMI: ' + bmi + ' (' + label + ')';
    } else {
        bmiInput.value = '';
        status.innerHTML = 'BMI status will appear here';
    }

    buildObjective();
}

function buildObjective() {
    const objective = document.querySelector('[name="objective"]');

    const bp = getValue('blood_pressure');
    const pulse = getValue('pulse');
    const oxygen = getValue('oxygen');
    const temp = getValue('temperature');
    const weight = getValue('weight');
    const height = getValue('height');
    const bmi = getValue('bmi');
    const careLogs = getValue('care_logs');
    const screeningOther = getValue('screening_other');
    const screenings = getSelectedScreenings();

    let text = "Clinical Measurements:\n";

    if (bp) text += "- BP: " + bp + "\n";
    if (pulse) text += "- Pulse: " + pulse + "\n";
    if (oxygen) text += "- Oxygen: " + oxygen + "%\n";
    if (temp) text += "- Temperature: " + temp + "\n";
    if (weight) text += "- Weight: " + weight + " lb\n";
    if (height) text += "- Height: " + height + " inches\n";
    if (bmi) text += "- BMI: " + bmi + " (" + getBmiStatus(parseFloat(bmi)) + ")\n";

    if (screenings.length > 0 || screeningOther) {
        text += "\nAdult Screening & Immunization Review:\n";

        screenings.forEach(function(item) {
            text += "- " + item + "\n";
        });

        if (screeningOther) {
            text += "- Other: " + screeningOther + "\n";
        }
    }

    if (careLogs) {
        text += "\nCare Logs / Visit Observations:\n" + careLogs + "\n";
    }

    objective.value = text;
}

['blood_pressure', 'pulse', 'oxygen', 'temperature', 'weight', 'height', 'care_logs', 'screening_other'].forEach(function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('input', calculateBMI);
    }
});

document.querySelectorAll('.screening-item').forEach(function(item) {
    item.addEventListener('change', buildObjective);
});
</script>
@endsection
