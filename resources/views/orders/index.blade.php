@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">💊 Pharmacy Orders</h1>

        <a href="{{ route('orders.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
            + New Prescription
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white shadow rounded-xl p-4 border">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $order->medication }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            Client: {{ $order->client_name ?? 'N/A' }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ $order->dosage }} • {{ $order->frequency }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="tel:{{ $order->phone ?? '' }}"
                           class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                            📞 Call
                        </a>

                        <a href="{{ route('orders.fax', $order->id) }}"
                           class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                            📠 Fax
                        </a>

                        <a href="{{ route('orders.pdf', $order->id) }}"
                           class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                            📄 PDF
                        </a>

                        <a href="{{ route('orders.email', $order->id) }}"
                           class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                            ✉️ Email
                        </a>

                        <span class="px-3 py-2 rounded-lg text-sm font-medium
                            @if(($order->status ?? 'pending') == 'pending')
                                bg-yellow-100 text-yellow-700
                            @elseif(($order->status ?? 'pending') == 'sent')
                                bg-blue-100 text-blue-700
                            @elseif(($order->status ?? 'pending') == 'delivered')
                                bg-green-100 text-green-700
                            @else
                                bg-red-100 text-red-700
                            @endif">
                            {{ ucfirst($order->status ?? 'pending') }}
                        </span>

                        <a href="{{ route('orders.edit', $order->id) }}"
                           class="inline-flex items-center bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                            Update
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
                No pharmacy orders found.
            </div>
        @endforelse
    </div>
</div>
@endsection
