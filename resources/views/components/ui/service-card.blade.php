@props([
    'title',
    'description',
    'image' => null,
    'link' => null,
])

<article {{ $attributes->class(['em-card em-card-accent group overflow-hidden transition hover:-translate-y-1']) }}>
    <div class="h-44 overflow-hidden bg-[#f6ece3]">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="em-angled flex h-full items-center justify-center px-4 text-center text-sm font-semibold text-white">Outdoor Media Solutions</div>
        @endif
    </div>
    <div class="p-5">
        <h3 class="text-lg font-bold text-[#171717]">{{ $title }}</h3>
        <p class="mt-2 text-sm leading-7 text-[#515151]">{{ $description }}</p>
        @if ($link)
            <a href="{{ $link }}" class="mt-4 inline-flex text-sm font-semibold text-[#8b1e1a] hover:text-[#f04a2a]">Request Quote</a>
        @endif
    </div>
</article>
