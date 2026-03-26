@extends('layouts.app')

@section('content')

<div class="container">

<h2>Lab Requests</h2>

<a href="/labs/create" class="btn btn-primary mb-3">Request Lab</a>

<table class="table table-bordered">

<tr>
<th>Client</th>
<th>Test</th>
<th>Priority</th>
<th>Status</th>
</tr>

@foreach($labs as $lab)

<tr>

<td>{{ $lab->client->name }}</td>

<td>{{ $lab->test_type }}</td>

<td>{{ $lab->priority }}</td>

<td>

<form method="POST" action="/labs/{{ $lab->id }}/status">

@csrf
@method('PATCH')

<select name="status" onchange="this.form.submit()">

<option value="pending" {{ $lab->status == 'pending' ? 'selected' : '' }}>Pending</option>

<option value="processing" {{ $lab->status == 'processing' ? 'selected' : '' }}>Processing</option>

<option value="completed" {{ $lab->status == 'completed' ? 'selected' : '' }}>Completed</option>

</select>

</form>

</td>

</tr>

@endforeach

</table>

</div>

@endsection
