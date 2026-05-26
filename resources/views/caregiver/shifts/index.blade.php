<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Shifts | KassCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-emerald-950 text-white">
<div class="max-w-6xl mx-auto px-6 py-10">

    <div class="mb-10">
        <p class="text-xs uppercase tracking-[0.35em] text-emerald-300">KASSCARE CAREGIVER</p>
        <h1 class="mt-3 text-5xl font-black">My Assigned Shifts</h1>
        <p class="mt-4 text-emerald-100/70 text-lg">
            View assigned residents, duties, notes, GPS EVV clock-in, and shift instructions.
        </p>

        <div class="mt-5 flex flex-wrap gap-3">
            <a href="{{ route('caregiver.dashboard') }}"
               class="rounded-xl bg-white/10 px-4 py-2 text-sm font-bold text-white hover:bg-white/20">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($shifts as $shift)
            <div class="rounded-3xl border border-emerald-800 bg-emerald-900/60 p-6 shadow-xl">

                <div class="flex flex-wrap items-start justify-between gap-5">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-emerald-300">Shift Date</p>
                        <h2 class="mt-2 text-3xl font-black">
                            {{ $shift->shift_date?->format('F d, Y') }}
                        </h2>
                    </div>

                    <div class="flex gap-3">
                        <div class="rounded-2xl bg-emerald-950 p-4">
                            <p class="text-xs uppercase tracking-[0.3em] text-emerald-400">Start</p>
                            <p class="mt-2 text-xl font-bold">{{ $shift->shift_start ?? '--' }}</p>
                        </div>

                        <div class="rounded-2xl bg-emerald-950 p-4">
                            <p class="text-xs uppercase tracking-[0.3em] text-emerald-400">End</p>
                            <p class="mt-2 text-xl font-bold">{{ $shift->shift_end ?? '--' }}</p>
                        </div>
                    </div>

                    <span class="rounded-2xl bg-cyan-500/20 px-5 py-3 text-sm font-black text-cyan-200">
                        {{ strtoupper($shift->status) }}
                    </span>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    @if(!$shift->clock_in_at)
                        <form method="POST"
                              action="{{ route('caregiver.shifts.clock-in', $shift->id) }}"
                              class="gps-clock-form">
                            @csrf
                            <input type="hidden" name="latitude" class="latitude-input">
                            <input type="hidden" name="longitude" class="longitude-input">

                            <button type="submit"
                                    class="rounded-2xl bg-emerald-400 px-5 py-3 text-sm font-black text-emerald-950">
                                ✅ Clock In
                            </button>
                        </form>
                    @elseif(!$shift->clock_out_at)
                        <form method="POST"
                              action="{{ route('caregiver.shifts.clock-out', $shift->id) }}"
                              class="gps-clock-form">
                            @csrf
                            <input type="hidden" name="latitude" class="latitude-input">
                            <input type="hidden" name="longitude" class="longitude-input">

                            <button type="submit"
                                    class="rounded-2xl bg-amber-400 px-5 py-3 text-sm font-black text-amber-950">
                                ⏱ Clock Out
                            </button>
                        </form>
                    @else
                        <span class="rounded-2xl bg-slate-700 px-5 py-3 text-sm font-black text-white">
                            ✅ Shift Completed
                        </span>
                    @endif
                </div>

                @if($shift->clock_in_at || $shift->clock_out_at)
                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-emerald-950 p-4">
                            <p class="text-xs uppercase tracking-[0.3em] text-emerald-400">Clock In</p>
                            <p class="mt-2 text-sm font-bold">
                                {{ $shift->clock_in_at?->format('M d, Y h:i A') ?? 'Not clocked in' }}
                            </p>

                            @if($shift->clock_in_latitude && $shift->clock_in_longitude)
                                <p class="mt-2 text-xs text-emerald-200/70">
                                    GPS: {{ $shift->clock_in_latitude }}, {{ $shift->clock_in_longitude }}
                                </p>
                            @endif
                        </div>

                        <div class="rounded-2xl bg-emerald-950 p-4">
                            <p class="text-xs uppercase tracking-[0.3em] text-emerald-400">Clock Out</p>
                            <p class="mt-2 text-sm font-bold">
                                {{ $shift->clock_out_at?->format('M d, Y h:i A') ?? 'Not clocked out' }}
                            </p>

                            @if($shift->clock_out_latitude && $shift->clock_out_longitude)
                                <p class="mt-2 text-xs text-emerald-200/70">
                                    GPS: {{ $shift->clock_out_latitude }}, {{ $shift->clock_out_longitude }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                @if($shift->clients->count())
                    <div class="mt-6">
                        <p class="mb-2 text-xs uppercase tracking-[0.3em] text-cyan-300">Assigned Residents</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($shift->clients as $client)
                                <span class="rounded-full bg-cyan-500/10 px-3 py-1 text-xs font-bold text-cyan-100">
                                    {{ $client->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($shift->duties))
                    <div class="mt-6">
                        <p class="mb-2 text-xs uppercase tracking-[0.3em] text-emerald-300">Shift Duties</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($shift->duties as $duty)
                                <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-100">
                                    {{ $duty }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($shift->notes)
                    <div class="mt-6 rounded-2xl bg-emerald-950 p-4 text-emerald-100">
                        {{ $shift->notes }}
                    </div>
                @endif

                @if($shift->special_instructions)
                    <div class="mt-4 rounded-2xl bg-amber-500/10 p-4 text-amber-100">
                        {{ $shift->special_instructions }}
                    </div>
                @endif

            </div>
        @empty
            <div class="rounded-3xl border border-dashed border-emerald-700 p-12 text-center">
                <p class="text-2xl font-bold text-emerald-200">No shifts assigned yet.</p>
            </div>
        @endforelse
    </div>

</div>

<script>
document.querySelectorAll('.gps-clock-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        const latInput = form.querySelector('.latitude-input');
        const lngInput = form.querySelector('.longitude-input');

        if (!latInput || !lngInput) return;
        if (latInput.value && lngInput.value) return;

        event.preventDefault();

        if (!navigator.geolocation) {
            alert('GPS location is required to clock in/out.');
            return;
        }

        navigator.geolocation.getCurrentPosition(function (position) {
            latInput.value = position.coords.latitude;
            lngInput.value = position.coords.longitude;
            form.submit();
        }, function () {
            alert('Please allow location access to clock in/out.');
        });
    });
});
</script>

</body>
</html>
