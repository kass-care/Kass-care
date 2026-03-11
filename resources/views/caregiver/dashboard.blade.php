<!DOCTYPE html>
<html>
<head>
<title>Caregiver Dashboard</title>

<style>
body{
font-family:Arial;
background:#f4f4f4;
padding:40px;
}

.card{
background:white;
padding:20px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

th,td{
border:1px solid #ddd;
padding:10px;
text-align:left;
}

th{
background:#f0f0f0;
}
</style>

</head>

<body>

<h1>Caregiver Dashboard</h1>

<div class="card">

<h2>Today's Schedule</h2>

<table>
<tr>
<th>Client</th>
<th>Start Time</th>
<th>End Time</th>
</tr>

@php
$schedules = \App\Models\Schedule::with('client')
    ->where('caregiver_id', session('caregiver_id'))
    ->get();
@endphp

@if($schedules->count() > 0)

@foreach($schedules as $schedule)

<tr>
<td>{{ $schedule->client->name ?? 'Client' }}</td>
<td>{{ $schedule->start_time }}</td>
<td>{{ $schedule->end_time }}</td>
</tr>

@endforeach

@else

<tr>
<td colspan="3">No visits scheduled</td>
</tr>

@endif

</table>

</div>

</body>
</html>
