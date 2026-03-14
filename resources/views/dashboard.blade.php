@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-header">Visits</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $visitsCount }}</h5>
                    <a href="{{ route('visits.index') }}" class="btn btn-light btn-sm">View All Visits</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-header">Clients</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $clientsCount }}</h5>
                    <a href="{{ route('clients.index') }}" class="btn btn-light btn-sm">View All Clients</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-header">Caregivers</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $caregiversCount }}</h5>
                    <a href="{{ route('caregivers.index') }}" class="btn btn-light btn-sm">View All Caregivers</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <h4>Today's Visits</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Caregiver</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\Visit::whereDate('created_at', now())->get() as $visit)
                    <tr>
                        <td>{{ $visit->client->name ?? 'N/A' }}</td>
                        <td>{{ $visit->caregiver->name ?? 'N/A' }}</td>
                        <td>{{ $visit->time ?? 'N/A' }}</td>
                        <td>{{ $visit->status ?? 'Pending' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <h4>Recently Added Clients</h4>
            <ul class="list-group">
                @foreach(\App\Models\Client::latest()->take(5)->get() as $client)
                <li class="list-group-item">{{ $client->name }} - {{ $client->created_at->format('M d, Y') }}</li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-6">
            <h4>Recently Added Caregivers</h4>
            <ul class="list-group">
                @foreach(\App\Models\Caregiver::latest()->take(5)->get() as $caregiver)
                <li class="list-group-item">{{ $caregiver->name }} - {{ $caregiver->created_at->format('M d, Y') }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
