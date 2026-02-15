<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                Detalhes da Tarefa
            </h2>
            @if (session('error'))
                <div
                    style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; margin-bottom: 1rem; border-radius: 0.5rem;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex gap-3">
                @can('update', $task)
                    <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                        class="inline-flex items-center px-3 py-2 bg-indigo-600 dark:bg-indigo-500
                        border border-transparent rounded-md font-semibold text-xs text-white uppercase
                        tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">
                        Editar
                    </a>
                @endcan

                <a href="{{ route('projects.show', $project) }}"
                    class="inline-flex items-center px-3 py-2 bg-gray-200 dark:bg-gray-700
                    border border-transparent rounded-md font-semibold text-xs text-gray-700
                    dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Voltar
                </a>
            </div>
        </div>
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
                            <span
                                class="px-2 py-1 rounded text-xs
                                @if ($task->status === 'pending') bg-gray-200 text-gray-700
                                @elseif($task->status === 'in_progress') bg-yellow-200 text-yellow-800
                                @elseif($task->status === 'done') bg-green-200 text-green-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>

                        <div>
                            <span class="font-medium">Prioridade:</span>
                            {{ ucfirst($task->priority) }}
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
                        enctype="multipart/form-data" class="flex gap-3 items-center mb-6">
                        @csrf

                        <input type="file" name="file" required
                            class="block w-full text-sm text-gray-900 dark:text-gray-300
                                border border-gray-300 dark:border-gray-700 rounded-md
                                cursor-pointer bg-gray-50 dark:bg-gray-900">

                        <button type="submit"
                            class="shrink-0 inline-flex items-center px-2 py-2
                            bg-indigo-600 dark:bg-indigo-500
                            border border-transparent rounded-md
                            font-semibold text-xs text-white uppercase tracking-widest
                            hover:bg-indigo-700 dark:hover:bg-indigo-600
                            transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 16V4m0 0l-4 4m4-4l4 4M4 20h16" />
                            </svg>
                            Upload
                        </button>
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
