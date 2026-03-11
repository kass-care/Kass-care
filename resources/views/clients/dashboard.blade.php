@extends('layouts.app')

@section('content')

<div class="container">

<h2>Clinical Dashboard</h2>

<hr>

<h3>{{ $client->name }}</h3>

<div class="row">

<div class="col-md-4">
<div class="card">
<div class="card-header">
Recent Visits
</div>

<div class="card-body">

@foreach($visits as $visit)

<p>
{{ $visit->created_at->format('Y-m-d') }}
</p>

@endforeach

</div>
</div>
</div>


<div class="col-md-4">
<div class="card">
<div class="card-header">
Provider Notes
</div>

<div class="card-body">

@foreach($notes as $note)

<p>
{{ $note->note }}
</p>

@endforeach

</div>
</div>
</div>


<div class="col-md-4">
<div class="card">
<div class="card-header">
Lab Results
</div>

<div class="card-body">

@foreach($labs as $lab)

<p>
{{ $lab->test_name }} : {{ $lab->result }}
</p>

@endforeach

</div>
</div>
</div>


</div>

</div>

@endsection
