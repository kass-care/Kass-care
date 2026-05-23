<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Visit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white min-h-screen">
    <div class="max-w-5xl mx-auto px-6 py-10">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold">Schedule Visit</h1>
                <p class="mt-2 text-sm text-slate-400">
                    Facility: {{ $facility->name ?? 'Selected Facility' }}
                </p>
            </div>

            <a href="{{ route('facility.visits.index') }}"
               class="rounded-xl bg-white text-slate-900 px-6 py-3 font-semibold hover:bg-slate-200 transition">
                Back to Visits
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-500/40 bg-red-500/10 px-5 py-4">
                <h2 class="font-semibold text-red-300 mb-2">Please fix the following:</h2>
                <ul class="list-disc pl-5 text-sm text-red-200 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 shadow-2xl p-8">
            <form method="POST" action="{{ route('facility.visits.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="client_id" class="block text-sm font-semibold text-slate-300 mb-2">
                        Client
                    </label>
                    <select
                        name="client_id"
                        id="client_id"
                        required
                        class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Select client</option>
                        @foreach ($clients as $client)
                            @php
                                $clientName = $client->name
                                    ?? trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''));

                                if (!$clientName) {
                                    $clientName = 'Unnamed Client';
                                }
                            @endphp
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $clientName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                          <div>
    <label for="provider_id" class="block text-sm font-semibold text-slate-300 mb-2">
        Provider
    </label>
    <select
        name="provider_id"
        id="provider_id"
        class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-indigo-500 focus:outline-none"
    >
        <option value="">Select provider</option>
        @foreach ($providers as $provider)
            <option value="{{ $provider->id }}" {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
                {{ $provider->name ?? 'Unnamed Provider' }}
            </option>
        @endforeach
    </select>
</div>

             <div>
    <label class="block text-sm font-semibold text-slate-300 mb-2">
        Assign Caregivers
    </label>

    <select
        name="caregiver_ids[]"
        multiple
        class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-indigo-500 focus:outline-none min-h-[180px]"
    >
        @foreach ($caregivers as $caregiver)
            <option value="{{ $caregiver->id }}">
                {{ $caregiver->name ?? 'Unnamed Caregiver' }}
            </option>
        @endforeach
    </select>

    <p class="mt-2 text-xs text-slate-400">
        Hold CTRL (Windows) or CMD (Mac) to select multiple caregivers.
    </p>
</div>

<div class="mt-6">
    <label class="block text-sm font-semibold text-slate-300 mb-3">
        Duty Templates
    </label>

    <div class="flex flex-wrap gap-2">
        @foreach($dutyTemplates as $template)
            <button
                type="button"
                onclick="insertDutyTemplate(`{{ addslashes($template->duties) }}`)"
                class="rounded-xl bg-indigo-600/20 border border-indigo-500/40 px-4 py-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-600/30"
            >
                {{ $template->title }}
            </button>
        @endforeach
    </div>
</div>

<div class="mt-6">
    <label for="duties" class="block text-sm font-semibold text-slate-300 mb-2">
        Shift Duties / Instructions
    </label>

    <textarea
        name="duties"
        id="duties"
        rows="6"
        class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-indigo-500 focus:outline-none"
        placeholder="Enter caregiver duties and shift instructions..."
    >{{ old('duties') }}</textarea>
</div>

<script>
function insertDutyTemplate(text) {
    const textarea = document.getElementById('duties');

    if (textarea.value.trim() !== '') {
        textarea.value += "\n\n";
    }

    textarea.value += text;
}
</script>
                </div>

                <div>
                    <label for="visit_date" class="block text-sm font-semibold text-slate-300 mb-2">
                        Visit Date
                    </label>
                    <input
                        type="date"
                        name="visit_date"
                        id="visit_date"
                        value="{{ old('visit_date', now()->format('Y-m-d')) }}"
                        required
                        class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-300 mb-2">
                        Status
                    </label>
                    <select
                        name="status"
                        id="status"
                        class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="scheduled" {{ old('status', 'scheduled') === 'scheduled' ? 'selected' : '' }}>
                            Scheduled
                        </option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>
                            In Progress
                        </option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="rounded-xl bg-indigo-600 px-8 py-3 font-semibold text-white hover:bg-indigo-700 transition"
                    >
                        Create Visit
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

