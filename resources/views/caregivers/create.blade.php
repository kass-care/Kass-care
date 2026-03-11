@extends('layouts.app')

@section('content')

<div class="container">

<h2>Add New Caregiver</h2>

<form method="POST" action="{{ url('/caregivers') }}">

@csrf

<div class="card p-4">

<div class="mb-3">
<label>Caregiver Name</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control">
</div>

<div class="mb-3">
<label>Phone</label>
<input type="text" name="phone" class="form-control">
</div>

<div class="mb-3">
<label>Role</label>
<select name="role" class="form-control">

<option value="Caregiver">Caregiver</option>
<option value="Nurse">Nurse</option>
<option value="Supervisor">Supervisor</option>

</select>

</div>

<button type="submit" class="btn btn-success">

Save Caregiver

</button>

</div>

</form>

</div>

@endsection
