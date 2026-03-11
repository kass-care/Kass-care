<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caregiver;

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

        Caregiver::create([
            'organization_id' => 1,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'Active'
        ]);

        return redirect('/caregivers')->with('success','Caregiver created successfully');

    }

}
