<?php

namespace App\Http\Controllers;

use App\Http\Requests\{AddProjectMemberRequest, StoreProjectRequest, UpdateProjectRequest};

use App\Models\Project;

use Illuminate\Support\Facades\{DB, Log};

class ProjectController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }
    /**
     * Exibe uma lista paginada de projetos associados ao usuário autenticado, garantindo que os projetos sejam carregados com seus membros e tarefas relacionados para exibição eficiente, e lidando com erros de forma robusta para melhorar a experiência do usuário.
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
     * Mostra o formulário para criar um novo projeto
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Armazena um novo projeto no banco de dados, garantindo que ele esteja associado ao usuário autenticado e lidando com o upload de arquivos
     */
    public function store(StoreProjectRequest $request)
    {

        try {
            DB::transaction(function () use ($request) {
                $project = Project::create([
                    'owner_id' => auth()->id(),
                    ...$request->validated()
                ]);

                if ($request->hasFile('file')) {
                    $this->uploadFile($request->file('file'), $project);
                }
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

                if ($request->hasFile('file')) {
                    $this->uploadFile($request->file('file'), $project);
                }
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

            $project->delete();

            return redirect()
                ->route('projects.index')
                ->with('success_project', 'Projeto removido com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao deletar projeto: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Não foi possível remover o projeto.');
        }
    }

    private function uploadFile($file, Project $project)
    {
        try {
            $path = $file->store('projects/' . $project->id, 'public');

            $project->files()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName()
            ]);
        } catch (\Throwable $e) {
            Log::error('Erro ao fazer upload de arquivo: ' . $e->getMessage());
            throw $e;
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
