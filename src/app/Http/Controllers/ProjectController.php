<?php

namespace App\Http\Controllers;


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

            return back()->with('error', 'Não foi possível carregar os projetos.');
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
                ->with('success', 'Projeto criado com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao criar projeto: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Erro ao criar projeto. Tente novamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        try {
            $project->load([
                'tasks',
                'members',
                'owner',
                'files'
            ]);

            return view('projects.show', compact('project'));
        } catch (\Throwable $e) {
            Log::error('Erro ao exibir projeto: ' . $e->getMessage());

            return redirect()
                ->route('projects.index')
                ->with('error', 'Projeto não encontrado ou indisponível.');
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
                ->with('success', 'Projeto atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar projeto: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Não foi possível atualizar o projeto.');
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
                ->with('success', 'Projeto removido com sucesso.');
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
}
