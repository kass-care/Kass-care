@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Create Client</h1>
            <p class="text-sm text-slate-500 mt-1">Add a new patient into the selected facility context.</p>
        </div>

        <a href="{{ route('admin.clients.index') }}"
           class="inline-flex items-center rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800">
            Back
        </a>
    </div>

    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.clients.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Basic Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Gender</label>
                    <select name="gender"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Room</label>
                    <input type="text" name="room" value="{{ old('room') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Body Metrics</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Height (cm)</label>
                    <input type="number" step="0.01" name="height" id="height" value="{{ old('height') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">BMI (auto)</label>
                    <input type="text" id="bmi_preview" readonly
                           class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-600">
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Clinical Intake</h2>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Chief Complaint</label>
                    <textarea name="chief_complaint" rows="3"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('chief_complaint') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Medical History</label>
                    <textarea name="medical_history" rows="4"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('medical_history') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Family History</label>
                    <textarea name="family_history" rows="4"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('family_history') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Social History</label>
                    <textarea name="social_history" rows="4"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('social_history') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-2 text-white hover:bg-indigo-700">
                Create Client
            </button>

            <a href="{{ route('admin.clients.index') }}"
               class="inline-flex items-center rounded-lg border border-slate-300 px-6 py-2 text-slate-700 hover:bg-slate-50">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    function calculateBMI() {
        const height = parseFloat(document.getElementById('height')?.value || 0);
        const weight = parseFloat(document.getElementById('weight')?.value || 0);
        const bmiField = document.getElementById('bmi_preview');

        if (!bmiField) return;

        if (height > 0 && weight > 0) {
            const heightMeters = height / 100;
            const bmi = weight / (heightMeters * heightMeters);
            bmiField.value = bmi.toFixed(2);
        } else {
            bmiField.value = '';
        }
    }

    document.getElementById('height')?.addEventListener('input', calculateBMI);
    document.getElementById('weight')?.addEventListener('input', calculateBMI);
    window.addEventListener('load', calculateBMI);
</script>
@endsection
