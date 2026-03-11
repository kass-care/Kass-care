@extends('layouts.app')

@section('content')

<div class="container">

<h2>New Client</h2>

<form method="POST" action="/clients">

@csrf

<div class="form-group">
<label>Name</label>
<input type="text" name="name" class="form-control">
</div>

<div class="form-group">
<label>Phone</label>
<input type="text" name="phone" class="form-control">
</div>

<br>

<button class="btn btn-success">
Save Client
</button>

</form>

</div>

@endsection
