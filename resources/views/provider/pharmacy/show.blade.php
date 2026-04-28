@extends('layouts.app')

@section('content')
@php
    $clientName = optional($order->client)->name ?? 'N/A';
    $providerName = optional($order->provider)->name ?? 'N/A';

    $statusClasses = [
        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'sent' => 'bg-blue-100 text-blue-800 border-blue-200',
        'filled' => 'bg-green-100 text-green-800 border-green-200',
        'delivered' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
    ];

    $statusClass = $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
@endphp

<div class="min-h-screen bg-slate-100 py-8">
    <div class="mx-auto max-w-6xl px-4">

        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('provider.pharmacy.index') }}"
               class="rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow hover:bg-slate-100">
                ← Back to Pharmacy Dashboard
            </a>

            <span class="rounded-full border px-4 py-2 text-xs font-black uppercase {{ $statusClass }}">
                {{ $order->status ?? 'pending' }}
            </span>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-2xl border border-green-200 bg-green-50 p-4 font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 p-4 font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-3xl p-8 text-white shadow-2xl"
             style="background:#4f46e5;">
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-100">
                KASS Care Prescription Record
            </p>

            <h1 class="mt-2 text-4xl font-black tracking-tight text-white">
                RX-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
            </h1>

            <p class="mt-2 text-sm font-semibold text-indigo-100">
                Created {{ optional($order->prescribed_at)->format('M j, Y g:i A') ?? $order->created_at->format('M j, Y g:i A') }}
            </p>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('provider.pharmacy.pdf', $order->id) }}"
                   class="rounded-xl bg-white px-5 py-3 text-sm font-black text-indigo-700 shadow hover:bg-slate-100">
                    📄 Download PDF
                </a>

                <form method="POST"
                      action="{{ route('provider.pharmacy.email', $order->id) }}"
                      onsubmit="return confirm('Send this prescription to {{ $order->pharmacy_email }}?');">
                    @csrf

                    <button type="submit"
                            class="rounded-xl bg-slate-950 px-5 py-3 text-sm font-black text-white shadow hover:bg-slate-800">
                        ✉️ Send / Resend Email
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2">

            <div class="rounded-3xl bg-white p-6 shadow">
                <h2 class="text-xl font-black text-slate-900">Patient</h2>

                <div class="mt-4 space-y-2 text-sm text-slate-700">
                    <p><strong>Name:</strong> {{ ucwords(strtolower($clientName)) }}</p>
                    <p><strong>Client ID:</strong> {{ $order->client_id }}</p>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow">
                <h2 class="text-xl font-black text-slate-900">Prescribing Provider</h2>

                <div class="mt-4 space-y-2 text-sm text-slate-700">
                    <p><strong>Provider:</strong> {{ $providerName }}</p>
                    <p><strong>Provider ID:</strong> {{ $order->provider_id }}</p>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow md:col-span-2">
                <h2 class="text-xl font-black text-slate-900">Medication Details</h2>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Medication</p>
                        <p class="mt-1 text-2xl font-black text-slate-900">{{ $order->medication_name ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Dosage</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ $order->dosage ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Frequency</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ $order->frequency ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Route</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ $order->route ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Quantity</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ $order->quantity ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Refills</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ $order->refills ?? 0 }}</p>
                    </div>
                </div>

                <div class="mt-5 rounded-2xl bg-indigo-50 p-5">
                    <p class="text-xs font-black uppercase tracking-wide text-indigo-700">Instructions / SIG</p>
                    <p class="mt-2 text-slate-800">
                        {{ $order->instructions ?? 'No instructions provided.' }}
                    </p>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow md:col-span-2">
                <h2 class="text-xl font-black text-slate-900">Pharmacy Destination</h2>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Pharmacy Name</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $order->pharmacy_name ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Pharmacy Email</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $order->pharmacy_email ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Phone</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $order->pharmacy_phone ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Fax</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $order->pharmacy_fax ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow md:col-span-2">
                <h2 class="text-xl font-black text-slate-900">Status Management</h2>

                <form method="POST" action="{{ route('provider.pharmacy.status', $order->id) }}" class="mt-4 flex flex-wrap items-center gap-3">
                    @csrf
                    @method('PATCH')

                    <select name="status" class="rounded-xl border border-slate-300 px-4 py-3">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="sent" {{ $order->status === 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="filled" {{ $order->status === 'filled' ? 'selected' : '' }}>Filled</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <button type="submit"
                            class="rounded-xl bg-slate-900 px-5 py-3 font-bold text-white hover:bg-slate-800">
                        Update Status
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
