<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = AuditLog::latest()->take(50)->get();

        return view('admin.activity.index', compact('activities'));
    }
}
