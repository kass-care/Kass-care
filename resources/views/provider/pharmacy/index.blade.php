@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4">

        <div class="mb-8 rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-800 p-8 text-white shadow-xl">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-indigo-200">
                        KASS Care Pharmacy Command Center
                    </p>
                    <h1 class="mt-2 text-3xl font-black">
                        💊 Pharmacy Orders Dashboard
                    </h1>
                    <p class="mt-2 max-w-3xl text-indigo-100">
                        Create, send, download, and track prescription communications from provider to pharmacy.
                    </p>
                </div>

                <a href="{{ route('provider.pharmacy.create') }}"
                   class="rounded-2xl bg-white px-5 py-3 font-bold text-indigo-800 shadow hover:bg-indigo-50">
                    + New Prescription
                </a>
            </div>
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

        <div class="mb-6 grid gap-4 md:grid-cols-5">
            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-slate-500">Total</p>
                <p class="mt-2 text-3xl font-black text-slate-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-yellow-600">Pending</p>
                <p class="mt-2 text-3xl font-black text-yellow-700">{{ $stats['pending'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-blue-600">Sent</p>
                <p class="mt-2 text-3xl font-black text-blue-700">{{ $stats['sent'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-green-600">Filled</p>
                <p class="mt-2 text-3xl font-black text-green-700">{{ $stats['filled'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-indigo-600">Delivered</p>
                <p class="mt-2 text-3xl font-black text-indigo-700">{{ $stats['delivered'] ?? 0 }}</p>
            </div>
        </div>

        <div class="mb-6 rounded-3xl bg-white p-5 shadow">
            <form method="GET" action="{{ route('provider.pharmacy.index') }}" class="grid gap-4 md:grid-cols-3">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search patient, medication, pharmacy, email..."
                       class="rounded-xl border border-slate-300 px-4 py-3">

                <select name="status" class="rounded-xl border border-slate-300 px-4 py-3">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="filled" {{ request('status') === 'filled' ? 'selected' : '' }}>Filled</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <button class="rounded-xl bg-slate-900 px-4 py-3 font-bold text-white hover:bg-slate-800">
                    Search / Filter
                </button>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($orders as $order)
                @php
                    $clientName = trim((optional($order->client)->first_name ?? '') . ' ' . (optional($order->client)->last_name ?? ''))
                        ?: (optional($order->client)->name ?? 'N/A');

                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'sent' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'filled' => 'bg-green-100 text-green-800 border-green-200',
                        'delivered' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                    ];

                    $statusClass = $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                @endphp

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow">
                    <div class="flex flex-wrap items-start justify-between gap-5">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="text-xl font-black text-slate-900">
                                    {{ $order->medication_name ?? 'Medication N/A' }}
                                </h2>

                                <span class="rounded-full border px-3 py-1 text-xs font-black uppercase {{ $statusClass }}">
                                    {{ $order->status ?? 'pending' }}
                                </span>
                            </div>

                            <p class="mt-2 text-sm text-slate-600">
                                <strong>Patient:</strong> {{ ucwords(strtolower($clientName)) }}
                            </p>

                            <p class="mt-1 text-sm text-slate-600">
                                <strong>Dose:</strong> {{ $order->dosage ?? 'N/A' }}
                                · <strong>Frequency:</strong> {{ $order->frequency ?? 'N/A' }}
                                · <strong>Qty:</strong> {{ $order->quantity ?? 'N/A' }}
                                · <strong>Refills:</strong> {{ $order->refills ?? 0 }}
                            </p>

                            <p class="mt-1 text-sm text-slate-600">
                                <strong>Pharmacy:</strong> {{ $order->pharmacy_name ?? 'N/A' }}
                                · <strong>Email:</strong> {{ $order->pharmacy_email ?? 'N/A' }}
                            </p>

                            <p class="mt-1 text-xs font-semibold text-slate-400">
                                RX-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                · {{ optional($order->prescribed_at)->format('M j, Y g:i A') ?? $order->created_at->format('M j, Y g:i A') }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if($order->pharmacy_phone)
                                <a href="tel:{{ $order->pharmacy_phone }}"
                                   class="rounded-xl bg-green-600 px-3 py-2 text-sm font-bold text-white hover:bg-green-700">
                                    📞 Call
                                </a>
                            @endif
                                  <a href="{{ route('pharmacy.show', $order->id) }}"
   class="rounded-xl bg-slate-800 px-3 py-2 text-sm font-bold text-white hover:bg-slate-900">
    👁 View
</a>
                            <a href="{{ route('provider.pharmacy.pdf', $order->id) }}"
                               class="rounded-xl bg-indigo-600 px-3 py-2 text-sm font-bold text-white hover:bg-indigo-700">
                                📄 PDF
                            </a>

                            <form method="POST"
                                  action="{{ route('provider.pharmacy.email', $order->id) }}"
                                  onsubmit="return confirm('Send this prescription to {{ $order->pharmacy_email ?? 'the saved pharmacy email' }}?');">
                                @csrf
                                <button type="submit"
                                        class="rounded-xl bg-purple-600 px-3 py-2 text-sm font-bold text-white hover:bg-purple-700">
                                    ✉️ Send
                                </button>
                            </form>

                            <form method="POST" action="{{ route('provider.pharmacy.status', $order->id) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')

                                <select name="status" class="rounded-xl border border-slate-300 px-2 py-2 text-sm">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="sent" {{ $order->status === 'sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="filled" {{ $order->status === 'filled' ? 'selected' : '' }}>Filled</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>

                                <button type="submit"
                                        class="rounded-xl bg-slate-900 px-3 py-2 text-sm font-bold text-white hover:bg-slate-800">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl bg-white p-10 text-center shadow">
                    <p class="text-lg font-bold text-slate-700">No pharmacy orders yet.</p>
                    <a href="{{ route('provider.pharmacy.create') }}"
                       class="mt-4 inline-block rounded-xl bg-indigo-600 px-5 py-3 font-bold text-white">
                        Create First Prescription
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
