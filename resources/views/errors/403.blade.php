<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - KASS Care</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-6">
    <div class="max-w-xl w-full bg-white shadow-xl rounded-2xl border border-gray-200 p-10 text-center">
        <div class="text-5xl mb-4">🔒</div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Access denied</h1>
        <p class="text-gray-600 mb-8">
            You do not have permission to view this page.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow hover:bg-indigo-700 transition">
                Go to Dashboard
            </a>

            <a href="{{ url()->previous() }}"
               class="inline-flex items-center justify-center rounded-xl bg-gray-200 px-5 py-3 text-sm font-bold text-gray-800 hover:bg-gray-300 transition">
                Go Back
            </a>
        </div>
    </div>
</body>
</html>
