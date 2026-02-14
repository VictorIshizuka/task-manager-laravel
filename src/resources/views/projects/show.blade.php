<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Detalhes do Projeto
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('projects.index') }}"
                    class="inline-flex items-center px-3 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>

                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}"
                        class="inline-flex items-center px-3 py-2 bg-yellow-600 dark:bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 dark:hover:bg-yellow-600 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Editar
                    </a>
                @endcan

                @can('delete', $project)
                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                        onsubmit="return confirm('Tem certeza que deseja excluir este projeto?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 bg-red-600 dark:bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Excluir
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Card Principal -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">

                    <!-- Título -->
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                        {{ $project->title }}
                    </h1>

                    <!-- Descrição -->
                    @if ($project->description)
                        <div class="mb-6">
                            <h3
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Descrição
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $project->description }}
                            </p>
                        </div>
                    @endif

                    <!-- Informações do Projeto -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- Data de Início -->
                        @if ($project->start_date)
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de
                                        Início</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($project->start_date)->diffForHumans() }}
                                </p>
                            </div>
                        @endif

                        <!-- Data de Conclusão -->
                        @if ($project->due_date)
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de
                                        Conclusão</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($project->due_date)->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($project->due_date)->diffForHumans() }}
                                </p>
                            </div>
                        @endif

                        <!-- Duração -->
                        @if ($project->start_date && $project->due_date)
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Duração</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($project->start_date)->diffInDays(\Carbon\Carbon::parse($project->due_date)) }}
                                    dias
                                </p>
                            </div>
                        @endif

                    </div>

                    <!-- Arquivos do Projeto -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    Arquivos ({{ $project->files->count() }})
                                </h3>

                                @can('update', $project)
                                    <form method="POST" action="{{ route('projects.files.store', $project) }}"
                                        enctype="multipart/form-data" class="flex gap-2">
                                        @csrf
                                        <input type="file" name="file" class="text-sm border-gray-300 rounded-md">

                                        <button
                                            class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-xs rounded-md ">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 16V4m0 0l-4 4m4-4l4 4M4 20h16" />
                                            </svg>
                                            Upload
                                        </button>
                                    </form>
                                @endcan
                            </div>

                            @forelse($project->files as $file)
                                <div class="flex justify-between items-center border-b py-2">
                                    <a href="{{ route('projects.files.download', [$project, $file]) }}"
                                        class="text-indigo-600 hover:underline text-sm">
                                        {{ $file->original_name }}
                                    </a>

                                    @can('update', $project)
                                        <form method="POST"
                                            action="{{ route('projects.files.destroy', [$project, $file]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-500 text-xs">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Nenhum arquivo anexado.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Metadados -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Criado por: {{ $project->owner->name ?? 'Desconhecido' }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Criado em: {{ $project->created_at->format('d/m/Y H:i') }}
                            </span>
                            @if ($project->updated_at != $project->created_at)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Atualizado em: {{ $project->updated_at->format('d/m/Y H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            @if (auth()->id() === $project->owner_id)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4">Compartilhar Projeto</h3>

                    <form method="POST" action="{{ route('projects.members.add', $project) }}">
                        @csrf

                        <div class="flex items-end gap-3">

                            <div class="flex-1">
                                <input type="email" name="email" placeholder="Email do usuário"
                                    class="w-full border-gray-300 dark:border-gray-700
                                        dark:bg-gray-900 dark:text-gray-300
                                        rounded-md shadow-sm
                                        focus:border-indigo-500 dark:focus:border-indigo-600
                                        focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            </div>

                            <button type="submit"
                                class="shrink-0 inline-flex items-center px-4 py-2
                                    bg-indigo-600 dark:bg-indigo-500
                                    border border-transparent rounded-md
                                    font-semibold text-xs text-white uppercase tracking-widest
                                    hover:bg-indigo-700 dark:hover:bg-indigo-600
                                    transition">
                                + Adicionar
                            </button>

                        </div>
                    </form>

                    <div class="mt-4">
                        <h4 class="font-semibold">Membros:</h4>

                        @foreach ($project->members as $member)
                            <div class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $member->name }}</span>
                                @if ($member->id === $project->owner_id)
                                    <span class="text-green-500">(Proprietário)</span>
                                @endif
                                @if ($member->id === auth()->id())
                                    <span class="text-blue-500">(Você)</span>
                                @endif
                                @if ($member->id !== $project->owner_id && $member->id !== auth()->id())
                                    <span class="text-gray-400">(Membro)</span>
                                @endif

                                @if ($member->id !== $project->owner_id)
                                    {{-- Não permitir remover o proprietário --}}
                                    <form method="POST"
                                        action="{{ route('projects.members.remove', [$project, $member]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 text-sm">
                                            Remover
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tarefas do Projeto -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Tarefas ({{ $project->tasks->count() }})
                        </h3>

                        @can('create', [App\Models\Task::class, $project])
                            <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}"
                                class="inline-flex items-center px-3 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">
                                + Nova Tarefa
                            </a>
                        @endcan
                    </div>

                    @forelse($project->tasks as $task)
                        <div class="border-b border-gray-200 dark:border-gray-700 py-4 last:border-b-0">
                            <div class="flex items-start justify-between">

                                <div class="flex items-start">
                                    <input type="checkbox" {{ $task->status === 'done' ? 'checked' : '' }} disabled
                                        class="mt-1 rounded border-gray-300 dark:border-gray-700 text-indigo-600">

                                    <div class="ml-3">
                                        <p
                                            class="text-sm font-medium
                                {{ $task->status === 'done' ? 'line-through text-gray-400' : 'text-gray-900 dark:text-gray-100' }}">
                                            {{ $task->title }}
                                        </p>

                                        <div class="flex gap-3 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            @if ($task->due_date)
                                                <span>
                                                    Vencimento:
                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                                </span>
                                            @endif

                                            <span>
                                                Prioridade: {{ ucfirst($task->priority) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('tasks.show', $task) }}"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 text-sm">
                                    Ver →
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                            Nenhuma tarefa neste projeto ainda.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
