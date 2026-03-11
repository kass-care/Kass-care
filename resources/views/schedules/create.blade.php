@extends('layouts.app')

@section('content')

<div class="container">

<h2>Create Schedule</h2>

<form method="POST" action="/schedules">

@csrf

<div>
<label>Client</label>
<select name="client_id">
@foreach($clients as $client)
<option value="{{ $client->id }}">{{ $client->name }}</option>
@endforeach
</select>
</div>

<br>

<div>
<label>Caregiver</label>
<select name="caregiver_id">
@foreach($caregivers as $caregiver)
<option value="{{ $caregiver->id }}">{{ $caregiver->name }}</option>
@endforeach
</select>
</div>

<br>

<div>
<label>Date</label>
<input type="date" name="date">
</div>

<br>

<div>
<label>Start Time</label>
<input type="time" name="start_time">
</div>

<br>

<div>
<label>End Time</label>
<input type="date" name="schedule_date">
</div>

<br>

<button type="submit">Save Schedule</button>

</form>

</div>

@endsection
