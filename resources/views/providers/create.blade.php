@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-indigo-700">➕ Add Provider</h1>

        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800">
            ⬅ Back to Dashboard
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-xl p-6">
        <form action="{{ route('providers.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>

            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                Save Provider
            </button>
        </form>
    </div>

</div>
@endsection
