<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProviderWorkspaceController extends Controller
{
    public function schedule(Request $request)
    {
        return view('provider.schedule');
    }

    public function carelogs(Request $request)
    {
        return view('provider.carelogs');
    }
}
