@props(['color' => 'gray', 'class' => ''])

@php
    $colors = [
        'red' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
        'green' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
        'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
        'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100',
        'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
    ];
@endphp

<span class="px-2 py-0.5 rounded text-xs font-medium {{ $colors[$color] }} {{ $class }}">
    {{ $slot }}
</span>
