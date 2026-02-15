<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Detalhes do Projeto
            </h2>
            @if (session('error'))
                <div
                    style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; margin-bottom: 1rem; border-radius: 0.5rem;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex gap-2">
                <a href="{{ route('projects.index') }}"
                    class="inline-flex items-center px-3 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent
                    rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 transition duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>

                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}"
                        class="inline-flex items-center px-3 py-2 bg-yellow-600 dark:bg-yellow-500 border border-transparent
                        rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 dark:hover:bg-yellow-600 transition duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Editar Projeto
                    </a>
                @endcan

                @can('delete', $project)
                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                        onsubmit="return confirm('Tem certeza que deseja excluir este projeto?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 bg-red-600 dark:bg-red-500 border border-transparent
                            rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600 transition duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Excluir Projeto
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
                        <div class="">
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
                                            class="shrink-0 inline-flex items-center px-2 py-2
                                            bg-indigo-600 dark:bg-indigo-500
                                            border border-transparent rounded-md
                                            font-semibold text-xs text-white uppercase tracking-widest
                                            hover:bg-indigo-700 dark:hover:bg-indigo-600
                                            transition duration-200">
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
                                <div
                                    class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 py-3 last:border-b-0">
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
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum arquivo anexado.
                                </p>
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

            {{-- @if (auth()->id() === $project->owner_id) --}}
            <div class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Compartilhar Projeto</h3>

                @if (auth()->id() === $project->owner_id)
                    <form method="POST" action="{{ route('projects.members.add', $project) }}">
                        @csrf

                        <div class="flex gap-3">

                            <div class="flex-1">
                                <label class="block text-sm mb-1 text-gray-600 dark:text-gray-400">
                                    Selecionar usuário
                                </label>

                                <select name="user_id"
                                    class="w-full h-10 border-gray-300 dark:border-gray-700
                                        dark:bg-gray-900 dark:text-gray-300
                                        rounded-md shadow-sm
                                        focus:border-indigo-500 focus:ring-indigo-500">

                                    <option value="">Selecione um usuário</option>

                                    @foreach ($users as $user)
                                        {{-- @if ($user->id !== $project->owner_id) --}}
                                        <option value="{{ $user->id }}"
                                            {{ $project->members->contains($user->id) ? 'disabled' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                        {{-- @endif --}}
                                    @endforeach
                                </select>

                                @error('user_id')
                                    <p class="text-red-500 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                    class="h-10 px-4 mt-6
                                        inline-flex items-center justify-center
                                        bg-indigo-600 dark:bg-indigo-500
                                        border border-transparent rounded-md
                                        text-sm font-medium text-white uppercase
                                        hover:bg-indigo-700 dark:hover:bg-indigo-600
                                        transition">
                                    Adicionar
                                </button>
                            </div>

                        </div>
                    </form>
                @endif

                <div class="mt-4">
                    <h4 class="font-semibold">Membros:</h4>

                    @forelse($project->members as $member)
                        <div class="flex items-center justify-between mt-2 text-md text-gray-500 dark:text-gray-400">

                            <!-- Lado esquerdo -->
                            <div class="flex items-center gap-3">

                                <span class="font-medium text-gray-800 dark:text-gray-200">
                                    {{ $member->name }}
                                </span>

                                {{-- Badge de status --}}
                                @if ($member->id === $project->owner_id)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-600">
                                        Proprietário
                                    </span>
                                @elseif ($member->id === auth()->id())
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-600">
                                        Você
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-200 text-gray-600">
                                        Membro
                                    </span>
                                @endif

                            </div>

                            <!-- Lado direito -->
                            @if (auth()->id() === $project->owner_id || auth()->id() === $member->id)
                                <form method="POST"
                                    action="{{ route('projects.members.remove', [$project, $member]) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-500 hover:text-red-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            @endif

                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                            Nenhum membro neste projeto ainda.
                        </p>
                    @endforelse
                </div>
            </div>
            {{-- @endif --}}

            <!-- Tarefas do Projeto -->
            <!-- Tarefas do Projeto -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Tarefas ({{ $project->tasks->count() }})
                        </h3>

                        @can('create', [App\Models\Task::class, $project])
                            <a href="{{ route('projects.tasks.create', $project) }}"
                                class="inline-flex items-center px-3 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent
                                rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 transition duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nova Tarefa
                            </a>
                        @endcan
                    </div>

                    <!-- Filtros -->
                    <form method="GET" class="flex flex-wrap items-end gap-3 mb-4">

                        <div>
                            <select name="status"
                                class="h-10 border-gray-300 dark:border-gray-700
                                dark:bg-gray-900 dark:text-gray-300
                                rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos os Status</option>
                                <option value="pending" @selected(request('status') == 'pending')>Pendente</option>
                                <option value="in_progress" @selected(request('status') == 'in_progress')>Em andamento</option>
                                <option value="done" @selected(request('status') == 'done')>Concluída</option>
                            </select>
                        </div>

                        <div>
                            <select name="priority"
                                class="h-10 border-gray-300 dark:border-gray-700
                                dark:bg-gray-900 dark:text-gray-300
                                rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todas as Prioridades</option>
                                <option value="low" @selected(request('priority') == 'low')>Baixa</option>
                                <option value="medium" @selected(request('priority') == 'medium')>Média</option>
                                <option value="high" @selected(request('priority') == 'high')>Alta</option>
                            </select>
                        </div>

                        <button type="submit"
                            class="h-10 px-4 bg-indigo-600 dark:bg-indigo-500
                            text-white rounded-md text-sm font-medium
                            hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">
                            Filtrar
                        </button>

                        @if (request('status') || request('priority'))
                            <a href="{{ route('projects.show', $project) }}"
                                class="h-10 px-4 bg-gray-200 dark:bg-gray-700
                                text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium
                                hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center">
                                Limpar
                            </a>
                        @endif

                    </form>

                    <!-- Mensagens -->
                    @if (session('success_task'))
                        <div
                            class="mb-4 p-3 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded">
                            {{ session('success_task') }}
                        </div>
                    @endif

                    @if (session('error_task'))
                        <div
                            class="mb-4 p-3 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded">
                            {{ session('error_task') }}
                        </div>
                    @endif

                    <!-- Lista de Tasks -->
                    @forelse($project->tasks->filter(function($task) {
                        $statusMatch = !request('status') || $task->status == request('status');
                        $priorityMatch = !request('priority') || $task->priority == request('priority');
                        return $statusMatch && $priorityMatch;
                    }) as $task)
                        <div class="border-b border-gray-200 dark:border-gray-700 py-4 last:border-b-0">
                            <div class="flex items-start justify-between">

                                <div class="flex items-start flex-1">

                                    {{-- Checkbox de Conclusão --}}
                                    @can('update', $task)
                                        <form method="POST"
                                            action="{{ route('projects.tasks.toggle', [$project, $task]) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="relative flex items-center justify-center w-5 h-5 mt-0.5
                                        border rounded-md transition
                                        {{ $task->status === 'done'
                                            ? 'bg-indigo-600 border-indigo-600'
                                            : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:border-indigo-400' }}">

                                                @if ($task->status === 'done')
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif

                                            </button>
                                        </form>
                                    @else
                                        <div
                                            class="w-5 h-5 mt-0.5 border rounded-md
                                {{ $task->status === 'done' ? 'bg-indigo-600 border-indigo-600' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600' }}">
                                            @if ($task->status === 'done')
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </div>
                                    @endcan

                                    <div class="ml-3 flex-1">
                                        <p
                                            class="text-sm font-medium
                                {{ $task->status === 'done' ? 'line-through text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-gray-100' }}">
                                            {{ $task->title }}
                                        </p>

                                        <div
                                            class="flex flex-wrap gap-3 mt-1 text-xs text-gray-500 dark:text-gray-400">

                                            @if ($task->due_date)
                                                <span
                                                    class="flex items-center {{ $task->due_date->isPast() && $task->status !== 'done' ? 'text-red-600 dark:text-red-400' : '' }}">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    {{ $task->due_date->format('d/m/Y') }}
                                                </span>
                                            @endif

                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                {{ $task->priority == 'high' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : '' }}
                                                {{ $task->priority == 'medium' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' : '' }}
                                                {{ $task->priority == 'low' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : '' }}
                                            ">
                                                {{ ucfirst($task->priority) }}
                                            </span>

                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                {{ $task->status == 'done' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : '' }}
                                                {{ $task->status == 'in_progress' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : '' }}
                                                {{ $task->status == 'pending' ? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' : '' }}
                                            ">
                                                {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ações --}}
                                <div class="flex items-center gap-2 ml-4">
                                    <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition"
                                        title="Ver detalhes">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>

                                    @can('update', $task)
                                        <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition"
                                            title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                    @endcan

                                    @can('delete', $task)
                                        <form method="POST"
                                            action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition"
                                                title="Excluir">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan

                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ request('status') || request('priority') ? 'Nenhuma tarefa encontrada com esses filtros.' : 'Nenhuma tarefa neste projeto ainda.' }}
                            </p>
                            @can('create', [App\Models\Task::class, $project])
                                <div class="mt-4">
                                    <a href="{{ route('projects.tasks.create', $project) }}"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Criar primeira tarefa
                                    </a>
                                </div>
                            @endcan
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
