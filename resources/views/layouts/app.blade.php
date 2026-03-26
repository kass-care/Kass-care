<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kass Care</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-100 text-gray-900 font-sans antialiased">
    <nav class="bg-white border-b-2 border-gray-300 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-extrabold text-indigo-700 tracking-tight">
                        KASSCare
                    </a>

                    @auth
                        @php
                            $role = auth()->user()->role ?? null;

                            $dashboardRoute = match ($role) {
                                'super_admin' => 'admin.dashboard',
                                'admin' => 'admin.dashboard',
                                'provider' => 'provider.dashboard',
                                'caregiver' => 'caregiver.dashboard',
                                default => 'dashboard',
                            };
                        @endphp

                        <div class="hidden md:flex items-center space-x-4">
                            <a href="{{ route($dashboardRoute) }}"
                               class="px-3 py-2 text-sm font-semibold text-gray-700 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition">
                                Dashboard
                            </a>

                            @if(in_array($role, ['admin', 'super_admin']))
                                <a href="{{ route('billing.index') }}"
                                   class="px-3 py-2 text-sm font-semibold text-gray-700 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition">
                                    Billing
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <span class="hidden md:inline text-sm font-medium text-gray-600">
                            {{ auth()->user()->name }}
                        </span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="bg-rose-50 text-rose-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-rose-100 transition">
                                Logout
                            </button>
                        </form>
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
