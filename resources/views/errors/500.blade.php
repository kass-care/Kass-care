@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-xl rounded-3xl border border-slate-200 bg-white shadow-xl p-8 md:p-10 text-center">
        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-red-50 border border-red-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8 14A1 1 0 003.14 19h17.72a1 1 0 00.85-1.5l-8-14a1 1 0 00-1.72 0z" />
            </svg>
        </div>

        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-red-500 mb-3">
            KASS CARE
        </p>

        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
            Something went wrong.
        </h1>

        <p class="text-base md:text-lg text-slate-600 mb-2">
            Our team has been notified.
        </p>

        <p class="text-sm text-slate-500 mb-8">
            Please try again in a moment or return to your dashboard.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                Go to Dashboard
            </a>

            <a href="{{ url()->previous() }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">
                Go Back
            </a>
        </div>
    </div>
</div>
@endsection
