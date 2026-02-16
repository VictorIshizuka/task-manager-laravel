<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $projects = $user->allProjects()
            ->with(['tasks' => function ($query) {
                $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
                    ->orderBy('due_date', 'asc');
            }])
            ->orderBy('status', 'asc')
            ->get();

        $taskSummary = [];

        $projects->pluck('tasks')->flatten()->each(function ($task) use (&$taskSummary) {
            $taskSummary[$task->status] = $taskSummary[$task->status] ?? 0;
            $taskSummary[$task->status]++;
        });

        $pending = $taskSummary['pending'] ?? 0;
        $inProgress = $taskSummary['in_progress'] ?? 0;
        $done = $taskSummary['done'] ?? 0;

        return view('dashboard', compact('projects', 'pending', 'inProgress', 'done'));
    }
}
