<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskFileRequest;

use App\Models\{Project, Task, TaskFile};

use Illuminate\Support\Facades\{Log, Storage};

class TaskFileController extends Controller
{
    /**
     * Armazena um arquivo associado a uma tarefa, garantindo que ele esteja vinculado ao projeto correto e que o usuário tenha permissão para adicionar arquivos à tarefa.
     */
    public function store(StoreTaskFileRequest $request, Project $project, Task $task)
    {
        try {
            $this->ensureTaskBelongsToProject($task, $project);

            $path = $request->file('file')->store('tasks/' . $task->id, 'public');

            $task->files()->create([
                'path' => $path,
                'original_name' => $request->file('file')->getClientOriginalName(),
            ]);

            return back()->with('success_task_file', 'Arquivo enviado com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao enviar arquivo para tarefa: ' . $e->getMessage());

            return back()->with('error_task_file', 'Não foi possível enviar o arquivo.');
        }
    }

    /**
     * Remove um arquivo associado a uma tarefa, garantindo que ele esteja vinculado ao projeto correto e que o usuário tenha permissão para remover arquivos da tarefa.
     */
    public function destroy(Project $project, Task $task, TaskFile $file)
    {
        try {
            $this->ensureTaskBelongsToProject($task, $project);

            $this->ensureFileBelongsToTask($file, $task);

            $this->authorize('update', $task);

            $file->delete();

            return back()->with('success_task', 'Arquivo removido com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao deletar arquivo: ' . $e->getMessage());

            return back()->with('error_task', 'Não foi possível remover o arquivo.');
        }
    }

    public function download(Project $project, Task $task, TaskFile $file)
    {
        try {
            $this->ensureTaskBelongsToProject($task, $project);

            $this->ensureFileBelongsToTask($file, $task);

            if (Storage::disk('public')->exists($file->path)) {
                return Storage::disk('public')->download($file->path, $file->original_name);
            }

            return back()->with('error_task', 'Arquivo não encontrado para download.');
        } catch (\Throwable $e) {
            Log::error('Erro ao baixar arquivo: ' . $e->getMessage());

            return back()->with('error_task', 'Não foi possível baixar o arquivo.');
        }
    }

    /**
     * Aborta a requisição com um erro 404 se a tarefa não pertencer ao projeto, garantindo que as operações sejam realizadas apenas em tarefas associadas ao projeto correto.
     */
    protected function ensureTaskBelongsToProject(Task $task, Project $project)
    {
        abort_if($task->project_id !== $project->id, 404, 'Esta tarefa não pertence a este projeto.');
    }

    /**
     * Aborta a requisição com um erro 404 se o arquivo não pertencer à tarefa, garantindo que as operações sejam realizadas apenas em arquivos associados à tarefa correta.
     */
    protected function ensureFileBelongsToTask(TaskFile $file, Task $task)
    {
        abort_if($file->task_id !== $task->id, 404, 'Este arquivo não pertence a esta tarefa.');
    }
}
