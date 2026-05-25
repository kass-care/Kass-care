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
