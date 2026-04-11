<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacilityProviderCycle;

class ComplianceController extends Controller
{
    public function index()
    {
        $cycles = FacilityProviderCycle::with(['facility','provider'])
            ->orderBy('next_due_at')
            ->get();

        $overdue = $cycles->where('computed_status', 'overdue')->count();
        $dueSoon = $cycles->where('computed_status', 'due_soon')->count();
        $due = $cycles->where('computed_status', 'due')->count();
        $current = $cycles->where('computed_status', 'current')->count();

        return view('compliance.index', compact(
            'cycles',
            'overdue',
            'dueSoon',
            'due',
            'current'
        ));
    }
}
