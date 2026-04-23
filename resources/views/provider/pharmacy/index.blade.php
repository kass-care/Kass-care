@extends('layouts.app')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">💊 Pharmacy Orders</h1>

    <a href="{{ route('provider.pharmacy.create') }}"
       class="inline-block mb-4 rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
        + New Prescription
    </a>

    @if(session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif
    <div class="rounded bg-white p-4 shadow">
        @forelse($orders as $order)
            <div class="flex flex-wrap items-center justify-between gap-4 border-b py-4 last:border-b-0">
                <div>
                    <div class="text-lg font-semibold">
                        {{ $order->medication_name }}
                    </div>

                    <div class="text-sm text-gray-600">
                        Client:
{{ trim((optional($order->client)->first_name ?? '') . ' ' . (optional($order->client)->last_name ?? '')) ?: (optional($order->client)->name ?? 'N/A') }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ $order->dosage ?? 'No dosage' }}
                        ·
                        {{ $order->frequency ?? 'No frequency' }}
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if($order->pharmacy_phone)
                        <a href="tel:{{ $order->pharmacy_phone }}"
                           class="rounded bg-green-500 px-3 py-1 text-sm text-white hover:bg-green-600">
                            📞 Call
                        </a>
                    @endif

                    @if($order->pharmacy_fax)
                        <a href="#"
                           class="rounded bg-blue-500 px-3 py-1 text-sm text-white hover:bg-blue-600">
                            📠 Fax
                        </a>
                    @endif

                    <a href="{{ route('provider.pharmacy.pdf', $order->id) }}"
                       class="rounded bg-indigo-600 px-3 py-1 text-sm text-white hover:bg-indigo-700">
                        📄 PDF
                    </a>

                    <form method="POST" action="{{ route('provider.pharmacy.email', $order->id) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="rounded bg-purple-600 px-3 py-1 text-sm text-white hover:bg-purple-700">
                            ✉️ Email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('provider.pharmacy.status', $order->id) }}" class="flex items-center gap-2">
                        @csrf

                        <select name="status" class="rounded border px-2 py-1 text-sm">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="sent" {{ $order->status === 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="filled" {{ $order->status === 'filled' ? 'selected' : '' }}>Filled</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>

                        <button type="submit"
                                class="rounded bg-gray-800 px-3 py-1 text-sm text-white hover:bg-gray-900">
                            Update
                        </button>
                    </form>

                    @if($order->status === 'pending')
                        <span class="rounded bg-yellow-100 px-3 py-1 text-sm text-yellow-700">Pending</span>
                    @elseif($order->status === 'sent')
                        <span class="rounded bg-blue-100 px-3 py-1 text-sm text-blue-700">Sent</span>
                    @elseif($order->status === 'filled')
                        <span class="rounded bg-green-100 px-3 py-1 text-sm text-green-700">Filled</span>
                    @elseif($order->status === 'delivered')
                        <span class="rounded bg-indigo-100 px-3 py-1 text-sm text-indigo-700">Delivered</span>
                    @else
                        <span class="rounded bg-red-100 px-3 py-1 text-sm text-red-700">Cancelled</span>
                    @endif
                </div>
            </div>
        @empty
            <p>No prescriptions yet.</p>
        @endforelse
    </div>
</div>
@endsection
