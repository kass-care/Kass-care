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
        <div class="flex items-start justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-indigo-300">Kass Care</p>
                <h1 class="text-4xl font-bold mt-2">Facility Providers</h1>
                <p class="text-slate-400 mt-3">
                    {{ $facility->name ?? 'Selected Facility' }}
                </p>
            </div>

            <a href="{{ route('facility.admin.home') }}"
               class="px-6 py-3 rounded-2xl bg-white text-slate-900 font-semibold hover:bg-slate-100 transition">
                Back to Facility Home
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-rose-300">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 rounded-3xl border border-slate-800 bg-slate-900 p-8">
                <h2 class="text-2xl font-semibold mb-2">Providers Workspace</h2>
                <p class="text-slate-400 mb-6">
                    This provider list is locked to the selected facility context.
                </p>

                <div class="rounded-2xl border border-slate-800 bg-slate-950 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-900/80 text-slate-300">
                            <tr>
                                <th class="text-left px-4 py-3">Provider Name</th>
                                <th class="text-left px-4 py-3">Email</th>
                                <th class="text-left px-4 py-3">Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($providers as $provider)
                                <tr class="border-t border-slate-800">
                                    <td class="px-4 py-3">{{ $provider->name }}</td>
                                    <td class="px-4 py-3">{{ $provider->email }}</td>
                                    <td class="px-4 py-3 text-emerald-400">{{ ucfirst($provider->role) }}</td>
                                </tr>
                            @empty
                                <tr class="border-t border-slate-800">
                                    <td colspan="3" class="px-4 py-6 text-slate-400">
                                        No providers assigned to this facility yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-8">
                <h2 class="text-2xl font-semibold mb-2">Assign Provider</h2>
                <p class="text-slate-400 mb-6">
                    Link an existing provider to this facility.
                </p>

                <form method="POST" action="{{ route('facility.providers.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="provider_id" class="block text-sm font-medium text-slate-300 mb-2">
                            Select Provider
                        </label>
                        <select name="provider_id"
                                id="provider_id"
                                class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Choose provider</option>
                            @foreach($availableProviders as $provider)
                                <option value="{{ $provider->id }}">
                                    {{ $provider->name }} — {{ $provider->email }}
                                    @if($provider->facility_id)
                                        (Currently facility {{ $provider->facility_id }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full rounded-2xl bg-indigo-600 px-4 py-3 font-semibold text-white hover:bg-indigo-700 transition">
                        Assign Provider
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
