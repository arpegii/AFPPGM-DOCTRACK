@props(['href'])

<a {{ $attributes->merge([
        'href' => $href,
        'class' => 'block w-full rounded-lg px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 focus:outline-none transition'
    ]) }}>
    {{ $slot }}
</a>
