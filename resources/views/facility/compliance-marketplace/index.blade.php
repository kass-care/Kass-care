@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-6xl px-4">

        <div class="mb-8 rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-900 to-emerald-800 p-8 text-white shadow-xl">
            <p class="text-xs font-black uppercase tracking-[0.3em] text-emerald-200">
                KassCare Compliance Marketplace
            </p>

            <h1 class="mt-2 text-4xl font-black">
                📦 State Compliance Packages
            </h1>

            <p class="mt-2 text-emerald-100">
                Install starter document packages into your KassCare document library.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            @foreach($packages as $state => $package)
                <div class="rounded-3xl bg-white p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-black text-slate-900">{{ $state }}</h2>
                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-black text-indigo-800">
                            {{ count($package['documents']) }} docs
                        </span>
                    </div>

                    <h3 class="mt-4 text-lg font-black text-slate-900">
                        {{ $package['name'] }}
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        {{ $package['description'] }}
                    </p>

                    <div class="mt-5 space-y-2">
                        @foreach($package['documents'] as $document)
                            <div class="rounded-xl bg-slate-50 px-4 py-2 text-sm font-bold text-slate-700">
                                📄 {{ $document['category'] }}
                            </div>
                        @endforeach
                    </div>

                    <form method="POST"
                          action="{{ route('facility.compliance-marketplace.install', $state) }}"
                          class="mt-6">
                        @csrf

                        <button type="submit"
                                class="w-full rounded-2xl bg-emerald-600 px-5 py-3 font-black text-white shadow hover:bg-emerald-700">
                            Install {{ $state }} Package
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <a href="{{ route('facility.document-templates.index') }}"
               class="font-black text-indigo-700 hover:text-indigo-900">
                ← Back to Document Library
            </a>
        </div>

    </div>
</div>
@endsection
