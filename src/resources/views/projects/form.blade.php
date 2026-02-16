@props([
    'project' => null,
    'action',
    'method' => 'POST',
])

@php
    $project = $project ?? null;
    $method = $method ?? 'POST';
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6">

    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Título --}}
    <div>
        <x-input-label for="title" value="Título" />
        <x-ui.input name="title" :value="old('title', $project?->title)" :error="$errors->first('title')" class="mt-1 block w-full" />
    </div>

    {{-- Descrição --}}
    <div>
        <x-input-label for="description" value="Descrição" />
        <textarea name="description" rows="4"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
            rounded-md shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('description', $project?->description) }}</textarea>

        @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Datas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="start_date" value="Data de Início" />
            <x-ui.input type="date" name="start_date" :value="old('start_date', $project?->start_date?->format('Y-m-d'))" :error="$errors->first('start_date')"
                class="mt-1 block w-full" />
        </div>

        <div>
            <x-input-label for="due_date" value="Data Prevista de Conclusão" />
            <x-ui.input type="date" name="due_date" :value="old('due_date', $project?->due_date?->format('Y-m-d'))" :error="$errors->first('due_date')" class="mt-1 block w-full" />
        </div>
    </div>

    {{-- Upload --}}
    <div>
        <x-input-label for="file" value="Anexar Arquivo" />
        <x-ui.input type="file" name="file" :error="$errors->first('file')" class="mt-1 block w-full" />
    </div>

    {{-- Botões --}}
    <div class="flex justify-end gap-3">
        <x-ui.button as="a" href="{{ route('projects.index') }}" variant="secondary" size="md">
            Cancelar
        </x-ui.button>

        <x-ui.button type="submit">
            Salvar
        </x-ui.button>
    </div>

</form>
