<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KASS Care')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 56px;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 56px;
            left: 0;
            width: 220px;
            background-color: #343a40;
            color: white;
            padding-top: 1rem;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 0.75rem 1rem;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
            text-decoration: none;
        }
        .content {
            margin-left: 220px;
            padding: 1rem;
        }
    </style>
</head>
<body>

    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard.index') }}">KASS Care</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTop" aria-controls="navbarTop" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTop">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.index') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('adminlte.darkmode.toggle') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-light btn-sm" type="submit">🌙 Dark Mode</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <a href="{{ route('visits.index') }}">Visits</a>
        <a href="{{ route('clients.index') }}">Clients</a>
        <a href="{{ route('labs.index') }}">Labs</a>
        <a href="{{ route('caregivers.index') }}">Caregivers</a>
        <a href="{{ route('care-logs.index') }}">Care Logs</a>
        <a href="{{ route('schedules.index') }}">Schedules</a>
        <a href="{{ route('facilities.index') }}">Facilities</a>
        <a href="{{ route('users.index') }}">Users</a>
        <a href="{{ route('provider-dashboard.index') }}">Provider Dashboard</a>
        <a href="{{ route('alerts.index') }}">🔔 Alerts</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
