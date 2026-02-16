<x-app-layout>
    <x-slot name="header">

        <x-ui.page-header title="Detalhes da Tarefa" :backUrl="route('projects.show', $project)" :editUrl="route('projects.tasks.edit', [$project, $task])" :deleteUrl="route('projects.tasks.destroy', [$project, $task])"
            deleteMessage="Tem certeza que deseja excluir esta tarefa?" :model="$task" />
        @if (session('error'))
            <div class="pt-4">
                <x-ui.alert type="error">
                    {{ session('error') }}
                </x-ui.alert>
            </div>
        @endif

        <x-alerts.task />
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Card Informações -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                <div class="space-y-4">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $task->title }}
                        </h3>
                        @if ($task->description)
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ $task->description }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-6 text-sm text-gray-600 dark:text-gray-400">

                        <div>
                            <span class="font-medium">Status:</span>
                            <x-ui.badge class="uppercase" :color="$task->status == 'done'
                                ? 'green'
                                : ($task->status == 'in_progress'
                                    ? 'blue'
                                    : 'gray')">
                                {{ str_replace('_', ' ', ucfirst($task->status)) }}
                            </x-ui.badge>

                        </div>

                        <div>
                            <span class="font-medium">Prioridade:</span>
                            <x-ui.badge class="uppercase" :color="$task->priority == 'high'
                                ? 'red'
                                : ($task->priority == 'medium'
                                    ? 'yellow'
                                    : 'green')">
                                {{ ucfirst($task->priority) }}
                            </x-ui.badge>
                        </div>

                        @if ($task->due_date)
                            <div>
                                <span class="font-medium">Vencimento:</span>
                                {{ $task->due_date->format('d/m/Y') }}
                            </div>
                        @endif

                        <div>
                            <span class="font-medium">Projeto:</span>
                            {{ $task->project->title }}
                        </div>

                    </div>

                </div>
            </div>

            <!-- Upload de Arquivos -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Arquivos da Tarefa
                </h3>

                @can('update', $task)
                    <form method="POST" action="{{ route('projects.tasks.files.store', [$project, $task]) }}"
                        enctype="multipart/form-data" class="mb-6 w-full">
                        @csrf
                        <div class="flex flex-col md:flex-row md:items-end gap-3">
                            <div class="flex flex-col md:flex-row gap-3 md:items-end w-full">
                                <x-ui.input type="file" name="file" :error="$errors->first('file')" />
                            </div>
                            <x-ui.button type="submit" size="sm" class="w-auto md:w-auto whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 16V4m0 0l-4 4m4-4l4 4M4 20h16" />
                                </svg>
                                Upload
                            </x-ui.button>
                        </div>
                    </form>
                @endcan
                <!-- Lista de arquivos -->
                @forelse($task->files as $file)
                    <div
                        class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                        <a href="{{ route('projects.tasks.files.download', [$project, $task, $file]) }}"
                            class="text-indigo-600 hover:underline text-sm">
                            {{ $file->original_name }}
                        </a>

                        @can('update', $task)
                            <form method="POST"
                                action="{{ route('projects.tasks.files.destroy', [$project, $task, $file]) }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        @endcan

                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Nenhum arquivo enviado para esta tarefa.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
