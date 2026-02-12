@props(['href'])

<a {{ $attributes->merge([
        'href' => $href,
        'class' => 'block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 focus:outline-none transition'
    ]) }}>
    {{ $slot }}
</a>
