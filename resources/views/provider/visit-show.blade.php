<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
    <h2 class="text-2xl font-semibold text-slate-900 mb-6">Caregiver Logs</h2>

    @forelse($visit->careLogs as $log)
        <div class="mb-8 border border-slate-200 rounded-xl p-6 bg-slate-50">

            <div class="mb-4">
                <p class="text-sm text-slate-500">
                    Logged at {{ $log->created_at->format('M d, Y h:i A') }}
                </p>
            </div>

            {{-- ADLs --}}
            <div class="mb-6">
                <h3 class="font-semibold text-slate-900 mb-3">ADL Charting</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach(($log->adls ?? []) as $key => $value)
                        <div class="bg-white border rounded-lg p-3">
                            <p class="text-xs text-slate-500 capitalize">
                                {{ str_replace('_',' ',$key) }}
                            </p>
                            <p class="font-semibold text-slate-900">
                                {{ $value }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
@php
    $alerts = [];

    if (!empty($log->vitals['temperature']) && is_numeric($log->vitals['temperature']) && $log->vitals['temperature'] >= 38) {
        $alerts[] = 'Fever';
    }

    if (!empty($log->vitals['spo2']) && is_numeric($log->vitals['spo2']) && $log->vitals['spo2'] < 92) {
        $alerts[] = 'Low Oxygen Saturation';
    }

    if (!empty($log->vitals['heart_rate']) && is_numeric($log->vitals['heart_rate']) && ($log->vitals['heart_rate'] > 110 || $log->vitals['heart_rate'] < 50)) {
        $alerts[] = 'Abnormal Heart Rate';
    }

    if (!empty($log->vitals['bp_systolic']) && is_numeric($log->vitals['bp_systolic']) && $log->vitals['bp_systolic'] >= 140) {
        $alerts[] = 'High Systolic BP';
    }

    if (!empty($log->vitals['bp_diastolic']) && is_numeric($log->vitals['bp_diastolic']) && $log->vitals['bp_diastolic'] >= 90) {
        $alerts[] = 'High Diastolic BP';
    }
@endphp

@if(count($alerts))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
        <p class="text-sm font-semibold text-red-700 mb-2">Clinical Alerts</p>
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($alerts as $alert)
                <li>{{ $alert }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- VITALS --}}
<div class="mb-6">
    <h3 class="font-semibold text-slate-900 mb-3">Vitals</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach(($log->vitals ?? []) as $key => $value)
            @php
                $label = str_replace('_', ' ', $key);
                $alertClass = 'bg-white border rounded-lg p-3';
                $textClass = 'font-semibold text-slate-900';

                if ($key === 'temperature' && is_numeric($value) && $value >= 38) {
                    $alertClass = 'bg-red-50 border border-red-300 rounded-lg p-3';
                    $textClass = 'font-semibold text-red-700';
                }

                if ($key === 'spo2' && is_numeric($value) && $value < 92) {
                    $alertClass = 'bg-red-50 border border-red-300 rounded-lg p-3';
                    $textClass = 'font-semibold text-red-700';
                }

                if ($key === 'heart_rate' && is_numeric($value) && ($value > 110 || $value < 50)) {
                    $alertClass = 'bg-amber-50 border border-amber-300 rounded-lg p-3';
                    $textClass = 'font-semibold text-amber-700';
                }

                if ($key === 'bp_systolic' && is_numeric($value) && $value >= 140) {
                    $alertClass = 'bg-red-50 border border-red-300 rounded-lg p-3';
                    $textClass = 'font-semibold text-red-700';
                }

                if ($key === 'bp_diastolic' && is_numeric($value) && $value >= 90) {
                    $alertClass = 'bg-red-50 border border-red-300 rounded-lg p-3';
                    $textClass = 'font-semibold text-red-700';
                }

                if ($key === 'respiratory_rate' && is_numeric($value) && ($value > 24 || $value < 10)) {
                    $alertClass = 'bg-amber-50 border border-amber-300 rounded-lg p-3';
                    $textClass = 'font-semibold text-amber-700';
                }
            @endphp

            <div class="{{ $alertClass }}">
                <p class="text-xs text-slate-500 capitalize">{{ $label }}</p>
                <p class="{{ $textClass }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>
</div>
            {{-- NOTES --}}
            <div>
                <h3 class="font-semibold text-slate-900 mb-2">Care Notes</h3>
                <div class="bg-white border rounded-lg p-4 text-slate-800">
                    {{ $log->notes ?? 'No notes' }}
                </div>
            </div>

        </div>

    @empty
        <p class="text-slate-500">No care logs recorded yet.</p>
    @endforelse
</div>
