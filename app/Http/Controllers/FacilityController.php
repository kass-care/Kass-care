<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::latest()->get();
        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('admin.facilities.create');
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'address' => ['nullable', 'string', 'max:255'],
        'phone'   => ['nullable', 'string', 'max:255'],
        'email'   => ['nullable', 'email', 'max:255'],
    ]);

    Facility::create($validated);

    return redirect()
        ->route('dashboard')
        ->with('success', 'Facility created successfully.');
}
    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }
public function update(Request $request, Facility $facility)
{
    $validated = $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'address' => ['nullable', 'string', 'max:255'],
        'phone'   => ['nullable', 'string', 'max:255'],
        'email'   => ['nullable', 'email', 'max:255'],
    ]);

    $facility->update($validated);

    return redirect()
        ->route('admin.facilities.index')
        ->with('success', 'Facility updated successfully.');
}

    public function destroy(Facility $facility)
    {
        $facility->delete();

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Facility deleted.');
    }
}
