@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8 rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-900 to-cyan-800 p-8 text-white shadow-xl">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-200">
                        KassCare Admissions & E-Signature Center
                    </p>

                    <h1 class="mt-2 text-3xl font-black">
                        📝 Admission Packets
                    </h1>

                    <p class="mt-2 max-w-3xl text-cyan-100">
                        Create, manage, and track resident admission agreements and signature-ready documents.
                    </p>
                </div>

                <a href="{{ route('facility.admission-packets.create') }}"
                   class="rounded-2xl bg-white px-5 py-3 font-black text-indigo-800 shadow hover:bg-indigo-50">
                    + New Admission Packet
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @php
            $total = $packets->total();
            $draftCount = $packets->where('status', 'draft')->count();
            $sentCount = $packets->where('status', 'sent')->count();
            $signedCount = $packets->where('status', 'signed')->count();
        @endphp

        <div class="mb-6 grid gap-4 md:grid-cols-4">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Total Packets</p>
                <h2 class="mt-3 text-4xl font-black text-slate-900">{{ $total }}</h2>
            </div>

            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-amber-700">Draft</p>
                <h2 class="mt-3 text-4xl font-black text-amber-800">{{ $draftCount }}</h2>
            </div>

            <div class="rounded-3xl border border-blue-200 bg-blue-50 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-blue-700">Sent</p>
                <h2 class="mt-3 text-4xl font-black text-blue-800">{{ $sentCount }}</h2>
            </div>

            <div class="rounded-3xl border border-green-200 bg-green-50 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-green-700">Signed</p>
                <h2 class="mt-3 text-4xl font-black text-green-800">{{ $signedCount }}</h2>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-xl font-black text-slate-900">Recent Admission Packets</h2>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($packets as $packet)
                    @php
                        $clientName = $packet->client->name
                            ?? trim(($packet->client->first_name ?? '') . ' ' . ($packet->client->last_name ?? ''))
                            ?: ($packet->resident_name ?? 'Resident');

                        $statusClass = match($packet->status) {
                            'signed' => 'bg-green-100 text-green-800 border-green-200',
                            'sent' => 'bg-blue-100 text-blue-800 border-blue-200',
                            default => 'bg-amber-100 text-amber-800 border-amber-200',
                        };
                    @endphp

                    <a href="{{ route('facility.admission-packets.show', $packet) }}"
                       class="block p-5 transition hover:bg-indigo-50">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-lg font-black text-slate-900">
                                        {{ $packet->packet_name ?? 'Resident Admission Agreement' }}
                                    </h3>

                                    <span class="rounded-full border px-3 py-1 text-xs font-black uppercase {{ $statusClass }}">
                                        {{ $packet->status }}
                                    </span>
                                </div>

                                <p class="mt-2 text-sm text-slate-600">
                                    <strong>Resident:</strong> {{ $clientName }}
                                    · <strong>Monthly Rate:</strong> {{ $packet->monthly_rate ?? 'N/A' }}
                                </p>

                                <p class="mt-1 text-xs font-semibold text-slate-400">
                                    Created {{ optional($packet->created_at)->format('M j, Y g:i A') }}
                                </p>

                                <p class="mt-3 text-xs font-black uppercase tracking-widest text-indigo-700">
                                    View Agreement →
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-10 text-center">
                        <p class="text-lg font-bold text-slate-700">No admission packets yet.</p>
                        <a href="{{ route('facility.admission-packets.create') }}"
                           class="mt-4 inline-flex rounded-xl bg-indigo-700 px-5 py-3 font-bold text-white">
                            Create First Admission Packet
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-6">
            {{ $packets->links() }}
        </div>
    </div>
</div>
@endsection
