<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectFileRequest;

use App\Models\Project;
use App\Models\ProjectFile;

use Illuminate\Support\Facades\Storage;

class ProjectFileController extends Controller
{
    public function store(StoreProjectFileRequest $request, Project $project)
    {
        $path = $request->file('file')->store('projects', 'public');

        $project->files()->create([
            'path' => $path,
            'original_name' => $request->file('file')->getClientOriginalName(),
        ]);

        return back()->with('success_project_file', 'Arquivo enviado com sucesso!');
    }

    public function destroy(Project $project, ProjectFile $file)
    {
        abort_unless($file->project_id === $project->id, 404);

        $this->authorize('update', $project);

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return back()->with('success_project_file', 'Arquivo removido com sucesso!');
    }

    public function download(Project $project, ProjectFile $file)
    {
        $this->authorize('view', $project);

        if ($file->project_id !== $project->id) {
            abort(404);
        }

        return Storage::disk('public')
            ->download($file->path, $file->original_name);
    }
}
