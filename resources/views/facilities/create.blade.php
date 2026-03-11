@extends('layouts.app')

@section('content')

<h2>Add Facility</h2>

<form method="POST" action="/facilities">

@csrf

<div class="mb-3">
<label>Facility Name</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
<label>Address</label>
<input type="text" name="address" class="form-control">
</div>

<button class="btn btn-success">
Save Facility
</button>

<a href="/facilities" class="btn btn-secondary">
Cancel
</a>

</form>

@endsection
