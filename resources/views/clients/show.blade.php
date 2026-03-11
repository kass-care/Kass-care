@extends('layouts.app')

@section('content')

<div class="container">

<h2>Client Profile</h2>

<h3>{{ $client->name }}</h3>

<hr>

<h3>Care Timeline</h3>

<table border="1" width="100%" cellpadding="10">

<thead>
<tr>
<th>Date</th>
<th>Caregiver</th>
<th>Meal</th>
<th>Shower</th>
<th>BM</th>
<th>Notes</th>
</tr>
</thead>

<tbody>

@foreach($client->carelogs as $log)

<tr>

<td>{{ $log->created_at }}</td>

<td>{{ $log->caregiver->name ?? 'N/A' }}</td>

<td>{{ $log->meal }}</td>

<td>{{ $log->shower }}</td>

<td>{{ $log->bm }}</td>

<td>{{ $log->notes }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection
