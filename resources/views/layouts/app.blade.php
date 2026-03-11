<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KASS Care</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body{
            background:#f4f6f9;
        }

        .navbar a{
            text-decoration:none;
            font-weight:500;
        }

        .navbar a:hover{
            text-decoration:underline;
        }

        .container-main{
            margin-top:30px;
        }
    </style>

</head>

<body>

<nav class="navbar navbar-dark bg-dark navbar-expand-lg">
    <div class="container-fluid">

        <a class="navbar-brand" href="{{ route('dashboard.index') }}">
            KASS Care
        </a>

        <div class="d-flex flex-wrap gap-3">

            <a class="text-white" href="{{ route('dashboard.index') }}">
                Dashboard
            </a>

            <a class="text-white" href="{{ route('clients.index') }}">
                Clients
            </a>

            <a class="text-white" href="{{ route('caregivers.index') }}">
                Caregivers
            </a>

            <a class="text-white" href="{{ route('visits.index') }}">
                Visits
            </a>

            <a class="text-white" href="{{ route('care-logs.index') }}">
                Care Logs
            </a>

            <a class="text-white" href="{{ route('schedules.index') }}">
                Schedules
            </a>

            <a class="text-white" href="{{ route('facilities.index') }}">
                Facilities
            </a>

            <a class="text-white" href="{{ route('users.index') }}">
                Users
            </a>

            <a class="text-white" href="{{ route('provider-dashboard.index') }}">
                Provider Dashboard
            </a>

            <a class="text-white" href="{{ route('alerts.index') }}">
                🔔 Alerts
            </a>

        </div>

    </div>
</nav>


<div class="container container-main">

    @yield('content')

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
