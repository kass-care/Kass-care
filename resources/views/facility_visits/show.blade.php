@extends('layouts.app')

@section('content')

<div class="container">

<h2>Facility Visit — {{ $facility->name }}</h2>

<table class="table table-bordered">

<thead>
<tr>
<th>Patient</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

@foreach($clients as $client)

<tr>

<td>{{ $client->name }}</td>

<td>

<a href="/clients/{{ $client->id }}" class="btn btn-info">
Open Chart
</a>

<a href="/labs/create?client_id={{ $client->id }}" class="btn btn-warning">
Order Lab
</a>

<button class="btn btn-success">
Mark Reviewed
</button>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection
