@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20 font-sans">
    <div class="container mx-auto px-6 pt-10">
        <div class="mb-10">
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">SaaS <span class="text-indigo-600">Core</span></h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-2">Engine Status: Online</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-8 rounded-[2.5rem] shadow-2xl text-white">
                <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest">Clients</p>
                <h3 class="text-6xl font-black mt-4 leading-none">{{ $clientCount ?? 0 }}</h3>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-8 rounded-[2.5rem] shadow-2xl text-white">
                <p class="text-emerald-50 text-[10px] font-black uppercase tracking-widest">Staff</p>
                <h3 class="text-6xl font-black mt-4 leading-none">{{ $caregiverCount ?? 0 }}</h3>
            </div>
            <div class="bg-gradient-to-br from-amber-400 to-orange-500 p-8 rounded-[2.5rem] shadow-2xl text-white">
                <p class="text-orange-50 text-[10px] font-black uppercase tracking-widest">Visits</p>
                <h3 class="text-6xl font-black mt-4 leading-none">{{ $visitCount ?? 0 }}</h3>
            </div>
            <div class="bg-gradient-to-br from-rose-500 to-red-600 p-8 rounded-[2.5rem] shadow-2xl text-white">
                <p class="text-rose-50 text-[10px] font-black uppercase tracking-widest">Alerts</p>
                <h3 class="text-6xl font-black mt-4 leading-none">{{ $alertCount ?? 0 }}</h3>
            </div>
        </div>

        <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-6">Quick Launch Command</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            
            <a href="{{ route('clients.create') }}" class="flex flex-col items-center p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                <span class="text-3xl mb-3 group-hover:scale-125 transition-transform">👥</span>
                <span class="text-[9px] font-black text-slate-500 uppercase">Add Client</span>
            </a>

            <a href="{{ route('caregivers.create') }}" class="flex flex-col items-center p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                <span class="text-3xl mb-3 group-hover:scale-125 transition-transform">👩‍⚕️</span>
                <span class="text-[9px] font-black text-slate-500 uppercase">New Staff</span>
            </a>

            <a href="{{ route('calendar') }}" class="flex flex-col items-center p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                <span class="text-3xl mb-3 group-hover:scale-125 transition-transform">📅</span>
                <span class="text-[9px] font-black text-slate-500 uppercase">Calendar</span>
            </a>

        </div>
    </div>
</div>
@endsection
