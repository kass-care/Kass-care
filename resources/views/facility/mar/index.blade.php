@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="rounded-3xl bg-cyan-700 p-8 text-white shadow-xl">
        <h1 class="text-4xl font-black">Medication Administration Record</h1>
        <p class="mt-2 text-cyan-100">Read-only facility MAR oversight for approved medications.</p>
    </div>

    <div class="mt-8 bg-white rounded-3xl shadow overflow-hidden">
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
                    <tr class="border-t">
                        <td class="p-4 font-bold">{{ $medication->client->name ?? 'Unknown' }}</td>
                        <td class="p-4">{{ $medication->medication_name }}</td>
                        <td class="p-4">{{ $medication->dose ?? '-' }}</td>
                        <td class="p-4">{{ $medication->frequency ?? '-' }}</td>
                        <td class="p-4">
                            {{ is_array($medication->emar_times) ? implode(', ', $medication->emar_times) : ($medication->emar_times ?? '-') }}
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
