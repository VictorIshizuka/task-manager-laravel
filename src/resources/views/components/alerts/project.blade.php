@if (session('success_project'))
    <div class="pt-4">
        <x-ui.alert type="success">
            {{ session('success_project') }}
        </x-ui.alert>
    </div>
@endif

@if (session('error_project'))
    <div class="pt-4">
        <x-ui.alert type="error">
            {{ session('error_project') }}
        </x-ui.alert>
    </div>
@endif
