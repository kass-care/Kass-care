@extends('layouts.app')

@section('content')

<div class="container">

<h2>Create Care Log</h2>

<form method="POST" action="/carelogs">

@csrf


<div class="mb-3">
<label>Client</label>

<select name="client_id" class="form-control" required>

<option value="">Select Client</option>

@foreach($clients as $client)

<option value="{{ $client->id }}">
{{ $client->name }}
</option>

@endforeach

</select>

</div>



<div class="mb-3">
<label>Caregiver</label>

<select name="caregiver_id" class="form-control" required>

<option value="">Select Caregiver</option>

@foreach($caregivers as $caregiver)

<option value="{{ $caregiver->id }}">
{{ $caregiver->name }}
</option>

@endforeach

</select>

</div>



<div class="mb-3">

<label>Meal</label>

<select name="meal" class="form-control">

<option value="">Select</option>
<option value="100%">100%</option>
<option value="75%">75%</option>
<option value="50%">50%</option>
<option value="25%">25%</option>
<option value="Refused">Refused</option>

</select>

</div>



<div class="mb-3">

<label>Bath</label>

<select name="bath" class="form-control">

<option value="">Select</option>
<option value="Yes">Yes</option>
<option value="No">No</option>

</select>

</div>



<div class="mb-3">

<label>BM</label>

<select name="bm" class="form-control">

<option value="">Select</option>
<option value="Yes">Yes</option>
<option value="No">No</option>

</select>

</div>



<div class="mb-3">

<label>Notes</label>

<textarea name="notes" class="form-control"></textarea>

</div>


<button type="submit" class="btn btn-success">

Save Care Log

</button>


</form>

</div>

@endsection
