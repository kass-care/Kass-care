@extends('layouts.app')

@section('content')

<div class="container">

<h2>Caregiver Profile</h2>

<table class="table table-bordered">

<tr>
<th>ID</th>
<td>{{ $caregiver->id }}</td>
</tr>

<tr>
<th>Name</th>
<td>{{ $caregiver->name }}</td>
</tr>

<tr>
<th>Email</th>
<td>{{ $caregiver->email }}</td>
</tr>

<tr>
<th>Phone</th>
<td>{{ $caregiver->phone }}</td>
</tr>

<tr>
<th>Status</th>
<td>{{ $caregiver->status }}</td>
</tr>

</table>

<a href="/caregivers" class="btn btn-primary">Back</a>

</div>

@endsection
