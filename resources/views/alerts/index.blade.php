@extends('layouts.app')

@section('content')

<div class="container">

<h2>Clinical Alerts</h2>

<table class="table table-bordered">
<thead>
<tr>
<th>Patient</th>
<th>Alert</th>
<th>Time</th>
</tr>
</thead>

<tbody>

@foreach($alerts as $alert)

<tr>

<td>
@if($alert->client)
{{ $alert->client->name }}
@else
Unknown
@endif
</td>

<td>{{ $alert->message }}</td>

<td>{{ $alert->created_at }}</td>

</tr>

@endforeach

</tbody>
</table>

</div>

@endsection
