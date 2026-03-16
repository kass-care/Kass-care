@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20 font-sans bg-slate-50">
    <div class="container mx-auto px-6 pt-10">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">
                Schedule <span class="text-indigo-600">Visit</span>
            </h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-2">
                Create a new care record
            </p>
        </div>

        <div class="max-w-2xl mx-auto bg-white p-10 rounded-[3rem] shadow-xl border border-slate-100">
            <form method="POST" action="{{ route('visits.store') }}">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="client_id" class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest pl-2">
                            Client
                        </label>
                        <select
                            id="client_id"
                            name="client_id"
                            class="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-600"
                            required
                        >
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="visit_date" class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest pl-2">
                            Visit Date & Time
                        </label>
                        <input
                            id="visit_date"
                            type="datetime-local"
                            name="visit_date"
                            value="{{ request('date') ? request('date') . 'T09:00' : '' }}"
                            class="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-600"
                            required
                          <div class="mb-4">
                          <label class="block text-sm font-medium text-gray-700 mb-1">
                          Visit Time
                           </label>

                          <input 
                          type="time" 
                          name="visit_time"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
</div>
                        >
                    </div>

                    <div class="pt-4 flex flex-col space-y-3">
                        <button
                            type="submit"
                            class="w-full bg-indigo-600 text-white font-black py-5 rounded-2xl hover:bg-indigo-700 transition"
                        >
                            Save Visit
                        </button>

                        <a
                            href="{{ route('calendar') }}"
                            class="w-full text-center text-slate-500 font-bold py-2 hover:text-slate-700"
                        >
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
