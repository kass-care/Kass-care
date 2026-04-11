<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminActivityController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));

        $baseQuery = AuditLog::query();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('user_role', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $logs = (clone $baseQuery)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $totalLogs = AuditLog::count();

        $todayLogs = AuditLog::whereDate('created_at', now()->toDateString())->count();

        $adminActions = AuditLog::whereIn('user_role', ['admin', 'super_admin'])->count();

        $providerActions = AuditLog::where('user_role', 'provider')->count();

        return view('admin.activity.index', compact(
            'logs',
            'search',
            'totalLogs',
            'todayLogs',
            'adminActions',
            'providerActions'
        ));
    }
}
