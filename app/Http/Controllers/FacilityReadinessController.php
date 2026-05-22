<?php

namespace App\Http\Controllers;

use App\Models\ReadinessItem;
use Illuminate\Http\Request;

class FacilityReadinessController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if(!$facilityId, 403, 'No facility selected.');

        $items = ReadinessItem::where('facility_id', $facilityId)
            ->orderBy('category')
            ->orderBy('title')
            ->get();

        $groupedItems = $items->groupBy('category');

        $total = $items->count();
        $complete = $items->where('completed', true)->count();
        $issues = $items->whereIn('status', ['missing', 'critical', 'issue'])->count();
        $pending = $items->where('status', 'pending')->count();

        $score = $total > 0 ? round(($complete / $total) * 100) : 0;

        return view('facility.readiness.index', compact(
            'items',
            'groupedItems',
            'total',
            'complete',
            'issues',
            'pending',
            'score'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if(!$facilityId, 403, 'No facility selected.');

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'expires_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        ReadinessItem::create([
            'facility_id' => $facilityId,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'expires_at' => $validated['expires_at'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'missing',
            'completed' => false,
        ]);

        return back()->with('success', 'Readiness item added.');
    }

    public function update(Request $request, ReadinessItem $readinessItem)
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if((int) $readinessItem->facility_id !== (int) $facilityId, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:missing,pending,complete,issue,critical'],
            'notes' => ['nullable', 'string'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $readinessItem->update([
            'status' => $validated['status'],
            'completed' => $validated['status'] === 'complete',
            'notes' => $validated['notes'] ?? $readinessItem->notes,
            'expires_at' => $validated['expires_at'] ?? $readinessItem->expires_at,
        ]);

        return back()->with('success', 'Readiness item updated.');
    }
}
