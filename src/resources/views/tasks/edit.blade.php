<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Editar Tarefa
        </h2>
        @if (session('error'))
            <div
                style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; margin-bottom: 1rem; border-radius: 0.5rem;">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('projects.tasks.update', [$project, $task]) }}"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Título -->
                        <div>
                            <label for="title"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Título <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                                rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600
                                focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                required autofocus>
                            @error('title')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descrição
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                                rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600
                                focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                placeholder="Descreva a tarefa...">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Data e Prioridade -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label for="due_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Data de Vencimento
                                </label>
                                <input type="date" name="due_date" id="due_date"
                                    value="{{ old('due_date') ?? $task->due_date?->format('Y-m-d') }}"
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                                    rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600
                                    focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @error('due_date')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priority"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Prioridade
                                </label>
                                <select name="priority" id="priority"
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                                    rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600
                                    focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="low"
                                        {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Baixa</option>
                                    <option value="medium"
                                        {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Média
                                    </option>
                                    <option value="high"
                                        {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>Alta
                                    </option>
                                </select>
                                @error('priority')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- Arquivo PDF -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Anexar PDF
                            </label>
                            <input type="file" name="file" accept="application/pdf"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                                rounded-md shadow-sm">
                            @error('file')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ url()->previous() }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700
                                border border-transparent rounded-md font-semibold text-xs
                                text-gray-700 dark:text-gray-300 uppercase tracking-widest
                                hover:bg-gray-300 dark:hover:bg-gray-600
                                focus:outline-none focus:ring-2 focus:ring-indigo-500
                                focus:ring-offset-2 dark:focus:ring-offset-gray-800
                                transition ease-in-out duration-150">
                                Cancelar
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500
                                border border-transparent rounded-md font-semibold text-xs
                                text-white uppercase tracking-widest
                                hover:bg-indigo-700 dark:hover:bg-indigo-600
                                focus:outline-none focus:ring-2 focus:ring-indigo-500
                                focus:ring-offset-2 dark:focus:ring-offset-gray-800
                                transition ease-in-out duration-150">
                                Atualizar Tarefa
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
