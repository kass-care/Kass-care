<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KASS CARE SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans">
    <nav class="bg-slate-900 text-white shadow-2xl sticky top-0 z-50">
        <div class="container mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-8">
                <a href="/dashboard" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center font-black">K</div>
                    <span class="font-black tracking-tighter text-2xl uppercase">KASS<span class="text-indigo-400">CARE</span></span>
                </a>
                <div class="hidden lg:flex items-center gap-6 text-[10px] font-black uppercase tracking-widest">
                    <a href="{{ route('facilities.index') }}" class="hover:text-indigo-400">Facilities</a>
                    <a href="{{ route('caregivers.index') }}" class="hover:text-indigo-400">Caregivers</a>
                    <a href="{{ route('calendar') }}" class="hover:text-indigo-400">Calendar</a>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <span class="text-2xl">🚨</span>
                <a href="{{ route('visits.create') }}" class="relative z-[100] bg-indigo-600 px-6 py-2.5 rounded-xl text-[10px] font-bold text-white uppercase tracking-widest shadow-lg hover:bg-indigo-700 transition-all">
    + NEW VISIT
</a>
            </div>
        </div>
    </nav>

    <main>@yield('content')</main>
</body>
</html>
