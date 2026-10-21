@extends('layouts.website')

@section('title', $websitePage['title'] ?? 'What We Do | Effective Media')
@section('meta_description', $websitePage['meta_description'] ?? '')

@section('content')
    @php
        $s = $websitePage['sections'] ?? [];
        $profile = $profileContent;
        $street = $profile['street_light_box'] ?? [];
        $spotlight = $s['street_spotlight'] ?? [];
        $hero = $s['hero'] ?? [];
        $wwdHeroImg = trim((string) ($hero['image'] ?? ''));
        $wwdHeroBg = $wwdHeroImg !== '' ? $wwdHeroImg : asset('profile-gallery/profile-page-3.jpg');
        $cases = $s['campaign_structures']['cases'] ?? [];
        $plannerTeaser = $s['planner_teaser'] ?? [];
        $documents = $s['documents'] ?? [];
        $closing = $s['closing'] ?? [];
        $productExplorer = $s['product_explorer'] ?? [];
        $formatLinks = $s['format_links'] ?? [];
    @endphp

    <section id="product-explorer" class="relative isolate min-h-[36vh] overflow-hidden bg-[#faf5f0]">
        <div class="pointer-events-none absolute inset-0 z-0" aria-hidden="true">
            <img src="{{ $wwdHeroBg }}" alt="" width="1920" height="1080" class="h-full min-h-[36vh] w-full object-cover object-center opacity-35" loading="eager" decoding="async">
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(250,245,240,0.92)_0%,rgba(250,245,240,0.89)_45%,rgba(250,245,240,0.97)_100%)]"></div>
        </div>
        <div class="em-container relative z-10 grid gap-10 py-16 lg:grid-cols-2 lg:items-center lg:py-[4.5rem]">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#8b1e1a]">{{ $hero['kicker'] ?? 'Catalogue' }}</p>
                <h1 class="mt-4 text-[2.1rem] font-black leading-[1.05] tracking-tight text-[#1c1210] sm:text-4xl">{{ $hero['title'] ?? '' }}</h1>
                <p class="mt-6 max-w-xl text-sm leading-relaxed text-[#493830] sm:text-[15px]">{{ $hero['description'] ?? '' }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    @if (($hero['primary_cta_anchor'] ?? '') !== '')
                        <a href="{{ $hero['primary_cta_anchor'] }}" class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-[#1c1210] px-5 py-2.5 text-sm font-bold text-white">{{ $hero['primary_cta_label'] ?? 'Explore products' }}</a>
                    @endif
                    @if (($hero['secondary_cta_url'] ?? '') !== '')
                        <a href="{{ url($hero['secondary_cta_url']) }}" class="inline-flex min-h-[44px] items-center rounded-lg border border-[#c89f86] px-5 py-2.5 text-sm font-semibold text-[#5c1514]">{{ $hero['secondary_cta_label'] ?? 'Assistant' }}</a>
                    @endif
                </div>
            </div>
            <div class="relative">
                <img src="{{ $hero['image'] ?? asset('profile-gallery/profile-page-3.jpg') }}" alt="Outdoor media products" loading="lazy" decoding="async" class="relative z-10 aspect-[4/3] max-h-[min(460px,72vw)] w-full rounded-[2rem] border border-[#ead5c9]/70 object-cover shadow-[0_50px_110px_-62px_rgb(92,21,20,0.75)] sm:aspect-auto sm:max-h-[460px]">
                @if (($hero['floating_note'] ?? '') !== '')
                    <div class="relative z-20 mt-4 max-w-xl rounded-2xl bg-white/95 p-4 text-sm text-[#392a26] shadow-xl ring-1 ring-[#ead5c8] backdrop-blur-md lg:absolute lg:-left-4 lg:bottom-8 lg:mt-0 lg:max-w-xs">
                        {{ $hero['floating_note'] }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section id="street-spotlight" class="border-y border-[#e5d2c3] bg-[#120c0c] py-16 text-[#fae9df] lg:py-[4.5rem]">
        <div class="em-container grid gap-10 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.9fr)]">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#f9b08b]">{{ $spotlight['label'] ?? 'Street lights' }}</p>
                <h2 class="mt-4 text-[1.95rem] font-black leading-tight sm:text-3xl">{{ $spotlight['title'] ?? '' }}</h2>
                <p class="mt-6 text-sm leading-relaxed text-[#e3d1c7]">{{ $spotlight['body'] ?: ($street['explanation'] ?? '') }}</p>
                <dl class="mt-10 grid gap-6 sm:grid-cols-3">
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#d7b6a5]">Installed poles</dt>
                        <dd class="mt-2 text-3xl font-black text-white">730+</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#d7b6a5]">Daily window</dt>
                        <dd class="mt-2 text-3xl font-black text-white">12–16h</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#d7b6a5]">County span</dt>
                        <dd class="mt-2 text-3xl font-black text-white">7+</dd>
                    </div>
                </dl>
            </div>
            <div class="space-y-6">
                <img src="{{ $spotlight['image'] ?? asset('profile-gallery/profile-page-2.jpg') }}" alt="Street light product" class="h-64 w-full rounded-2xl object-cover ring-1 ring-white/10 lg:h-80">
                <p class="text-xs leading-relaxed text-[#d7c6bc]">{{ $spotlight['footnote'] ?? 'Totals sync with your GIS register when integrations are enabled.' }}</p>
            </div>
        </div>
    </section>

    <section class="bg-white py-14 lg:py-20" aria-label="Product explorer">
        <div class="em-container">
            <div class="max-w-3xl border-b border-[#eedfd3] pb-8">
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $productExplorer['label'] ?? '' }}</p>
                <h2 class="mt-3 text-3xl font-black text-[#1c1210]">{{ $productExplorer['title'] ?? '' }}</h2>
                @if (($productExplorer['description'] ?? '') !== '')
                    <p class="mt-4 text-sm text-[#59443b]">{{ $productExplorer['description'] }}</p>
                @endif
            </div>
            <div class="mt-12 space-y-16">
                @foreach ($serviceExplorer as $index => $svc)
                    @php($isEven = $index % 2 === 0)
                    @php($formatAnchor = 'format-'.(preg_replace('/[^a-z0-9_]/', '', (string) ($svc['format_key'] ?? 'generic')) ?: 'generic'))
                    <article id="{{ $formatAnchor }}" class="grid scroll-mt-[calc(var(--em-header-offset)+12px)] gap-10 lg:grid-cols-2 lg:items-center">
                        <div class="{{ $isEven ? '' : 'lg:order-2' }}">
                            <img src="{{ $svc['image'] }}" alt="{{ $svc['title'] }}" class="aspect-[4/3] w-full rounded-[1.75rem] object-cover shadow-[0_30px_70px_-42px_rgb(60,30,24,0.55)] min-h-[12rem] lg:aspect-auto lg:h-[340px] lg:min-h-0">
                        </div>
                        <div class="space-y-4 {{ $isEven ? '' : 'lg:order-1' }}">
                            <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $svc['kicker'] }}</p>
                            <h3 class="text-[1.65rem] font-black leading-tight text-[#1c1210]">{{ $svc['headline'] }}</h3>
                            <p class="text-sm leading-relaxed text-[#493830]">{{ $svc['positioning'] }}</p>
                            @if (($svc['summary'] ?? '') !== '')
                                <p class="text-sm leading-relaxed text-[#6b5348]">{{ $svc['summary'] }}</p>
                            @endif
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#a67c6a]">Ideal use cases</p>
                                    <ul class="mt-3 space-y-2 text-sm text-[#392a26]">
                                        @foreach (array_slice($svc['ideal_use_cases'] ?? [], 0, 6) as $u)
                                            <li class="flex gap-2"><span class="text-[#f04a2a]">▹</span><span>{{ $u }}</span></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#a67c6a]">Campaign objectives</p>
                                    <ul class="mt-3 space-y-2 text-sm text-[#392a26]">
                                        @foreach (array_slice($svc['campaign_objectives'] ?? [], 0, 6) as $o)
                                            <li class="flex gap-2"><span class="text-[#5c1514]">◆</span><span>{{ $o }}</span></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#6b5348]">Outcome profile</p>
                            <p class="text-sm text-[#392a26]">{{ $svc['impact_line'] }}</p>
                            <p class="text-xs text-[#735c51]"><span class="font-semibold text-[#5c1514]">Example:</span> {{ $svc['example'] }}</p>
                            <div class="flex flex-wrap gap-3 pt-2">
                                <a href="{{ route('quote', ['media_type' => $svc['title']]) }}" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-4 text-xs font-bold text-white">{{ $svc['cta_primary_label'] }}</a>
                                @if (!empty($svc['showcase_slug']))
                                    <a href="{{ route('services.show', ['serviceSlug' => $svc['showcase_slug']]) }}" class="inline-flex min-h-[42px] items-center rounded-lg border border-[#cfa38b] px-4 text-xs font-bold text-[#5c1514]">{{ $svc['cta_secondary_label'] }}</a>
                                @else
                                    <a href="{{ route('contact-us') }}" class="inline-flex min-h-[42px] items-center rounded-lg border border-[#cfa38b] px-4 text-xs font-bold text-[#5c1514]">{{ $svc['cta_secondary_label'] }}</a>
                                @endif
                                <a href="{{ route('coverage') }}" class="text-xs font-semibold uppercase tracking-wide text-[#8b1e1a] underline-offset-4 hover:underline">{{ $formatLinks['coverage'] ?? 'Coverage map' }}</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[#f3e8dd] py-14">
        <div class="em-container">
            <div class="max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $s['campaign_structures']['label'] ?? 'Structures' }}</p>
                <h2 class="mt-3 text-2xl font-black text-[#1c1210] sm:text-3xl">{{ $s['campaign_structures']['title'] ?? 'Example deployment shapes' }}</h2>
                <p class="mt-4 text-sm text-[#5d453b]">{{ $s['campaign_structures']['description'] ?? '' }}</p>
            </div>
            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                @foreach ($cases as $case)
                    <div class="rounded-2xl border border-[#dfc4b4] bg-white p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#8b1e1a]">{{ $case['client'] ?? '' }}</p>
                        <p class="mt-2 text-lg font-black text-[#1c1210]">{{ $case['location'] ?? '' }}</p>
                        <dl class="mt-4 space-y-2 text-sm text-[#4f3a32]">
                            <div class="flex justify-between gap-4 border-b border-[#f0e2d6] py-2"><dt>Format</dt><dd class="font-semibold text-right">{{ $case['format'] ?? '' }}</dd></div>
                            <div class="flex justify-between gap-4 border-b border-[#f0e2d6] py-2"><dt>Units</dt><dd class="font-semibold text-right">{{ $case['units'] ?? '' }}</dd></div>
                            <div class="flex justify-between gap-4 py-2"><dt>Duration</dt><dd class="font-semibold text-right">{{ $case['duration'] ?? '' }}</dd></div>
                        </dl>
                        <p class="mt-4 text-sm text-[#5b4338]"><span class="font-bold text-[#5c1514]">Outcome:</span> {{ $case['outcome'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-y border-[#e9d6c8] bg-[#1a1210] py-14 text-white">
        <div class="em-container flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#fbc1a1]">{{ $plannerTeaser['label'] ?? 'Assistant' }}</p>
                <h2 class="mt-3 text-2xl font-black sm:text-3xl">{{ $plannerTeaser['title'] ?? '' }}</h2>
                <p class="mt-4 text-sm leading-relaxed text-[#e7d5cc]">{{ $plannerTeaser['description'] ?? '' }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ url($plannerTeaser['cta_url'] ?? '/smart-campaign-planner') }}" class="inline-flex min-h-[48px] items-center justify-center rounded-xl bg-white px-6 text-sm font-black text-[#1a1210]">{{ $plannerTeaser['cta_label'] ?? 'Open planner' }}</a>
                <a href="{{ url($plannerTeaser['secondary_url'] ?? '/quote') }}" class="inline-flex min-h-[48px] items-center justify-center rounded-xl border border-white/40 px-6 text-sm font-semibold text-white">{{ $plannerTeaser['secondary_label'] ?? 'Skip ahead' }}</a>
            </div>
        </div>
    </section>

    <section class="em-container py-14">
        <div class="max-w-3xl">
            <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $documents['label'] ?? 'Collateral' }}</p>
            <h2 class="mt-3 text-2xl font-black text-[#1c1210] sm:text-3xl">{{ $documents['title'] ?? '' }}</h2>
            @if (($documents['subtitle'] ?? '') !== '')
                <p class="mt-3 text-sm text-[#5f453b]">{{ $documents['subtitle'] }}</p>
            @endif
        </div>
        <div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($companyProfiles as $profile)
                <x-ui.document-card :title="$profile['name']" :url="$profile['url']" category="Capability deck" :date="'Updated ' . now()->format('M Y')" />
            @empty
                <x-ui.empty-state class="sm:col-span-2 xl:col-span-3" title="Upload PDFs in admin" message="Once company profiles are uploaded they appear here automatically." />
            @endforelse
        </div>
    </section>

    <section class="bg-[#fdf6ef] py-14">
        <div class="em-container text-center">
            <h2 class="text-2xl font-black text-[#1c1210]">{{ $closing['title'] ?? 'Ready to anchor a corridor?' }}</h2>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <x-ui.brand-button href="{{ url($closing['primary_url'] ?? '/quote') }}">{{ $closing['primary_label'] ?? 'Lock pricing' }}</x-ui.brand-button>
                <x-ui.brand-button href="{{ url($closing['secondary_url'] ?? '/coverage') }}" variant="secondary">{{ $closing['secondary_label'] ?? 'Open GIS coverage' }}</x-ui.brand-button>
                <x-ui.brand-button href="{{ route('contact-us') }}" variant="secondary">Call sales desk</x-ui.brand-button>
            </div>
        </div>
    </section>
@endsection
