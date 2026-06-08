@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-5xl px-4">

        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('facility.document-templates.show', $documentTemplate) }}"
               class="text-sm font-bold text-indigo-700 hover:text-indigo-900">
                ← Back to Template
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
                    ✏️ Edit Document Template
                </h1>

                <p class="mt-2 text-sm text-cyan-100">
                    Update reusable agreements, consent forms, disclosures, and admission documents.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('facility.document-templates.update', $documentTemplate) }}"
                  class="space-y-6 p-8">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Template Title</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $documentTemplate->title) }}"
                           placeholder="Washington AFH Admission Agreement"
                           required
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">State</label>
                        <select name="state" class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                            <option value="" @selected(old('state', $documentTemplate->state) == '')>General / Not State Specific</option>
                            <option value="WA" @selected(old('state', $documentTemplate->state) == 'WA')>Washington</option>
                            <option value="OR" @selected(old('state', $documentTemplate->state) == 'OR')>Oregon</option>
                            <option value="CA" @selected(old('state', $documentTemplate->state) == 'CA')>California</option>
                            <option value="TX" @selected(old('state', $documentTemplate->state) == 'TX')>Texas</option>
                            <option value="FL" @selected(old('state', $documentTemplate->state) == 'FL')>Florida</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Category</label>
                        <select name="category" class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                            <option value="Admission Agreement" @selected(old('category', $documentTemplate->category) == 'Admission Agreement')>Admission Agreement</option>
                            <option value="Resident Rights" @selected(old('category', $documentTemplate->category) == 'Resident Rights')>Resident Rights</option>
                            <option value="HIPAA Notice" @selected(old('category', $documentTemplate->category) == 'HIPAA Notice')>HIPAA Notice</option>
                            <option value="Medication Consent" @selected(old('category', $documentTemplate->category) == 'Medication Consent')>Medication Consent</option>
                            <option value="Financial Agreement" @selected(old('category', $documentTemplate->category) == 'Financial Agreement')>Financial Agreement</option>
                            <option value="House Rules" @selected(old('category', $documentTemplate->category) == 'House Rules')>House Rules</option>
                            <option value="Emergency Contact" @selected(old('category', $documentTemplate->category) == 'Emergency Contact')>Emergency Contact</option>
                            <option value="Release of Information" @selected(old('category', $documentTemplate->category) == 'Release of Information')>Release of Information</option>
                            <option value="Transportation Consent" @selected(old('category', $documentTemplate->category) == 'Transportation Consent')>Transportation Consent</option>
                            <option value="Photo Consent" @selected(old('category', $documentTemplate->category) == 'Photo Consent')>Photo Consent</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Template Content</label>
                    <textarea name="content"
                              rows="18"
                              required
                              class="w-full rounded-2xl border border-slate-300 px-4 py-3"
                              placeholder="Paste or write the template content here...">{{ old('content', $documentTemplate->content) }}</textarea>

                    <p class="mt-2 text-xs text-slate-500">
                        Smart merge tags include: {{ '{{resident_name}}' }}, {{ '{{facility_name}}' }}, {{ '{{monthly_rate}}' }}, {{ '{{admission_date}}' }}, {{ '{{representative_name}}' }}, and {{ '{{representative_relationship}}' }}.
                    </p>
                </div>

                <label class="flex items-center gap-2 text-sm font-bold text-slate-700">
                    <input type="checkbox"
                           name="active"
                           value="1"
                           @checked(old('active', $documentTemplate->active))>
                    Active template
                </label>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit"
                            class="rounded-2xl bg-indigo-700 px-6 py-3 font-black text-white shadow hover:bg-indigo-800">
                        Save Changes
                    </button>

                    <a href="{{ route('facility.document-templates.show', $documentTemplate) }}"
                       class="rounded-2xl border border-slate-300 px-6 py-3 font-black text-slate-700 hover:bg-slate-100">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
