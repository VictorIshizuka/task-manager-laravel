<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectFileController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'file' => 'required|file|max:2048|mimes:pdf,jpg,jpeg,png|mimetypes:application/pdf,image/jpeg,image/png'
        ]);

        $path = $request->file('file')->store('projects', 'public');

        $project->files()->create([
            'path' => $path,
            'original_name' => $request->file('file')->getClientOriginalName(),
        ]);

        return back()->with('success', 'Arquivo enviado com sucesso.');
    }

    public function destroy(Project $project, ProjectFile $file)
    {
        $this->authorize('update', $project);

        if ($file->project_id !== $project->id) {
            abort(404);
        }

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return back()->with('success', 'Arquivo removido com sucesso.');
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
