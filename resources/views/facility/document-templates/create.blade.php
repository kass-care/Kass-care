@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-5xl px-4">

        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('facility.document-templates.index') }}"
               class="text-sm font-bold text-indigo-700 hover:text-indigo-900">
                ← Back to Templates
            </a>

            <a href="{{ route('facility.admin.home') }}"
               class="text-sm font-bold text-slate-600 hover:text-slate-900">
                Dashboard
            </a>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-xl">
            <div class="bg-gradient-to-r from-slate-950 via-indigo-900 to-cyan-800 px-8 py-6 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-cyan-200">
                    KassCare Legal Document Library
                </p>

                <h1 class="mt-2 text-3xl font-black">
                    📚 Create Document Template
                </h1>

                <p class="mt-2 text-sm text-cyan-100">
                    Add reusable agreements, consent forms, disclosures, and admission documents.
                </p>
            </div>

            <form method="POST" action="{{ route('facility.document-templates.store') }}" class="space-y-5 p-8">
                @csrf

                @if($errors->any())
                    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Template Title</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="Washington AFH Admission Agreement"
                           required
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">State</label>
                        <select name="state" class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                            <option value="">General / Not State Specific</option>
                            <option value="WA">Washington</option>
                            <option value="OR">Oregon</option>
                            <option value="CA">California</option>
                            <option value="TX">Texas</option>
                            <option value="FL">Florida</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Category</label>
                        <select name="category" class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                            <option value="Admission Agreement">Admission Agreement</option>
                            <option value="Resident Rights">Resident Rights</option>
                            <option value="HIPAA Consent">HIPAA Consent</option>
                            <option value="Medication Consent">Medication Consent</option>
                            <option value="Financial Agreement">Financial Agreement</option>
                            <option value="House Rules">House Rules</option>
                            <option value="Emergency Contact">Emergency Contact</option>
                            <option value="Release of Information">Release of Information</option>
                            <option value="Transportation Consent">Transportation Consent</option>
                            <option value="Photo Consent">Photo Consent</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Template Content</label>
                    <textarea name="content"
                              rows="18"
                              required
                              class="w-full rounded-2xl border border-slate-300 px-4 py-3"
                              placeholder="Paste or write the template content here...">{{ old('content') }}</textarea>

                    <p class="mt-2 text-xs text-slate-500">
                        Later we will add smart merge tags like [[resident_name]], [[facility_name]], [[monthly_rate]], and [[admission_date]].
                    </p>
                </div>

                <label class="flex items-center gap-2 text-sm font-bold text-slate-700">
                    <input type="checkbox" name="active" value="1" checked>
                    Active template
                </label>

                <button type="submit"
                        class="rounded-2xl bg-indigo-700 px-6 py-3 font-black text-white shadow hover:bg-indigo-800">
                    Save Template
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
