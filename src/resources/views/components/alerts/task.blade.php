@if (session('success_task'))
    <div class="pt-4">
        <x-ui.alert type="success">
            {{ session('success_task') }}
        </x-ui.alert>
    </div>
@endif

@if (session('error_task'))
    <div class="pt-4">
        <x-ui.alert type="error">
            {{ session('error_task') }}
        </x-ui.alert>
    </div>
@endif
