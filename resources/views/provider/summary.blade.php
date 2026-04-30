@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Provider Summary Disabled</h1>
        <p class="text-slate-600 mb-4">
            This old summary page has been temporarily disabled to keep provider notes stable.
        </p>

        <a href="{{ route('provider.notes.index') }}"
           class="inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-white font-bold">
            Back to Provider Notes
        </a>
    </div>
</div>
@endsection
