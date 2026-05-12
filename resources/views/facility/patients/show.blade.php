@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900">
                Patient Profile
            </h1>

            <p class="text-gray-500 mt-1">
                Facility patient workspace and document vault
            </p>
        </div>

        <a href="{{ route('facility.patients.index') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700">
            Back to Patients
        </a>
    </div>

    {{-- Patient Overview --}}
    <div class="bg-white shadow rounded-2xl p-6 space-y-4 border border-gray-100">

        <div>
            <strong>Name:</strong>
            {{ $patient->name ?? 'N/A' }}
        </div>

        <div>
            <strong>Date of Birth:</strong>
            {{ $patient->dob ?? 'Not provided' }}
        </div>

        <div>
            <strong>Facility ID:</strong>
            {{ $patient->facility_id }}
        </div>

        <div>
            <strong>Patient ID:</strong>
            {{ $patient->id }}
        </div>

    </div>
{{-- Medication / MAR Setup --}}
<div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow">
    <h2 class="text-2xl font-black text-slate-900">
        💊 Medication / Supplement Request
    </h2>

    <p class="mt-2 text-sm font-semibold text-slate-600">
        Add a medication, vitamin, or supplement request for provider approval before it appears in caregiver eMAR.
    </p>

    <a href="{{ route('medications.create', $patient->id) }}"
       class="mt-5 inline-flex rounded-2xl bg-amber-500 px-6 py-3 text-sm font-black text-slate-950 hover:bg-amber-400">
        Add Medication / Supplement
    </a>
</div>

    {{-- Document Vault --}}
    <div class="bg-white shadow rounded-2xl p-6 border border-gray-100 mt-6">

        <div class="mb-5">
            <h2 class="text-2xl font-black text-slate-900">
                📁 Patient Document Vault
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Store generated PDFs, care plans, hospital records, signed forms, and survey-ready documentation.
            </p>
        </div>

        {{-- Upload Form --}}
        <form method="POST"
              action="{{ route('provider.patient-documents.store') }}"
              enctype="multipart/form-data"
              class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 mb-6">

            @csrf

            <input type="hidden"
                   name="patient_id"
                   value="{{ $patient->id }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Document Title
                    </label>

                    <input type="text"
                           name="title"
                           required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
                           placeholder="Example: Physician Order">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Category
                    </label>

                    <select name="category"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">

                        <option value="General">General</option>
                        <option value="Care Plan">Care Plan</option>
                        <option value="Provider Note">Provider Note</option>
                        <option value="Medication / MAR">Medication / MAR</option>
                        <option value="Hospital Record">Hospital Record</option>
                        <option value="Consent Form">Consent Form</option>
                        <option value="Lab Result">Lab Result</option>
                        <option value="State Survey">State Survey</option>

                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Upload File
                    </label>

                    <input type="file"
                           name="document"
                           required
                           class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm">
                </div>

            </div>

            <button type="submit"
                    class="mt-5 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-black text-white hover:bg-emerald-700">
                Upload to Patient Vault
            </button>

        </form>

        {{-- Existing Documents --}}
        <div class="space-y-3">

            @forelse($patient->documents as $document)

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 rounded-2xl border border-gray-200 bg-gray-50 p-4">

                    <div>
                        <p class="font-black text-gray-900">
                            {{ $document->title }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ $document->category ?? 'General' }}
                            · Uploaded {{ optional($document->created_at)->format('M d, Y g:i A') }}

                            @if($document->uploader)
                                · By {{ $document->uploader->name }}
                            @endif
                        </p>
                    </div>

                    <div class="flex gap-2">

                        <a href="{{ route('provider.patient-documents.download', $document->id) }}"
                           class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700">
                            Download
                        </a>

                        <form method="POST"
                              action="{{ route('provider.patient-documents.destroy', $document->id) }}"
                              onsubmit="return confirm('Delete this document?');">

                            @csrf
                            @method('DELETE')

                            <button class="rounded-xl bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700">
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 p-8 text-center">

                    <p class="font-bold text-gray-700">
                        No documents uploaded yet.
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Generated PDFs and uploaded files will appear here.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection
