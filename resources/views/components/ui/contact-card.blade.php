@props([
    'label',
    'value',
])

<article {{ $attributes->class(['em-card em-card-accent p-5']) }}>
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">{{ $label }}</p>
    <p class="mt-2 text-base font-semibold text-[#171717]">{{ $value }}</p>
</article>
