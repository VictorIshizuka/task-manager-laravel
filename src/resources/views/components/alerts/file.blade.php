@if (session('success_file'))
    <div class="pt-4">
        <x-ui.alert type="success">
            {{ session('success_file') }}
        </x-ui.alert>
    </div>
@endif

@if (session('error_file'))
    <div class="pt-4">
        <x-ui.alert type="error">
            {{ session('error_file') }}
        </x-ui.alert>
    </div>
@endif
