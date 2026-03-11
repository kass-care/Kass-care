@extends('layouts.app')

@section('content')

<h2>Facilities</h2>

<a href="/facilities/create" class="btn btn-primary mb-3">
Add Facility
</a>

<table class="table table-bordered">

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Address</th>
</tr>
</thead>

<tbody>

@foreach($facilities as $facility)

<tr>
<td>{{ $facility->id }}</td>
<td>{{ $facility->name }}</td>
<td>{{ $facility->address }}</td>
</tr>

@endforeach

</tbody>

</table>

@endsection
