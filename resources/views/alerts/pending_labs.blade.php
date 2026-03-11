@extends('layouts.app')

@section('content')

<h1>Pending Labs</h1>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Client</th>
<th>Lab Type</th>
<th>Status</th>
</tr>

@foreach($labs as $lab)

<tr>
<td>{{ $lab->id }}</td>
<td>{{ $lab->client_id }}</td>
<td>{{ $lab->lab_type }}</td>
<td>{{ $lab->status }}</td>
</tr>

@endforeach

</table>

@endsection
