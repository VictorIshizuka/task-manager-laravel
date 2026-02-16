@php
    $task = $task ?? null;
    $method = $method ?? 'POST';
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6">

    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Título --}}
    <div>
        <x-input-label for="title" value="Título *" />
        <x-ui.input name="title" :value="old('title', $task?->title)" :error="$errors->first('title')" class="mt-1 block w-full" required autofocus />
    </div>

    {{-- Descrição --}}
    <div>
        <x-input-label for="description" value="Descrição" />
        <textarea name="description" rows="4"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
            rounded-md shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600"
            placeholder="Descreva a tarefa...">{{ old('description', $task?->description) }}</textarea>

        @error('description')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Data e Prioridade --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <x-input-label for="due_date" value="Data de conclusão" />
            <x-ui.input type="date" name="due_date" :value="old('due_date', $task?->due_date?->format('Y-m-d'))" :error="$errors->first('due_date')" class="mt-1 block w-full" />
        </div>

        <div>
            <x-input-label for="priority" value="Prioridade" />
            <select name="priority"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900
                dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500">

                @php
                    $priority = old('priority', $task?->priority ?? 'medium');
                @endphp

                <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>Baixa</option>
                <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>Média</option>
                <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>Alta</option>
            </select>

            @error('priority')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Arquivo --}}
    <div>
        <x-input-label value="Anexar PDF" />
        <x-ui.input type="file" name="file" :error="$errors->first('file')" />
    </div>

    {{-- Botões --}}
    <div class="flex justify-end gap-4">
        <x-ui.button as="a" href="{{ url()->previous() }}" variant="secondary" size="md">
            Cancelar
        </x-ui.button>

        <x-ui.button type="submit">
            Salvar
        </x-ui.button>
    </div>

</form>
