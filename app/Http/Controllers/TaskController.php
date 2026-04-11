<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facility;
use App\Models\Task;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $routePrefix = $this->routePrefix();

        $query = Task::with([
            'facility',
            'client',
            'visit',
            'assignedUser',
            'creator',
        ])->latest();

        if (in_array($user->role, ['admin', 'super_admin', 'superadmin'])) {
            $selectedFacilityId = session('facility_id');

            if ($selectedFacilityId) {
                $query->where(function ($q) use ($selectedFacilityId) {
                    $q->where('facility_id', $selectedFacilityId)
                        ->orWhereNull('facility_id');
                });
            }
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhere('created_by', $user->id);
            });

            if (!empty($user->facility_id)) {
                $query->where(function ($q) use ($user) {
                    $q->where('facility_id', $user->facility_id)
                        ->orWhereNull('facility_id');
                });
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->paginate(12)->withQueryString();

        $tasks->getCollection()->transform(function ($task) {
            $task->open_url = $this->resolveTaskOpenUrl($task);
            return $task;
        });

        $baseCountQuery = Task::query();

        if (in_array($user->role, ['admin', 'super_admin', 'superadmin'])) {
            $selectedFacilityId = session('facility_id');

            if ($selectedFacilityId) {
                $baseCountQuery->where(function ($q) use ($selectedFacilityId) {
                    $q->where('facility_id', $selectedFacilityId)
                        ->orWhereNull('facility_id');
                });
            }
        } else {
            $baseCountQuery->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhere('created_by', $user->id);
            });

            if (!empty($user->facility_id)) {
                $baseCountQuery->where(function ($q) use ($user) {
                    $q->where('facility_id', $user->facility_id)
                        ->orWhereNull('facility_id');
                });
            }
        }

        $urgentCount = (clone $baseCountQuery)
            ->where('priority', 'urgent')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $nonUrgentCount = (clone $baseCountQuery)
            ->whereIn('priority', ['low', 'medium', 'high'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $assignedCount = (clone $baseCountQuery)
            ->where('assigned_to', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $reviewCount = (clone $baseCountQuery)
            ->where('status', 'review')
            ->count();

        $completedCount = (clone $baseCountQuery)
            ->where('status', 'completed')
            ->count();

        return view('tasks.index', compact(
            'tasks',
            'urgentCount',
            'nonUrgentCount',
            'assignedCount',
            'reviewCount',
            'completedCount',
            'routePrefix'
        ));
    }

    public function create()
    {
        $user = auth()->user();
        $routePrefix = $this->routePrefix();

        $facilityId = session('facility_id') ?: ($user->facility_id ?? null);

        $usersQuery = User::query()->whereIn('role', [
            'admin',
            'provider',
            'caregiver',
            'super_admin',
            'superadmin',
        ]);

        if ($facilityId && !in_array($user->role, ['super_admin', 'superadmin'])) {
            $usersQuery->where(function ($q) use ($facilityId) {
                $q->where('facility_id', $facilityId)
                    ->orWhereNull('facility_id');
            });
        }

        $clientsQuery = Client::query();
        $visitsQuery = Visit::query();
        $facilitiesQuery = Facility::query();

        if ($facilityId) {
            $clientsQuery->where('facility_id', $facilityId);
            $visitsQuery->where('facility_id', $facilityId);
        }

        $users = $usersQuery->orderBy('name')->get();
        $clients = $clientsQuery->orderBy('id', 'desc')->get();
        $visits = $visitsQuery->orderBy('id', 'desc')->get();
        $facilities = $facilitiesQuery->orderBy('name')->get();

        return view('tasks.create', compact(
            'users',
            'clients',
            'visits',
            'facilities',
            'routePrefix'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'facility_id' => ['nullable', 'exists:facilities,id'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'visit_id' => ['nullable', 'exists:visits,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'status' => ['required', 'in:open,in_progress,review,completed,cancelled'],
            'due_date' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        if (empty($validated['facility_id'])) {
            $validated['facility_id'] = session('facility_id') ?: ($user->facility_id ?? null);
        }

        $validated['created_by'] = $user->id;

        Task::create($validated);

        return redirect()->route($this->routePrefix() . 'tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        abort_unless($this->canAccessTask($task), 403);

        $task->load(['facility', 'client', 'visit', 'assignedUser', 'creator']);
        $routePrefix = $this->routePrefix();

        return view('tasks.show', compact('task', 'routePrefix'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        abort_unless($this->canAccessTask($task), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:open,in_progress,review,completed,cancelled'],
        ]);

        $task->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route($this->routePrefix() . 'tasks.index')
            ->with('success', 'Task status updated successfully.');
    }

    public function destroy(Task $task)
    {
        abort_unless($this->canManageTask($task), 403);

        $task->delete();

        return redirect()->route($this->routePrefix() . 'tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    private function canAccessTask(Task $task): bool
    {
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'super_admin', 'superadmin'])) {
            return true;
        }

        return (int) $task->assigned_to === (int) $user->id
            || (int) $task->created_by === (int) $user->id;
    }

    private function canManageTask(Task $task): bool
    {
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'super_admin', 'superadmin'])) {
            return true;
        }

        return (int) $task->created_by === (int) $user->id;
    }

    private function resolveTaskOpenUrl(Task $task): string
    {
        $user = auth()->user();
        $role = $user->role ?? null;
        $clientId = $this->resolveTaskClientId($task);

        if ($role === 'provider' && $clientId) {
            if (Route::has('provider.patients.workspace')) {
                return route('provider.patients.workspace', $clientId);
            }

            if (Route::has('provider.patients.summary')) {
                return route('provider.patients.summary', $clientId);
            }
        }

        if ($role === 'admin' && $clientId && Route::has('facility.patients.show')) {
            return route('facility.patients.show', $clientId);
        }

        if (Route::has($this->routePrefix() . 'tasks.show')) {
            return route($this->routePrefix() . 'tasks.show', $task);
        }

        return route($this->routePrefix() . 'tasks.index');
    }

    private function resolveTaskClientId(Task $task): ?int
    {
        if (!empty($task->client_id)) {
            return (int) $task->client_id;
        }

        if ($task->relationLoaded('visit') && $task->visit && !empty($task->visit->client_id)) {
            return (int) $task->visit->client_id;
        }

        if ($task->visit && !empty($task->visit->client_id)) {
            return (int) $task->visit->client_id;
        }

        return null;
    }

    private function routePrefix(): string
    {
        $role = auth()->user()->role ?? null;

        return match ($role) {
            'provider' => 'provider.',
            'caregiver' => 'caregiver.',
            default => 'admin.',
        };
    }
}
