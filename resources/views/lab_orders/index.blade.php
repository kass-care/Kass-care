@extends('layouts.app')

@section('content')

<h2>Lab Orders</h2>

<a href="{{ route('lab_orders.create') }}">Add Lab Order</a>

<table border="1" cellpadding="8">

<tr>
<th>ID</th>
<th>Client</th>
<th>Lab Type</th>
<th>Status</th>
<th>Action</th>
</tr>

@foreach($orders as $order)

<tr>

<td>{{ $order->id }}</td>

<td>{{ $order->client->name }}</td>

<td>{{ $order->lab_type }}</td>

<td>{{ $order->status }}</td>

<td>

@if($order->status == 'pending')

<a href="{{ route('lab_orders.complete', $order->id) }}">
Complete
</a>

@else

Completed

@endif

</td>

</tr>

@endforeach

</table>

@endsection
