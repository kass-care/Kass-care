@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-6">
        <a href="{{ route('facility.patients.index') }}"
           class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
            ← Back to Facility Patients
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-700 to-blue-600 text-white">
            <p class="text-xs uppercase tracking-[0.3em] text-indigo-100 font-semibold">KASS Care Facility</p>
            <h1 class="mt-2 text-3xl font-bold">Add New Patient</h1>
            <p class="mt-2 text-sm text-indigo-100">
                Register a new resident under the current facility. This patient will be isolated to this facility automatically.
            </p>
        </div>

        <div class="p-8">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-4">
                    <h2 class="text-sm font-bold text-red-700 mb-2">Please fix the following:</h2>
                    <ul class="list-disc pl-5 text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('facility.patients.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="first_name"
                               value="{{ old('first_name') }}"
                               required
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Enter first name">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="last_name"
                               value="{{ old('last_name') }}"
                               required
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Enter last name">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Date of Birth
                        </label>
                        <input type="date"
                               name="date_of_birth"
                               value="{{ old('date_of_birth') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Gender
                        </label>
                        <select name="gender"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select gender</option>
                            <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Room Number
                        </label>
                        <input type="text"
                               name="room_number"
                               value="{{ old('room_number') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Enter room number">
                    </div>
                </div>

                <div class="mt-8 rounded-3xl border border-red-100 bg-red-50 p-6">
                    <h2 class="text-xl font-black text-slate-900 mb-2">Clinical Snapshot Details</h2>
                    <p class="text-sm text-slate-600 mb-5">
                        These fields appear in the provider Clinical Snapshot.
                    </p>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Allergies
                            </label>
                            <textarea name="allergies"
                                      rows="3"
                                      class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-400"
                                      placeholder="Example: Penicillin, peanuts, latex">{{ old('allergies') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Psychiatrist</label>
                                <input type="text" name="psychiatrist" value="{{ old('psychiatrist') }}"
                                       class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       placeholder="Name or clinic">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Cardiologist</label>
                                <input type="text" name="cardiologist" value="{{ old('cardiologist') }}"
                                       class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       placeholder="Name or clinic">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Primary Care Provider</label>
                                <input type="text" name="primary_care_provider" value="{{ old('primary_care_provider') }}"
                                       class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       placeholder="Name or clinic">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pharmacy</label>
                                <input type="text" name="pharmacy" value="{{ old('pharmacy') }}"
                                       class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       placeholder="Pharmacy name">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Patient Photo
                    </label>

                    <input type="file"
                           name="photo"
                           accept="image/*"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-white">
                </div>

                <div class="pt-6 flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 shadow">
                        Save Patient
                    </button>

                    <a href="{{ route('facility.patients.index') }}"
                       class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold px-6 py-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
