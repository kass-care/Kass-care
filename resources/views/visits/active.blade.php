@extends('layouts.app')

@section('content')

<h2>Active Visits</h2>

<table border="1" width="100%">
<tr>
<th>Client</th>
<th>Caregiver</th>
<th>Start Time</th>
<th>Hours Worked</th>
<th>Action</th>
</tr>

@foreach($visits as $visit)

<tr>

<td>{{ $visit->client->name ?? '' }}</td>

<td>{{ $visit->caregiver->name ?? '' }}</td>

<td>{{ $visit->start_time }}</td>

<td>
{{ round(now()->diffInMinutes($visit->start_time)/60,2) }} hrs
</td>

<td>

<form method="POST" action="/visits/end/{{ $visit->id }}">
@csrf
<button type="submit">End Visit</button>
</form>

</td>

</tr>

@endforeach

</table>

@endsection
