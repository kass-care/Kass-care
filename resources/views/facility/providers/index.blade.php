<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Providers | Kass Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white min-h-screen">

<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- HEADER -->
    <div class="flex items-start justify-between mb-8">
        <div>
            <p class="text-xs uppercase tracking-[0.35em] text-indigo-300">Kass Care</p>
            <h1 class="text-4xl font-bold mt-2">Facility Providers</h1>
            <p class="text-slate-400 mt-3">{{ $facility->name }}</p>
        </div>

        <a href="{{ route('facility.admin.home') }}"
           class="px-6 py-3 rounded-2xl bg-white text-slate-900 font-semibold">
            Back
        </a>
    </div>

    <!-- ALERTS -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300">
            {{ session('error') }}
        </div>
    @endif

    <!-- GRID -->
    <div class="grid lg:grid-cols-3 gap-8">

        <!-- CURRENT PROVIDERS -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-3xl p-6">
            <h2 class="text-xl font-bold mb-4">Assigned Providers</h2>

            <table class="w-full text-sm">
                <thead class="text-slate-400">
                    <tr>
                        <th class="text-left py-2">Name</th>
                        <th class="text-left py-2">Email</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($providers as $p)
                        <tr class="border-t border-slate-800">
                            <td class="py-3">{{ $p->name }}</td>
                            <td>{{ $p->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-6 text-slate-500">
                                No providers yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ACTIONS -->
        <div class="space-y-6">

            <!-- EXISTING PROVIDER -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
                <h3 class="font-bold mb-3">Assign Existing Provider</h3>

                <form method="POST" action="{{ route('providers.store') }}">
                    @csrf

                    <select name="provider_id"
                            class="w-full mb-4 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3">
                        <option value="">Select provider</option>
                        @foreach($availableProviders as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->name }} — {{ $p->email }}
                            </option>
                        @endforeach
                    </select>

                    <button class="w-full bg-indigo-600 py-3 rounded-xl font-bold">
                        Assign
                    </button>
                </form>
            </div>

            <!-- INVITE BY EMAIL -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
                <h3 class="font-bold mb-3">Invite Provider (by Email)</h3>

                <form method="POST" action="{{ route('providers.store') }}">
                    @csrf

                    <input type="email"
                           name="provider_email"
                           placeholder="provider@email.com"
                           class="w-full mb-4 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3">

                    <button class="w-full bg-emerald-600 py-3 rounded-xl font-bold">
                        Invite / Link Provider
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>

</body>
</html>
