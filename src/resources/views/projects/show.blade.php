@php
    // Traduções para pt-BR
    $statusTranslations = [
        'task' => [
            'pending' => 'Pendente',
            'in_progress' => 'Em andamento',
            'done' => 'Concluída',
        ],
        'project' => [
            'active' => 'Ativo',
            'in_progress' => 'Em andamento',
            'completed' => 'Concluído',
            'archived' => 'Arquivado',
        ],
        'priority' => [
            'low' => 'Baixa',
            'medium' => 'Média',
            'high' => 'Alta',
        ],
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Detalhes do Projeto" :backUrl="route('projects.index')" :editUrl="route('projects.edit', $project)" :deleteUrl="route('projects.destroy', $project)"
            deleteMessage="Tem certeza que deseja excluir este projeto?" :model="$project" />

        @if (session('error'))
            <div class="pt-4">
                <x-ui.alert type="error">
                    {{ session('error') }}
                </x-ui.alert>
            </div>
        @endif

        <x-alerts.project />
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Card Principal -->
            <x-ui.card class="mb-6">

                <!-- Título -->
                <h1 class="text-3xl font-bold mb-4">
                    {{ $project->title }}
                </h1>

                <x-alerts.project />

                <!-- Descrição -->
                @if ($project->description)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
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
                                {{ $project->start_date->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $project->created_at->diffForHumans() }}
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
                                {{ $project->due_date->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $project->due_date->diffForHumans() }}
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
                                {{ $project->start_date->diffInDays($project->due_date) }}
                                dias
                            </p>
                        </div>
                    @endif

                </div>

                <!-- Arquivos do Projeto -->
                <div class=" mt-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Arquivos ({{ $project->files->count() }})
                        </h3>

                        @can('update', $project)
                            <form method="POST" action="{{ route('projects.files.store', $project) }}"
                                enctype="multipart/form-data" class="flex gap-2">
                                @csrf
                                <div class="flex w-full gap-3 items-start">
                                    <div class="flex-1">
                                        <x-ui.input type="file" name="file" :error="$errors->first('file')" />
                                    </div>
                                    <x-ui.button type="submit" variant="primary" size="sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 16V4m0 0l-4 4m4-4l4 4M4 20h16" />
                                        </svg>
                                        Upload
                                    </x-ui.button>
                                </div>
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
                                <form method="POST" action="{{ route('projects.files.destroy', [$project, $file]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="outline-danger" title="Excluir" size="sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </x-ui.button>
                                </form>
                            @endcan
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum arquivo anexado.
                        </p>
                    @endforelse
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
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Atualizado em: {{ $project->updated_at->format('d/m/Y H:i') }}
                            </span>
                        @endif
                    </div>
                </div>

            </x-ui.card>

            {{-- @if (auth()->id() === $project->owner_id) --}}
            <x-ui.card class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Compartilhar Projeto</h3>

                @if (auth()->id() === $project->owner_id)
                    <form method="POST" action="{{ route('projects.members.store', $project) }}">
                        @csrf

                        <div class="flex gap-3 items-start">
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

                            <div class="flex items-end pt-6">
                                <x-ui.button type="submit" variant="primary" size="md"
                                    title="Adicionar Membro">
                                    Adicionar Membro
                                </x-ui.button>
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
                                <x-ui.badge :color="$member->id === $project->owner_id
                                    ? 'green'
                                    : ($member->id === auth()->id()
                                        ? 'blue'
                                        : 'gray')">
                                    {{ $member->id === $project->owner_id ? 'Proprietário' : ($member->id === auth()->id() ? 'Você' : 'Membro') }}
                                </x-ui.badge>

                            </div>

                            <!-- Lado direito -->
                            @if (auth()->id() === $project->owner_id || auth()->id() === $member->id)
                                <form method="POST"
                                    action="{{ route('projects.members.destroy', [$project, $member]) }}">
                                    @csrf
                                    @method('DELETE')

                                    <x-ui.button variant="outline-danger" size="sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </x-ui.button>
                                </form>
                            @endif

                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                            Nenhum membro neste projeto ainda.
                        </p>
                    @endforelse
                </div>
            </x-ui.card>
            {{-- @endif --}}

            <!-- Tarefas do Projeto -->
            <x-ui.card class="mt-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">
                        Tarefas ({{ $project->tasks->count() }})
                    </h3>

                    @can('create', [App\Models\Task::class, $project])
                        <x-ui.button as="a" href="{{ route('projects.tasks.create', $project) }}"
                            variant="primary" size="md">
                            Nova Tarefa
                        </x-ui.button>
                    @endcan
                </div>

                <!-- Mensagens de alerta -->
                <x-alerts.task />

                <!-- Filtros -->
                <form method="GET" class="flex flex-wrap items-end gap-3 mb-4">

                    <select name="status"
                        class="h-10 border-gray-300 dark:border-gray-700
                                dark:bg-gray-900 dark:text-gray-300
                                rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos os Status</option>
                        <option value="pending" @selected(request('status') == 'pending')>Pendente</option>
                        <option value="in_progress" @selected(request('status') == 'in_progress')>Em andamento</option>
                        <option value="done" @selected(request('status') == 'done')>Concluída</option>
                    </select>

                    <select name="priority"
                        class="h-10 border-gray-300 dark:border-gray-700
                                dark:bg-gray-900 dark:text-gray-300
                                rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todas as Prioridades</option>
                        <option value="low" @selected(request('priority') == 'low')>Baixa</option>
                        <option value="medium" @selected(request('priority') == 'medium')>Média</option>
                        <option value="high" @selected(request('priority') == 'high')>Alta</option>
                    </select>

                    <x-ui.button type="submit" variant="primary" size="md">
                        Filtrar
                    </x-ui.button>

                    @if (request('status') || request('priority'))
                        <x-ui.button as="a" href="{{ route('projects.show', $project) }}"
                            variant="secondary" size="md">
                            Limpar
                        </x-ui.button>
                    @endif

                </form>

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
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7" />
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

                                    <div class="flex flex-wrap gap-3 mt-1 text-xs text-gray-500 dark:text-gray-400">

                                        @if ($task->due_date)
                                            <span
                                                class="flex items-center {{ $task->due_date->isPast() && $task->status !== 'done' ? 'text-red-600 dark:text-red-400' : '' }}">
                                                <svg class="w-3 h-3 mr-1 mb-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                {{ $task->due_date->format('d/m/Y') }}
                                            </span>
                                        @endif

                                        <x-ui.badge class="uppercase" :color="$task->priority == 'high'
                                            ? 'red'
                                            : ($task->priority == 'medium'
                                                ? 'yellow'
                                                : 'green')">
                                            {{ $statusTranslations['priority'][$task->priority] ?? ucfirst($task->priority) }}
                                        </x-ui.badge>
                                        <x-ui.badge class="uppercase" :color="$task->status == 'done'
                                            ? 'green'
                                            : ($task->status == 'in_progress'
                                                ? 'blue'
                                                : 'gray')">
                                            {{ $statusTranslations['task'][$task->status] ?? ucfirst($task->status) }}
                                        </x-ui.badge>

                                    </div>
                                </div>
                            </div>

                            {{-- Ações --}}
                            <div class="flex items-center gap-2 ml-4">
                                @can('update', $task)
                                    @if ($task->status !== 'done')
                                        <form method="POST"
                                            action="{{ route('projects.tasks.status-toggle', [$project, $task]) }}"
                                            class="ml-2">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button type="submit" variant="outline-blue" size="sm"
                                                title="Alternar status">
                                                @if ($task->status === 'pending')
                                                    Inicar tarefa
                                                @elseif($task->status === 'in_progress')
                                                    Parar tarefa
                                                @endif
                                            </x-ui.button>
                                        </form>
                                    @endif
                                @endcan
                                <x-ui.button as="a"
                                    href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                    variant="outline-secondary" size="sm" title="Ver detalhes">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </x-ui.button>

                                @can('update', $task)
                                    <x-ui.button as="a"
                                        href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                                        variant="outline-warning" size="sm" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </x-ui.button>
                                @endcan

                                @can('delete', $task)
                                    <form method="POST"
                                        action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                                        onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                        @csrf
                                        @method('DELETE')

                                        <x-ui.button type="submit" variant="outline-danger" size="sm"
                                            title="Excluir">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </x-ui.button>
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
                                <x-ui.button as="a" href="{{ route('projects.tasks.create', $project) }}"
                                    size="md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Criar primeira tarefa
                                </x-ui.button>
                            </div>
                        @endcan
                    </div>
                @endforelse

            </x-ui.card>

        </div>
    </div>
</x-app-layout>
