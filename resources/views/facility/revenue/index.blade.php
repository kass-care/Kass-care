@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4">

        <div class="bg-indigo-900 text-white p-8 rounded-3xl mb-8">
            <h1 class="text-3xl font-black">Facility Revenue Command Center</h1>
            <p class="text-indigo-200 mt-2">All provider billing performance in this facility</p>
        </div>

        <!-- CARDS -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">

            <div class="bg-white p-6 rounded-2xl shadow">
                <p class="text-sm text-slate-500">Total Revenue</p>
                <p class="text-2xl font-black text-emerald-600">
                    ${{ number_format($totalPaid,2) }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow">
                <p class="text-sm text-slate-500">Pending</p>
                <p class="text-2xl font-black text-yellow-600">
                    ${{ number_format($totalPending,2) }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow">
                <p class="text-sm text-slate-500">Denied</p>
                <p class="text-2xl font-black text-red-600">
                    ${{ number_format($totalDenied,2) }}
                </p>
            </div>

        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Claim</th>
                        <th class="px-4 py-3 text-left">Provider</th>
                        <th class="px-4 py-3 text-left">Client</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Amount</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($claims as $claim)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $claim->claim_number }}</td>
                            <td class="px-4 py-3">{{ $claim->provider?->name }}</td>
                            <td class="px-4 py-3">{{ $claim->client?->name }}</td>
                            <td class="px-4 py-3 uppercase">{{ $claim->status }}</td>
                            <td class="px-4 py-3 font-bold text-emerald-600">
                                ${{ number_format($claim->estimated_amount,2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
