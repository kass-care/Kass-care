<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert;

class AlertController extends Controller
{

    public function index()
    {
        $alerts = Alert::with('client')->latest()->get();

        return view('alerts.index', compact('alerts'));
    }

}
