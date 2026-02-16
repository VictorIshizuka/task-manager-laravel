@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'class' => '',
    'title' => '',
    'href' => null,
    'as' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center rounded-md font-medium transition uppercase';

    $variants = [
        'primary' => 'bg-indigo-600 dark:bg-indigo-700 hover:bg-indigo-700 dark:hover:bg-indigo-800 text-white',
        'danger' => 'bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-800 text-white',
        'warning' => 'bg-yellow-600 dark:bg-yellow-700 hover:bg-yellow-700 dark:hover:bg-yellow-800 text-white',
        'info' => 'bg-blue-600 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-600 text-white',
        'secondary' => 'bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-800 text-white',

        'outline-primary' => 'bg-transparent hover:bg-indigo-600 hover:text-white text-indigo-600',
        'outline-danger' => 'bg-transparent hover:bg-red-600 hover:text-white text-red-600',
        'outline-warning' => 'bg-transparent  hover:bg-yellow-600 hover:text-white text-yellow-600',
        'outline-secondary' => 'bg-transparent hover:bg-gray-600 hover:text-white text-gray-600',

    ];

    $sizes = [
        'sm' => 'px-3 py-1 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $classes =
        $base .
        ' ' .
        ($variants[$variant] ?? $variants['primary']) .
        ' ' .
        ($sizes[$size] ?? $sizes['md']) .
        ' ' .
        $class;
@endphp

@if ($as === 'a')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} title="{{ $title }}">
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} title="{{ $title }}">
        {{ $slot }}
    </button>
@endif
