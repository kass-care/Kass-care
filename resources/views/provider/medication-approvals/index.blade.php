@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white p-6">

    <div class="max-w-7xl mx-auto">

        <div class="mb-8">
            <p class="text-xs uppercase tracking-[0.35em] text-indigo-300">
                Kass Care
            </p>

            <h1 class="text-4xl font-black mt-2">
                Medication Approval Center
            </h1>

            <p class="text-slate-400 mt-3">
                Review, approve, and govern medication orders safely across your facility ecosystem.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @forelse($medications as $medication)

            <div class="mb-6 rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-2xl">

                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

                    <div class="space-y-3">

                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-indigo-300">
                                Patient
                            </p>

                            <h2 class="text-3xl font-bold">
                                {{ $medication->client->full_name ?? $medication->client->name ?? 'Unknown Patient' }}
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                            <div class="rounded-2xl bg-slate-950 border border-slate-800 p-4">
                                <p class="text-xs text-slate-400 uppercase tracking-[0.3em]">
                                    Medication
                                </p>

                                <p class="text-xl font-bold mt-2">
                                    {{ $medication->medication_name }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-950 border border-slate-800 p-4">
                                <p class="text-xs text-slate-400 uppercase tracking-[0.3em]">
                                    Dose
                                </p>

                                <p class="text-xl font-bold mt-2">
                                    {{ $medication->dose }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-950 border border-slate-800 p-4">
                                <p class="text-xs text-slate-400 uppercase tracking-[0.3em]">
                                    Frequency
                                </p>

                                <p class="text-xl font-bold mt-2">
                                    {{ $medication->frequency }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-950 border border-slate-800 p-4">
                                <p class="text-xs text-slate-400 uppercase tracking-[0.3em]">
                                    Instructions
                                </p>

                                <p class="text-sm text-slate-300 mt-2">
                                    {{ $medication->instructions ?? 'No instructions provided.' }}
                                </p>
                            </div>

                        </div>
                    </div>

                    <div class="w-full lg:w-96">

                        <form method="POST"
                              action="{{ route('provider.medication-approvals.approve', $medication) }}"
                              class="space-y-4 mb-4">
                            @csrf

                            <textarea
                                name="provider_note"
                                rows="4"
                                placeholder="Provider approval note..."
                                class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-indigo-500 focus:ring-0"
                            ></textarea>

                            <button
                                type="submit"
                                class="w-full rounded-2xl bg-emerald-600 px-5 py-4 text-lg font-bold text-white hover:bg-emerald-700">
                                Approve Medication
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('provider.medication-approvals.reject', $medication) }}">
                            @csrf

                            <input type="hidden" name="provider_note" value="Medication rejected by provider review.">

                            <button
                                type="submit"
                                class="w-full rounded-2xl border border-red-500 bg-red-500/10 px-5 py-4 text-lg font-bold text-red-200 hover:bg-red-500/20">
                                Reject Medication
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        @empty

            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-20 text-center">
                <h2 class="text-3xl font-bold text-white">
                    No Pending Medication Approvals
                </h2>

                <p class="mt-4 text-slate-400">
                    All medication orders have been reviewed.
                </p>
            </div>

        @endforelse

    </div>

</div>
@endsection
