<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kass Care</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">

<div class="container-fluid">

<a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
Kass Care
</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarNav">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="{{ route('dashboard') }}">
Dashboard
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('clients.index') }}">
Clients
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('caregivers.index') }}">
Caregivers
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('visits.index') }}">
Visits
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('care-logs.index') }}">
Care Logs
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('labs.index') }}">
Labs
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('facilities.index') }}">
Facilities
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('calendar') }}">
Calendar
</a>
</li>

</ul>

</div>

</div>

</nav>


<div class="container">

@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

@yield('content')

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
