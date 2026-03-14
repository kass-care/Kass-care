@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <!-- New Visit Form -->
        <div class="bg-white rounded-2xl shadow-2xl p-10">
            <h2 class="text-2xl font-bold mb-6">New Visit Record</h2>
            <form action="{{ route('visits.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Patient</label>
                    <select name="client_id" class="w-full bg-slate-50 rounded-2xl p-4 font-bold">
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Caregiver</label>
                    <select name="caregiver_id" class="w-full bg-slate-50 rounded-2xl p-4 font-bold">
                        @foreach($caregivers as $caregiver)
                            <option value="{{ $caregiver->id }}">{{ $caregiver->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Clinical Notes / Activity</label>
                    <input type="text" name="activity" placeholder="e.g. CT Scan, Routine Checkup" class="w-full bg-slate-50 rounded-2xl p-4 font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Visit Date</label>
                    <input type="date" name="scheduled_at" class="w-full bg-slate-50 rounded-2xl p-4 font-bold" value="{{ date('Y-m-d') }}">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white rounded-2xl p-4 font-bold shadow-lg hover:bg-blue-700 transition">
                    Confirm & Log Visit
                </button>
            </form>
        </div>

        <!-- Recent Logs -->
        <div class="bg-slate-50 rounded-2xl p-10">
            <h2 class="text-2xl font-bold mb-6 text-slate-800">Recent Logs</h2>
            <div class="space-y-4">
                @foreach($visits as $visit)
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-slate-900">{{ $visit->client->name }}</p>
                                <p class="text-xs text-slate-500">{{ $visit->activity }}</p>
                            </div>
                            <span class="text-[10px] font-black uppercase text-blue-500 bg-blue-50 px-2 py-1 rounded-lg">
                                {{ $visit->visit_date }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
