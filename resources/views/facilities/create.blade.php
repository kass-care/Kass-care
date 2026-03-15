@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20 font-sans">
    <div class="container mx-auto px-6 pt-10">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">Add New <span class="text-indigo-600">Facility</span></h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-2">Register a new care location</p>
        </div>

        <div class="max-w-2xl mx-auto bg-white p-10 rounded-[3rem] shadow-xl border border-slate-100">
            <form>
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest pl-2">Facility Name</label>
                        <input type="text" class="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="e.g. Sunset Care Center">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest pl-2">Address / Location</label>
                        <input type="text" class="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="Enter full address...">
                    </div>

                    <div class="pt-4 flex flex-col space-y-3">
                        <button type="button" class="w-full bg-indigo-600 text-white font-black py-5 rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-100 uppercase tracking-widest text-xs transition-all">
                            Save Facility
                        </button>
                        <a href="{{ route('facilities.index') }}" class="w-full text-center text-slate-400 font-bold py-2 hover:text-slate-600 transition-all text-xs uppercase tracking-widest">
                            Cancel and Go Back
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
