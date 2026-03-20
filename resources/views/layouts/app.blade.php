<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KASSCare</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div>
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-indigo-700">
                    KASSCare
                </a>
            </div>

            @auth
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-700">
                        {{ Auth::user()->name ?? 'User' }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                            Logout
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <main class="py-8">
        @yield('content')
    </main>
</body>
</html>
