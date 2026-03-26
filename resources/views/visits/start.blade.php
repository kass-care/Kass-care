@extends('layouts.app')

@section('content')

<div style="padding:30px">

<h1>Start Visit</h1>

<form method="POST" action="/visits/start">

@csrf

<label>Client</label>

<br>

<select name="client_id">

@foreach($clients as $client)

<option value="{{ $client->id }}">
{{ $client->name }}
</option>

@endforeach

</select>

<br><br>

<label>Caregiver</label>

<br>

<select name="caregiver_id">

@foreach($caregivers as $caregiver)

<option value="{{ $caregiver->id }}">
{{ $caregiver->name }}
</option>

@endforeach

</select>

<br><br>

<button style="padding:10px 20px;background:green;color:white;border:none">
Start Visit
</button>

</form>

</div>

@endsection
