@extends('layout')

@section('content')

<h1>Facility Visit Schedule</h1>

@if(session('success'))
<p style="color:green;">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="8" cellspacing="0" width="100%">

<tr>
<th>Facility</th>
<th>Address</th>
<th>Next Visit</th>
<th>Frequency</th>
<th>Action</th>
</tr>

@foreach($facilities as $facility)

<tr>

<td>{{ $facility->name }}</td>

<td>{{ $facility->address }}</td>

<td>{{ $facility->next_visit }}</td>

<td>{{ $facility->visit_frequency_days }} days</td>

<td>

<form method="POST" action="{{ route('facility.completeVisit',$facility->id) }}">
@csrf
<button type="submit">Complete Visit</button>
</form>

</td>

</tr>

@endforeach

</table>

@endsection
