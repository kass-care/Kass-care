@extends('layouts.app')

@section('content')

<h2>Today Schedule</h2>

<table border="1" cellpadding="10">

<tr>
<th>Caregiver</th>
<th>Client</th>
<th>Start</th>
<th>End</th>
</tr>

@foreach($schedules as $schedule)

<tr>

<td>{{ $schedule->caregiver->name }}</td>

<td>{{ $schedule->client->name }}</td>

<td>{{ $schedule->start_time }}</td>

<td>{{ $schedule->end_time }}</td>

</tr>

@endforeach

</table>

@endsection
