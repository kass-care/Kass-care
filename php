<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    // Display a list of all facilities
    public function index()
    {
        $facilities = Facility::latest()->get();
        return view('facilities.index', compact('facilities'));
    }

    // Show the form to create a new facility
    public function create()
    {
        return view('facilities.create');
    }

    // Store a new facility
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        Facility::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('facilities.index')
                         ->with('success', 'Facility created successfully.');
    }

    // Show a single facility
    public function show(Facility $facility)
    {
        return view('facilities.show', compact('facility'));
    }

    // Show the form to edit a facility
    public function edit(Facility $facility)
    {
        return view('facilities.edit', compact('facility'));
    }

    // Update a facility
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $facility->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('facilities.index')
                         ->with('success', 'Facility updated successfully.');
    }

    // Delete a facility
    public function destroy(Facility $facility)
    {
        $facility->delete();

        return redirect()->route('facilities.index')
                         ->with('success', 'Facility deleted successfully.');
    }
}
