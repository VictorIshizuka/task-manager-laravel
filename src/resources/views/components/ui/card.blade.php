@props(['class' => ''])

<div
    {{ $attributes->merge([
        'class' => "bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100 p-6 $class",
    ]) }}>
    {{ $slot }}
</div>
