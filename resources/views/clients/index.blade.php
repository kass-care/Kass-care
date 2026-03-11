@extends('layouts.app')

@section('content')

<div class="container">

<h2>Clients</h2>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Name</th>
<th>Phone</th>
<th>Email</th>
<th>Diagnosis</th>
<th>Timeline</th>
</tr>

@foreach($clients as $client)

<tr>

<td>{{ $client->id }}</td>

<td>
<a href="/clients/{{ $client->id }}">
{{ $client->name }}
</a>
</td>

<td>{{ $client->phone }}</td>

<td>{{ $client->email }}</td>

<td>{{ $client->diagnosis }}</td>

<td>
<a href="/clients/{{ $client->id }}/dashboard" class="btn btn-primary">
Dashboard
</a>
<a href="/clients/{{ $client->id }}/timeline" class="btn btn-info">
Timeline
</a>
</td>

</tr>

@endforeach

</table>

</div>

@endsection
