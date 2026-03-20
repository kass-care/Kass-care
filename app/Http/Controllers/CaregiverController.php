<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use Illuminate\Http\Request;

class CaregiverController extends Controller
{
public function index()
{
    $user = auth()->user();

    // Find caregiver linked to this user
    $caregiver = \App\Models\Caregiver::where('user_id', $user->id)->first();

    if (!$caregiver) {
        $visits = collect(); // empty
    } else {
        $visits = \App\Models\Visit::with('client')
            ->where('caregiver_id', $caregiver->id)
            ->latest()
            ->get();
    }

    return view('caregiver.dashboard', compact('visits'));
}
    public function create()
    {
        return view('caregivers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        Caregiver::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'active',
            'organization_id' => 1,
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
