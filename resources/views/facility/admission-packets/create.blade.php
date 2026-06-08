@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('facility.admission-packets.index') }}"
               class="text-sm font-bold text-indigo-700 hover:text-indigo-900">
                ← Back to Admission Packets
            </a>

            <a href="{{ route('facility.admin.home') }}"
               class="text-sm font-bold text-slate-600 hover:text-slate-900">
                Dashboard
            </a>
        </div>

        <div class="overflow-hidden rounded-3xl border border-indigo-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-slate-950 via-indigo-900 to-cyan-800 px-8 py-6 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-200">
                    KassCare Admissions & E-Signature Center
                </p>

                <h1 class="mt-2 text-3xl font-black">
                    📝 New Admission Packet
                </h1>

                <p class="mt-2 text-sm text-cyan-100">
                    Create a resident admission agreement that can later be signed and stored in KassCare.
                </p>
            </div>

            <form method="POST" action="{{ route('facility.admission-packets.store') }}" class="space-y-6 p-8">
                @csrf
		<input type="hidden" name="template_id" value="{{ $selectedTemplate?->id }}">

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Package State</label>
                    <select name="package_state"
                            required
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                        <option value="">Select state package</option>
                        <option value="WA" @selected(old('package_state', $selectedTemplate?->state) == 'WA')>Washington</option>
                        <option value="OR" @selected(old('package_state', $selectedTemplate?->state) == 'OR')>Oregon</option>
                        <option value="CA" @selected(old('package_state', $selectedTemplate?->state) == 'CA')>California</option>
                    </select>
                    <p class="mt-2 text-xs text-slate-500">
                        KassCare will generate documents only from this state package.
                    </p>
                </div>

                @if($errors->any())
                    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Resident / Client</label>
                    <select name="client_id"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                        <option value="">Select existing resident or type name below</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name ?? trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? '')) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Resident Name</label>
                        <input type="text" name="resident_name" value="{{ old('resident_name') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3"
                               placeholder="Resident full name" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Admission Date</label>
                        <input type="date" name="admission_date" value="{{ old('admission_date') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Representative Name</label>
                        <input type="text" name="representative_name" value="{{ old('representative_name') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3"
                               placeholder="Family / POA / Guardian">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Relationship</label>
                        <input type="text" name="representative_relationship" value="{{ old('representative_relationship') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3"
                               placeholder="Daughter, son, POA...">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Monthly Rate</label>
                        <input type="text" name="monthly_rate" value="{{ old('monthly_rate') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3"
                               placeholder="$5,500/month">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Agreement Text</label>
		     <textarea name="document_text"
          rows="10"
          class="w-full rounded-2xl border border-slate-300 px-4 py-3"
          placeholder="Leave blank to use KassCare default admission agreement template.">{{ old('document_text', $selectedTemplate?->content) }}</textarea>		
                    <p class="mt-2 text-xs text-slate-500">
                        This can be customized later for each facility's official admission agreement.
                    </p>
                </div>

                <button type="submit"
                        class="rounded-2xl bg-indigo-700 px-6 py-3 font-black text-white shadow hover:bg-indigo-800">
                    Save Admission Packet
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
