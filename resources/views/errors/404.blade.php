<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - KASS Care</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-6">

    <div class="max-w-xl w-full bg-white shadow-xl rounded-2xl border border-gray-200 p-10 text-center">

        <div class="text-5xl mb-4">🔎</div>

        <h1 class="text-3xl font-extrabold text-gray-900 mb-3">
            Page not found
        </h1>

        <p class="text-gray-600 mb-8">
            The page you are looking for does not exist or may have been moved.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">

            {{-- SMART BUTTON --}}
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
               class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow hover:bg-indigo-700 transition">
                {{ auth()->check() ? 'Go to Dashboard' : 'Login' }}
            </a>

            {{-- BACK BUTTON --}}
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center justify-center rounded-xl bg-gray-200 px-5 py-3 text-sm font-bold text-gray-800 hover:bg-gray-300 transition">
                Go Back
            </a>

        </div>

    </div>

</body>
</html>
