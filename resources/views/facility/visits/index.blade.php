<!DOCTYPE html>
<html>
<head>
    <title>Facility Visits</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white min-h-screen">

<div class="max-w-7xl mx-auto px-6 py-10">

    <div class="flex justify-between mb-8">

        <div>
            <p class="text-xs text-indigo-400 uppercase tracking-widest">Kass Care</p>
            <h1 class="text-4xl font-bold">Facility Visits</h1>
            <p class="text-slate-400 mt-2">{{ $facility->name }}</p>
        </div>

        <a href="{{ route('facility.admin.home') }}"
           class="bg-white text-slate-900 px-6 py-3 rounded-xl font-semibold">
            Back to Facility Home
        </a>

    </div>


    <div class="bg-slate-900 rounded-3xl border border-slate-800 p-8">

        <div class="flex justify-between items-center mb-6">

            <h2 class="text-2xl font-semibold">Visits Workspace</h2>

              <a href="{{ route('facility.visits.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg font-semibold">
                Schedule Visit
            </a>

        </div>


        <table class="w-full text-sm">

            <thead class="text-slate-400 border-b border-slate-800">
                <tr>
                    <th class="text-left py-3">Client</th>
                    <th class="text-left py-3">Provider</th>
                    <th class="text-left py-3">Date</th>
                    <th class="text-left py-3">Status</th>
                    <th class="text-left py-3">Action</th>
                </tr>
            </thead>


            <tbody>

            @forelse($visits as $visit)

                <tr class="border-b border-slate-800">

                    <td class="py-3">
                        {{ $patients[$visit->client_id]->first_name ?? '' }}
                        {{ $patients[$visit->client_id]->last_name ?? '' }}
                    </td>

                    <td class="py-3">
                        {{ $providers[$visit->provider_id]->name ?? 'Unassigned' }}
                    </td>

                    <td class="py-3">
                        {{ \Carbon\Carbon::parse($visit->visit_date)->format('M j, Y') }}
                    </td>

                    <td class="py-3 text-emerald-400">
                        {{ ucfirst($visit->status) }}
                    </td>

                    <td class="py-3">

                        <a href="{{ route('facility.facility.visits.show', $visit->id) }}"
                           class="bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded-lg text-xs font-semibold">

                           Open Visit

                        </a>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="py-6 text-slate-500">
                        No visits scheduled yet.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
