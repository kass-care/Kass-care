<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use Illuminate\Http\Request;

class CaregiverController extends Controller
{
    public function index()
    {
        $caregivers = Caregiver::all();
        return view('caregivers.index', compact('caregivers'));
    }

    public function create()
    {
        return view('caregivers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Caregiver::create([
            'name' => $request->name,
            'organization_id' => auth()->user()->organization_id ?? 1,
        ]);

        return redirect()
            ->route('caregivers.index')
            ->with('success', 'Caregiver added!');
    }

    public function destroy($id)
    {
        $caregiver = Caregiver::findOrFail($id);
        $caregiver->delete();

        return redirect()->route('caregivers.index');
    }
}
