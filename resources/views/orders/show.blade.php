@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Order Details</h1>
        <a href="{{ route('orders.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
            Back
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-6 space-y-4">
        <p><strong>Client:</strong> {{ $order->client->name ?? 'N/A' }}</p>
        <p><strong>Type:</strong> {{ ucfirst($order->type) }}</p>
        <p><strong>Title:</strong> {{ $order->title }}</p>
        <p><strong>Description:</strong> {{ $order->description ?? 'N/A' }}</p>
        <p><strong>Destination:</strong> {{ $order->destination ?? 'N/A' }}</p>
        <p><strong>Priority:</strong> {{ ucfirst($order->priority) }}</p>
        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
        <p><strong>Ordered Date:</strong> {{ $order->ordered_date ?? 'N/A' }}</p>
        <p><strong>Follow Up Date:</strong> {{ $order->follow_up_date ?? 'N/A' }}</p>
        <p><strong>Created By:</strong> {{ $order->creator->name ?? 'N/A' }}</p>

        <div>
            <strong>Result Notes:</strong>
            <div class="mt-2 p-4 bg-slate-50 rounded-lg border">
                {{ $order->result_notes ?? 'No notes yet.' }}
            </div>
        </div>
    </div>
</div>
@endsection
