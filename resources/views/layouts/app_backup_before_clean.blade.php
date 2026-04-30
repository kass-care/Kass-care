<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kass Care</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-gray-100 text-gray-900 font-sans antialiased">
@php
    $role = auth()->check() ? (auth()->user()->role ?? 'guest') : 'guest';

    $dashboardRoute = match ($role) {
        'super_admin' => 'admin.dashboard',
        'admin' => 'admin.dashboard',
        'provider' => 'provider.dashboard',
        'caregiver' => 'caregiver.dashboard',
        default => 'dashboard',
    };

    $themeColor = match ($role) {
        'provider' => 'indigo',
        'caregiver' => 'green',
        default => 'blue',
    };
@endphp

<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <div class="flex items-center gap-8">
                <a href="{{ Route::has($dashboardRoute) ? route($dashboardRoute) : url('/') }}"
                   class="text-2xl font-extrabold text-indigo-700">
                    KASSCare
                </a>

                @auth
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 text-sm font-semibold text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-700">
                            Dashboard
                        </a>

                        @if(Route::has('alerts.index'))
                            <a href="{{ route('alerts.index') }}"
                               class="px-3 py-2 text-sm font-semibold text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-700">
                                Alerts
                            </a>
                        @endif

                        @if($role === 'provider' && Route::has('provider.notes.index'))
                            <a href="{{ route('provider.notes.index') }}"
                               class="px-3 py-2 text-sm font-semibold text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-700">
                                Notes
                            </a>
                        @endif

                        @if($role === 'provider' && Route::has('provider.compliance'))
                            <a href="{{ route('provider.compliance') }}"
                               class="px-3 py-2 text-sm font-semibold text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-700">
                                Compliance
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <span class="hidden md:inline text-sm font-medium text-gray-600">
                        {{ auth()->user()?->name ?? 'User' }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-lg bg-rose-50 px-4 py-2 text-sm font-bold text-rose-700 hover:bg-rose-100">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700">
                        Login
                    </a>
                @endauth
            </div>

        </div>
    </div>
</nav>

@if(auth()->check())
    <div class="bg-gradient-to-r from-indigo-700 via-purple-700 to-indigo-800 text-white text-sm">
        <div class="mx-auto max-w-7xl px-4 py-2 text-center font-medium">
            🚀 KASSCare Pilot Program — Thank you for helping shape the future of healthcare documentation.
        </div>
    </div>
@endif

<main>
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <div class="font-bold mb-2">Please fix the following:</div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="mt-20 border-t bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-8 text-center text-sm text-gray-600">
        <div class="font-semibold text-gray-700">
            KASS CARE PLATFORM
        </div>

        <div class="mt-1">
            &copy; {{ date('Y') }} KASS MTV USA LLC
        </div>

        <div class="mt-2 text-xs text-gray-500">
            HIPAA-aware platform for healthcare documentation and care coordination.
        </div>

        <div class="mt-4 flex justify-center space-x-6">
            @if(Route::has('terms'))
                <a href="{{ route('terms') }}" class="hover:text-indigo-600">Terms</a>
            @endif
            <a href="#" class="hover:text-indigo-600">Privacy</a>
            <a href="#" class="hover:text-indigo-600">System Status</a>
        </div>

        <span class="mt-4 inline-block text-gray-400">
            Version 1.0
        </span>
    </div>
</footer>

</body>
</html>
