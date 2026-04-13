<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>KassCare</title>

@vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 text-white">

<div class="min-h-screen flex flex-col">

<!-- NAV -->

<header class="flex justify-between items-center px-10 py-6">

<h1 class="text-2xl font-bold text-cyan-400">
KASSCare
</h1>

<a href="/login" class="bg-cyan-500 hover:bg-cyan-400 text-black px-4 py-2 rounded-lg font-semibold">
Dashboard
</a>

</header>

<!-- HERO -->

<section class="text-center mt-16 px-6">

<p class="uppercase text-cyan-400 tracking-widest mb-4">
PLATFORM
</p>

<h1 class="text-5xl font-bold mb-6">
One platform for healthcare operations
</h1>

<p class="text-lg text-gray-300 max-w-2xl mx-auto mb-10">
KassCare reduces paperwork, improves coordination, and gives providers and
facilities the tools they need to manage care with confidence.
</p>

<div class="flex justify-center gap-4">

<a href="/register-facility"
class="bg-indigo-500 hover:bg-indigo-400 px-6 py-3 rounded-xl font-semibold">
Register Facility
</a>

<a href="/login"
class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl font-semibold">
Sign In
</a>

</div>

</section>

<!-- FEATURES -->

<section class="grid md:grid-cols-3 gap-8 px-10 mt-20">

<div class="bg-white/10 backdrop-blur p-6 rounded-2xl">
<h3 class="text-xl font-bold mb-3">Patient Management</h3>
<p class="text-gray-300">
Manage clients, diagnoses, medications, and documentation from one organized platform.
</p>
</div>

<div class="bg-white/10 backdrop-blur p-6 rounded-2xl">
<h3 class="text-xl font-bold mb-3">Facility Operations</h3>
<p class="text-gray-300">
Track caregivers, schedule visits, monitor daily care activity, and run facility workflows.
</p>
</div>

<div class="bg-white/10 backdrop-blur p-6 rounded-2xl">
<h3 class="text-xl font-bold mb-3">Provider Intelligence</h3>
<p class="text-gray-300">
Surface alerts, compliance signals, patient summaries, and clinical insights.
</p>
</div>

</section>

<!-- SOLUTIONS -->

<section class="text-center mt-24 px-10">

<h2 class="text-3xl font-bold mb-12">
Built for the people who keep care moving
</h2>

<div class="grid md:grid-cols-4 gap-6">

<div class="bg-white/10 p-5 rounded-xl">
<h4 class="font-bold">Providers</h4>
<p class="text-sm text-gray-300 mt-2">
Access patient intelligence and clinical workspace.
</p>
</div>

<div class="bg-white/10 p-5 rounded-xl">
<h4 class="font-bold">Facilities</h4>
<p class="text-sm text-gray-300 mt-2">
Manage caregivers, visits, and operations.
</p>
</div>

<div class="bg-white/10 p-5 rounded-xl">
<h4 class="font-bold">Caregivers</h4>
<p class="text-sm text-gray-300 mt-2">
Daily visit workflows and patient charting.
</p>
</div>

<div class="bg-white/10 p-5 rounded-xl">
<h4 class="font-bold">Multi-Facility Teams</h4>
<p class="text-sm text-gray-300 mt-2">
Platform level oversight for growth and compliance.
</p>
</div>

</div>

</section>

<footer class="text-center text-gray-400 text-sm mt-24 pb-10">
KassCare © {{ date('Y') }}
</footer>

</div>

</body>
</html>
