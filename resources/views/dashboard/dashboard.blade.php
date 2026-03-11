{{-- resources/views/dashboard/dashboard.blade.php --}}
@extends('layouts.layout') {{-- Make sure layouts/layout.blade.php exists --}}

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">Main Dashboard</h1>

    {{-- Top Buttons --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <a href="{{ route('visits.index') }}" class="btn btn-primary w-100">Visits</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('clients.index') }}" class="btn btn-primary w-100">Clients</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('labs.index') }}" class="btn btn-primary w-100">Labs</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('provider-visits.index') }}" class="btn btn-primary w-100">Provider Visits</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <a href="{{ route('facility-visits.index') }}" class="btn btn-secondary w-100">Facility Visits</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">Users</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('alerts.index') }}" class="btn btn-warning w-100 position-relative">
                Alerts
                @if(isset($missedAlerts) && $missedAlerts > 0)
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                        {{ $missedAlerts }}
                    </span>
                @endif
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('schedules.index') }}" class="btn btn-secondary w-100">Schedules</a>
        </div>
    </div>

    {{-- Provider / Caregiver Dashboards --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <a href="{{ route('provider-dashboard.index') }}" class="btn btn-success w-100">Provider Dashboard</a>
        </div>
        <div class="col-md-6 mb-3">
            <a href="{{ route('caregiver-dashboard.index') }}" class="btn btn-info w-100">Caregiver Dashboard</a>
        </div>
    </div>

</div>
@endsection
