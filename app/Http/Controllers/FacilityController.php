<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the facilities.
     */
    public function index()
    {
        $facilities = Facility::latest()->get();
        return view('facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new facility.
     */
    public function create()
    {
        return view('facilities.create');
    }

    /**
     * Store a newly created facility in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Facility::create($request->all());

        return redirect()->route('facilities.index')
            ->with('success', 'Facility created successfully.');
    }

    /**
     * THE SWITCHBOARD: Select a facility and lock it into the session.
     */
    public function select(Facility $facility)
    {
        // This is the "Donkey Step" that makes the whole dashboard filter!
        session(['facility_id' => $facility->id]);
        session(['facility_name' => $facility->name]);

        return redirect()->route('dashboard')
            ->with('success', "You are now managing: " . $facility->name);
    }

    /**
     * Display the specified facility.
     */
    public function show(Facility $facility)
    {
        return view('facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified facility.
     */
    public function edit(Facility $facility)
    {
        return view('facilities.edit', compact('facility'));
    }

    /**
     * Update the specified facility in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $facility->update($request->all());

        return redirect()->route('facilities.index')
            ->with('success', 'Facility updated successfully.');
    }

    /**
     * Remove the specified facility from storage.
     */
    public function destroy(Facility $facility)
    {
        // Safety check: Don't delete the facility if you are currently inside it
        if (session('facility_id') == $facility->id) {
            session()->forget(['facility_id', 'facility_name']);
        }

        $facility->delete();

        return redirect()->route('facilities.index')
            ->with('success', 'Facility deleted successfully.');
    }
}
