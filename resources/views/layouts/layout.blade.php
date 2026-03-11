<!DOCTYPE html>
<html>
<head>

<title>Kass Care</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">

<div class="container-fluid">

<a class="navbar-brand" href="{{ route('dashboard.index') }}">
Kass Care
</a>

<div class="collapse navbar-collapse">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="{{ route('dashboard.index') }}">
Dashboard
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('provider-dashboard.index') }}">
Provider
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('caregiver-dashboard.index') }}">
Caregiver
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="/dashboard">
Home
</a>
</li>

</ul>

</div>

</div>

</nav>

<div class="container">

@yield('content')

</div>

</body>
</html>
