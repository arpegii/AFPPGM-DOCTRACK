@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-2.5 rounded-lg text-start text-sm font-semibold text-slate-900 bg-white border border-slate-200 shadow-sm'
            : 'block w-full px-4 py-2.5 rounded-lg text-start text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-white/90 border border-transparent hover:border-slate-200 transition duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
