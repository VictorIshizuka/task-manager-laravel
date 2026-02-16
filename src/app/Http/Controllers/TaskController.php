<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\{StoreTaskRequest, UpdateTaskRequest};

use App\Models\{Project, Task};

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Exibe uma lista paginada de tarefas associadas ao usuário autenticado, permitindo filtragem por status e prioridade, e garantindo que as tarefas sejam carregadas com seus projetos relacionados para exibição eficiente.
     */
    public function index(Request $request)
    {
        try {
            $tasks = Task::query()
                ->where('user_id', auth()->id())
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
                ->with('project')
                ->latest()
                ->paginate(10);

            return view('tasks.index', compact('tasks'));
        } catch (\Throwable $e) {
            Log::error('Erro ao listar tarefas: ' . $e->getMessage());
            return back()->with('error_task', 'Não foi possível carregar as tarefas.');
        }
    }

    /**
     *  Mostra o formulário para criar uma nova tarefa, garantindo que ela esteja associada ao projeto correto e que o usuário tenha permissão para criá-la.
     */
    public function create(Request $request, Project $project)
    {

        $this->authorize('create', [Task::class, $project]);

        return view('tasks.create', compact('project'));
    }

    /**
     * Armazena uma nova tarefa no banco de dados, garantindo que ela esteja associada ao projeto correto e que o usuário tenha permissão para criá-la.
     */
    public function store(StoreTaskRequest $request, Project $project)
    {
        try {
            $this->authorize('create', [Task::class, $project]);

            $task = Task::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'priority' => $request->priority,
                'status' => $request->status ?? 'pending',
            ]);

            if ($request->hasFile('file')) {
                $this->uploadFile($request->file('file'), $task);
            }

            return redirect()
                ->route('projects.show', $project)
                ->with('success_task', 'Tarefa criada com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao criar tarefa: ' . $e->getMessage());

            return back()
                // ->withInput()
                ->with('error_task', 'Erro ao criar tarefa. Tente novamente.');
        }
    }

    /**
     * Exibe os detalhes de uma tarefa específica, garantindo que ela pertença ao projeto correto e que o usuário tenha permissão para visualizá-la.
     */
    public function show(Project $project, Task $task)
    {
        try {
            $this->ensureTaskBelongsToProject($task, $project);

            $this->authorize('view', $task);

            return view('tasks.show', compact('project', 'task'));
        } catch (\Throwable $e) {
            Log::error('Erro ao exibir tarefa: ' . $e->getMessage());

            return back()
                ->with('error_task', 'Não foi possível carregar a tarefa.');
        }
    }

    /**
     * Mostra o formulário para editar uma tarefa existente, garantindo que ela pertença ao projeto correto e que o usuário tenha permissão para editá-la.
     */
    public function edit(Project $project, Task $task)
    {
        try {
            $this->ensureTaskBelongsToProject($task, $project);

            $this->authorize('update', $task);

            return view('tasks.edit', compact('project', 'task'));
        } catch (\Throwable $e) {
            Log::error('Erro ao carregar formulário de edição de tarefa: ' . $e->getMessage());

            return back()
                ->with('error_task', 'Não foi possível carregar o formulário de edição.');
        }
    }

    /**
     * Atualiza os detalhes de uma tarefa existente no banco de dados, garantindo que ela pertença ao projeto correto e que o usuário tenha permissão para editá-la.
     */
    public function update(UpdateTaskRequest $request, Project $project, Task $task)
    {
        try {
            $this->ensureTaskBelongsToProject($task, $project);

            $this->authorize('update', $task);

            $task->update($request->validated());

            if ($request->hasFile('file')) {
                $this->uploadFile($request->file('file'), $task);
            }

            return redirect()
                ->route('projects.show', $task->project)
                ->with('success_task', 'Tarefa atualizada com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar tarefa: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error_task', 'Erro ao atualizar tarefa. Tente novamente.');
        }
    }

    /**
     * Remove uma tarefa do banco de dados, garantindo que ela pertença ao projeto correto e que o usuário tenha permissão para deletá-la.
     */
    public function destroy(Project $project, Task $task)
    {
        try {
            $this->ensureTaskBelongsToProject($task, $project);

            $this->authorize('delete', $task);

            $task->delete();

            return redirect()
                ->route('projects.show', $project)
                ->with('success_task', 'Tarefa removida com sucesso!');
        } catch (\Throwable $e) {
            Log::error(
                'Erro ao deletar tarefa: ' . $e->getMessage(),
                [
                    'project_id' => $project->id,
                    'task_id' => $task->id,
                    'user_id' => auth()->id(),
                ]
            );

            return back()->with('error_task', 'Não foi possível remover a tarefa.');
        }
    }

    public function toggle(Project $project, Task $task)
    {
        $this->ensureTaskBelongsToProject($task, $project);

        $this->authorize('update', $task);

        $task->update([
            'status' => $task->status === 'done' ? 'pending' : 'done'
        ]);

        return back();
    }

    public function toggleStatus(Project $project, Task $task)
    {
        $this->ensureTaskBelongsToProject($task, $project);

        $this->authorize('update', $task);

        if ($task->status === 'pending') {
            $task->status = 'in_progress';
        } elseif ($task->status === 'in_progress') {
            $task->status = 'pending';
        }

        $task->save();

        return redirect()->back();
    }

    /**
     * Faz o upload de um arquivo associado a uma tarefa
     */
    protected function uploadFile($file, Task $task)
    {
        try {
            $path = $file->store('tasks/' . $task->id, 'public');

            $task->files()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Erro ao fazer upload do arquivo: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Aborta a requisição com um erro 404 se a tarefa não pertencer ao projeto, garantindo que as operações sejam realizadas apenas em tarefas associadas ao projeto correto.
     */
    protected function ensureTaskBelongsToProject(Task $task, Project $project)
    {
        abort_if(
            $task->project_id !== $project->id,
            404,
            'Esta tarefa não pertence a este projeto.'
        );
    }
}
