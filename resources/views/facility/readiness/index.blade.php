
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>State Inspection Readiness | KassCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100">

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="rounded-3xl bg-emerald-800 p-8 text-white shadow-2xl">
        <p class="text-xs uppercase tracking-[0.35em] font-black text-emerald-100">
            KASS CARE COMPLIANCE ENGINE
        </p>

        <h1 class="mt-3 text-4xl font-black">
            State Inspection Readiness Command Center
        </h1>

        <p class="mt-3 max-w-3xl text-emerald-100 text-lg">
            Track facility readiness, inspection preparation, compliance tasks,
            unresolved issues, and operational survey requirements.
        </p>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-500 font-black">Total Items</p>
            <p class="mt-4 text-5xl font-black text-slate-900">{{ $total }}</p>
        </div>

        <div class="rounded-3xl bg-emerald-50 border border-emerald-200 p-6 shadow-sm">
            <p class="text-xs uppercase tracking-[0.3em] text-emerald-700 font-black">Complete</p>
            <p class="mt-4 text-5xl font-black text-emerald-700">{{ $complete }}</p>
        </div>

        <div class="rounded-3xl bg-yellow-50 border border-yellow-200 p-6 shadow-sm">
            <p class="text-xs uppercase tracking-[0.3em] text-yellow-700 font-black">Pending</p>
            <p class="mt-4 text-5xl font-black text-yellow-700">{{ $pending }}</p>
        </div>

        <div class="rounded-3xl bg-red-50 border border-red-200 p-6 shadow-sm">
            <p class="text-xs uppercase tracking-[0.3em] text-red-700 font-black">Issues</p>
            <p class="mt-4 text-5xl font-black text-red-700">{{ $issues }}</p>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <a href="{{ route('facility.readiness.packet.download') }}"
           class="inline-flex items-center justify-center rounded-2xl bg-red-700 px-8 py-4 text-lg font-black text-white shadow-xl transition hover:bg-red-800 hover:scale-[1.01]">
            📄 Download State Survey Packet
        </a>
    </div>

    <div class="mt-8 rounded-3xl border border-indigo-200 bg-white p-8 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-indigo-600 font-black">
                    Survey Readiness Score
                </p>

                <h2 class="mt-2 text-4xl font-black text-slate-900">
                    {{ $score }}%
                </h2>
            </div>

            <div class="w-40">
                <div class="h-5 overflow-hidden rounded-full bg-slate-200">
                    <div class="h-full bg-emerald-700" style="width: {{ $score }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-6">
            <h2 class="text-2xl font-black text-slate-900">
                Add Inspection Readiness Item
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Track compliance tasks and inspection preparation requirements.
            </p>
        </div>

        <form method="POST" action="{{ route('facility.readiness.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Category</label>
                    <select name="category" required class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm">
                        <option value="Medication">Medication</option>
                        <option value="Staffing">Staffing</option>
                        <option value="Training">Training</option>
                        <option value="Documentation">Documentation</option>
                        <option value="Care Plans">Care Plans</option>
                        <option value="Safety">Safety</option>
                        <option value="Environment">Environment</option>
                        <option value="Emergency Prep">Emergency Prep</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">Due Date</label>
                    <input type="date" name="due_date" class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm">
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-black text-slate-700 mb-2">Inspection Item</label>
                <input type="text" name="title" required placeholder="Example: Medication refrigerator logs updated"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm">
            </div>

            <div class="mt-6">
                <label class="block text-sm font-black text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="4"
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm"></textarea>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-emerald-700 px-8 py-4 text-lg font-black text-white shadow-xl transition hover:bg-emerald-800 hover:scale-[1.01]">
                    ✅ Add Readiness Item
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-200 px-8 py-5">
            <h2 class="text-2xl font-black text-slate-900">
                Inspection Readiness Items
            </h2>
        </div>

        <div class="divide-y divide-slate-100">

            @forelse($groupedItems as $category => $categoryItems)

    <div class="rounded-3xl border border-slate-200 overflow-hidden mb-8">

        <div class="bg-slate-900 px-6 py-5">
            <h3 class="text-2xl font-black text-white">
                {{ $category }}
            </h3>
        </div>

        <div class="divide-y divide-slate-100 bg-white">

            @foreach($categoryItems as $item)

                <div class="p-8">

                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">

                        <div class="flex-1">

                            <div class="flex items-center gap-3 flex-wrap">

                                @if($item->status === 'complete')

                                    <span class="rounded-full bg-emerald-100 px-4 py-1 text-xs font-black text-emerald-700">
                                        COMPLETE
                                    </span>

                                @elseif($item->status === 'critical')

                                    <span class="rounded-full bg-red-100 px-4 py-1 text-xs font-black text-red-700">
                                        CRITICAL
                                    </span>

                                @elseif($item->status === 'issue')

                                    <span class="rounded-full bg-orange-100 px-4 py-1 text-xs font-black text-orange-700">
                                        ISSUE
                                    </span>

                                @else

                                    <span class="rounded-full bg-yellow-100 px-4 py-1 text-xs font-black text-yellow-700">
                                        PENDING
                                    </span>

                                @endif

                            </div>

                            <h3 class="mt-4 text-2xl font-black text-slate-900">
                                {{ $item->title }}
                            </h3>

                            @if($item->notes)

                                <p class="mt-3 text-slate-600 leading-7">
                                    {{ $item->notes }}
                                </p>

                            @endif

                            @if($item->expires_at)

                                <p class="mt-4 text-sm font-semibold text-slate-500">
                                    Expires:
                                    {{ \Carbon\Carbon::parse($item->expires_at)->format('M d, Y') }}
                                </p>

                            @endif

                        </div>

                        <div class="w-full lg:w-80">

                            <form method="POST"
                                  action="{{ route('facility.readiness.update', $item) }}"
                                  class="space-y-4">

                                @csrf
                                @method('PATCH')

                                <select name="status"
                                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-bold">

                                    <option value="pending"
                                        {{ $item->status === 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="complete"
                                        {{ $item->status === 'complete' ? 'selected' : '' }}>
                                        Complete
                                    </option>

                                    <option value="issue"
                                        {{ $item->status === 'issue' ? 'selected' : '' }}>
                                        Issue
                                    </option>

                                    <option value="critical"
                                        {{ $item->status === 'critical' ? 'selected' : '' }}>
                                        Critical
                                    </option>

                                </select>

                                <textarea name="notes"
                                          rows="3"
                                          placeholder="Inspection notes..."
                                          class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ $item->notes }}</textarea>

                                <button type="submit"
                                        class="w-full rounded-2xl bg-indigo-700 px-6 py-4 text-lg font-black text-white shadow-xl transition hover:bg-indigo-800">

                                    Save Update

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

@empty

    <div class="p-12 text-center">

        <p class="text-lg font-bold text-slate-500">
            No readiness items yet.
        </p>

    </div>

@endforelse
        </div>
    </div>

</div>
<footer class="mt-20 border-t bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-8 text-center text-sm text-gray-600">
        <div class="font-semibold text-gray-700">KASS CARE PLATFORM</div>
        <div class="mt-1">&copy; {{ date('Y') }} KASS MTV USA LLC</div>
        <div class="mt-2 text-xs text-gray-500">
            HIPAA-aware platform for healthcare documentation and care coordination.
        </div>
        <div class="mt-4 flex justify-center gap-6">
            <a href="{{ route('terms') }}" class="hover:text-indigo-600">Terms</a>
            <a href="#" class="hover:text-indigo-600">Privacy</a>
            <a href="#" class="hover:text-indigo-600">System Status</a>
        </div>
        <span class="mt-4 inline-block text-gray-400">Version 1.0</span>
    </div>
</footer>
</body>
</html>
