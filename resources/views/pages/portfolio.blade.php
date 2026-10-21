@extends('layouts.website')

@section('title', $websitePage['title'] ?? 'Portfolio | Effective Media')
@section('meta_description', $websitePage['meta_description'] ?? '')

@section('content')
    @php
        $s = $websitePage['sections'] ?? [];
        $reachRows = collect($profileContent['reach'] ?? []);
        $counties = $reachRows->pluck('county')->filter()->unique()->values();
        $industries = collect($profileContent['industries'] ?? [])->take(16);
        $hero = $s['hero'] ?? [];
        $gallerySec = $s['gallery'] ?? [];
        $countySec = $s['counties'] ?? [];
        $industrySec = $s['industries'] ?? [];
        $beforeAfter = $s['before_after'] ?? [];
        $deploySec = $s['deployments'] ?? [];
        $documents = $s['documents'] ?? [];
        $cta = $s['cta'] ?? [];
        $pairs = $beforeAfter['pairs'] ?? [];
        $timeline = $deploySec['items'] ?? [];
        $heroBg = trim((string) ($hero['image'] ?? ''));
        $portfolioHeroFallback = asset('profile-gallery/profile-page-2.jpg');
    @endphp

    <section class="relative isolate min-h-[34vh] overflow-hidden bg-[#5c2720] md:min-h-[38vh]">
        <div class="pointer-events-none absolute inset-0 z-0 bg-[#3d251f]" aria-hidden="true">
            <img
                src="{{ $heroBg !== '' ? $heroBg : $portfolioHeroFallback }}"
                alt=""
                width="1920"
                height="1080"
                class="h-full min-h-[34vh] w-full object-cover object-[center_40%]"
                loading="eager"
                fetchpriority="high"
                decoding="async"
            >
        </div>
        <div class="pointer-events-none absolute inset-0 z-[1] bg-[linear-gradient(105deg,rgba(255,245,240,0.94)_0%,rgba(255,212,199,0.68)_38%,rgba(240,116,94,0.28)_68%,rgba(92,21,20,0.78)_100%)]"></div>
        <div class="pointer-events-none absolute inset-0 z-[2] bg-[linear-gradient(to_bottom,rgba(254,243,232,0.55)_0%,transparent_45%,rgba(253,247,243,0.96)_100%)]"></div>
        <div class="em-container relative z-10 py-16 lg:py-20">
            <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#8b1e1a]">{{ $hero['kicker'] ?? 'Portfolio' }}</p>
            <h1 class="mt-4 max-w-4xl text-[2.05rem] font-black leading-[1.05] text-[#171210] sm:text-4xl lg:text-[2.7rem]">{{ $hero['title'] ?? '' }}</h1>
            <p class="mt-6 max-w-3xl text-sm leading-relaxed text-[#4c3b35] sm:text-[15px]">{{ $hero['description'] ?? '' }}</p>
            <div class="-mx-4 flex gap-2 overflow-x-auto overscroll-x-contain pb-3 pl-4 pr-4 [-ms-overflow-style:none] [scrollbar-width:none] sm:mx-0 sm:flex-wrap sm:overflow-visible sm:pl-0 sm:pr-0 [&::-webkit-scrollbar]:hidden snap-x snap-mandatory" data-portfolio-filters>
                @foreach (['All', 'Street Light Ads', 'Billboards', 'Office Branding', 'Pavement Ads', 'Activations'] as $filter)
                    <button type="button" class="min-h-[44px] shrink-0 snap-start rounded-full border border-[#c98f7c] bg-white/70 px-4 py-2.5 text-[11px] font-bold uppercase tracking-wide text-[#5c1514] shadow-sm backdrop-blur-sm transition hover:border-[#5c1514] hover:bg-[#5c1514] hover:text-white touch-manipulation active:brightness-95" data-filter="{{ $filter }}">{{ $filter }}</button>
                @endforeach
            </div>
            <div class="mt-6 flex flex-col gap-4 text-xs font-semibold uppercase tracking-[0.16em] text-[#6b5249] sm:flex-row sm:flex-wrap sm:gap-x-6">
                <a href="{{ route('coverage') }}" class="inline-flex min-h-[44px] items-center border-b border-dashed border-[#a67c6c] pb-1 transition hover:text-[#5c1514]">County intelligence → GIS page</a>
                <a href="{{ route('quote') }}" class="inline-flex min-h-[44px] items-center border-b border-dashed border-[#a67c6c] pb-1 transition hover:text-[#5c1514]">Procurement brief desk</a>
            </div>
        </div>
    </section>

    <section class="border-y border-[#ebe0d5] bg-white py-14" id="sectors">
        <div class="em-container">
            <div class="max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $industrySec['label'] ?? 'Industries' }}</p>
                <h2 class="mt-3 text-2xl font-black text-[#171210]">{{ $industrySec['title'] ?? 'Sectors returning to the medium' }}</h2>
            </div>
            <div class="mt-8 flex flex-wrap gap-2">
                @foreach ($industries as $industry)
                    <span class="rounded-full bg-[#f4e8dd] px-4 py-2 text-xs font-semibold text-[#5c1514]">{{ $industry }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[#f7efe6] py-14" id="counties">
        <div class="em-container">
            <div class="max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $countySec['label'] ?? 'Counties' }}</p>
                <h2 class="mt-3 text-2xl font-black text-[#171210]">{{ $countySec['title'] ?? 'Where executions publish' }}</h2>
                <p class="mt-3 text-sm text-[#5b453c]">{{ $countySec['description'] ?? 'Corridor proof now lives beside inventory — open the coverage page for tables and totals.' }}</p>
            </div>
            <div class="mt-8 flex gap-3 overflow-x-auto pb-2">
                @foreach ($counties as $county)
                    <a href="{{ route('quote', ['county' => $county]) }}" class="shrink-0 rounded-2xl border border-[#e1c8b4] bg-white px-5 py-3 text-sm font-bold text-[#5c1514] shadow-sm">{{ $county }}</a>
                @endforeach
            </div>
        </div>
    </section>

    @if (count($pairs) > 0)
        <section class="em-container py-14" id="before-after">
            <div class="max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $beforeAfter['label'] ?? 'Before / After' }}</p>
                <h2 class="mt-3 text-2xl font-black text-[#171210]">{{ $beforeAfter['title'] ?? 'Condition to launch readiness' }}</h2>
            </div>
            <div class="mt-10 grid gap-8 lg:grid-cols-2">
                @foreach ($pairs as $pair)
                    <div class="grid gap-4 sm:grid-cols-2">
                        <figure class="space-y-2">
                            <div class="aspect-[4/3] overflow-hidden rounded-2xl bg-[#efe3d7]">
                                @if (!empty($pair['before_image']))
                                    <img src="{{ $pair['before_image'] }}" alt="Before" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center px-6 text-center text-xs font-semibold text-[#6d584e]">{{ $pair['before_caption'] ?? 'Awaiting CMS asset' }}</div>
                                @endif
                            </div>
                            <figcaption class="text-xs font-semibold uppercase tracking-[0.16em] text-[#935b4f]">{{ $pair['before_caption'] ?? 'Before' }}</figcaption>
                        </figure>
                        <figure class="space-y-2">
                            <div class="aspect-[4/3] overflow-hidden rounded-2xl bg-[#efe3d7] ring-2 ring-[#f04a2a]/40">
                                @if (!empty($pair['after_image']))
                                    <img src="{{ $pair['after_image'] }}" alt="After" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center px-6 text-center text-xs font-semibold text-[#6d584e]">{{ $pair['after_caption'] ?? 'Awaiting CMS asset' }}</div>
                                @endif
                            </div>
                            <figcaption class="text-xs font-semibold uppercase tracking-[0.16em] text-[#935b4f]">{{ $pair['after_caption'] ?? 'After' }}</figcaption>
                        </figure>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if (count($timeline) > 0)
        <section class="border-y border-[#ebe2d9] bg-[#0f0908] py-14 text-[#fdeee7]" id="timeline">
            <div class="em-container">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#f8b697]">{{ $deploySec['label'] ?? '' }}</p>
                    <h2 class="mt-4 text-[1.95rem] font-black">{{ $deploySec['title'] ?? '' }}</h2>
                </div>
                <ol class="mt-12 grid gap-6 md:grid-cols-3">
                    @foreach ($timeline as $t)
                        <li class="rounded-3xl border border-white/15 bg-white/5 p-6">
                            <p class="font-mono text-[11px] text-[#f8b697]">{{ $t['phase'] ?? '' }}</p>
                            <p class="mt-4 text-sm leading-relaxed">{{ $t['detail'] ?? '' }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    <section class="scroll-mt-28 bg-white py-16" id="executions">
        <div class="em-container">
            <div class="max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $gallerySec['label'] ?? 'Executions' }}</p>
                <h2 class="mt-3 text-3xl font-black text-[#171210]">{{ $gallerySec['title'] ?? 'Published campaign gallery' }}</h2>
                <p class="mt-4 text-sm text-[#59463d]">{{ $gallerySec['description'] ?? '' }}</p>
            </div>
            <div id="gallery" class="-mt-2 pt-12">
                @if ($items->isNotEmpty())
                    <div class="columns-1 gap-5 sm:columns-2 lg:columns-3" data-portfolio-grid>
                        @foreach ($portfolioGrid as $row)
                            @php
                                $item = $row['item'];
                                $isGalleryRow = ($row['type'] ?? 'primary') === 'gallery';
                                $category = $item->category ?: ($item->media_type ?: 'Street Light Ads');
                                if ($isGalleryRow) {
                                    $gp = (string) ($row['gallery_path'] ?? '');
                                    $image = str_starts_with($gp, 'http://') || str_starts_with($gp, 'https://')
                                        ? $gp
                                        : asset(ltrim($gp, '/'));
                                    $cardTitle = $item->title.' · Field photo '.((int) ($row['gallery_index'] ?? 0) + 1);
                                } else {
                                    $image = $item->image_path
                                        ? (str_starts_with($item->image_path, 'http') ? $item->image_path : asset($item->image_path))
                                        : null;
                                    $cardTitle = $item->title;
                                }
                                $desc = $item->description ?: ($gallerySec['item_placeholder_story'] ?? 'Enrich this execution story in Portfolio CMS.');
                            @endphp
                            <div class="mb-5 break-inside-avoid" data-category="{{ $category }}">
                                <x-ui.portfolio-card
                                    :title="$cardTitle"
                                    :client="$item->client_name ?: 'Effective Media partner'"
                                    :location="$item->campaign_location ?: 'Kenya'"
                                    :media-type="$category"
                                    :description="$desc"
                                    :image="$image"
                                    :campaign-slug="\Illuminate\Support\Str::slug($item->title)"
                                />
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="columns-1 gap-5 sm:columns-2 lg:columns-3" data-portfolio-grid>
                        @foreach (collect($profileContent['portfolio'] ?? [])->unique(fn (array $row): string => (string) ($row['image'] ?? $row['title'] ?? '')) as $item)
                            <div class="mb-5 break-inside-avoid" data-category="{{ $item['media_type'] ?? 'Street Light Ads' }}">
                                <x-ui.portfolio-card
                                    :title="$item['title']"
                                    :client="$item['client']"
                                    :location="$item['location']"
                                    :media-type="$item['media_type']"
                                    :description="$item['caption']"
                                    :image="asset('profile-gallery/' . ($item['image'] ?? 'profile-page-2.jpg'))"
                                    :campaign-slug="\Illuminate\Support\Str::slug($item['title'])"
                                />
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8 rounded-2xl border border-dashed border-[#d9b699] px-6 py-8 text-center text-sm text-[#6b584f]">
                        Publishing live cases from CMS removes reliance on placeholders.
                        @auth
                            @can('cms.portfolio.manage')
                                <div class="mt-4"><x-ui.brand-button href="{{ route('admin.cms.portfolio.index') }}">Open portfolio CMS</x-ui.brand-button></div>
                            @endcan
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="em-container pb-20" id="documents">
        <div class="grid gap-8 rounded-[1.75rem] border border-[#e6d7ca] bg-[#fffaf6] p-5 sm:p-8 lg:grid-cols-[1fr_minmax(0,280px)] lg:gap-10 lg:p-10">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $documents['label'] ?? 'Documents' }}</p>
                <h2 class="mt-4 text-[1.8rem] font-black text-[#171210]">{{ $documents['title'] ?? '' }}</h2>
                <p class="mt-4 text-sm text-[#5b453d]">{{ $documents['subtitle'] ?? '' }}</p>
                <div class="mt-8 grid gap-5 sm:grid-cols-2">
                    @forelse ($companyProfiles as $profile)
                        <x-ui.document-card :title="$profile['name']" :url="$profile['url']" category="Portfolio pack" :date="now()->format('M Y')" />
                    @empty
                        <p class="text-sm text-[#6b584f]">Drop PDF collateral into storage to unlock download tiles.</p>
                    @endforelse
                </div>
            </div>
            <div class="self-center rounded-[1.5rem] border border-[#e1c8b8] bg-white p-6 text-sm shadow-inner">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#8b1e1a]">{{ $documents['aside_title'] ?? '' }}</p>
                <p class="mt-4 leading-relaxed text-[#59463d]">{{ $documents['aside_body'] ?? '' }}</p>
                <div class="mt-6 space-y-3">
                    <a href="{{ !empty($cta['primary_url'] ?? null) ? url($cta['primary_url']) : route('coverage') }}" class="block rounded-xl bg-[#5c1514] px-4 py-3 text-center text-xs font-black text-white">{{ $cta['primary_label'] ?? '' }}</a>
                    <a href="{{ !empty($cta['secondary_url'] ?? null) ? url($cta['secondary_url']) : route('quote') }}" class="block rounded-xl border border-[#d7b699] px-4 py-3 text-center text-xs font-black text-[#5c1514]">{{ $cta['secondary_label'] ?? '' }}</a>
                </div>
                @if (($documents['aside_footer'] ?? '') !== '')
                    <p class="mt-4 text-xs text-[#7c655a]">{{ $documents['aside_footer'] }}</p>
                @endif
            </div>
        </div>
    </section>

    <div id="em-portfolio-lightbox" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/70 p-3 backdrop-blur-sm sm:p-4" role="dialog" aria-modal="true" aria-labelledby="em-portfolio-lightbox-title" data-portfolio-lightbox>
        <div class="max-h-[calc(100dvh-1.25rem)] w-full max-w-4xl overflow-hidden rounded-2xl border border-white/15 bg-[#1a1212] shadow-[0_28px_90px_-28px_rgba(0,0,0,0.75)]">
            <div class="flex items-start justify-between gap-4 border-b border-white/10 px-5 py-4">
                <div>
                    <p id="em-portfolio-lightbox-title" class="text-lg font-black text-white" data-lightbox-title>Campaign</p>
                    <div class="mt-2 flex flex-wrap gap-2 text-[11px] font-semibold uppercase tracking-wide text-[#f7c4a8]">
                        <span data-lightbox-media></span>
                        <span data-lightbox-location></span>
                    </div>
                </div>
                <button type="button" class="rounded-lg border border-white/20 px-3 py-1 text-xs font-semibold text-white hover:bg-white/10" data-portfolio-lightbox-close>Close</button>
            </div>
            <div class="grid max-h-[calc(100dvh-5.5rem)] overflow-y-auto lg:max-h-[calc(92vh-88px)] lg:grid-cols-[1.25fr_1fr] lg:overflow-hidden">
                <div class="relative max-h-[min(48vh,22rem)] min-h-[12rem] bg-black sm:max-h-[52vh] lg:max-h-none lg:min-h-0">
                    <img src="" alt="" class="h-full max-h-[min(48vh,22rem)] w-full object-contain sm:max-h-[52vh] lg:max-h-[calc(92vh-88px)]" data-lightbox-image hidden>
                    <div class="flex h-full min-h-[12rem] items-center justify-center text-sm text-white/60 lg:min-h-0" data-lightbox-no-image>Image preview unavailable</div>
                </div>
                <div class="max-h-none space-y-3 overflow-y-auto p-4 text-sm text-[#e8d8cc] sm:p-5 lg:max-h-[calc(92vh-88px)]">
                    <p class="text-xs uppercase tracking-[0.16em] text-[#f7b396]">Client</p>
                    <p class="font-semibold text-white" data-lightbox-client>—</p>
                    <p class="mt-4 text-xs uppercase tracking-[0.16em] text-[#f7b396]">Creative notes</p>
                    <p class="leading-relaxed" data-lightbox-caption>—</p>
                    <a href="{{ route('quote') }}" class="mt-6 inline-flex rounded-lg bg-[#f04a2a] px-4 py-2 text-xs font-bold text-white hover:bg-[#ff6f4d]" data-lightbox-quote>Mirror this strategy</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lightbox = document.querySelector('[data-portfolio-lightbox]');
            const openLightbox = (card) => {
                if (!lightbox) return;
                const title = card.getAttribute('data-portfolio-title') || 'Campaign';
                const image = card.getAttribute('data-portfolio-image') || '';
                const location = card.getAttribute('data-portfolio-location') || '';
                const media = card.getAttribute('data-portfolio-media') || '';
                const client = card.getAttribute('data-portfolio-client') || '';
                const description = card.getAttribute('data-portfolio-description') || '';

                lightbox.querySelector('[data-lightbox-title]').textContent = title;
                lightbox.querySelector('[data-lightbox-media]').textContent = media || 'Outdoor';
                lightbox.querySelector('[data-lightbox-location]').textContent = location || 'Kenya';
                lightbox.querySelector('[data-lightbox-client]').textContent = client || 'Effective Media partner';
                lightbox.querySelector('[data-lightbox-caption]').textContent = description || 'Execution notes originate from CMS items.';

                const img = lightbox.querySelector('[data-lightbox-image]');
                const fallback = lightbox.querySelector('[data-lightbox-no-image]');
                if (image && img instanceof HTMLImageElement) {
                    img.src = image;
                    img.alt = title;
                    img.removeAttribute('hidden');
                    fallback?.setAttribute('hidden', '');
                } else if (img) {
                    img.setAttribute('hidden', '');
                    fallback?.removeAttribute('hidden');
                }

                const quote = lightbox.querySelector('[data-lightbox-quote]');
                if (quote instanceof HTMLAnchorElement) {
                    const params = new URLSearchParams({ campaign: title, location, media_type: media });
                    quote.href = `{{ route('quote') }}?${params.toString()}`;
                }

                lightbox.classList.remove('hidden');
                lightbox.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            };

            const closeLightbox = () => {
                if (!lightbox) return;
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            };

            document.querySelectorAll('[data-portfolio-lightbox-open]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    const card = button.closest('[data-portfolio-card]');
                    if (card) openLightbox(card);
                });
            });

            lightbox?.querySelector('[data-portfolio-lightbox-close]')?.addEventListener('click', closeLightbox);
            lightbox?.addEventListener('click', (event) => {
                if (event.target === lightbox) closeLightbox();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeLightbox();
            });

            const buttons = Array.from(document.querySelectorAll('[data-filter]'));
            const cards = Array.from(document.querySelectorAll('[data-category]'));

            if (buttons.length && cards.length) {
                const applyFilter = (selectedFilter) => {
                    const normalizedSelected = String(selectedFilter || 'All').toLowerCase();
                    buttons.forEach((btn) => btn.classList.remove('bg-[#5c1514]', 'text-white'));
                    buttons.forEach((btn) => {
                        const normalizedLabel = String(btn.dataset.filter || '').toLowerCase().replace(/\s+/g, '-');
                        if (normalizedSelected === 'all' || normalizedSelected === normalizedLabel) {
                            btn.classList.add('bg-[#5c1514]', 'text-white');
                        }
                    });
                    cards.forEach((card) => {
                        const normalizedCard = String(card.dataset.category || '').toLowerCase().replace(/\s+/g, '-');
                        const match = normalizedSelected === 'all' || normalizedSelected === normalizedCard;
                        card.style.display = match ? 'block' : 'none';
                    });
                };

                buttons.forEach((button) => {
                    button.addEventListener('click', () => applyFilter(button.dataset.filter || 'All'));
                });

                const queryFilter = new URLSearchParams(window.location.search).get('filter');
                applyFilter(queryFilter || 'All');
            }
        });
    </script>
@endsection



