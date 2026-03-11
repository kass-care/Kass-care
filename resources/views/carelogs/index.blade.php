@extends('layouts.app')

@section('content')

<div class="container">

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">

<h2>Care Logs</h2>

<a href="/carelogs/create" style="background:#3490dc;color:white;padding:10px 15px;text-decoration:none;border-radius:5px;">
+ Add Care Log
</a>

</div>

<table border="1" width="100%" cellpadding="10">

<thead>

<tr>
<th>ID</th>
<th>Client</th>
<th>Caregiver</th>
<th>Meal</th>
<th>Shower</th>
<th>BM</th>
<th>Notes</th>
<th>Date</th>
</tr>

</thead>

<tbody>

@foreach($carelogs as $log)

<tr>

<td>{{ $log->id }}</td>

<td>{{ $log->client->name ?? 'N/A' }}</td>

<td>{{ $log->caregiver->name ?? 'N/A' }}</td>

<td>{{ $log->meal }}</td>

<td>{{ $log->shower }}</td>

<td>{{ $log->bm }}</td>

<td>{{ $log->notes }}</td>

<td>{{ $log->created_at }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection
