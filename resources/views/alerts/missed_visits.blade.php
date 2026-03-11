@extends('layouts.app')

@section('content')

<h1>Missed Visits</h1>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Client</th>
<th>Status</th>
</tr>

@foreach($visits as $visit)

<tr>
<td>{{ $visit->id }}</td>
<td>{{ $visit->client_id }}</td>
<td>{{ $visit->status }}</td>
</tr>

@endforeach

</table>

@endsection
