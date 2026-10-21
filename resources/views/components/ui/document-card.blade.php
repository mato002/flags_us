@props([
    'title',
    'url',
    'category' => 'Company Profile',
    'date' => null,
    'cover' => null,
])

<article {{ $attributes->class(['em-card overflow-hidden']) }}>
    <div class="h-2 w-full bg-[#8b1e1a]"></div>
    <div class="p-5">
        <div class="relative mb-4 aspect-video max-h-40 overflow-hidden rounded-lg border border-[#ecdac8] bg-gradient-to-br from-[#5c1514] via-[#8b1e1a] to-[#241312] sm:aspect-auto sm:h-36 sm:max-h-none">
            @if ($cover)
                <img
                    src="{{ $cover }}"
                    alt=""
                    loading="lazy"
                    decoding="async"
                    class="absolute inset-0 h-full w-full object-cover"
                    width="640"
                    height="360"
                    sizes="(max-width:768px) 100vw, 33vw"
                >
            @else
                <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 p-4 text-center">
                    <svg class="h-12 w-12 text-white/90" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <rect x="8" y="6" width="32" height="36" rx="3" stroke="currentColor" stroke-width="2" />
                        <path d="M14 17h14M14 23h14M14 29h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-white/85">PDF document</span>
                </div>
            @endif
        </div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">{{ $category }}</p>
        <h3 class="mt-2 truncate text-base font-bold text-[#171717]">{{ $title }}</h3>
        <p class="mt-1 text-xs text-[#666]">{{ $date ?: 'Latest release' }}</p>
        <div class="mt-4 flex flex-col gap-2 min-[380px]:flex-row">
            <a
                href="{{ $url }}"
                target="_blank"
                rel="noopener noreferrer"
                class="em-btn-secondary inline-flex min-h-[44px] flex-1 items-center justify-center px-3 py-2 text-center text-xs"
            >
                Preview
            </a>
            <button
                type="button"
                class="min-h-[44px] flex-1 rounded-md bg-[#f04a2a] px-3 py-2 text-center text-xs font-semibold text-white transition hover:bg-[#8b1e1a]"
                data-download-trigger
                data-download-url="{{ $url }}"
                data-download-title="{{ $title }}"
                data-track-event="profile_download"
            >
                Download Profile
            </button>
        </div>
    </div>
</article>
