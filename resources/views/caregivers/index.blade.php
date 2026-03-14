@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Add New Caregiver</h2>
            <p class="text-slate-500">Enter the details below to register a new member.</p>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200 p-8">
            <form action="{{ route('caregivers.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="name" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Full Name</label>
                    <input type="text" name="name" id="name" required 
                           class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3">
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-[12px] font-black uppercase tracking-widest transition shadow-lg">
                        Save Caregiver
                    </button>
                    <a href="{{ route('caregivers.index') }}" class="text-slate-500 text-[12px] font-black uppercase tracking-widest hover:text-slate-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
