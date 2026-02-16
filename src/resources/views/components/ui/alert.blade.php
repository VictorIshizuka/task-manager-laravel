@props(['type' => 'success'])

<div @class([
    'mb-4 p-3 border rounded font-semibold',
    'bg-green-100 border-green-400 text-green-700' => $type === 'success',
    'bg-red-100 border-red-400 text-red-700' => $type === 'error',
    'bg-yellow-100 border-yellow-400 text-yellow-700' => $type === 'warning',
])>
    {{ $slot }}
</div>
