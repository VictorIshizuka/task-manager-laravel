<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectFileRequest;

use App\Models\{Project, ProjectFile};

use Illuminate\Support\Facades\{Log, Storage};

class ProjectFileController extends Controller
{
    /**
     * Armazena um arquivo associado a um projeto, garantindo que ele esteja vinculado ao projeto correto e que o usuário tenha permissão para adicionar arquivos ao projeto.
     */
    public function store(StoreProjectFileRequest $request, Project $project)
    {
        try {
            $path = $request->file('file')->store('projects/' . $project->id, 'public');

            $project->files()->create([
                'path' => $path,
                'original_name' => $request->file('file')->getClientOriginalName(),
            ]);

            return back()->with('success_project_file', 'Arquivo enviado com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao enviar arquivo para projeto: ' . $e->getMessage());

            return back()->with('error_project_file', 'Não foi possível enviar o arquivo.');
        }
    }

    /**
     * Remove um arquivo associado a um projeto, garantindo que ele esteja vinculado ao projeto correto e que o usuário tenha permissão para remover arquivos do projeto.
     */
    public function destroy(Project $project, ProjectFile $file)
    {
        $this->ensureFileBelongsToProject($file, $project);

        $this->authorize('update', $project);

        $file->delete();

        return back()->with('success_project_file', 'Arquivo removido com sucesso!');
    }

    /**
     * Faz o download de um arquivo associado a um projeto, garantindo que ele esteja vinculado ao projeto correto e que o usuário tenha permissão para visualizar o projeto. Se o arquivo não for encontrado, retorna uma mensagem de erro adequada.
     */
    public function download(Project $project, ProjectFile $file)
    {
        try {
            $this->ensureFileBelongsToProject($file, $project);

            $this->authorize('view', $project);

            if (Storage::disk('public')->exists($file->path)) {
                return Storage::disk('public')->download($file->path, $file->original_name);
            }

            return back()->with('error_task', 'Arquivo não encontrado para download.');
        } catch (\Throwable $e) {
            Log::error('Erro ao baixar arquivo do projeto: ' . $e->getMessage());

            return back()->with('error_project_file', 'Não foi possível baixar o arquivo.');
        }
    }

    /**
     * Verifica se um arquivo pertence a um projeto específico, abortando com um erro 404 se não pertencer. Isso é importante para garantir a segurança e integridade dos dados, evitando que usuários acessem ou manipulem arquivos que não estão relacionados ao projeto correto.
     */
    protected function ensureFileBelongsToProject(ProjectFile $file, Project $project)
    {
        abort_if($file->project_id !== $project->id, 404, 'Arquivo não pertence a este projeto.');
    }
}
