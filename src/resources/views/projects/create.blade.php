<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Criar Novo Projeto</h2>

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
        <x-ui.card class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @include('projects.form', [
                'action' => route('projects.store'),
            ])
        </x-ui.card>
    </div>


</x-app-layout>
