@props(['active' => false])

@php
$classes = $active
    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-900 text-sm font-semibold text-blue-900 transition-colors duration-200'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold text-gray-600 hover:text-blue-900 hover:border-blue-900 transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
