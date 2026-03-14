@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold mb-6">Visit Logs</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-slate-100 text-slate-600 uppercase text-sm">
                    <th class="px-5 py-3 border-b">Patient</th>
                    <th class="px-5 py-3 border-b">Caregiver</th>
                    <th class="px-5 py-3 border-b">Activity</th>
                    <th class="px-5 py-3 border-b">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visits as $visit)
                    <tr>
                        <td class="px-5 py-5 border-b">{{ $visit->client->name ?? 'N/A' }}</td>
                        <td class="px-5 py-5 border-b">{{ $visit->caregiver->name ?? 'N/A' }}</td>
                        <td class="px-5 py-5 border-b">{{ $visit->activity }}</td>
                        <td class="px-5 py-5 border-b">{{ $visit->visit_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
