@extends('layouts.app')

@section('content')

<div class="container">

<h2>Visit Details</h2>

<div style="background:white;padding:20px;border-radius:10px">

<p><strong>ID:</strong> {{ $visit->id }}</p>

<p><strong>Client:</strong> {{ $visit->client->name ?? '' }}</p>

<p><strong>Caregiver:</strong> {{ $visit->caregiver->name ?? '' }}</p>

<p><strong>Date:</strong> {{ $visit->visit_date }}</p>

<p><strong>Start Time:</strong> {{ $visit->start_time }}</p>

<p><strong>Status:</strong> {{ $visit->status }}</p>

<a href="/visits" class="btn btn-primary">Back</a>

</div>

</div>

@endsection
