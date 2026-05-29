<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shift Management | KassCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-white">
<div class="max-w-7xl mx-auto px-6 py-10">

    <div class="mb-10">
        <p class="text-xs uppercase tracking-[0.35em] text-indigo-400">KASSCARE OPERATIONS</p>
        <h1 class="mt-3 text-5xl font-black">Shift Management Command Center</h1>
        <p class="mt-4 text-slate-400 text-lg">Schedule caregivers, assign residents, and manage shift duties.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-8">
            <h2 class="text-2xl font-bold mb-6">Schedule Shift</h2>

            <form method="POST" action="{{ route('facility.shifts.store') }}">
                @csrf

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-bold text-slate-300">Caregiver</label>
                    <select name="caregiver_id" required class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white">
                        <option value="">Select caregiver</option>
                        @foreach($caregivers as $caregiver)
                            <option value="{{ $caregiver->id }}">{{ $caregiver->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-bold text-slate-300">Assign Residents</label>
                     <div class="grid grid-cols-1 gap-2 rounded-2xl border border-slate-700 bg-slate-950 p-4">
   	 @foreach($clients as $client)
        <label class="flex items-center gap-3 rounded-xl bg-slate-900 px-3 py-2">
            <input
                type="checkbox"
                name="client_ids[]"
                value="{{ $client->id }}"
                class="rounded border-slate-600 bg-slate-900 text-cyan-500"
            >
            <span>{{ $client->name }}</span>
        </label>
    @endforeach
</div> 
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-bold text-slate-300">Shift Date</label>
                    <input type="date" name="shift_date" required class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <input type="time" name="shift_start" class="rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white">
                    <input type="time" name="shift_end" class="rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white">
                </div>

                <div class="mb-5">
                    <label class="block mb-3 text-sm font-bold text-slate-300">Shift Duties</label>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        @foreach(['Medication Pass','Bathing Assistance','Meal Preparation','Vitals Monitoring','Laundry','Room Checks','Behavior Monitoring','Care Documentation','Transfers','Exercise Assistance','Toileting Assistance','Safety Checks'] as $duty)
                            <label class="flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-950 px-3 py-3">
                                <input type="checkbox" name="duties[]" value="{{ $duty }}">
                                <span>{{ $duty }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-bold text-slate-300">Notes</label>
                    <textarea name="notes" rows="3" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white" placeholder="Shift instructions..."></textarea>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-bold text-slate-300">Special Instructions</label>
                    <textarea name="special_instructions" rows="3" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-4 text-white" placeholder="Fall precautions, medication reminders..."></textarea>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-indigo-600 px-6 py-4 text-lg font-black hover:bg-indigo-700">
                    ➕ Schedule Shift
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 rounded-3xl border border-slate-800 bg-slate-900 p-8">
            <h2 class="text-3xl font-black">Scheduled Shifts</h2>
            <p class="text-slate-400 mt-2 mb-6">Live caregiver staffing operations.</p>

            <div class="space-y-5">
                @forelse($shifts as $shift)
                    <div class="rounded-3xl border border-slate-800 bg-slate-950 p-6">
                        <div class="flex flex-wrap items-start justify-between gap-6">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-indigo-400">Caregiver</p>
                                <h3 class="mt-2 text-3xl font-black">{{ $shift->caregiver?->name ?? 'Unassigned' }}</h3>
                                <p class="mt-3 text-slate-400">{{ $shift->shift_date?->format('F d, Y') }}</p>
                            </div>

                            <div class="flex gap-3">
                                <div class="rounded-2xl bg-slate-900 p-4">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Start</p>
                                    <p class="mt-2 text-xl font-bold">{{ $shift->shift_start ?? '--' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-900 p-4">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">End</p>
                                    <p class="mt-2 text-xl font-bold">{{ $shift->shift_end ?? '--' }}</p>
                                </div>
                            </div>

                            <span class="rounded-2xl bg-emerald-500/20 px-5 py-3 text-sm font-black text-emerald-300">
                                {{ strtoupper($shift->status) }}
                            </span>
                        </div>

                        @if($shift->clients->count())
                            <div class="mt-5">
                                <p class="mb-2 text-xs uppercase tracking-[0.3em] text-cyan-400">Assigned Residents</p>
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
                            <div class="mt-5">
                                <p class="mb-2 text-xs uppercase tracking-[0.3em] text-indigo-400">Shift Duties</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($shift->duties as $duty)
                                        <span class="rounded-full bg-indigo-500/10 px-3 py-1 text-xs font-bold text-indigo-100">
                                            {{ $duty }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($shift->notes)
                            <div class="mt-5 rounded-2xl bg-slate-900 p-4 text-slate-300">{{ $shift->notes }}</div>
                        @endif

                        @if($shift->special_instructions)
                            <div class="mt-4 rounded-2xl bg-amber-500/10 p-4 text-amber-100">{{ $shift->special_instructions }}</div>
                        @endif
                    </div>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-700 p-12 text-center">
                        <p class="text-2xl font-bold text-slate-400">No shifts scheduled yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
</body>
</html>
