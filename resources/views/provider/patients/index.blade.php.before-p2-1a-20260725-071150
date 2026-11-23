<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provider Patients | Kass Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white min-h-screen">

<div class="max-w-7xl mx-auto px-6 py-10">

    <div class="flex items-center justify-between mb-10">
        <div>
            <p class="text-xs uppercase tracking-[0.35em] text-indigo-300">Kass Care</p>
            <h1 class="text-4xl font-bold mt-2">Provider Patients</h1>
            <p class="text-slate-400 mt-2">
                Clinical workspace for patient management.
            </p>
        </div>

        <a href="{{ route('provider.dashboard') }}"
           class="px-5 py-3 rounded-2xl bg-white text-slate-900 font-semibold hover:bg-slate-100 transition">
            Back to Dashboard
        </a>
    </div>

    <div class="rounded-3xl bg-slate-900 border border-slate-800 p-8">

        <table class="w-full text-left">

            <thead class="text-slate-400 border-b border-slate-800">
                <tr>
                    <th class="py-4">Patient</th>
                    <th>Facility</th>
                    <th>DOB</th>
                    <th></th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-800">

                @forelse($patients ?? [] as $patient)

                <tr class="hover:bg-slate-800/40 transition">
                    <td class="py-4 font-semibold">
                        {{ $patient->first_name }} {{ $patient->last_name }}
                    </td>

                    <td>
                        {{ $patient->facility->name ?? '—' }}
                    </td>

                    <td>
                        {{ $patient->dob ?? '—' }}
                    </td>

                    <td class="text-right">
                        <a href="{{ route('provider.patients.workspace', $patient->id) }}"
                           class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 transition text-sm font-semibold">
                            Open Workspace
                        </a>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="4" class="py-8 text-center text-slate-500">
                        No patients available.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
