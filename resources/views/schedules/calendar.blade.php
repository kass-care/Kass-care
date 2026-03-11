@extends('layouts.app')

@section('content')

<div class="container">

<h2 class="mb-4">Provider Weekly Calendar</h2>

<table class="table table-bordered text-center">

<thead class="table-dark">
<tr>
<th>Client</th>
<th>Caregiver</th>
<th>Date</th>
<th>Notes</th>
</tr>
</thead>

<tbody>

@foreach($schedules as $schedule)

<tr>

<td>{{ $schedule->client->name ?? 'N/A' }}</td>

<td>{{ $schedule->caregiver->name ?? 'N/A' }}</td>

<td>{{ \Carbon\Carbon::parse($schedule->date)->format('D M d Y') }}</td>

<td>{{ $schedule->notes ?? '-' }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection
