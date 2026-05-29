@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="mb-8 rounded-3xl border border-cyan-200 bg-white p-8 shadow-sm">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-cyan-700">
            KASS CARE MAR
        </p>

        <h1 class="mt-3 text-4xl font-black text-slate-900">
            Medication Administration Record
        </h1>

        <p class="mt-2 text-slate-600">
            Facility-wide MAR overview for active medications and pass times.
        </p>

        <div class="mt-5">
            <a href="{{ route('facility.patients.index') }}"
               class="inline-flex rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-slate-950 hover:bg-amber-400">
                Add Medication / Supplement — Choose Client First
            </a>
        </div>
    </div>
            @if($flaggedAdministrations->count())
    <div class="mb-8 rounded-3xl border border-red-200 bg-red-50 p-6 shadow-sm">
        <h2 class="text-2xl font-black text-red-800">⚠ Medication Issues Needing Review</h2>

        <div class="mt-5 space-y-4">
            @foreach($flaggedAdministrations as $record)
                <div class="rounded-2xl bg-white p-5 border border-red-100">
                    <p class="font-black text-slate-900">
                        {{ $record->client->name ?? 'Unknown Patient' }} —
                        {{ $record->medication->medication_name ?? 'Medication' }}
                    </p>

                    <p class="mt-1 text-sm text-slate-600">
                        Current Status: <strong>{{ strtoupper($record->status) }}</strong>
                        · Signed by {{ $record->caregiver->name ?? 'Unknown' }}
                        · {{ $record->administered_at?->format('M d, Y g:i A') }}
                    </p>

                    @if($record->is_corrected)
                        <p class="mt-2 rounded-xl bg-emerald-50 p-3 text-sm font-bold text-emerald-700">
                            Corrected from {{ strtoupper($record->previous_status) }}
                            by {{ $record->correctedBy->name ?? 'Unknown' }}
                            at {{ $record->corrected_at?->format('M d, Y g:i A') }}.
                            Reason: {{ $record->correction_reason }}
                        </p>
                    @endif

                    <form method="POST"
                          action="{{ route('facility.mar.administrations.correct', $record->id) }}"
                          class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                        @csrf
                        @method('PATCH')

                        <select name="status" required class="rounded-xl border border-slate-300 px-4 py-3 text-sm">
                            <option value="given">Given</option>
                            <option value="refused">Refused</option>
                            <option value="held">Held</option>
                            <option value="missed">Missed</option>
                            <option value="side_effects">Side Effects</option>
                        </select>

                        <input type="text"
                               name="correction_reason"
                               required
                               class="rounded-xl border border-slate-300 px-4 py-3 text-sm"
                               placeholder="Reason for correction">

                        <input type="text"
                               name="notes"
                               class="rounded-xl border border-slate-300 px-4 py-3 text-sm"
                               placeholder="Updated note">

                        <button class="md:col-span-3 rounded-xl bg-red-700 px-5 py-3 text-sm font-black text-white">
                            Save Correction With Audit Trail
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endif 
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-700">
                <tr>
                    <th class="p-4 text-left">Patient</th>
                    <th class="p-4 text-left">Medication</th>
                    <th class="p-4 text-left">Dose</th>
                    <th class="p-4 text-left">Frequency</th>
                    <th class="p-4 text-left">eMAR Times</th>
                    <th class="p-4 text-left">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($medications as $medication)
                    <tr class="border-t border-slate-100 hover:bg-slate-50">
                        <td class="p-4 font-bold text-slate-900">
                            {{ $medication->client->name ?? 'Unknown' }}
                        </td>

                        <td class="p-4 text-slate-700">
                            {{ $medication->medication_name ?? 'N/A' }}
                        </td>

                        <td class="p-4 text-slate-700">
                            {{ $medication->dose ?? '-' }}
                        </td>

                        <td class="p-4 text-slate-700">
                            {{ $medication->frequency ?? '-' }}
                        </td>

                        <td class="p-4 text-slate-700">
                            {{ is_array($medication->emar_times ?? null)
                                ? implode(', ', $medication->emar_times)
                                : ($medication->emar_times ?? '-') }}
                        </td>

                        <td class="p-4">
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                {{ strtoupper($medication->status ?? 'active') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-500">
                            No approved medications found for this facility.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
