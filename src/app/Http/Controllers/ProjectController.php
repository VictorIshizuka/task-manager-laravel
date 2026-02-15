<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProjectMemberRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

use App\Models\Project;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ProjectController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $projects = auth()->user()
                ->allProjects()
                ->with(['members', 'tasks'])
                ->latest()
                ->get();

            return view('projects.index', compact('projects'));
        } catch (\Throwable $e) {
            Log::error('Erro ao listar projetos: ' . $e->getMessage());

            return back()->with('error_project', 'Não foi possível carregar os projetos.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {

        try {
            DB::transaction(function () use ($request) {
                $project = Project::create([
                    'owner_id' => auth()->id(),
                    ...$request->validated()
                ]);

                $this->handleFileUpload($request, $project);
            });

            return redirect()
                ->route('projects.index')
                ->with('success_project', 'Projeto criado com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao criar projeto: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error_project', 'Erro ao criar projeto. Tente novamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        try {
            $project->load([
                // 'tasks:id,project_id,title,status,due_date,priority',
                'members:id,name,email',
                'owner:id,name',
                'files:id,project_id,path,original_name'
            ]);

            $tasks = $project->tasks()
                ->when(request('status'), fn($q) =>
                $q->where('status', request('status')))
                ->when(request('priority'), fn($q) =>
                $q->where('priority', request('priority')))
                ->orderByRaw("FIELD(status, 'pending','in_progress','done')")
                ->orderBy('due_date')
                ->get(['id', 'project_id', 'title', 'status', 'due_date', 'priority', 'user_id']);

            $project->setRelation('tasks', $tasks);

            $users = \App\Models\User::select('id', 'name')->get();

            return view('projects.show', compact('project', 'users'));
        } catch (\Throwable $e) {
            Log::error('Erro ao exibir projeto: ' . $e->getMessage());

            return redirect()
                ->route('projects.index')
                ->with('error_project', 'Projeto não encontrado ou indisponível.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        try {
            DB::transaction(function () use ($request, $project) {
                $project->update($request->validated());

                $this->handleFileUpload($request, $project);
            });

            return redirect()
                ->route('projects.index')
                ->with('success_project', 'Projeto atualizado com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar projeto: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error_project', 'Não foi possível atualizar o projeto.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {

            foreach ($project->files as $file) {
                Storage::disk('public')->delete($file->path);
            }

            $project->delete();

            return redirect()
                ->route('projects.index')
                ->with('success_project', 'Projeto removido com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao deletar projeto: ' . $e->getMessage());

            return back()->with('error', 'Não foi possível remover o projeto.');
        }
    }

    private function handleFileUpload($request, Project $project)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('projects', 'public');

            $project->files()->create([
                'path' => $path,
                'original_name' => $request->file('file')->getClientOriginalName()
            ]);
        }
    }

    public function  addMember(AddProjectMemberRequest $request, Project $project)
    {
        $project->members()->syncWithoutDetaching([$request->user_id]);

        return back()->with('success_project_member', 'Usuário adicionado com sucesso!');
    }

    public function removeMember(Project $project, $userId)
    {
        $this->authorize('update', $project);

        $project->members()->detach($userId);

        return back()->with('success_project_member', 'Usuário removido com sucesso!');
    }
}
