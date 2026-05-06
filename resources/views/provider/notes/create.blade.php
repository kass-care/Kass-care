@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-indigo-800 rounded-3xl shadow-2xl p-8 mb-8 border border-indigo-900">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-widest text-indigo-200 font-bold mb-2">KASS CARE</p>
                    <h1 class="text-3xl font-extrabold text-white">Visit Clinical Note</h1>
                    <p class="text-indigo-100 mt-2">Create provider documentation for this visit.</p>
                </div>

                <a href="{{ route('provider.notes.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-indigo-800 shadow">
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
                        {{ !empty($visit->visit_date) ? \Carbon\Carbon::parse($visit->visit_date)->format('m/d/Y') : 'N/A' }}
                    </p>
                    <p><strong>MRN:</strong> {{ $visit->client->mrn ?? 'N/A' }}</p>
                </div>
            </div>

            <form action="{{ route('provider.notes.store') }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="visit_id" value="{{ $visit->id }}">

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
                            <label class="flex items-center gap-3 rounded-xl bg-white border border-purple-100 px-4 py-3 text-sm font-medium text-slate-700">
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
                                  placeholder="Write any additional screening, vaccine, risk factor, or provider note here..."></textarea>
                    </div>
<div class="mt-4">
    <label class="block text-sm font-semibold text-indigo-700">
        Auto-generated Screening & Immunization Review
    </label>

    <textarea 
        id="screening_preview"
        class="w-full rounded-xl border border-indigo-300 bg-indigo-50 p-3 text-sm mt-2"
        rows="4"
        readonly
        placeholder="Selected screenings will appear here automatically..."
    ></textarea>
</div>

<input type="hidden" name="screening_notes" id="screening_notes">
                </div>

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
                      <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
    <label class="block text-sm font-bold text-slate-700 mb-2">
        Previous Notes Timeline
    </label>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <button type="button"
                onclick="loadPreviousTimeline({{ $visit->id }})"
                class="rounded-xl bg-amber-500 px-4 py-3 text-sm font-bold text-white">
            Load Timeline
        </button>

        <select id="previousNoteSelect"
                onchange="previewSelectedPreviousNote()"
                class="md:col-span-2 w-full rounded-xl border border-amber-300 bg-white px-4 py-3 text-sm">
            <option value="">-- Load timeline first --</option>
        </select>
    </div>

    <div class="mt-4 rounded-xl bg-white border border-amber-200 p-4">
        <div class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
            <p class="text-sm font-black text-slate-800">Preview</p>

            <select id="previousInsertMode" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                <option value="replace">Replace SOAP fields</option>
                <option value="append">Append to SOAP fields</option>
            </select>
        </div>

        <div id="previousNotePreview" class="mt-3 text-sm text-slate-700 whitespace-pre-line">
            Select a previous note to preview it here.
        </div>
    </div>

    <button type="button"
            onclick="insertSelectedPreviousNote()"
            class="mt-4 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white">
        Insert Selected Note
    </button>
</div>
<div class="mb-6">
    <label class="text-sm font-bold text-slate-700">Smart Phrases</label>

    <div class="flex gap-2 mt-2">
        <select id="smartPhraseSelect" class="border rounded-lg px-3 py-2 w-full">
            <option value="">-- Select Phrase --</option>

            @foreach(\App\Models\SmartPhrase::where('user_id', auth()->id())->get() as $phrase)
                <option value="{{ $phrase->content }}">
                    {{ $phrase->shortcut }}
                </option>
            @endforeach
        </select>

        <button type="button"
            onclick="insertSmartPhrase()"
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
            Insert
        </button>
    </div>
</div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Subjective</label>
                    <textarea name="subjective" rows="5"
                              class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('subjective', $subjective ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Objective</label>
                    <textarea name="objective" rows="7"
                              class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('objective', $objective ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment</label>
                    <textarea name="assessment" rows="5"
                              class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('assessment', $assessment ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Plan</label>
                    <textarea name="plan" rows="5"
                              class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('plan', $plan ?? '') }}</textarea>
                </div>

                <button type="submit"
                        class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold shadow hover:bg-indigo-700">
                    Save Note
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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

    function calculateBMI() {
        const weight = parseFloat(getValue('weight'));
        const height = parseFloat(getValue('height'));
        const bmiInput = document.getElementById('bmi');
        const status = document.getElementById('bmiStatus');

        if (!bmiInput || !status) return;

        if (weight > 0 && height > 0) {
            const bmi = ((weight / (height * height)) * 703).toFixed(1);
            const label = getBmiStatus(parseFloat(bmi));
            bmiInput.value = bmi;
            status.innerHTML = 'BMI: ' + bmi + ' (' + label + ')';
        } else {
            bmiInput.value = '';
            status.innerHTML = 'BMI status will appear here';
        }
    }

    function buildObjective() {
        const objective = document.querySelector('[name="objective"]');
        if (!objective) return;

        const bp = getValue('blood_pressure');
        const pulse = getValue('pulse');
        const oxygen = getValue('oxygen');
        const temp = getValue('temperature');
        const weight = getValue('weight');
        const height = getValue('height');
        const bmi = getValue('bmi');
        const careLogs = getValue('care_logs');

        let text = "Clinical Measurements:\n";

        if (bp) text += "- BP: " + bp + "\n";
        if (pulse) text += "- Pulse: " + pulse + "\n";
        if (oxygen) text += "- Oxygen: " + oxygen + "%\n";
        if (temp) text += "- Temperature: " + temp + "\n";
        if (weight) text += "- Weight: " + weight + " lb\n";
        if (height) text += "- Height: " + height + " inches\n";
        if (bmi) text += "- BMI: " + bmi + " (" + getBmiStatus(parseFloat(bmi)) + ")\n";

        if (careLogs) {
            text += "\nCare Logs / Visit Observations:\n" + careLogs + "\n";
        }

        objective.value = text;
    }

    function getSelectedScreenings() {
        return Array.from(document.querySelectorAll('.screening-item:checked'))
            .map(item => item.value);
    }

    function buildScreeningNotes() {
        const screenings = getSelectedScreenings();
        const other = getValue('screening_other');
        const preview = document.getElementById('screening_preview');
        const hidden = document.getElementById('screening_notes');

        let text = '';

        if (screenings.length || other) {
            text += "Adult Screening & Immunization Review:\n";

            screenings.forEach(function(item) {
                text += "- " + item + "\n";
            });

            if (other) {
                text += "- Other: " + other + "\n";
            }
        }

        if (preview) preview.value = text;
        if (hidden) hidden.value = text;
    }

    ['blood_pressure', 'pulse', 'oxygen', 'temperature', 'weight', 'height', 'care_logs'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', function () {
                calculateBMI();
                buildObjective();
            });
        }
    });

    document.querySelectorAll('.screening-item').forEach(function(item) {
        item.addEventListener('change', buildScreeningNotes);
    });

    const screeningOther = document.getElementById('screening_other');
    if (screeningOther) {
        screeningOther.addEventListener('input', buildScreeningNotes);
    }

    calculateBMI();
    buildObjective();
    buildScreeningNotes();
});
let previousNotesCache = [];

function loadPreviousTimeline(visitId) {
    fetch(`/provider/notes/previous/${visitId}`)
        .then(function(res) {
            if (!res.ok) throw new Error('No previous notes');
            return res.json();
        })
        .then(function(notes) {
            previousNotesCache = notes;

            const select = document.getElementById('previousNoteSelect');
            const preview = document.getElementById('previousNotePreview');

            if (!select) return;

            select.innerHTML = '<option value="">-- Select previous note --</option>';

            notes.forEach(function(note, index) {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = note.label || ('Visit #' + note.visit_id);
                select.appendChild(option);
            });

            if (preview) preview.textContent = 'Timeline loaded. Select a previous note to preview it here.';
        })
        .catch(function() {
            previousNotesCache = [];

            const select = document.getElementById('previousNoteSelect');
            const preview = document.getElementById('previousNotePreview');

            if (select) select.innerHTML = '<option value="">No previous notes found</option>';
            if (preview) preview.textContent = 'No previous notes found for this patient.';

            alert('No previous notes found for this patient.');
        });
}

function previewSelectedPreviousNote() {
    const select = document.getElementById('previousNoteSelect');
    const preview = document.getElementById('previousNotePreview');

    if (!select || !preview || select.value === '') {
        if (preview) preview.textContent = 'Select a previous note to preview it here.';
        return;
    }

    const note = previousNotesCache[parseInt(select.value, 10)];

    if (!note) {
        preview.textContent = 'Previous note could not be loaded.';
        return;
    }

    preview.textContent =
        'Subjective:\n' + (note.subjective || 'N/A') +
        '\n\nObjective:\n' + (note.objective || 'N/A') +
        '\n\nAssessment:\n' + (note.assessment || 'N/A') +
        '\n\nPlan:\n' + (note.plan || 'N/A');
}

function mergeSoapField(selector, value, mode, label) {
    const field = document.querySelector(selector);
    if (!field) return;

    const incoming = value || '';
    if (!incoming) return;

    if (mode === 'append' && field.value.trim() !== '') {
        field.value = field.value.trim() + "\n\n--- Forwarded " + label + " ---\n" + incoming;
    } else {
        field.value = incoming;
    }
}

function insertSelectedPreviousNote() {
    const select = document.getElementById('previousNoteSelect');
    const modeSelect = document.getElementById('previousInsertMode');

    if (!select || select.value === '') {
        alert('Please select a previous note first.');
        return;
    }

    const note = previousNotesCache[parseInt(select.value, 10)];

    if (!note) {
        alert('Previous note could not be loaded.');
        return;
    }

    const mode = modeSelect ? modeSelect.value : 'replace';
    const label = note.label || ('Visit #' + note.visit_id);

    mergeSoapField('textarea[name="subjective"]', note.subjective, mode, label);
    mergeSoapField('textarea[name="objective"]', note.objective, mode, label);
    mergeSoapField('textarea[name="assessment"]', note.assessment, mode, label);
    mergeSoapField('textarea[name="plan"]', note.plan, mode, label);

    previewSelectedPreviousNote();
}
function insertSmartPhrase() {
    const select = document.getElementById('smartPhraseSelect');
const smartPhraseMap = @json(
    \App\Models\SmartPhrase::where('user_id', auth()->id())
        ->pluck('content', 'shortcut')
);

function enableSmartPhraseAutoExpand() {
    const fields = document.querySelectorAll(
        'textarea[name="subjective"], textarea[name="assessment"], textarea[name="plan"]'
    );

    fields.forEach(function(field) {
        field.addEventListener('keyup', function(e) {
            if (e.key !== ' ' && e.key !== 'Enter') return;

            let text = field.value;

            Object.keys(smartPhraseMap).forEach(function(shortcut) {
                const phrase = smartPhraseMap[shortcut];

                const pattern = new RegExp('(^|\\s)' + shortcut.replace('.', '\\.') + '(\\s|$)', 'g');

                text = text.replace(pattern, function(match, before, after) {
                    return before + phrase + after;
                });
            });

            field.value = text;
        });
    });
}

enableSmartPhraseAutoExpand();   
 const field = document.querySelector('textarea[name="subjective"]');

    if (!select || !field || !select.value) return;

    field.value += "\n" + select.value;
}
</script>
@endpush

@endsection
