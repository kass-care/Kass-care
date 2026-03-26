@extends('layouts.app')

@section('content')

<h2>Visit History</h2>

<table class="table table-bordered">

<thead>

<tr>
<th>ID</th>
<th>Client</th>
<th>Caregiver</th>
<th>Start Time</th>
<th>End Time</th>
<th>Status</th>
</tr>

</thead>

<tbody>

@foreach($visits as $visit)

<tr>

<td>{{ $visit->id }}</td>

<td>{{ $visit->client->name ?? 'N/A' }}</td>

<td>{{ $visit->caregiver->name ?? 'N/A' }}</td>

<td>{{ $visit->start_time }}</td>

<td>{{ $visit->end_time }}</td>

<td>{{ $visit->status }}</td>

</tr>

@endforeach

</tbody>

</table>

@endsection
