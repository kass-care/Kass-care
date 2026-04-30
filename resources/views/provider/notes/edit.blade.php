@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">

    <h1 class="text-xl font-bold mb-4">Edit Clinical Note</h1>
<form method="POST" action="{{ route('provider.notes.update', $providerNote->id) }}">
    @csrf
    @method('PUT')

        <label>Subjective</label>
        <textarea name="subjective" class="w-full border p-2 mb-3">
{{ $providerNote->subjective }}
        </textarea>

        <label>Objective</label>
        <textarea name="objective" class="w-full border p-2 mb-3">
{{ $providerNote->objective }}
        </textarea>

        <label>Assessment</label>
        <textarea name="assessment" class="w-full border p-2 mb-3">
{{ $providerNote->assessment }}
        </textarea>

        <label>Plan</label>
        <textarea name="plan" class="w-full border p-2 mb-3">
{{ $providerNote->plan }}
        </textarea>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            Update Note
        </button>

    </form>

</div>
@endsection
