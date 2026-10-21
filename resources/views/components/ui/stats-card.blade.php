@props([
    'label',
    'value',
    'suffix' => '',
])

<article {{ $attributes->class(['em-card em-card-accent p-5']) }}>
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">{{ $label }}</p>
    <p class="mt-3 text-3xl font-bold text-[#171717]">{{ $value }}{{ $suffix }}</p>
</article>
