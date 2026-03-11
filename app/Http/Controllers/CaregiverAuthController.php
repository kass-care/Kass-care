<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caregiver;

class CaregiverAuthController extends Controller
{
    public function showLogin()
    {
        return view('caregiver.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'caregiver_id' => 'required',
            'pin' => 'required'
        ]);

        $caregiver = Caregiver::where('id', $request->caregiver_id)
            ->where('pin', $request->pin)
            ->first();

        if (!$caregiver) {
            return back()->with('error', 'Invalid ID or PIN');
        }

        session(['caregiver_id' => $caregiver->id]);

        return redirect('/caregiver-dashboard');
    }

    public function logout()
    {
        session()->forget('caregiver_id');
        return redirect('/caregiver-login');
    }
}
