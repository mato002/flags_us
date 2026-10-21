@props([
    'title',
    'message',
])

<article {{ $attributes->class(['em-card em-card-accent p-8 text-center']) }}>
    <h3 class="text-2xl font-bold text-[#5c1514]">{{ $title }}</h3>
    <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-[#5c5c5c]">{{ $message }}</p>
    @if (trim((string) $slot))
        <div class="mt-6 flex flex-wrap justify-center gap-3">
            {{ $slot }}
        </div>
    @endif
</article>
