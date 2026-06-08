@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4">

        <div class="mb-8 rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-900 to-cyan-800 p-8 text-white shadow-xl">
            <div class="flex flex-wrap items-center justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-cyan-200">
                        KassCare Legal Document Library
                    </p>

                    <h1 class="mt-2 text-4xl font-black">
                        📚 Document Templates
                    </h1>

                    <p class="mt-2 text-cyan-100">
                        Build state-specific admission agreements, consents, disclosures and legal forms.
                    </p>
                </div>

                <a href="{{ route('facility.document-templates.create') }}"
                   class="rounded-2xl bg-white px-5 py-3 font-black text-indigo-800 shadow-lg">
                    + New Template
                </a>

            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-8 grid gap-6 md:grid-cols-3">
            @foreach($states as $state)
                @php
                    $matrix = $complianceMatrix[$state];
                    $percent = $matrix['total_required'] > 0
                        ? round(($matrix['total_loaded'] / $matrix['total_required']) * 100)
                        : 0;
                @endphp

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.25em] text-slate-500">
                                State Package
                            </p>

                            <h2 class="mt-2 text-3xl font-black text-slate-900">
                                {{ $state }}
                            </h2>
                        </div>
			<a href="{{ route('facility.document-templates.create') }}?state={{ $state }}"
   class="mt-3 inline-block rounded-xl bg-indigo-700 px-3 py-2 text-xs font-black text-white">
    ➕ Add Missing Document
</a>

                        <div class="rounded-full bg-indigo-100 px-4 py-2 text-sm font-black text-indigo-800">
                            {{ $percent }}%
                        </div>
                    </div>

                    <div class="mt-5 space-y-2">
                        @foreach($requiredCategories as $category)
                            @php
                                $loaded = in_array($category, $matrix['loaded']);
                            @endphp

                            <div class="flex items-center justify-between rounded-xl {{ $loaded ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }} px-4 py-2 text-sm font-bold">
                                <span>{{ $category }}</span>
                                <span>{{ $loaded ? '✅' : '❌' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rounded-3xl bg-white shadow-lg overflow-hidden">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-xl font-black text-slate-900">
                    Available Templates
                </h2>
            </div>

            <div class="divide-y divide-slate-100">

                @forelse($templates as $template)

                    <div class="p-6 flex flex-wrap items-center justify-between gap-4">

                        <div>
                            <h3 class="text-lg font-black text-slate-900">
                                {{ $template->title }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $template->state ?? 'General' }}
                                •
                                {{ $template->category ?? 'Template' }}
                            </p>
                        </div>

                        <a href="{{ route('facility.document-templates.show', $template) }}"
                           class="rounded-xl bg-indigo-700 px-4 py-2 font-bold text-white">
                            Open
                        </a>

                    </div>

                @empty

                    <div class="p-12 text-center">

                        <h3 class="text-xl font-black text-slate-700">
                            No Templates Yet
                        </h3>

                        <p class="mt-2 text-slate-500">
                            Create your first Washington, Oregon or California template.
                        </p>

                    </div>

                @endforelse

            </div>
        </div>

        <div class="mt-6">
            {{ $templates->links() }}
        </div>

    </div>
</div>
@endsection
