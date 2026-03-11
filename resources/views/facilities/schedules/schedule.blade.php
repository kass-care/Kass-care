@extends('layouts.app')

@section('content')

<h1>Facility Visit Schedule</h1>

<table border="1" cellpadding="10" style="width:100%;margin-top:20px">

<tr>
<th>Facility</th>
<th>Address</th>
<th>Next Visit</th>
<th>Frequency</th>
</tr>

@foreach($facilities as $facility)

<tr>

<td>{{ $facility->name }}</td>

<td>{{ $facility->address }}</td>

<td>{{ $facility->next_visit }}</td>

<td>{{ $facility->visit_frequency_days }} days</td>

</tr>

@endforeach

</table>

@endsection
