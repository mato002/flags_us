@props([
    'href' => '#',
    'variant' => 'primary',
])

<a href="{{ $href }}" {{ $attributes->class([$variant === 'secondary' ? 'em-btn-secondary' : 'em-btn-primary']) }}>
    {{ $slot }}
</a>
