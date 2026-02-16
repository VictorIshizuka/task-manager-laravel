<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Trazer todos os projetos do usuário com tasks
        $projects = Project::with(['tasks' => function ($query) {
            $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
                ->orderBy('due_date', 'asc');
        }])
            ->whereHas('members', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('status', 'asc')
            ->get();

        // Resumo geral das tasks do usuário
        $taskSummary = $user->tasks()
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $pending = $taskSummary['pending'] ?? 0;
        $inProgress = $taskSummary['in_progress'] ?? 0;
        $done = $taskSummary['done'] ?? 0;

        return view('dashboard', compact('projects', 'pending', 'inProgress', 'done'));
    }
}
