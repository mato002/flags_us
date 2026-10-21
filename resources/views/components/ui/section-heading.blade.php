@props([
    'label' => null,
    'title',
    'description' => null,
    'light' => false,
])

<div {{ $attributes->class(['space-y-4']) }}>
    @if ($label)
        <p class="text-xs font-semibold uppercase tracking-[0.25em] {{ $light ? 'text-[#f9bf9f]' : 'text-[#8b1e1a]' }}">{{ $label }}</p>
    @endif
    <h2 class="text-3xl font-bold leading-tight sm:text-4xl {{ $light ? 'text-white' : 'text-[#171717]' }}">{{ $title }}</h2>
    @if ($description)
        <p class="max-w-3xl text-base leading-8 {{ $light ? 'text-[#f8d6c2]' : 'text-[#4f4f4f]' }}">{{ $description }}</p>
    @endif
</div>
