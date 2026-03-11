@extends('layouts.app')

@section('content')

<div class="container">

<h2>Create Visit</h2>

<form method="POST" action="/visits">

@csrf

<div class="mb-3">

<label>Client ID</label>

<input type="number" name="client_id" class="form-control">

</div>

<div class="mb-3">

<label>Caregiver ID</label>

<input type="number" name="caregiver_id" class="form-control">

</div>

<button type="submit" class="btn btn-success">Save Visit</button>

</form>

</div>

@endsection
