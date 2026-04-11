@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-700 via-indigo-600 to-indigo-500 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-white">Alerts Center</h1>
                <p class="text-indigo-100 text-sm mt-1">Recent notifications across your system</p>
            </div>

            <button id="alertsMarkAllRead"
                    type="button"
                    class="bg-white/15 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-white/25 transition">
                Mark all as read
            </button>
        </div>

        <div id="alertsList" class="divide-y divide-gray-100">
            @forelse($alerts as $alert)
                <div class="px-6 py-5 {{ is_null($alert->read_at) ? 'bg-indigo-50' : 'bg-white' }}">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 capitalize">
                                {{ $alert->type ?? 'Alert' }}
                            </h2>

                            <p class="text-gray-700 mt-1">
                                {{ $alert->message }}
                            </p>

                            <p class="text-xs text-gray-400 mt-2">
                                {{ $alert->created_at?->diffForHumans() }}
                            </p>
                        </div>

                        @if(is_null($alert->read_at))
                            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                New
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-10 text-center text-gray-500">
                    No alerts found.
                </div>
            @endforelse
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $alerts->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const markAllBtn = document.getElementById('alertsMarkAllRead');

    if (markAllBtn) {
        markAllBtn.addEventListener('click', function () {
            fetch("{{ route('notifications.readAll') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(() => {
                window.location.reload();
            })
            .catch(() => {
                alert('Could not mark alerts as read.');
            });
        });
    }
});
</script>
@endsection
