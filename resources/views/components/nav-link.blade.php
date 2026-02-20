@props(['active' => false])

@php
$classes = $active
    ? 'inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold text-slate-900 bg-white shadow-sm border border-slate-200'
    : 'inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-white/80 border border-transparent hover:border-slate-200 transition duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
