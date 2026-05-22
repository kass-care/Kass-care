@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="rounded-3xl bg-slate-900 p-8 text-white shadow-2xl">
        <p class="text-xs uppercase tracking-[0.35em] font-black text-cyan-300">
            KASSCARE COMPLIANCE VAULT
        </p>

        <h1 class="mt-3 text-4xl font-black">
            Compliance Document Upload Center
        </h1>

        <p class="mt-3 max-w-3xl text-slate-300 text-lg">
            Upload AFH license, CPR cards, TB records, insurance documents, emergency plans,
            staff files, resident forms, and inspection evidence.
        </p>
    </div>
         <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="rounded-3xl border border-red-200 bg-red-50 p-6 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-red-700 font-black">
            Expired Documents
        </p>

        <h2 class="mt-4 text-5xl font-black text-red-700">
            {{ $expiredDocuments->count() }}
        </h2>

        <p class="mt-3 text-sm text-red-600 font-semibold">
            Documents requiring immediate renewal.
        </p>
    </div>

    <div class="rounded-3xl border border-yellow-200 bg-yellow-50 p-6 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-yellow-700 font-black">
            Expiring Within 30 Days
        </p>

        <h2 class="mt-4 text-5xl font-black text-yellow-700">
            {{ $expiringSoonDocuments->count() }}
        </h2>

        <p class="mt-3 text-sm text-yellow-700 font-semibold">
            Compliance documents approaching expiration.
        </p>
    </div>

</div>
    <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <h2 class="text-2xl font-black text-slate-900 mb-6">
            Upload Compliance Document
        </h2>

        <form method="POST" action="{{ route('facility.compliance-documents.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Document Title</label>
                    <input type="text" name="title" required
                           placeholder="Example: Current AFH License"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Category</label>
                    <select name="category"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm">
                        <option value="">Select category</option>
                        <option value="Administrative Binder">Administrative Binder</option>
                        <option value="Staff Files">Staff Files</option>
                        <option value="Resident Files">Resident Files</option>
                        <option value="Medication Records">Medication Records</option>
                        <option value="Safety & Environment">Safety & Environment</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Attach to Readiness Item</label>
                    <select name="readiness_item_id"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm">
                        <option value="">Optional</option>
                        @foreach($readinessItems as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->category }} — {{ $item->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Expiration Date</label>
                    <input type="date" name="expires_at"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm">
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-black text-slate-700 mb-2">Document File</label>
                <input type="file" name="document" required
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm">
            </div>

            <div class="mt-6">
                <label class="block text-sm font-black text-slate-700 mb-2">Notes</label>
                <textarea name="notes" rows="4"
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm"></textarea>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="rounded-2xl bg-cyan-700 px-8 py-4 text-lg font-black text-white shadow-xl hover:bg-cyan-800">
                    📤 Upload Document
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-200 px-8 py-5">
            <h2 class="text-2xl font-black text-slate-900">
                Uploaded Compliance Documents
            </h2>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($documents as $document)
                <div class="p-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xl font-black text-slate-900">
                            {{ $document->title }}
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-500">
                            {{ $document->category ?? 'Uncategorized' }}
                            @if($document->readinessItem)

                                • {{ $document->readinessItem->title }}
                            @endif
                        </p>
                                @php
    $isExpired = $document->expires_at && $document->expires_at->isPast();

    $isExpiringSoon = $document->expires_at
        && !$document->expires_at->isPast()
        && now()->diffInDays($document->expires_at, false) <= 30;
@endphp

<div class="mt-3">
    @if($isExpired)
        <span class="rounded-full bg-red-100 px-4 py-1 text-xs font-black text-red-700">
            EXPIRED
        </span>
    @elseif($isExpiringSoon)
        <span class="rounded-full bg-yellow-100 px-4 py-1 text-xs font-black text-yellow-700">
            EXPIRING SOON
        </span>
    @else
        <span class="rounded-full bg-emerald-100 px-4 py-1 text-xs font-black text-emerald-700">
            COMPLIANT
        </span>
    @endif
</div>
                        @if($document->expires_at)
                            <p class="mt-2 text-sm font-bold text-orange-600">
                                Expires: {{ $document->expires_at->format('M d, Y') }}
                            </p>
                        @endif

                        @if($document->notes)
                            <p class="mt-2 text-slate-600">
                                {{ $document->notes }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                           class="rounded-2xl bg-indigo-700 px-5 py-3 font-black text-white hover:bg-indigo-800">
                            View File
                        </a>

                        <form method="POST" action="{{ route('facility.compliance-documents.destroy', $document) }}">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    onclick="return confirm('Delete this compliance document?')"
                                    class="rounded-2xl bg-red-700 px-5 py-3 font-black text-white hover:bg-red-800">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <p class="text-lg font-bold text-slate-500">
                        No compliance documents uploaded yet.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
