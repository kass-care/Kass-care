<?php

namespace App\Http\Controllers;

use App\Models\Facility; // STEP 1: Import the Model (The filing cabinet)
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // STEP 2: Fetch all facilities from the database
        $facilities = Facility::all();
        
        // Pass them to the view
        return view('facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // STEP 3: Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // STEP 4: Actually SAVE to the database
        // We use our "Safety Net" for organization_id
        Facility::create([
            'name' => $request->name,
            'organization_id' => auth()->user()->organization_id ?? 1,
        ]);

        return redirect()->route('facilities.index')
            ->with('success', 'Facility created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // STEP 5: Find the specific facility to edit
        $facility = Facility::findOrFail($id);
        
        return view('facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $facility = Facility::findOrFail($id);
        $facility->update([
            'name' => $request->name,
        ]);

        return redirect()->route('facilities.index')
            ->with('success', 'Facility updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // STEP 6: Find it and Kill it (Delete)
        $facility = Facility::findOrFail($id);
        $facility->delete();

        return redirect()->route('facilities.index')
            ->with('success', 'Facility deleted successfully.');
    }
}
