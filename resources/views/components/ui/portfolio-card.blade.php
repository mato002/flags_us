@props([
    'title',
    'client' => null,
    'location' => null,
    'mediaType' => null,
    'description' => null,
    'image' => null,
    'campaignSlug' => null,
    'priority' => false,
])

<article
    {{ $attributes->class(['em-card em-card-accent group flex flex-col overflow-hidden transition sm:hover:-translate-y-1']) }}
    data-portfolio-card
    data-portfolio-title="{{ $title }}"
    @if ($image)
        data-portfolio-image="{{ $image }}"
    @endif
    data-portfolio-location="{{ $location ?? '' }}"
    data-portfolio-media="{{ $mediaType ?? '' }}"
    data-portfolio-client="{{ $client ?? '' }}"
    data-portfolio-description="{{ Str::limit(strip_tags($description ?? ''), 400) }}"
>
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-[#f6ece3] sm:aspect-[16/10] sm:max-h-none">
        @if ($image)
            <img
                src="{{ $image }}"
                alt="{{ $title }}"
                width="800"
                height="520"
                class="h-full w-full object-cover transition duration-500 sm:group-hover:scale-105"
                @if ($priority)
                    fetchpriority="high"
                    loading="eager"
                    decoding="async"
                @else
                    loading="lazy"
                    decoding="async"
                    sizes="(max-width:640px) 100vw, (max-width:1024px) 50vw, 33vw"
                @endif
            >
        @else
            <div class="em-angled flex min-h-[11rem] flex-1 items-center justify-center px-6 py-10 text-center text-sm font-semibold text-white sm:min-h-0 sm:py-0">Effective Media Campaign</div>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-5">
        <div class="mb-3 flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-wide">
            @if ($mediaType)
                <span class="rounded-full bg-[#8b1e1a] px-3 py-1 text-white">{{ $mediaType }}</span>
            @endif
            @if ($location)
                <span class="rounded-full bg-[#f4e3c4] px-3 py-1 text-[#5c1514]">{{ $location }}</span>
            @endif
        </div>
        <h3 class="text-lg font-bold text-[#171717]">{{ $title }}</h3>
        @if ($client)
            <p class="mt-1 text-sm font-medium text-[#8b1e1a]">{{ $client }}</p>
        @endif
        <p class="mt-2 text-sm leading-7 text-[#515151]">{{ $description }}</p>
        <div class="mt-auto flex flex-col gap-2 pt-4 sm:flex-row sm:flex-wrap">
            <button type="button" class="em-btn-secondary min-h-[44px] w-full justify-center px-3 py-2.5 text-xs sm:w-auto" data-portfolio-lightbox-open>View Details</button>
            <a
                href="{{ route('quote', ['campaign' => $campaignSlug, 'location' => $location, 'media_type' => $mediaType]) }}"
                class="inline-flex min-h-[44px] w-full items-center justify-center rounded-md bg-[#f04a2a] px-3 py-2.5 text-center text-xs font-semibold text-white transition hover:bg-[#8b1e1a] sm:w-auto"
                data-track-event="portfolio_campaign_clicked"
            >
                Run Similar Campaign
            </a>
        </div>
    </div>
</article>
