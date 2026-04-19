@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-indigo-800 rounded-3xl shadow-2xl p-8 mb-8 border border-indigo-900">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-widest text-indigo-200 font-bold mb-2">
                        KASS CARE
                    </p>
                    <h1 class="text-3xl font-extrabold text-white">
                        Write Clinical Note
                    </h1>
                    <p class="text-indigo-100 mt-2">
                        Add provider documentation for this visit.
                    </p>
                </div>

                <a href="{{ route('provider.notes.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100 transition">
                    ← Back to Notes
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm">
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
            <div class="mb-6 space-y-2">
                <p><strong>Visit ID:</strong> {{ $visit->id }}</p>
                <p><strong>Client:</strong> {{ $visit->client->full_name ?? 'N/A' }}</p>
                <p><strong>Caregiver:</strong> {{ $visit->caregiver->name ?? 'N/A' }}</p>
                <p><strong>Date:</strong> {{ $visit->visit_date ?? 'N/A' }}</p>
            </div>

            @php
                $weightValue = old('weight', '');
                $heightValue = old('height', '');
                $bmiValue = old('bmi', '');

                $subjectiveValue = old('subjective', '');
                $objectiveValue = old('objective', '');
                $assessmentValue = old('assessment', '');
                $planValue = old('plan', '');
            @endphp

            <form action="{{ route('provider.notes.store') }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                <input type="hidden" name="note" id="finalNote">

                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Clinical Measurements</h3>
                    <p class="text-sm text-slate-500 mb-6">
                        Enter height and weight to auto-calculate BMI for this provider note.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="weight" class="block text-sm font-semibold text-gray-700 mb-2">
                                Weight (kg)
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                id="weight"
                                name="weight"
                                value="{{ $weightValue }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="e.g. 70"
                            >
                        </div>

                        <div>
                            <label for="height" class="block text-sm font-semibold text-gray-700 mb-2">
                                Height (cm)
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                id="height"
                                name="height"
                                value="{{ $heightValue }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="e.g. 175"
                            >
                        </div>

                        <div>
                            <label for="bmi" class="block text-sm font-semibold text-gray-700 mb-2">
                                BMI
                            </label>
                            <input
                                type="text"
                                id="bmi"
                                name="bmi"
                                value="{{ $bmiValue }}"
                                readonly
                                class="w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 text-gray-700"
                                placeholder="Auto-calculated"
                            >
                        </div>
                    </div>

                    <div class="mt-4">
                        <p id="bmiStatus"
                           class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-600">
                            BMI status will appear here
                        </p>
                    </div>
                </div>

                <div>
                    <label for="subjective" class="block text-sm font-semibold text-gray-700 mb-2">
                        Subjective
                    </label>
                    <textarea
                        id="subjective"
                        name="subjective"
                        rows="4"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Patient complaints, symptoms, history..."
                    >{{ $subjectiveValue }}</textarea>
                </div>

                <div>
                    <label for="objective" class="block text-sm font-semibold text-gray-700 mb-2">
                        Objective
                    </label>
                    <textarea
                        id="objective"
                        name="objective"
                        rows="4"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Vitals, physical exam findings..."
                    >{{ $objectiveValue }}</textarea>
                </div>

                <div>
                    <label for="assessment" class="block text-sm font-semibold text-gray-700 mb-2">
                        Assessment
                    </label>
                    <textarea
                        id="assessment"
                        name="assessment"
                        rows="4"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Clinical diagnosis or impression..."
                    >{{ $assessmentValue }}</textarea>
                </div>

                <div>
                    <label for="plan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Plan
                    </label>
                    <textarea
                        id="plan"
                        name="plan"
                        rows="4"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Treatment plan, orders, follow-up..."
                    >{{ $planValue }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-white font-semibold shadow-sm hover:bg-indigo-700 transition">
                        Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateBMI() {
    const weightInput = document.getElementById('weight');
    const heightInput = document.getElementById('height');
    const bmiInput = document.getElementById('bmi');
    const bmiStatus = document.getElementById('bmiStatus');

    if (!weightInput || !heightInput || !bmiInput || !bmiStatus) {
        return;
    }

    const weight = parseFloat(weightInput.value);
    const heightCm = parseFloat(heightInput.value);

    if (!weight || !heightCm || heightCm <= 0) {
        bmiInput.value = '';
        bmiStatus.textContent = 'BMI status will appear here';
        bmiStatus.className = 'inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-600';
        return;
    }

    const heightM = heightCm / 100;
    const bmi = weight / (heightM * heightM);
    const rounded = bmi.toFixed(2);

    bmiInput.value = rounded;

    let status = '';
    let style = '';

    if (bmi < 18.5) {
        status = 'Underweight';
        style = 'bg-blue-100 text-blue-700';
    } else if (bmi < 25) {
        status = 'Normal';
        style = 'bg-green-100 text-green-700';
    } else if (bmi < 30) {
        status = 'Overweight';
        style = 'bg-yellow-100 text-yellow-700';
    } else {
        status = 'Obese';
        style = 'bg-red-100 text-red-700';
    }

    bmiStatus.textContent = 'BMI: ' + rounded + ' (' + status + ')';
    bmiStatus.className = 'inline-flex rounded-full px-3 py-1 text-xs font-semibold ' + style;
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const weightInput = document.getElementById('weight');
    const heightInput = document.getElementById('height');

    if (weightInput) {
        weightInput.addEventListener('input', calculateBMI);
    }

    if (heightInput) {
        heightInput.addEventListener('input', calculateBMI);
    }

    calculateBMI();

    if (form) {
        form.addEventListener('submit', function () {
            const subjective = document.querySelector('[name="subjective"]').value || '';
            const objective = document.querySelector('[name="objective"]').value || '';
            const assessment = document.querySelector('[name="assessment"]').value || '';
            const plan = document.querySelector('[name="plan"]').value || '';

            const weight = document.getElementById('weight')?.value || '';
            const height = document.getElementById('height')?.value || '';
            const bmi = document.getElementById('bmi')?.value || '';

            const finalNote =
                "Clinical Measurements\n" +
                "Weight: " + weight + " kg\n" +
                "Height: " + height + " cm\n" +
                "BMI: " + bmi + "\n\n" +
                "S:\n" + subjective + "\n\n" +
                "O:\n" + objective + "\n\n" +
                "A:\n" + assessment + "\n\n" +
                "P:\n" + plan;

            document.getElementById('finalNote').value = finalNote;
        });
    }
});
</script>
@endsection
