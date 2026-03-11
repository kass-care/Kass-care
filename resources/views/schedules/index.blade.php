@extends('layouts.app')

@section('content')

<h2>Schedules</h2>

<a href="/schedules/create">Add Schedule</a>

<br><br>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Client</th>
<th>Caregiver</th>
<th>Date</th>
<th>Start</th>
<th>End</th>
</tr>

@foreach($schedules as $schedule)

<tr>

<td>{{ $schedule->id }}</td>

<td>{{ $schedule->client->name }}</td>

<td>{{ $schedule->caregiver->name }}</td>

<td>{{ $schedule->schedule_date }}</td>

<td>{{ $schedule->start_time }}</td>

<td>{{ $schedule->end_time }}</td>

</tr>

@endforeach

</table>

@endsection
