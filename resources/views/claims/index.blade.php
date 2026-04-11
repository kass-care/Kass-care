@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Claims Ledger</h1>
            <p class="text-sm text-gray-500 mt-1">Track insurance claims, payments, balances, and follow-up status.</p>
        </div>

        <a href="{{ route('claims.create') }}"
           class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
            New Claim
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500">Total Claims</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_claims'] }}</div>
        </div>

        <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500">Total Billed</div>
            <div class="mt-2 text-3xl font-bold text-blue-600">${{ number_format($stats['total_billed'], 2) }}</div>
        </div>

        <div class="rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500">Total Paid</div>
            <div class="mt-2 text-3xl font-bold text-green-600">${{ number_format($stats['total_paid'], 2) }}</div>
        </div>

        <div class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500">Outstanding Balance</div>
            <div class="mt-2 text-3xl font-bold text-red-600">${{ number_format($stats['total_balance'], 2) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">
            <div class="text-xs uppercase tracking-wide text-blue-600 font-semibold">Submitted</div>
            <div class="mt-2 text-2xl font-bold text-blue-700">{{ $stats['submitted'] ?? 0 }}</div>
        </div>

        <div class="rounded-2xl border border-yellow-100 bg-yellow-50 p-4">
            <div class="text-xs uppercase tracking-wide text-yellow-600 font-semibold">Partial</div>
            <div class="mt-2 text-2xl font-bold text-yellow-700">{{ $stats['partial'] ?? 0 }}</div>
        </div>

        <div class="rounded-2xl border border-green-100 bg-green-50 p-4">
            <div class="text-xs uppercase tracking-wide text-green-600 font-semibold">Paid</div>
            <div class="mt-2 text-2xl font-bold text-green-700">{{ $stats['paid'] ?? 0 }}</div>
        </div>

        <div class="rounded-2xl border border-red-100 bg-red-50 p-4">
            <div class="text-xs uppercase tracking-wide text-red-600 font-semibold">Denied</div>
            <div class="mt-2 text-2xl font-bold text-red-700">{{ $stats['denied'] ?? 0 }}</div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-600">
                        <th class="px-4 py-3 font-semibold">Patient</th>
                        <th class="px-4 py-3 font-semibold">Claim</th>
                        <th class="px-4 py-3 font-semibold">Payer</th>
                        <th class="px-4 py-3 font-semibold">Service Date</th>
                        <th class="px-4 py-3 font-semibold">Billed</th>
                        <th class="px-4 py-3 font-semibold">Paid</th>
                        <th class="px-4 py-3 font-semibold">Balance</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($claims as $claim)
                        @php
                            $badgeClasses = match($claim->status) {
                                'submitted' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                'partial' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                                'paid' => 'bg-green-100 text-green-700 border border-green-200',
                                'denied' => 'bg-red-100 text-red-700 border border-red-200',
                                'void' => 'bg-gray-100 text-gray-700 border border-gray-200',
                                default => 'bg-slate-100 text-slate-700 border border-slate-200',
                            };
                        @endphp

                        <tr class="hover:bg-gray-50/70">
                            <td class="px-4 py-4 font-medium text-gray-900">
                                {{ $claim->client->name ?? '-' }}
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                {{ $claim->claim_number ?? '-' }}
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                {{ $claim->payer_name ?? '-' }}
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                {{ $claim->service_date ? \Carbon\Carbon::parse($claim->service_date)->format('M d, Y') : '-' }}
                            </td>

                            <td class="px-4 py-4 font-semibold text-blue-600">
                                ${{ number_format((float) $claim->billed_amount, 2) }}
                            </td>

                            <td class="px-4 py-4 font-semibold text-green-600">
                                ${{ number_format((float) $claim->paid_amount, 2) }}
                            </td>

                            <td class="px-4 py-4 font-semibold text-red-600">
                                ${{ number_format((float) $claim->balance_amount, 2) }}
                            </td>

                            <td class="px-4 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses }}">
                                    {{ ucfirst($claim->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-3 text-sm">
                                    <a href="{{ route('claims.edit', $claim->id) }}"
                                       class="font-medium text-indigo-600 hover:text-indigo-800">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('claims.destroy', $claim->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                onclick="return confirm('Delete this claim?')"
                                                class="font-medium text-red-600 hover:text-red-800">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-sm text-gray-500">
                                No claims added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $claims->links() }}
    </div>
</div>
@endsection
