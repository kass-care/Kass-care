@extends('layouts.app')

@section('content')

<div class="container">

<h2>Request Lab Test</h2>

<form method="POST" action="{{ route('labs.store') }}">

@csrf

<div class="mb-3">
<label>Client</label>
<select name="client_id" class="form-control">

@foreach($clients as $client)
<option value="{{ $client->id }}">{{ $client->name }}</option>
@endforeach

</select>
</div>

<div class="mb-3">
<label>Test Type</label>
<select name="test_type" class="form-control">
<option>Blood Draw</option>
<option>Urine Test</option>
<option>CBC Panel</option>
<option>Glucose Test</option>
</select>
</div>

<div class="mb-3">
<label>Priority</label>
<select name="priority" class="form-control">
<option>Normal</option>
<option>Urgent</option>
<option>Stat</option>
</select>
</div>

<div class="mb-3">
<label>Notes</label>
<textarea name="notes" class="form-control"></textarea>
</div>

<button class="btn btn-success">Submit Lab Request</button>

</form>

</div>

@endsection
