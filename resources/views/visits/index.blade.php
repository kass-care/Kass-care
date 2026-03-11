@extends('layouts.app')

@section('content')

<div class="container">

<h2>Visits</h2>

<a href="/visits/create" class="btn btn-primary mb-3">New Visit</a>

<table class="table table-bordered">

<thead>

<tr>
<th>ID</th>
<th>Client</th>
<th>Caregiver</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

</thead>

<tbody>

@foreach($visits as $visit)

<tr>

<td>{{ $visit->id }}</td>

<td>{{ $visit->client->name ?? '' }}</td>

<td>{{ $visit->caregiver->name ?? '' }}</td>

<td>{{ $visit->visit_date }}</td>

<td>{{ $visit->status }}</td>

<td>

<a href="/visits/{{ $visit->id }}" class="btn btn-sm btn-success">View</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection
