<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Admin Home | Kass Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between mb-10">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-indigo-300">Kass Care</p>
                <h1 class="text-4xl font-bold mt-2">Facility Command Center</h1>
                <p class="text-slate-400 mt-3">
                    {{ auth()->user()->facility?->name ?? 'Your Facility' }}
                </p>
                <p class="text-slate-500 mt-2">
                    Real-time facility intelligence for clients, caregivers, visits, providers, messaging, and operations.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-900">
                    Dashboard
                </a>

                <form method="POST" action="/select-facility/{{ auth()->user()->facility_id }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center rounded-2xl border border-indigo-400 px-5 py-3 text-sm font-semibold text-indigo-200">
                        Enter Facility Context
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center rounded-2xl border border-red-400/50 bg-red-500/10 px-5 py-3 text-sm font-semibold text-red-200">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="rounded-3xl bg-slate-900 border border-slate-800 p-6">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Clients</p>
                <p class="text-5xl font-bold mt-4">{{ $clientsCount ?? 0 }}</p>
                <p class="text-slate-400 mt-2">Registered in this facility</p>
            </div>

            <div class="rounded-3xl bg-slate-900 border border-slate-800 p-6">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Caregivers</p>
                <p class="text-5xl font-bold mt-4">{{ $caregiversCount ?? 0 }}</p>
                <p class="text-slate-400 mt-2">Assigned workforce</p>
            </div>

            <div class="rounded-3xl bg-slate-900 border border-slate-800 p-6">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Visits</p>
                <p class="text-5xl font-bold mt-4">{{ $visitsCount ?? 0 }}</p>
                <p class="text-slate-400 mt-2">Scheduled visits</p>
            </div>

            <div class="rounded-3xl bg-slate-900 border border-slate-800 p-6">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Providers</p>
                <p class="text-5xl font-bold mt-4">{{ $providersCount ?? 0 }}</p>
                <p class="text-slate-400 mt-2">Linked providers</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 rounded-3xl bg-slate-900 border border-slate-800 p-8">
                <h2 class="text-2xl font-semibold mb-2">Facility Details</h2>
                <p class="text-slate-400 mb-6">Core information for this facility.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="rounded-2xl bg-slate-950 border border-slate-800 p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Name</p>
                        <p class="text-2xl font-semibold mt-3">{{ auth()->user()->facility?->name ?? 'Not provided' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-950 border border-slate-800 p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Address</p>
                        <p class="text-2xl font-semibold mt-3">{{ auth()->user()->facility?->address ?? 'Not provided' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-950 border border-slate-800 p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Phone</p>
                        <p class="text-2xl font-semibold mt-3">{{ auth()->user()->facility?->phone ?? 'Not provided' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-950 border border-slate-800 p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Email</p>
                        <p class="text-2xl font-semibold mt-3">{{ auth()->user()->email ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-slate-900 border border-slate-800 p-8">
                <h2 class="text-2xl font-semibold mb-2">Quick Actions</h2>
                <p class="text-slate-400 mb-6">Jump into facility workflows.</p>

                <div class="space-y-3">
                <a href="/facility/messages/create"
   class="block w-full text-center px-4 py-4 rounded-2xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
    Message Provider
</a>

<a href="/facility/messages"
   class="block w-full text-center px-4 py-4 rounded-2xl border border-emerald-500 text-emerald-200 font-semibold hover:bg-emerald-500/10">
    View Messages
</a>
                    <a href="{{ route('facility.patients.index') }}"
                       class="block w-full text-center px-4 py-4 rounded-2xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                        Open Clients
                    </a>

                    <a href="{{ route('facility.caregivers.index') }}"
                       class="block w-full text-center px-4 py-4 rounded-2xl border border-slate-700 text-slate-300 font-semibold hover:bg-slate-800">
                        Open Caregivers
                    </a>

                    <a href="{{ route('facility.visits.index') }}"
                       class="block w-full text-center px-4 py-4 rounded-2xl border border-slate-700 text-slate-300 font-semibold hover:bg-slate-800">
                        Open Visits
                    </a>

                    <a href="{{ route('facility.providers.index') }}"
                       class="block w-full text-center px-4 py-4 rounded-2xl border border-slate-700 text-slate-300 font-semibold hover:bg-slate-800">
                        Open Providers
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
