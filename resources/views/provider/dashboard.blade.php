@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Provider Dashboard</h1>
    <div class="row">
        <div class="col-md-3 mb-3">
            <a href="{{ route('visits.index') }}" class="btn btn-primary w-100">Visits ({{ $totalVisits }})</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('clients.index') }}" class="btn btn-primary w-100">Clients ({{ $totalClients }})</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('labs.index') }}" class="btn btn-primary w-100">Labs ({{ $totalLabs }})</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('provider-visits.index') }}" class="btn btn-success w-100">Provider Visits ({{ $totalProviderVisits }})</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('facility-visits.index') }}" class="btn btn-secondary w-100">Facility Visits ({{ $totalFacilityVisits }})</a>
        </div>
    </div>
</div>
@endsection
