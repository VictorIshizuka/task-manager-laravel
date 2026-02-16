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
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>

    <div class="py-8 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Cards de resumo das tasks (horizontal) -->
        <div class="flex flex-wrap gap-4">
            <x-ui.card class="flex-1 min-w-[200px] flex justify-between items-center p-4">
                <span class="font-semibold text-gray-700 dark:text-gray-200">Tarefas Pendentes</span>
                <x-ui.badge class="uppercase" color="yellow">{{ $pending }}</x-ui.badge>
            </x-ui.card>

            <x-ui.card class="flex-1 min-w-[200px] flex justify-between items-center p-4">
                <span class="font-semibold text-gray-700 dark:text-gray-200">Tarefas Em Andamento</span>
                <x-ui.badge class="uppercase" color="blue">{{ $inProgress }}</x-ui.badge>
            </x-ui.card>

            <x-ui.card class="flex-1 min-w-[200px] flex justify-between items-center p-4">
                <span class="font-semibold text-gray-700 dark:text-gray-200">Tarefas Concluídas</span>
                <x-ui.badge class="uppercase" color="green">{{ $done }}</x-ui.badge>
            </x-ui.card>
        </div>

        <!-- Lista de projetos -->
        <div class="space-y-6">
            @forelse($projects as $project)
                <x-ui.card class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $project->title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300">{{ $project->description }}</p>
                        </div>
                        <div class="space-x-2">
                            @php
                                $statusColor = match ($project->status) {
                                    'active' => 'blue',
                                    'in_progress' => 'yellow',
                                    'done', 'completed' => 'green',
                                    'archived' => 'gray',
                                    default => 'gray',
                                };
                            @endphp
                            <x-ui.badge class="uppercase"
                                :color="$statusColor">{{ $statusTranslations['task'][$project->status] ?? ucfirst($project->status) }}</x-ui.badge>
                        </div>
                    </div>

                    <!-- Progress bar -->
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                            <div class="h-4 rounded-full"
                                style="width: {{ $project->progress }}%; background-color: #3b82f6;">
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $project->progress }}% concluído</p>
                    </div>

                    <!-- Lista de tasks -->
                    <div class="mt-4 space-y-2">
                        @foreach ($project->tasks as $task)
                            <div
                                class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 p-3 border-b border-gray-200 dark:border-gray-700 rounded-t-none">
                                <div class="flex flex-col">
                                    <span
                                        class="font-medium text-gray-900 dark:text-gray-100">{{ $task->title }}</span>
                                    <span
                                        class="text-sm text-gray-500 dark:text-gray-400">{{ $task->due_date?->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    @php
                                        $priorityColor = match ($task->priority) {
                                            'high' => 'red',
                                            'medium' => 'yellow',
                                            'low' => 'green',
                                            default => 'gray',
                                        };
                                        $statusColor = match ($task->status) {
                                            'pending' => 'yellow',
                                            'in_progress' => 'blue',
                                            'done' => 'green',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <div class="mr-2">
                                        <x-ui.badge class="uppercase" :color="$priorityColor">
                                            {{ $statusTranslations['priority'][$task->priority] ?? ucfirst($task->priority) }}
                                        </x-ui.badge>
                                    </div>
                                    <div class="ml-2">
                                        <x-ui.badge class="uppercase" :color="$statusColor">
                                            {{ $statusTranslations['task'][$task->status] ?? ucfirst($task->status) }}
                                        </x-ui.badge>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            @empty
                <p class="text-gray-600 dark:text-gray-400">Você ainda não tem projetos atribuídos.</p>
            @endforelse
        </div>

    </div>
</x-app-layout>
