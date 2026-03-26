@extends('layouts.app')

@section('content')
<div class="p-6 max-w-6xl mx-auto">

    <h1 class="text-2xl font-bold mb-4">💊 Pharmacy Orders</h1>

    <a href="{{ route('provider.pharmacy.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded mb-4 inline-block">
        + New Prescription
    </a>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded p-4">

        @forelse($orders as $order)
            <div class="border-b py-4 flex justify-between items-center">

                <!-- LEFT SIDE -->
                <div>
                    <div class="font-semibold text-lg">
                        {{ $order->medication_name }}
                    </div>

                    <div class="text-sm text-gray-600">
                        Client: {{ $order->client->name ?? 'N/A' }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ $order->dosage ?? 'No dosage' }}
                        •
                        {{ $order->frequency ?? 'No frequency' }}
                    </div>
                </div>

                <!-- RIGHT SIDE ACTIONS -->
                <div class="flex items-center gap-2 flex-wrap">

                    <!-- CALL -->
                    @if($order->pharmacy_phone)
                        <a href="tel:{{ $order->pharmacy_phone }}"
                           class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                            📞 Call
                        </a>
                    @endif

                    <!-- FAX -->
                    @if($order->pharmacy_fax)
                        <a href="#"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                            📠 Fax
                        </a>
                    @endif

                    <!-- PDF -->
                    <a href="{{ route('provider.pharmacy.pdf', $order->id) }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm shadow">
                        📄 PDF
                    </a>

                    <!-- EMAIL -->
                    <form method="POST"
                          action="{{ route('provider.pharmacy.email', $order->id) }}">
                        @csrf
                        <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm shadow">
                            ✉️ Email
                        </button>
                    </form>

                    <!-- STATUS UPDATE -->
                    <form method="POST"
                          action="{{ route('provider.pharmacy.status', $order->id) }}"
                          class="flex items-center gap-2">
                        @csrf

                        <select name="status"
                            class="border rounded px-2 py-1 text-sm">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="sent" {{ $order->status == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="filled" {{ $order->status == 'filled' ? 'selected' : '' }}>Filled</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>

                        <button type="submit"
                            class="bg-gray-800 hover:bg-gray-900 text-white px-3 py-1 rounded text-sm">
                            Update
                        </button>
                    </form>

                    <!-- STATUS BADGE -->
                    @if($order->status == 'pending')
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-sm">
                            Pending
                        </span>
                    @elseif($order->status == 'sent')
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm">
                            Sent
                        </span>
                    @elseif($order->status == 'filled')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded text-sm">
                            Filled
                        </span>
                    @elseif($order->status == 'delivered')
                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded text-sm">
                            Delivered
                        </span>
                    @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm">
                            Cancelled
                        </span>
                    @endif

                </div>

            </div>
        @empty
            <p>No prescriptions yet.</p>
        @endforelse

    </div>
</div>
@endsection
