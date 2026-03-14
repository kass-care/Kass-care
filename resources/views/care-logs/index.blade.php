@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-slate-50 pb-12">
    <div class="container mx-auto px-6 py-10">

        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                    Care <span class="text-indigo-600">Logs</span>
                </h1>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">
                    Daily Activity & Medical Tracking
                </p>
            </div>

            <button class="px-6 py-3 bg-indigo-600 text-white text-xs font-black rounded-xl shadow-lg shadow-indigo-200 uppercase tracking-widest hover:bg-indigo-700 transition-all">
                + Add Entry
            </button>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
            <table class="w-full text-left">

                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Time
                        </th>

                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Client
                        </th>

                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Caregiver
                        </th>

                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Activity
                        </th>

                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">

                    <tr class="hover:bg-indigo-50/30 transition-colors">

                        <td class="px-8 py-6">
                            <span class="text-sm font-black text-slate-900">
                                09:15 AM
                            </span>
                        </td>

                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-700">
                                Sweeny
                            </span>
                        </td>

                        <td class="px-8 py-6">
                            <span class="text-sm text-slate-500">
                                Kass Admin
                            </span>
                        </td>

                        <td class="px-8 py-6">
                            <span class="text-xs font-medium text-slate-600 bg-slate-100 px-3 py-1 rounded-full italic">
                                Medication Administered
                            </span>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[9px] font-black uppercase">
                                Completed
                            </span>
                        </td>

                    </tr>

                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
