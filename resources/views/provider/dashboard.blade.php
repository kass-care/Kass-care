@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Provider Dashboard</h1>
        <p class="text-slate-600 mb-8">Welcome to the Kass Care provider panel.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('provider.calendar') }}"
               class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition block">
                <h2 class="text-xl font-semibold text-slate-900">Provider Calendar</h2>
                <p class="text-slate-600 mt-2">View scheduled, completed, and missed visits.</p>
            </a>
        </div>
    </div>
</div>
@endsection
