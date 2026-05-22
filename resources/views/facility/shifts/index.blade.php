<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Management | KassCare</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-white">

<div class="max-w-7xl mx-auto px-6 py-10">

    <div class="mb-10">
        <p class="text-xs uppercase tracking-[0.35em] text-indigo-400">
            KASSCARE OPERATIONS
        </p>

        <h1 class="mt-3 text-5xl font-black">
            Shift Management Command Center
        </h1>

        <p class="mt-4 text-slate-400 text-lg">
            Schedule caregiver shifts, monitor staffing, and manage facility workforce operations.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1 rounded-3xl border border-slate-800 bg-slate-900 p-8">

            <h2 class="text-2xl font-bold mb-6">
                Schedule Shift
            </h2>

            <form method="POST" action="{{ route('facility.shifts.store') }}">
                @csrf

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-bold text-slate-300">
                        Caregiver
                    </label>

                    <select
                        name="caregiver_id"
                        required
                        class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white"
                    >
                        <option value="">Select caregiver</option>

                        @foreach($caregivers as $caregiver)
                            <option value="{{ $caregiver->id }}">
                                {{ $caregiver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-bold text-slate-300">
                        Shift Date
                    </label>

                    <input
                        type="date"
                        name="shift_date"
                        required
                        class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white"
                    >
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">

                    <div>
                        <label class="block mb-2 text-sm font-bold text-slate-300">
                            Start
                        </label>

                        <input
                            type="time"
                            name="shift_start"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white"
                        >
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-bold text-slate-300">
                            End
                        </label>

                        <input
                            type="time"
                            name="shift_end"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white"
                        >
                    </div>

                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-bold text-slate-300">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        rows="4"
                        class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white"
                        placeholder="Shift instructions..."
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-indigo-600 px-6 py-4 text-lg font-black hover:bg-indigo-700"
                >
                    ➕ Schedule Shift
                </button>

            </form>

        </div>

        <div class="lg:col-span-2 rounded-3xl border border-slate-800 bg-slate-900 p-8">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-3xl font-black">
                        Scheduled Shifts
                    </h2>

                    <p class="text-slate-400 mt-2">
                        Live caregiver staffing operations.
                    </p>
                </div>
            </div>

            <div class="space-y-5">

                @forelse($shifts as $shift)

                    <div class="rounded-3xl border border-slate-800 bg-slate-950 p-6">

                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-indigo-400">
                                    Caregiver
                                </p>

                                <h3 class="mt-2 text-3xl font-black">
                                    {{ $shift->caregiver?->name ?? 'Unassigned' }}
                                </h3>

                                <p class="mt-3 text-slate-400">
                                    {{ $shift->shift_date?->format('F d, Y') }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">

                                <div class="rounded-2xl bg-slate-900 p-4">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">
                                        Start
                                    </p>

                                    <p class="mt-2 text-xl font-bold">
                                        {{ $shift->shift_start ?? '--' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-slate-900 p-4">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">
                                        End
                                    </p>

                                    <p class="mt-2 text-xl font-bold">
                                        {{ $shift->shift_end ?? '--' }}
                                    </p>
                                </div>

                            </div>

                            <div>
                                <span class="inline-flex rounded-2xl bg-emerald-500/20 px-5 py-3 text-sm font-black text-emerald-300">
                                    {{ strtoupper($shift->status) }}
                                </span>
                            </div>

                        </div>

                        @if($shift->notes)
                            <div class="mt-5 rounded-2xl bg-slate-900 p-4 text-slate-300">
                                {{ $shift->notes }}
                            </div>
                        @endif

                    </div>

                @empty

                    <div class="rounded-3xl border border-dashed border-slate-700 p-12 text-center">
                        <p class="text-2xl font-bold text-slate-400">
                            No shifts scheduled yet.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

</body>
</html>
