@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold tracking-[0.25em] text-indigo-600 uppercase">
                    KASS CARE
                </p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">
                    Caregiver Visits
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    View assigned visits, check in, check out, and track EVV activity.
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(isset($visits) && $visits->count())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach ($visits as $visit)
                    @php
                        $status = $visit->status ?? 'scheduled';

                        $clientName =
                            optional($visit->client)->full_name
                            ?? trim(
                                (optional($visit->client)->first_name ?? '')
                                . ' ' .
                                (optional($visit->client)->last_name ?? '')
                            );

                        if (blank($clientName)) {
                            $clientName = optional($visit->client)->name ?: 'Client';
                        }

                        $facilityName = optional($visit->facility)->name ?? 'No facility linked';

                        $providerName =
                            optional($visit->provider)->name
                            ?? trim(
                                (optional($visit->provider)->first_name ?? '')
                                . ' ' .
                                (optional($visit->provider)->last_name ?? '')
                            );

                        if (blank($providerName)) {
                            $providerName = optional($visit->provider)->email ?: 'Not linked';
                        }

                        $visitDate = $visit->visit_date
                            ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y')
                            : 'N/A';

                        $visitTimeValue = $visit->check_in_time
                            ?? $visit->check_in
                            ?? $visit->start_time
                            ?? null;

                        $visitTime = $visitTimeValue
                            ? \Carbon\Carbon::parse($visitTimeValue)->format('M d, Y h:i A')
                            : 'N/A';

                        $checkInValue = $visit->check_in_time ?? $visit->check_in ?? null;
                        $checkInText = $checkInValue
                            ? \Carbon\Carbon::parse($checkInValue)->format('M d, Y h:i A')
                            : 'Not checked in yet';

                        $checkOutValue = $visit->check_out_time ?? $visit->check_out ?? null;
                        $checkOutText = $checkOutValue
                            ? \Carbon\Carbon::parse($checkOutValue)->format('M d, Y h:i A')
                            : 'Not checked out yet';

                        $durationText = 'Not available yet';

                        if (!empty($visit->duration_minutes)) {
                            $hours = floor((int) $visit->duration_minutes / 60);
                            $minutes = (int) $visit->duration_minutes % 60;

                            if ($hours > 0) {
                                $durationText = $hours . ' hr ' . $minutes . ' mins';
                            } else {
                                $durationText = $minutes . ' mins';
                            }
                        } elseif ($checkInValue && $checkOutValue) {
                            try {
                                $start = \Carbon\Carbon::parse($checkInValue);
                                $end = \Carbon\Carbon::parse($checkOutValue);
                                $minutes = $start->diffInMinutes($end);
                                $hours = floor($minutes / 60);
                                $mins = $minutes % 60;

                                $durationText = $hours > 0
                                    ? $hours . ' hr ' . $mins . ' mins'
                                    : $mins . ' mins';
                            } catch (\Throwable $e) {
                                $durationText = 'Not available yet';
                            }
                        }

                        $canCheckIn = !$checkInValue && !in_array($status, ['completed', 'missed']);
                        $canCheckOut = $checkInValue && !$checkOutValue && $status !== 'completed';
                    @endphp

                    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-indigo-500 px-6 py-5 text-white">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100 font-semibold">
                                        Assigned Visit
                                    </p>
                                    <h2 class="mt-2 text-3xl font-bold leading-tight">
                                        {{ $clientName }}
                                    </h2>
                                    <p class="mt-2 text-sm text-indigo-100">
                                        Facility: {{ $facilityName }}
                                    </p>
                                </div>

                                <div>
                                    @if($status === 'completed')
                                        <span class="inline-flex rounded-full bg-green-100 px-4 py-2 text-xs font-bold text-green-700">
                                            Completed
                                        </span>
                                    @elseif($status === 'in_progress')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-4 py-2 text-xs font-bold text-yellow-700">
                                            In Progress
                                        </span>
                                    @elseif($status === 'missed')
                                        <span class="inline-flex rounded-full bg-red-100 px-4 py-2 text-xs font-bold text-red-700">
                                            Missed
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-blue-100 px-4 py-2 text-xs font-bold text-blue-700">
                                            Scheduled
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                <div class="rounded-xl bg-slate-50 p-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Visit Date</p>
                                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $visitDate }}</p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Visit Time</p>
                                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $visitTime }}</p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Check In</p>
                                    <p class="mt-1 text-xl font-bold text-slate-900">{{ $checkInText }}</p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Check Out</p>
                                    <p class="mt-1 text-xl font-bold text-slate-900">{{ $checkOutText }}</p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Duration</p>
                                    <p class="mt-1 text-xl font-bold text-slate-900">{{ $durationText }}</p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Provider</p>
                                    <p class="mt-1 text-xl font-bold text-slate-900">{{ $providerName }}</p>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-wrap gap-3">
                                @if($canCheckIn)
                                    <form method="POST" action="{{ route('caregiver.checkin.store', $visit->id) }}" class="evv-form">
                                        @csrf
                                        <input type="hidden" name="latitude" class="latitude-input">
                                        <input type="hidden" name="longitude" class="longitude-input">

                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700 transition">
                                            Check In
                                        </button>
                                    </form>
                                @endif

                                @if($canCheckOut)
                                    <form method="POST" action="{{ route('caregiver.visits.checkout', $visit->id) }}" class="evv-form">
                                        @csrf
                                        <input type="hidden" name="latitude" class="latitude-input">
                                        <input type="hidden" name="longitude" class="longitude-input">

                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700 transition">
                                            Check Out
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $visit->id]) }}"
                                   class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                                    Open Visit
                                </a>
                            </div>

                            @if($visit->check_in_latitude && $visit->check_in_longitude)
                                <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                    <p class="font-semibold text-slate-900">Check-In GPS</p>
                                    <p class="mt-1">Lat: {{ $visit->check_in_latitude }}</p>
                                    <p>Lng: {{ $visit->check_in_longitude }}</p>
                                </div>
                            @endif

                            @if($visit->check_out_latitude && $visit->check_out_longitude)
                                <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                    <p class="font-semibold text-slate-900">Check-Out GPS</p>
                                    <p class="mt-1">Lat: {{ $visit->check_out_latitude }}</p>
                                    <p>Lng: {{ $visit->check_out_longitude }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl bg-white px-6 py-12 text-center shadow-sm ring-1 ring-slate-200">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100">
                    <span class="text-2xl">🗓️</span>
                </div>
                <h2 class="mt-4 text-2xl font-bold text-slate-900">No visits assigned yet</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Once visits are assigned to this caregiver, they will appear here for EVV check-in and check-out.
                </p>

                <div class="mt-6">
                    <a href="{{ route('caregiver.dashboard') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.evv-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            if (!navigator.geolocation) {
                return;
            }

            const latitudeInput = form.querySelector('.latitude-input');
            const longitudeInput = form.querySelector('.longitude-input');

            if (!latitudeInput || !longitudeInput) {
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    latitudeInput.value = position.coords.latitude;
                    longitudeInput.value = position.coords.longitude;
                    form.submit();
                },
                function () {
                    form.submit();
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }, { once: true });
    });
});
</script>
@endsection
