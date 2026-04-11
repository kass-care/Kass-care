@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Claim</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new insurance claim ledger entry.</p>
        </div>

        <a href="{{ route('claims.index') }}"
           class="inline-flex items-center rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
            Back to Claims
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700">
            <div class="font-semibold mb-2">Please fix the following:</div>
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('claims.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Claim Details</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient</label>
                        <select name="client_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select patient</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Visit</label>
                        <select name="visit_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select visit</option>
                            @foreach($visits as $visit)
                                <option value="{{ $visit->id }}" @selected(old('visit_id') == $visit->id)>
                                    #{{ $visit->id }} - {{ optional($visit->client)->name ?? 'Unknown Patient' }} - {{ $visit->visit_date }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Facility</label>
                        <select name="facility_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select facility</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" @selected(old('facility_id') == $facility->id)>
                                    {{ $facility->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provider</label>
                        <select name="provider_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select provider</option>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}" @selected(old('provider_id') == $provider->id)>
                                    {{ $provider->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Billing Info</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payer Name</label>
                        <input type="text" name="payer_name" value="{{ old('payer_name') }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="e.g. Medicare, Medicaid, Aetna">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Claim Number</label>
                        <input type="text" name="claim_number" value="{{ old('claim_number') }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Enter claim reference">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service Date</label>
                        <input type="date" name="service_date" value="{{ old('service_date') }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="submitted" @selected(old('status') === 'submitted')>Submitted</option>
                            <option value="partial" @selected(old('status') === 'partial')>Partial</option>
                            <option value="paid" @selected(old('status') === 'paid')>Paid</option>
                            <option value="denied" @selected(old('status') === 'denied')>Denied</option>
                            <option value="void" @selected(old('status') === 'void')>Void</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Amounts</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Billed Amount</label>
                    <input type="number" step="0.01" min="0" name="billed_amount" value="{{ old('billed_amount', 0) }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paid Amount</label>
                    <input type="number" step="0.01" min="0" name="paid_amount" value="{{ old('paid_amount', 0) }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adjustment Amount</label>
                    <input type="number" step="0.01" min="0" name="adjustment_amount" value="{{ old('adjustment_amount', 0) }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Patient Responsibility</label>
                    <input type="number" step="0.01" min="0" name="patient_responsibility" value="{{ old('patient_responsibility', 0) }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Dates & Notes</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Submitted At</label>
                    <input type="date" name="submitted_at" value="{{ old('submitted_at') }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paid At</label>
                    <input type="date" name="paid_at" value="{{ old('paid_at') }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="4"
                          class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Optional billing notes...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                Save Claim
            </button>

            <a href="{{ route('claims.index') }}"
               class="inline-flex items-center rounded-xl bg-gray-100 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
