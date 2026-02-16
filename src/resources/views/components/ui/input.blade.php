@props(['error' => null])

<input
    {{ $attributes->merge([
        'class' => 'w-full border-gray-300 dark:border-gray-700
            dark:bg-gray-900 dark:text-gray-300
            rounded-md shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600',
    ]) }}>

@if ($error)
    <p class="text-red-500 text-xs mt-1">
        {{ $error }}
    </p>
@endif
