<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Projetos
            </h2>
            <x-ui.button as="a" href="{{ route('projects.create') }}" variant="primary" size="md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Novo Projeto
            </x-ui.button>
        </div>

        <!-- Mensagens -->
        @if (session('error'))
            <div class="pt-4">
                <x-ui.alert type="error">
                    {{ session('error') }}
                </x-ui.alert>
            </div>
        @endif

        <x-alerts.project />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Card Principal -->
            <x-ui.card>
                @forelse($projects as $project)
                    <div class="border-b border-gray-200 dark:border-gray-700 py-4 last:border-b-0">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">

                            <!-- Informações do Projeto -->
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold  mb-2">
                                    {{ $project->title }}
                                </h3>

                                @if ($project->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                        {{ Str::limit($project->description, 150) }}
                                    </p>
                                @endif

                                <!-- Datas -->
                                <div class="flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
                                    @if ($project->start_date)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Início:
                                            {{ $project->start_date->format('d/m/Y') }}
                                        </span>
                                    @endif

                                    @if ($project->due_date)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Conclusão:
                                            {{ $project->due_date->format('d/m/Y') }}
                                        </span>
                                    @endif

                                    <!-- Contador de Tasks -->
                                    @if (method_exists($project, 'tasks'))
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                </path>
                                            </svg>
                                            {{ $project->tasks->count() }}
                                            {{ $project->tasks->count() === 1 ? 'tarefa' : 'tarefas' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="flex gap-2 items-start">
                                <x-ui.button as="a" href="{{ route('projects.show', $project) }}" variant="info"
                                    size="md">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    {{-- Ver --}}
                                </x-ui.button>

                                @can('update', $project)
                                    <x-ui.button as="a" href="{{ route('projects.edit', $project) }}"
                                        variant="warning" size="md">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        {{-- Editar --}}
                                    </x-ui.button>
                                @endcan

                                @can('delete', $project)
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este projeto?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="danger" size="md">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            {{-- Excluir --}}
                                        </x-ui.button>
                                    </form>
                                @endcan
                            </div>

                        </div>
                    </div>
                @empty
                    <!-- Estado Vazio -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum projeto</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando um novo projeto.</p>
                        <div class="mt-6">
                            <x-ui.button as="a" href="{{ route('projects.create') }}" variant="primary"
                                size="md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Novo Projeto
                            </x-ui.button>
                        </div>
                    </div>
                @endforelse
            </x-ui.card>

            <!-- Paginação -->
            @if ($projects instanceof \Illuminate\Pagination\LengthAwarePaginator && $projects->hasPages())
                <div class="mt-4">
                    {{ $projects->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
