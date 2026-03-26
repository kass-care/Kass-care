@extends('layouts.app')

@section('content')

<div class="container">

<h2>Create Lab Order</h2>

<form method="POST" action="/lab_orders">

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
<label>Lab Type</label>
<input type="text" name="lab_type" required>
</div>

<br>

<div>
<label>Instructions</label>
<textarea name="instructions"></textarea>
</div>

<br>

<button type="submit">Save Lab Order</button>

</form>

</div>

@endsection
