<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'KASSCare') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">
    @php
        $user = auth()->user();

        $isSuperAdmin = $user && $user->role === 'super_admin';
        $isAdmin = $user && $user->role === 'admin';
        $isProvider = $user && $user->role === 'provider';
        $isCaregiver = $user && $user->role === 'caregiver';

        $dashboardRoute = 'dashboard';

        if ($isSuperAdmin && \Illuminate\Support\Facades\Route::has('admin.dashboard')) {
            $dashboardRoute = 'admin.dashboard';
        } elseif ($isAdmin && \Illuminate\Support\Facades\Route::has('facility.admin.home')) {
            $dashboardRoute = 'facility.admin.home';
        } elseif ($isProvider && \Illuminate\Support\Facades\Route::has('provider.dashboard')) {
            $dashboardRoute = 'provider.dashboard';
        } elseif ($isCaregiver && \Illuminate\Support\Facades\Route::has('caregiver.dashboard')) {
            $dashboardRoute = 'caregiver.dashboard';
        }
    @endphp

    <nav class="bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-10">
                    <a href="{{ \Illuminate\Support\Facades\Route::has($dashboardRoute) ? route($dashboardRoute) : url('/') }}"
                       class="text-2xl font-extrabold tracking-tight text-green-700">
                        KASSCare
                    </a>

                    <div class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-700">
                        @if (\Illuminate\Support\Facades\Route::has($dashboardRoute))
                            <a href="{{ route($dashboardRoute) }}" class="hover:text-indigo-600 transition">
                                Dashboard
                            </a>
                        @endif

                        @if ($isCaregiver)
                            @if (\Illuminate\Support\Facades\Route::has('caregiver.visits'))
                                <a href="{{ route('caregiver.visits') }}" class="hover:text-indigo-600 transition">
                                    Visits
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('caregiver.care-logs.index'))
                                <a href="{{ route('caregiver.care-logs.index') }}" class="hover:text-indigo-600 transition">
                                    Care Logs
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('alerts.index'))
                                <a href="{{ route('alerts.index') }}" class="hover:text-indigo-600 transition">
                                    Alerts
                                </a>
                            @endif
                        @endif

                        @if ($isProvider)
                            @if (\Illuminate\Support\Facades\Route::has('provider.calendar'))
                                <a href="{{ route('provider.calendar') }}" class="hover:text-indigo-600 transition">
                                    Calendar
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('provider.alerts'))
                                <a href="{{ route('provider.alerts') }}" class="hover:text-indigo-600 transition">
                                    Alerts
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('care-logs.index'))
                                <a href="{{ route('care-logs.index') }}" class="hover:text-indigo-600 transition">
                                    Care Logs
                                </a>
                            @endif
                        @endif

                        @if ($isAdmin)
                            @if (\Illuminate\Support\Facades\Route::has('facility.patients.index'))
                                <a href="{{ route('facility.patients.index') }}" class="hover:text-indigo-600 transition">
                                    Patients
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('facility.caregivers.index'))
                                <a href="{{ route('facility.caregivers.index') }}" class="hover:text-indigo-600 transition">
                                    Caregivers
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('facility.visits.index'))
                                <a href="{{ route('facility.visits.index') }}" class="hover:text-indigo-600 transition">
                                    Visits
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('billing.index'))
                                <a href="{{ route('billing.index') }}" class="hover:text-indigo-600 transition">
                                    Billing
                                </a>
                            @endif
                        @endif

                        @if ($isSuperAdmin)
                            @if (\Illuminate\Support\Facades\Route::has('admin.facilities.index'))
                                <a href="{{ route('admin.facilities.index') }}" class="hover:text-indigo-600 transition">
                                    Facilities
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('admin.providers.index'))
                                <a href="{{ route('admin.providers.index') }}" class="hover:text-indigo-600 transition">
                                    Providers
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('admin.caregivers.index'))
                                <a href="{{ route('admin.caregivers.index') }}" class="hover:text-indigo-600 transition">
                                    Caregivers
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('admin.activity.index'))
                                <a href="{{ route('admin.activity.index') }}" class="hover:text-indigo-600 transition">
                                    Activity
                                </a>
                            @endif
                        @endif

                        @if (!$isCaregiver && !$isProvider && !$isAdmin && !$isSuperAdmin)
                            @if (\Illuminate\Support\Facades\Route::has('alerts.index'))
                                <a href="{{ route('alerts.index') }}" class="hover:text-indigo-600 transition">
                                    Alerts
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <div class="hidden sm:flex items-center gap-2 text-sm text-slate-600">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-700 font-bold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </span>
                            <span>{{ auth()->user()->name }}</span>
                        </div>

                        @if (\Illuminate\Support\Facades\Route::has('logout'))
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center rounded-xl bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100 transition">
                                    Logout
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>
