@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-10">
    <div class="max-w-5xl mx-auto px-4">

<div class="bg-gradient-to-r from-indigo-900 via-blue-800 to-slate-900 rounded-3xl p-8 shadow-xl mb-8 text-white">
            <h1 class="text-3xl font-black">Smart Phrase Manager</h1>
            <p class="text-indigo-100 mt-2 font-semibold">
                Create shortcuts providers can quickly insert into notes.
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-lg p-6 mb-8">
            <form action="{{ route('provider.smart.phrases.store') }}" method="POST" class="space-y-5">
                @csrf
                        <div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-2">
        Category
    </label>

    <select name="category"
            class="w-full rounded-xl border border-slate-300 px-4 py-3">
        <option value="">Select category</option>
        <option value="Subjective">Subjective</option>
        <option value="Assessment">Assessment</option>
        <option value="Plan">Plan</option>
        <option value="Follow-up">Follow-up</option>
        <option value="COPD">COPD</option>
        <option value="Diabetes">Diabetes</option>
        <option value="Pain">Pain</option>
        <option value="Medication">Medication</option>
        <option value="General">General</option>
    </select>
</div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Shortcut
                    </label>

                    <input type="text"
                           name="shortcut"
                           placeholder=".sob"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Phrase Content
                    </label>

                    <textarea name="content"
                              rows="5"
                              placeholder="Patient reports shortness of breath with activity..."
                              class="w-full rounded-xl border border-slate-300 px-4 py-3"
                              required></textarea>
                </div>

                <button type="submit"
                        class="rounded-xl bg-indigo-600 px-6 py-3 font-bold text-white shadow hover:bg-indigo-700">
                    Save Smart Phrase
                </button>
            </form>
        </div>

        <div class="bg-white rounded-3xl shadow-lg p-6">
            <h2 class="text-2xl font-black text-slate-900 mb-6">
                Existing Smart Phrases
            </h2>

            <div class="space-y-4">
                @forelse($phrases as $phrase)
                    <div class="rounded-2xl border border-slate-200 p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>
                            <p class="text-lg font-black text-indigo-700">
                                {{ $phrase->shortcut }}
                            </p>

                            <p class="text-sm text-slate-700 mt-2 whitespace-pre-line">
                                {{ $phrase->content }}
                            </p>
                        </div>

                        <form action="{{ route('provider.smart.phrases.destroy', $phrase->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    onclick="return confirm('Delete this smart phrase?')"
                                    class="rounded-xl bg-red-600 px-4 py-2 text-sm font-bold text-white">
                                Delete
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
                        No smart phrases created yet.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
