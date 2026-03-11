@extends('layouts.app')

@section('content')

<div class="container">

<h2>Patient Timeline</h2>

<h4>{{ $client->name }}</h4>

<hr>

@foreach($timeline as $event)

<div class="card mb-3">
<div class="card-body">

<strong>{{ $event['type'] }}</strong>

<br>

{{ $event['text'] }}

<br>

<small>
{{ $event['date'] }}
</small>

</div>
</div>

@endforeach

</div>

@endsection
