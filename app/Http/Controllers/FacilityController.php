<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    // Display all facilities
    public function index()
    {
        $facilities = Facility::latest()->get();
        return view('facilities.index', compact('facilities'));
    }

    // Show form to create a new facility (not needed since modal is on index)
    public function create()
    {
        return view('facilities.create'); // Optional if you have separate page
    }

    // Store a new facility
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        Facility::create([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        return redirect()->route('facilities.index')
                         ->with('success', 'Facility created successfully.');
    }

    // Show a single facility (optional)
    public function show(Facility $facility)
    {
        return view('facilities.show', compact('facility'));
    }

    // Show form to edit facility (optional if you do inline edits)
    public function edit(Facility $facility)
    {
        return view('facilities.edit', compact('facility'));
    }

    // Update a facility
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $facility->update([
            'name' => $request->name,
            'address' => $request->address,
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
