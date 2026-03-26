@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-4xl mx-auto px-6">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">New Clinical Note</h1>
            <p class="text-slate-500 mt-2">Create a provider note linked to a visit.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('provider-notes.store') }}"
              class="bg-white p-8 rounded-2xl shadow space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Visit</label>
                <select id="visit_id" name="visit_id" class="w-full border rounded-lg p-3">
                    <option value="">-- Select Visit --</option>
                    @foreach($visits as $visit)
                        <option
                            value="{{ $visit->id }}"
                            data-client-id="{{ $visit->client->id ?? '' }}"
                            data-client-name="{{ $visit->client->name ?? 'No Client' }}"
                            {{ (old('visit_id', $selectedVisit ?? '') == $visit->id) ? 'selected' : '' }}
                        >
                            #{{ $visit->id }} - {{ $visit->client->name ?? 'No Client' }} - {{ $visit->visit_date ?? $visit->scheduled_at ?? 'No Date' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Client</label>
                <input
                    type="text"
                    id="client_display"
                    class="w-full border rounded-lg p-3 bg-slate-100"
                    readonly
                    value=""
                >
                <input type="hidden" id="client_id" name="client_id" value="{{ old('client_id') }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Subjective</label>
                <textarea
                    name="subjective"
                    rows="4"
                    class="w-full border rounded-lg p-3"
                    placeholder="Patient complaints, symptoms, concerns..."
                >{{ old('subjective') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Objective</label>
                <textarea
                    name="objective"
                    rows="4"
                    class="w-full border rounded-lg p-3"
                    placeholder="Vitals, observations, clinical findings..."
                >{{ old('objective') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Assessment</label>
                <textarea
                    name="assessment"
                    rows="4"
                    class="w-full border rounded-lg p-3"
                    placeholder="Clinical impression or diagnosis..."
                >{{ old('assessment') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Plan</label>
                <textarea
                    name="plan"
                    rows="4"
                    class="w-full border rounded-lg p-3"
                    placeholder="Treatment plan, medications, orders..."
                >{{ old('plan') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Follow Up</label>
                <textarea
                    name="follow_up"
                    rows="3"
                    class="w-full border rounded-lg p-3"
                    placeholder="Follow-up instructions or next steps..."
                >{{ old('follow_up') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" id="sign_note" name="sign_note" value="1" {{ old('sign_note') ? 'checked' : '' }}>
                <label for="sign_note" class="text-slate-700">Sign this note</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700">
                    Save Clinical Note
                </button>

                <a href="{{ route('provider-notes.index') }}"
                   class="bg-slate-200 text-slate-800 px-6 py-3 rounded-lg hover:bg-slate-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const visitSelect = document.getElementById('visit_id');
    const clientDisplay = document.getElementById('client_display');
    const clientIdInput = document.getElementById('client_id');

    function syncClientFromVisit() {
        const selectedOption = visitSelect.options[visitSelect.selectedIndex];

        if (selectedOption && selectedOption.value) {
            clientDisplay.value = selectedOption.dataset.clientName || '';
            clientIdInput.value = selectedOption.dataset.clientId || '';
        } else {
            clientDisplay.value = '';
            clientIdInput.value = '';
        }
    }

    visitSelect.addEventListener('change', syncClientFromVisit);
    syncClientFromVisit();
});
</script>
@endsection
