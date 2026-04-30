@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-8">
    <div class="mx-auto max-w-7xl px-4">

        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-indigo-600">
                    KASS Care Pharmacy Intelligence
                </p>
                <h1 class="mt-1 text-3xl font-black text-slate-900">
                    🧠 Pharmacy Intelligence Dashboard
                </h1>
                <p class="mt-2 text-slate-600">
                    Read-only insights from prescription activity, pharmacy usage, and pending actions.
                </p>
            </div>

            <a href="{{ route('provider.pharmacy.index') }}"
               class="rounded-xl bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow hover:bg-slate-50">
                ← Back to Pharmacy Dashboard
            </a>
        </div>

        <div class="mb-6 grid gap-4 md:grid-cols-6">
            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-slate-500">Total</p>
                <p class="mt-2 text-3xl font-black text-slate-900">{{ $stats['total'] }}</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-yellow-600">Pending</p>
                <p class="mt-2 text-3xl font-black text-yellow-700">{{ $stats['pending'] }}</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-blue-600">Sent</p>
                <p class="mt-2 text-3xl font-black text-blue-700">{{ $stats['sent'] }}</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-green-600">Filled</p>
                <p class="mt-2 text-3xl font-black text-green-700">{{ $stats['filled'] }}</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-indigo-600">Delivered</p>
                <p class="mt-2 text-3xl font-black text-indigo-700">{{ $stats['delivered'] }}</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow">
                <p class="text-sm font-bold text-red-600">Cancelled</p>
                <p class="mt-2 text-3xl font-black text-red-700">{{ $stats['cancelled'] }}</p>
            </div>
        </div>

        <div class="mb-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl border border-yellow-200 bg-yellow-50 p-5 shadow">
                <p class="text-sm font-black uppercase text-yellow-700">Needs Attention</p>
                <p class="mt-2 text-2xl font-black text-yellow-900">{{ $stats['pending'] }}</p>
                <p class="text-sm text-yellow-800">Pending prescriptions waiting to be sent or updated.</p>
            </div>

            <div class="rounded-3xl border border-indigo-200 bg-indigo-50 p-5 shadow">
                <p class="text-sm font-black uppercase text-indigo-700">Missing SIG</p>
                <p class="mt-2 text-2xl font-black text-indigo-900">{{ $missingInstructions }}</p>
                <p class="text-sm text-indigo-800">Prescriptions without instructions.</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow">
                <p class="text-sm font-black uppercase text-slate-700">Missing Phone</p>
                <p class="mt-2 text-2xl font-black text-slate-900">{{ $missingPharmacyPhone }}</p>
                <p class="text-sm text-slate-600">Orders without pharmacy phone number.</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">

            <div class="rounded-3xl bg-white p-6 shadow">
                <h2 class="text-xl font-black text-slate-900">Pending Pharmacy Actions</h2>

                <div class="mt-4 space-y-3">
                    @forelse($pendingOrders as $order)
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="font-black text-slate-900">
                                        RX-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                    </p>
                                    <p class="text-sm text-slate-600">
                                        {{ optional($order->client)->name ?? 'N/A' }} · {{ $order->medication_name ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ $order->pharmacy_name ?? 'No pharmacy name' }}
                                    </p>
                                </div>

                                <a href="{{ route('pharmacy.show', $order->id) }}"
                                   class="rounded-xl bg-indigo-600 px-3 py-2 text-sm font-bold text-white hover:bg-indigo-700">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-2xl bg-green-50 p-4 font-bold text-green-700">
                            No pending prescriptions. Great work.
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow">
                <h2 class="text-xl font-black text-slate-900">Top Pharmacies Used</h2>

                <div class="mt-4 space-y-3">
                    @forelse($topPharmacies as $pharmacy)
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">
                            <div>
                                <p class="font-black text-slate-900">{{ $pharmacy->pharmacy_name }}</p>
                                <p class="text-sm text-slate-500">Prescription activity</p>
                            </div>

                            <span class="rounded-full bg-indigo-100 px-4 py-2 text-sm font-black text-indigo-700">
                                {{ $pharmacy->total }}
                            </span>
                        </div>
                    @empty
                        <p class="rounded-2xl bg-slate-50 p-4 text-slate-600">
                            No pharmacy usage yet.
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow lg:col-span-2">
                <h2 class="text-xl font-black text-slate-900">Recent Prescription Activity</h2>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="border-b bg-slate-50 text-slate-600">
                                <th class="p-3">RX</th>
                                <th class="p-3">Patient</th>
                                <th class="p-3">Medication</th>
                                <th class="p-3">Pharmacy</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Date</th>
                                <th class="p-3">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr class="border-b">
                                    <td class="p-3 font-bold">
                                        RX-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="p-3">{{ optional($order->client)->name ?? 'N/A' }}</td>
                                    <td class="p-3">{{ $order->medication_name ?? 'N/A' }}</td>
                                    <td class="p-3">{{ $order->pharmacy_name ?? 'N/A' }}</td>
                                    <td class="p-3">
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black uppercase text-slate-700">
                                            {{ $order->status ?? 'pending' }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        {{ optional($order->prescribed_at)->format('M j, Y') ?? $order->created_at->format('M j, Y') }}
                                    </td>
                                    <td class="p-3">
                                        <a href="{{ route('pharmacy.show', $order->id) }}"
                                           class="font-bold text-indigo-600 hover:text-indigo-800">
                                            Open
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-5 text-center text-slate-500">
                                        No recent prescriptions.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
