<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KASSCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 text-gray-900 font-sans antialiased">

@php
    $role = auth()->check() ? auth()->user()->role : null;

    $dashboardRoute = match ($role) {
        'super_admin' => 'admin.dashboard',
        'admin' => 'dashboard',
        'provider' => 'provider.dashboard',
        'caregiver' => 'caregiver.dashboard',
        default => 'login',
    };
@endphp

<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between">
            <a href="{{ Route::has($dashboardRoute) ? route($dashboardRoute) : url('/') }}"
               class="text-2xl font-extrabold text-indigo-700">
                KASSCare
            </a>

            @auth
                <div class="flex items-center gap-4 text-sm font-semibold">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-700">Dashboard</a>
                                       
@if($role === 'provider' && Route::has('coding.assistant'))
    <a href="{{ route('coding.assistant') }}"
       class="px-3 py-2 text-sm font-semibold text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-700">
        🧠 Coding Assistant
    </a>
@endif
                    @if(Route::has('alerts.index'))
                        <a href="{{ route('alerts.index') }}" class="text-gray-700 hover:text-indigo-700">Alerts</a>
                    @endif

                    @if($role === 'provider' && Route::has('provider.notes.index'))
                        <a href="{{ route('provider.notes.index') }}" class="text-gray-700 hover:text-indigo-700">Notes</a>
                    @endif

                    @if($role === 'provider' && Route::has('provider.compliance'))
                        <a href="{{ route('provider.compliance') }}" class="text-gray-700 hover:text-indigo-700">Compliance</a>
                    @endif
                     @if($role === 'provider' && Route::has('provider.claims.index'))
    <a href="{{ route('provider.claims.index') }}"
       class="px-3 py-2 text-sm font-semibold text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-700">
        Claims
    </a>
@endif

                    <span class="text-gray-600">{{ auth()->user()->name ?? 'User' }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-lg bg-rose-50 px-4 py-2 text-rose-700 hover:bg-rose-100">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}"
                   class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700">
                    Sign In
                </a>
            @endauth
        </div>
    </div>
</nav>

@auth
    <div class="bg-gradient-to-r from-indigo-700 via-purple-700 to-indigo-800 text-white text-sm">
        <div class="mx-auto max-w-7xl px-4 py-2 text-center font-medium">
            🚀 KASSCare Pilot Program — Thank you for helping shape the future of healthcare documentation.
        </div>
    </div>
@endauth

<main class="min-h-screen">
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="mt-20 border-t bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-8 text-center text-sm text-gray-600">
        <div class="font-semibold text-gray-700">KASS CARE PLATFORM</div>
        <div class="mt-1">&copy; {{ date('Y') }} KASS MTV USA LLC</div>
        <div class="mt-2 text-xs text-gray-500">
            HIPAA-aware platform for healthcare documentation and care coordination.
        </div>
        <div class="mt-4 flex justify-center gap-6">
            @if(Route::has('terms'))
                <a href="{{ route('terms') }}" class="hover:text-indigo-600">Terms</a>
            @endif
            <a href="#" class="hover:text-indigo-600">Privacy</a>
            <a href="#" class="hover:text-indigo-600">System Status</a>
        </div>
        <span class="mt-4 inline-block text-gray-400">Version 1.0</span>
    </div>
</footer>

</body>
</html>
