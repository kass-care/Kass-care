@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-5xl px-4">

        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('facility.document-templates.index') }}"
               class="text-sm font-bold text-indigo-700 hover:text-indigo-900">
                ← Back to Templates
            </a>

            <span class="rounded-full bg-green-100 px-4 py-2 text-xs font-black uppercase text-green-800">
                {{ $documentTemplate->active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="rounded-3xl bg-white p-8 shadow-xl">
            <p class="text-xs font-black uppercase tracking-[0.3em] text-indigo-600">
                KassCare Legal Document Library
            </p>

            <h1 class="mt-2 text-4xl font-black text-slate-900">
                {{ $documentTemplate->title }}
            </h1>

            <p class="mt-3 text-sm font-semibold text-slate-500">
                {{ $documentTemplate->state ?? 'General' }}
                •
                {{ $documentTemplate->category ?? 'Template' }}
            </p>

            <div class="mt-6 flex flex-wrap gap-3">

                <form method="POST"
                      action="{{ route('facility.document-templates.generate-packet', $documentTemplate) }}">
                    @csrf

                    <button type="submit"
                            class="rounded-2xl bg-emerald-700 px-6 py-3 font-black text-white shadow hover:bg-emerald-800">
                        🚀 Generate Admission Packet
                    </button>
                </form>

                <a href="{{ route('facility.document-templates.edit', $documentTemplate) }}"
                   class="rounded-2xl bg-indigo-700 px-6 py-3 font-black text-white shadow hover:bg-indigo-800">
                    ✏️ Edit Template
                </a>

                <form method="POST"
                      action="{{ route('facility.document-templates.destroy', $documentTemplate) }}"
                      onsubmit="return confirm('Delete this template? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="rounded-2xl bg-red-700 px-6 py-3 font-black text-white shadow hover:bg-red-800">
                        🗑 Delete Template
                    </button>
                </form>

            </div>

            <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <h2 class="mb-4 text-xl font-black text-slate-900">
                    Template Content
                </h2>

                <div class="whitespace-pre-line leading-7 text-slate-700">
                    {{ $documentTemplate->content }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
