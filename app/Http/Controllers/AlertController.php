<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::latest()->paginate(20);

        return view('alerts.index', compact('alerts'));
    }

    public function fetch()
    {
        $alerts = Alert::latest()
            ->take(8)
            ->get()
            ->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'type' => $alert->type,
                    'message' => $alert->message,
                    'read_at' => $alert->read_at,
                    'created_at' => optional($alert->created_at)->diffForHumans(),
                ];
            });

        $unreadCount = Alert::whereNull('read_at')->count();

        return response()->json([
            'unread' => $unreadCount,
            'alerts' => $alerts,
        ]);
    }

    public function markAllRead()
    {
        Alert::whereNull('read_at')->update([
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
