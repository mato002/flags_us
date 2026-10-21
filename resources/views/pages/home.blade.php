@extends('layouts.website')

@section('title', $websitePage['title'] ?? null)
@section('meta_description', $websitePage['meta_description'] ?? '')

@section('content')
    @include('components.em-leaflet-loader-inline')
    @php
        $pw = $portalWebsite ?? [];
        $rawPlan = (string) ($pw['cta_plan_url'] ?? '/smart-campaign-planner');
        $planHref = str_starts_with($rawPlan, 'http') ? $rawPlan : url($rawPlan);
        $rawCov = (string) ($pw['cta_coverage_url'] ?? '/coverage');
        $covHref = str_starts_with($rawCov, 'http') ? $rawCov : url($rawCov);
        $heroFallbackPhotos = collect([
            asset('profile-gallery/profile-page-5.jpg'),
            asset('profile-gallery/profile-page-3.jpg'),
            asset('profile-gallery/profile-page-2.jpg'),
        ]);
        $fromPortfolioHero = collect($featured_portfolio ?? [])->map(fn ($row) => $row->image_url)->filter()->values();
        $heroPhotoCandidates = $fromPortfolioHero->merge($heroFallbackPhotos)->unique()->take(6)->values();
        $heroMainPhoto = $heroPhotoCandidates->get(0, $heroFallbackPhotos[0]);
        $heroSubPhotoA = $heroPhotoCandidates->get(1, $heroFallbackPhotos[1]);
        $heroSubPhotoB = $heroPhotoCandidates->get(2, $heroFallbackPhotos[2]);
        $heroBgSlides = $heroPhotoCandidates->take(2)->merge($heroFallbackPhotos)->unique()->take(3)->values()->all();
        $heroSlides = $heroFallbackPhotos->all();
        $reach = collect($profileContent['reach'] ?? []);
        $countiesCovered = max($reach->pluck('county')->filter()->unique()->count(), 7);
        $townsCovered = max(count($profileContent['coverage_towns'] ?? []), 18);
        $polesCovered = max((int) $reach->sum('poles'), 730);
        $coveragePoints = collect($profileContent['coverage_map_points'] ?? [])->values();
        $mapFocus = $coveragePoints->first() ?? ['town' => 'Nakuru', 'county' => 'Nakuru', 'media_type' => 'Street Light Ads'];
        $monthlyImpressions = collect($statistics ?? [])->first(function ($row) {
            $lab = strtolower((string) ($row->label ?? ''));

            return str_contains($lab, 'impression') || str_contains($lab, 'visibility') || str_contains($lab, 'monthly');
        });
        $monthlyImpressionsVal = $monthlyImpressions ? (int) preg_replace('/\D/', '', (string) ($monthlyImpressions->value ?? '0')) : 28000000;
        if ($monthlyImpressionsVal <= 0) {
            $monthlyImpressionsVal = 28000000;
        }
        $monthlyImpressionsMillions = max(1, (int) round($monthlyImpressionsVal / 1000000));
        $trustedBrands = ['KFC', 'Dr Mattress', 'KenJap', 'Wasili', 'Domaine', 'Ole Ken', 'EABL', 'Safaricom'];
        $mediaTypes = [
            ['title' => 'Street Light Ads', 'description' => 'Sequential pole placements across urban traffic corridors for repetitive brand recall.', 'image' => asset('profile-gallery/profile-page-2.jpg')],
            ['title' => 'Billboards', 'description' => 'Large-format static and illuminated billboards for highways, CBD entries, and major junctions.', 'image' => asset('profile-gallery/profile-page-3.jpg')],
            ['title' => 'Pavement Ads', 'description' => 'High-frequency roadside messages around retail zones, bus stops, and shopping districts.', 'image' => asset('profile-gallery/profile-page-5.jpg')],
            ['title' => 'Office Branding', 'description' => 'Corporate spaces and branch branding systems aligned to your campaign identity.', 'image' => asset('profile-gallery/profile-page-2.jpg')],
            ['title' => 'Activations', 'description' => 'Road shows and field activations that convert foot traffic into real customer interaction.', 'image' => asset('profile-gallery/profile-page-3.jpg')],
            ['title' => 'Roll-Up Banners', 'description' => 'Portable campaign units for events, retail entrances, and targeted touch points.', 'image' => asset('profile-gallery/profile-page-5.jpg')],
        ];
        $whyBlocks = [
            ['title' => 'Strategic Placement', 'desc' => 'High-traffic urban corridors and commuter routes.'],
            ['title' => 'Fast Deployment', 'desc' => 'Rapid installation, maintenance, and campaign turnaround.'],
            ['title' => 'Repetitive Visibility', 'desc' => 'Street-light sequencing improves ad recall and frequency.'],
            ['title' => 'Affordable Reach', 'desc' => 'Cost-effective exposure compared to traditional formats.'],
        ];
    @endphp

    <style>
        .em-reveal { opacity: 0; transform: translateY(24px); transition: opacity .7s ease, transform .7s ease; }
        .em-reveal.in { opacity: 1; transform: translateY(0); }
        .em-float { animation: emFloat 6s ease-in-out infinite; }
        .em-float:nth-child(2n) { animation-delay: .6s; }
        .em-float:nth-child(3n) { animation-delay: 1.2s; }
        .em-marquee { animation: emMarquee 20s linear infinite; }
        .em-marquee:hover { animation-play-state: paused; }
        .em-parallax { will-change: transform; }
        .em-hero-collage-photo {
            transition: transform 700ms cubic-bezier(0.22, 1, 0.36, 1), box-shadow 500ms ease;
        }
        .em-hero-collage-photo:hover {
            transform: translateY(-4px) scale(1.015);
            box-shadow: 0 28px 60px -20px rgb(0 0 0 / 55%);
        }
        #em-home-hero .em-hero-watermark {
            font-size: clamp(3rem, 10vw, 9rem);
            letter-spacing: 0.04em;
        }
        @keyframes emFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes emMarquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        @keyframes emBadgePulse {
            0%, 100% { box-shadow: 0 14px 40px -16px rgb(139 30 26 / 45%); }
            50% { box-shadow: 0 18px 48px -12px rgb(240 74 42 / 40%); }
        }
        .em-hero-points-badge {
            animation: emBadgePulse 6s ease-in-out infinite;
        }
        @media (max-width: 640px) {
            .em-tight-section { padding-top: 3.5rem; padding-bottom: 3.5rem; }
            .em-compact-text { font-size: 0.95rem; line-height: 1.55; }
        }
        @media (prefers-reduced-motion: reduce) {
            .em-hero-collage-photo { transition: none; }
            .em-hero-collage-photo:hover { transform: none; }
            .em-hero-points-badge { animation: none; }
        }
    </style>

    <section id="em-home-hero" class="relative isolate min-h-0 overflow-hidden bg-[#0f0b0b] pb-10 pt-6 text-white sm:pb-14 sm:pt-8 md:min-h-[calc(100dvh-var(--em-header-offset,126px))] md:pb-20 md:pt-14 lg:flex lg:flex-col lg:justify-center lg:pb-24 lg:pt-16">
        <div class="pointer-events-none absolute inset-0 hidden select-none md:block">
            <div class="em-hero-watermark absolute -left-[8%] top-1/2 -translate-y-1/2 font-black uppercase leading-none text-white/[0.035] blur-[0.5px]">Across Kenya</div>
            <div class="em-hero-watermark absolute right-[-4%] top-[18%] font-black uppercase leading-none text-white/[0.045] blur-[1px]">Effective Media</div>
        </div>
        {{-- Mobile: single background image (no slider) --}}
        <div class="absolute inset-0 bg-cover bg-center md:hidden" style="background-image: url('{{ $heroBgSlides[0] ?? $heroMainPhoto }}');"></div>
        <div class="absolute inset-0 hidden md:block" data-hero-slider>
            @foreach ($heroBgSlides as $index => $slide)
                <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-hero-slide style="background-image: url('{{ $slide }}');"></div>
            @endforeach
        </div>
        <div class="absolute inset-0 em-parallax bg-[linear-gradient(118deg,rgba(18,10,11,0.96),rgba(72,17,17,0.88)_38%,rgba(125,31,26,0.72)_72%,rgba(10,10,10,0.72))]" data-parallax="-0.06"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_88%_12%,rgba(255,154,92,0.18),transparent_42%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#070505]/95 via-transparent to-[#070505]/50"></div>
        <div class="em-container relative z-10 w-full lg:mr-14 lg:max-w-[min(100%-3.75rem,80rem)]">
            <div class="grid items-center gap-8 sm:gap-11 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] lg:gap-14">
                <div class="em-reveal order-1 text-center lg:order-none lg:text-left">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-[#fbc9a8]">{{ $pw['hero_badge'] ?? 'A Media Network Across Kenya' }}</p>
                    <h1 class="mt-3 text-[clamp(1.5rem,5.5vw,1.82rem)] font-black leading-[1.12] tracking-tight text-white drop-shadow-[0_12px_40px_rgb(0,0,0,0.45)] sm:text-[2.75rem] sm:leading-[1.08] md:text-[3.05rem] lg:mt-4 lg:text-[3.55rem] lg:leading-[1.02] xl:text-6xl">{{ $pw['hero_headline'] ?? 'Your Brand. Seen Across Kenya.' }}</h1>
                    <p class="em-compact-text mx-auto mt-4 max-w-[28rem] text-[#fde8dc]/95 md:text-lg lg:mx-0 lg:max-w-xl">{{ $pw['hero_subtext'] ?? 'Strategic outdoor advertising infrastructure across highways, CBDs, transport corridors and urban centers.' }}</p>
                    <div class="mx-auto mt-7 flex w-full max-w-xl flex-col gap-2.5 sm:mt-8 sm:flex-row sm:flex-wrap sm:justify-center sm:gap-3 lg:mx-0 lg:max-w-none lg:justify-start">
                        <a href="{{ $planHref }}" class="order-1 inline-flex min-h-[44px] w-full shrink-0 items-center justify-center rounded-xl bg-gradient-to-r from-[#5c1514] via-[#7a251f] to-[#f04a2a] px-5 py-3 text-[13px] font-bold text-white shadow-[0_8px_26px_-10px_rgb(139,30,26,0.55)] outline outline-1 outline-white/15 transition hover:-translate-y-1 hover:shadow-[0_16px_44px_-4px_rgb(240,74,42,0.55)] hover:brightness-[1.06] active:translate-y-0 sm:w-auto sm:min-w-[10rem] sm:text-sm">{{ $pw['cta_plan_label'] ?? 'Plan Campaign' }}</a>
                        <div class="grid w-full grid-cols-1 gap-3 min-[400px]:grid-cols-2 sm:flex sm:min-w-0 sm:flex-1 sm:flex-wrap sm:justify-center lg:flex-initial">
                            <a href="{{ $covHref }}" class="order-2 inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-white/55 bg-white/12 px-4 py-3 text-[13px] font-semibold text-white shadow-[inset_0_1px_0_rgb(255,255,255,0.12)] backdrop-blur-md transition hover:-translate-y-1 hover:bg-white/[0.22] hover:shadow-[0_12px_36px_-8px_rgb(255,154,92,0.35)] sm:min-w-[9.5rem] sm:flex-1 sm:text-sm md:px-5">{{ $pw['cta_coverage_label'] ?? 'Explore Coverage' }}</a>
                            <button type="button" data-open-download-modal class="order-3 inline-flex min-h-[44px] w-full items-center justify-center rounded-xl px-4 py-3 text-[13px] font-semibold text-[#fde8dc]/95 ring-1 ring-white/35 transition hover:-translate-y-1 hover:bg-white/15 hover:text-white sm:min-w-[9.5rem] sm:flex-1 sm:text-sm md:px-5">{{ $pw['cta_download_label'] ?? 'Download Profile' }}</button>
                        </div>
                    </div>
                    <div class="mx-auto mt-8 max-w-xl lg:mx-0">
                        <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-4 sm:gap-3 lg:max-w-3xl">
                            <div class="rounded-lg border border-white/20 bg-white/[0.08] px-3 py-2.5 text-left shadow-[inset_0_1px_0_rgb(255,255,255,0.08)] backdrop-blur-sm sm:py-3">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-[#f8c8a8]/90">Poles</p>
                                <p class="mt-0.5 text-lg font-black tabular-nums text-white sm:text-xl"><span data-counter="{{ $polesCovered }}">{{ number_format(max(730, $polesCovered)) }}</span>+</p>
                            </div>
                            <div class="rounded-lg border border-white/20 bg-white/[0.08] px-3 py-2.5 text-left shadow-[inset_0_1px_0_rgb(255,255,255,0.08)] backdrop-blur-sm sm:py-3">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-[#f8c8a8]/90">Towns</p>
                                <p class="mt-0.5 text-lg font-black tabular-nums text-white sm:text-xl"><span data-counter="{{ $townsCovered }}">{{ number_format(max(18, $townsCovered)) }}</span>+</p>
                            </div>
                            <div class="rounded-lg border border-white/20 bg-white/[0.08] px-3 py-2.5 text-left shadow-[inset_0_1px_0_rgb(255,255,255,0.08)] backdrop-blur-sm sm:py-3">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-[#f8c8a8]/90">Counties</p>
                                <p class="mt-0.5 text-lg font-black tabular-nums text-white sm:text-xl"><span data-counter="{{ $countiesCovered }}">{{ number_format(max(7, $countiesCovered)) }}</span>+</p>
                            </div>
                            <div class="col-span-2 rounded-lg border border-white/20 bg-white/[0.08] px-3 py-2.5 text-left shadow-[inset_0_1px_0_rgb(255,255,255,0.08)] backdrop-blur-sm sm:col-span-1 sm:py-3">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-[#f8c8a8]/90">Monthly roadside impressions</p>
                                <p class="mt-0.5 text-lg font-black tabular-nums text-white sm:text-xl"><span data-counter="{{ $monthlyImpressionsMillions }}">{{ number_format(max(1, $monthlyImpressionsMillions)) }}</span><span class="text-base font-black">M+</span></p>
                            </div>
                        </div>
                        <p class="mt-3 text-center text-[11px] font-medium leading-relaxed text-[#e8c4af]/80 sm:text-left sm:text-xs">Street Light Ads <span class="text-white/35">|</span> Pavement Ads <span class="text-white/35">|</span> Billboards</p>
                    </div>
                </div>
                {{-- Mobile: hide collage to keep hero compact (single-background only) --}}
                <div class="em-reveal relative order-2 mx-auto hidden w-full max-w-md md:block lg:order-none lg:mx-0 lg:max-w-none">
                    <div class="em-parallax flex items-stretch gap-3 sm:gap-4 lg:gap-5" data-parallax="-0.04">
                        <div class="group relative min-h-0 min-w-0 flex-1 self-stretch">
                            <img
                                src="{{ $heroMainPhoto }}"
                                alt="Outdoor advertising — primary coverage visual"
                                width="520"
                                height="640"
                                fetchpriority="high"
                                decoding="async"
                                class="em-hero-collage-photo em-parallax aspect-[4/5] max-h-[min(70vw,22rem)] w-full rounded-2xl border border-white/15 object-cover shadow-[0_28px_70px_-28px_rgb(0,0,0,0.75)] sm:aspect-auto sm:h-[min(44vw,26rem)] sm:max-h-none lg:h-[min(52vh,28rem)] lg:rounded-3xl"
                                data-parallax="-0.1"
                            >
                        </div>
                        <div class="hidden w-[34%] shrink-0 flex-col justify-between gap-3 self-stretch pt-10 sm:flex sm:gap-4 lg:pt-14">
                            <img
                                src="{{ $heroSubPhotoA }}"
                                alt="Billboard and highway visibility"
                                width="240"
                                height="220"
                                loading="lazy"
                                decoding="async"
                                sizes="30vw"
                                class="em-hero-collage-photo em-parallax aspect-[4/3] min-h-0 w-full flex-1 rounded-xl border border-white/15 object-cover shadow-[0_18px_44px_-20px_rgb(0,0,0,0.65)] sm:min-h-[6.75rem] lg:min-h-[7.5rem] lg:rounded-2xl"
                                data-parallax="-0.14"
                            >
                            <img
                                src="{{ $heroSubPhotoB }}"
                                alt="Street light and urban campaign placement"
                                width="240"
                                height="220"
                                loading="lazy"
                                decoding="async"
                                sizes="30vw"
                                class="em-hero-collage-photo em-parallax aspect-[4/3] min-h-0 w-full flex-1 rounded-xl border border-white/15 object-cover shadow-[0_18px_44px_-20px_rgb(0,0,0,0.65)] sm:min-h-[6.75rem] lg:min-h-[7.5rem] lg:rounded-2xl"
                                data-parallax="-0.12"
                            >
                        </div>
                    </div>
                    <div class="relative z-[1] mt-4 rounded-xl border border-white/30 bg-gradient-to-br from-[#3b1412]/95 to-[#1a0a09]/95 px-3.5 py-2.5 shadow-lg backdrop-blur-md sm:absolute sm:-bottom-2 sm:left-4 sm:mt-0 sm:right-auto lg:-bottom-3">
                        <p class="text-[9px] font-semibold uppercase tracking-[0.2em] text-[#f6c49e]">Network scale</p>
                        <p class="text-base font-black tabular-nums text-white sm:text-lg"><span data-counter="{{ $polesCovered }}">{{ number_format(max(730, $polesCovered)) }}</span>+ Media Points</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="overflow-hidden bg-[#1a1010] py-8 text-white sm:py-10">
        <div class="em-container">
            <p class="mb-5 text-center text-xs font-semibold uppercase tracking-[0.24em] text-[#f5b98f]">Trusted By Brands</p>
            <div class="relative overflow-hidden">
                <div class="em-marquee flex w-[200%] gap-4">
                    @foreach (array_merge($trustedBrands, $trustedBrands) as $brand)
                        <div class="flex min-w-[11rem] items-center justify-center rounded-xl border border-white/15 bg-white/10 px-6 py-3 text-sm font-semibold tracking-wide">
                            {{ $brand }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if (isset($statistics) && $statistics->isNotEmpty())
        <section class="border-y border-[#2c1818]/80 bg-[#231616] py-9 text-white sm:py-10">
            <div class="em-container grid grid-cols-2 gap-3 sm:gap-6 lg:grid-cols-4">
                @foreach ($statistics->take(4) as $stat)
                    <div class="em-reveal rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-center backdrop-blur-sm sm:px-5 sm:py-4">
                        <p class="text-2xl font-black text-[#fbc9a8] sm:text-4xl">{{ $stat->value }}{{ $stat->suffix ?? '' }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-[0.2em] text-white/75">{{ $stat->label }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if (isset($testimonials) && $testimonials->isNotEmpty())
        <section class="bg-[#fefaf6] py-16 sm:py-20">
            <div class="em-container">
                <div class="em-reveal max-w-3xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">Client Voice</p>
                    <h2 class="mt-2 text-3xl font-black text-[#1f1111] sm:text-4xl">Campaign partners trust the network</h2>
                </div>
                <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials->take(6) as $t)
                        <figure class="em-reveal flex h-full flex-col rounded-2xl border border-[#ead8c8] bg-white p-6 shadow-[0_20px_48px_-38px_rgba(92,21,20,0.45)]">
                            <blockquote class="text-sm leading-7 text-[#4a3e38]">{{ $t->quote }}</blockquote>
                            <figcaption class="mt-4 border-t border-[#f5e9dd] pt-4 text-sm font-bold text-[#5c1514]">{{ $t->client_name ?: 'Client' }}</figcaption>
                            @if (! empty($t->company_name))
                                <p class="text-xs font-semibold text-[#8b1e1a]">{{ $t->company_name }}</p>
                            @endif
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @php
        $normCountyKey = static function (?string $c): string {
            $s = strtolower(trim((string) $c));

            return trim(preg_replace('/\s+county$/i', '', $s));
        };
        $focusName = (string) ($mapFocus['county'] ?? 'Nakuru');
        $initialCountyPoles = (int) $reach->filter(fn ($row) => $normCountyKey($row['county'] ?? '') === $normCountyKey($focusName))->sum('poles');
        if ($initialCountyPoles < 1) {
            $initialCountyPoles = max(730, $polesCovered);
        }
    @endphp

    <section id="coverage-map" class="scroll-mt-28 bg-[#f8f3ee] py-16 sm:py-20">
        <div class="em-container grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
            <div class="em-card em-reveal order-2 border-0 shadow-[0_28px_60px_-40px_rgba(92,21,20,0.45)] lg:order-1">
                <div class="border-b border-[#ecdac8] p-5 sm:p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">Coverage Network</p>
                    <h2 class="mt-2 text-2xl font-black text-[#221211] sm:text-3xl">Interactive Kenya Coverage Map</h2>
                    <p class="mt-2 text-sm text-[#64544c]">Clustered counties, commuter corridors and heat-intensity signalling. Tap markers to zoom the insight rail.</p>
                </div>
                <div class="relative p-4 sm:p-6 sm:pt-0">
                    <div id="em-home-coverage-map" class="relative z-[1] h-[min(52vh,440px)] w-full overflow-hidden rounded-2xl border border-[#e6d0be] shadow-inner" aria-label="Kenya coverage map"></div>
                    <p class="mt-3 text-center text-[11px] text-[#7a6a62]">Map data © OpenStreetMap contributors · Intelligence layer © Effective Media</p>
                </div>
            </div>
            <div class="em-card em-reveal order-1 border-0 shadow-[0_28px_60px_-40px_rgba(92,21,20,0.45)] lg:order-2">
                <div class="hidden lg:block">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">Campaign Insight</p>
                    <h3 class="mt-2 text-3xl font-black text-[#1e1010]" data-map-town>{{ ($mapFocus['town'] ?? 'Nakuru').' Coverage' }}</h3>
                    <div class="mt-5 grid gap-3 text-sm text-[#4e3e38]">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="rounded-xl border border-[#ecdac8] bg-[#fffaf6] px-3 py-2.5">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-[#a8977a]">County poles</p>
                                <p class="mt-1 text-xl font-black text-[#1a1010]" data-home-poles>{{ $initialCountyPoles }}</p>
                            </div>
                            <div class="rounded-xl border border-[#ecdac8] bg-[#fffaf6] px-3 py-2.5">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-[#a8977a]">Visibility score</p>
                                <p class="mt-1 text-xl font-black text-[#1a1010]" data-home-visibility>88</p>
                            </div>
                            <div class="rounded-xl border border-[#ecdac8] bg-[#fffaf6] px-3 py-2.5">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-[#a8977a]">Traffic strength</p>
                                <p class="mt-1 text-lg font-black text-[#1a1010]" data-home-traffic>High</p>
                            </div>
                            <div class="rounded-xl border border-[#ecdac8] bg-[#fffaf6] px-3 py-2.5">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-[#a8977a]">Active routes</p>
                                <p class="mt-1 text-lg font-black text-[#1a1010]" data-home-routes>6+</p>
                            </div>
                        </div>
                        <p data-map-type-copy><span class="font-semibold text-[#171717]">{{ $mapFocus['media_type'] ?? 'Street Light Ads' }}</span> inventory aligned to highway, CBD and corridor sequencing.</p>
                        <p>County focus: <span class="font-semibold text-[#171717]" data-map-county>{{ $mapFocus['county'] ?? 'Nakuru' }}</span> · Town anchor: <span class="font-semibold text-[#171717]" data-home-town-label>{{ $mapFocus['town'] ?? 'Nakuru' }}</span>.</p>
                    </div>
                </div>
                <details class="rounded-xl border border-[#ecd7c6] bg-[#fff9f4] p-4 lg:hidden" open>
                    <summary class="cursor-pointer text-sm font-semibold text-[#8b1e1a]">Campaign Insight Panel</summary>
                    <h3 class="mt-4 text-2xl font-black text-[#1e1010]" data-map-town>{{ ($mapFocus['town'] ?? 'Nakuru').' Coverage' }}</h3>
                    <div class="mt-4 grid gap-3 text-sm text-[#4e3e38]">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="rounded-lg border border-[#ecdac8] bg-white px-2 py-2">
                                <p class="font-semibold text-[#a8977a]">County poles</p>
                                <p class="text-lg font-black" data-home-poles>{{ $initialCountyPoles }}</p>
                            </div>
                            <div class="rounded-lg border border-[#ecdac8] bg-white px-2 py-2">
                                <p class="font-semibold text-[#a8977a]">Visibility</p>
                                <p class="text-lg font-black" data-home-visibility>88</p>
                            </div>
                        </div>
                        <p data-map-type-copy><span class="font-semibold text-[#171717]">{{ $mapFocus['media_type'] ?? 'Street Light Ads' }}</span> inventory aligned to highway, CBD and corridor sequencing.</p>
                        <p>County focus: <span class="font-semibold text-[#171717]" data-map-county>{{ $mapFocus['county'] ?? 'Nakuru' }}</span>.</p>
                    </div>
                </details>
                <div class="border-t border-[#f0e3d8] p-6">
                    <a href="{{ route('quote') }}" class="em-btn-primary" data-home-cta-quote>Request Quote</a>
                    <a href="{{ route('smart-campaign-planner') }}" class="em-btn-secondary mt-3 sm:ml-3 sm:mt-0" data-home-cta-planner>Plan Campaign</a>
                </div>
            </div>
        </div>
    </section>

    <section class="em-tight-section bg-white py-16 sm:py-20">
        <div class="em-container space-y-10">
            <div class="em-reveal max-w-3xl">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">Media Types</p>
                <h2 class="mt-2 text-3xl font-black text-[#1f1111] sm:text-4xl">Infrastructure Designed For Visibility</h2>
            </div>
            @foreach ($mediaTypes as $index => $type)
                <div class="em-reveal grid items-center gap-6 {{ $index % 2 === 0 ? 'lg:grid-cols-[1.2fr_1fr]' : 'lg:grid-cols-[1fr_1.2fr]' }}">
                    @if ($index % 2 === 0)
                        <img src="{{ $type['image'] }}" alt="{{ $type['title'] }}" class="em-parallax h-72 w-full rounded-2xl object-cover shadow-lg" data-parallax="-0.08">
                    @endif
                    <div class="rounded-2xl border border-[#ecd9c7] bg-[#fffaf5] p-6 sm:p-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#8b1e1a]">0{{ $index + 1 }}</p>
                        <h3 class="mt-2 text-2xl font-black text-[#201211]">{{ $type['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[#55453f]">{{ $type['description'] }}</p>
                        <a href="{{ route('quote', ['media_type' => $type['title']]) }}" class="mt-5 inline-flex text-sm font-semibold text-[#8b1e1a]">Launch {{ $type['title'] }} Campaign</a>
                    </div>
                    @if ($index % 2 !== 0)
                        <img src="{{ $type['image'] }}" alt="{{ $type['title'] }}" class="em-parallax h-72 w-full rounded-2xl object-cover shadow-lg" data-parallax="-0.08">
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <section class="em-tight-section bg-[#130f0f] py-16 text-white sm:py-20">
        <div class="em-container">
            <div class="em-reveal mb-8 max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f1b18c]">Campaign Showcase</p>
                <h2 id="campaign-showcase" class="scroll-mt-28 mt-2 text-3xl font-black sm:text-4xl">Real Field Execution Across Kenya</h2>
                <p class="mt-2 text-sm text-white/75">Featured CMS work below; when fewer items exist we highlight live inventory towns from coverage data.</p>
            </div>
            @php
                $fallbackFieldSites = collect($profileContent['coverage_map_points'] ?? [])
                    ->unique(fn ($pt): string => strtolower(trim((string) ($pt['town'] ?? ''))).'|'.strtolower(trim((string) ($pt['county'] ?? ''))))
                    ->take(6)
                    ->values()
                    ->all();
            @endphp
            <div class="-mx-4 flex snap-x snap-mandatory gap-4 overflow-x-auto px-4 pb-2 sm:mx-0 sm:grid sm:overflow-visible sm:px-0 sm:pb-0 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($featured_portfolio->take(6) as $item)
                    <article class="group em-reveal min-w-[84%] snap-center overflow-hidden rounded-xl border border-white/15 bg-white/5 sm:min-w-0">
                        <img src="{{ $item->image_url ?? asset('profile-gallery/profile-page-3.jpg') }}" alt="{{ $item->title }}" width="800" height="448" loading="lazy" decoding="async" sizes="(max-width:640px) 84vw, (max-width:1024px) 50vw, 33vw" class="h-56 w-full object-cover transition duration-500 group-hover:scale-110">
                        <div class="space-y-2 p-4">
                            <p class="text-base font-semibold">{{ $item->title }}</p>
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="rounded-full bg-white/15 px-2 py-1">{{ $item->campaign_location ?: 'Kenya' }}</span>
                                <span class="rounded-full bg-white/15 px-2 py-1">{{ $item->media_type ?: ($item->category ?: 'Outdoor Media') }}</span>
                            </div>
                        </div>
                    </article>
                @empty
                    @forelse ($fallbackFieldSites as $idx => $pt)
                        <article class="group em-reveal min-w-[84%] snap-center overflow-hidden rounded-xl border border-white/15 bg-white/5 sm:min-w-0">
                            <img src="{{ $heroSlides[$idx % max(count($heroSlides), 1)] }}" alt="{{ $pt['town'] ?? 'Corridor' }} installation" class="h-56 w-full object-cover transition duration-500 group-hover:scale-110">
                            <div class="space-y-2 p-4">
                                <p class="text-base font-semibold">{{ $pt['town'] ?? 'Corridor site' }} · recent field installation</p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span class="rounded-full bg-white/15 px-2 py-1">{{ $pt['county'] ?? 'Kenya' }}</span>
                                    <span class="rounded-full bg-white/15 px-2 py-1">{{ $pt['media_type'] ?? 'Street Light Ads' }}</span>
                                </div>
                            </div>
                        </article>
                    @empty
                        @foreach (['Nakuru CBD corridor', 'Naivasha highway stretch', 'Eldoret commuter ring', 'Kisumu lake road', 'Nairobi–Nakuru link', 'Kiambu town entry'] as $idx => $label)
                            <article class="group em-reveal min-w-[84%] snap-center overflow-hidden rounded-xl border border-white/15 bg-white/5 sm:min-w-0">
                                <img src="{{ $heroSlides[$idx % max(count($heroSlides), 1)] }}" alt="{{ $label }}" class="h-56 w-full object-cover transition duration-500 group-hover:scale-110">
                                <div class="space-y-2 p-4">
                                    <p class="text-base font-semibold">{{ $label }}</p>
                                    <div class="flex flex-wrap gap-2 text-xs">
                                        <span class="rounded-full bg-white/15 px-2 py-1">Network site</span>
                                        <span class="rounded-full bg-white/15 px-2 py-1">Street Light Ads</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    @endforelse
                @endforelse
            </div>
        </div>
    </section>

    <section class="em-tight-section bg-[#faf5f0] py-16 sm:py-20">
        <div class="em-container">
            <div class="em-reveal mb-8 max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">Why Effective Media</p>
                <h2 class="mt-2 text-3xl font-black text-[#1f1111] sm:text-4xl">Built For Dominant Market Visibility</h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($whyBlocks as $block)
                    <article class="em-reveal rounded-2xl border border-[#ead7c5] bg-white p-6 shadow-sm">
                        <div class="mb-3 h-10 w-10 rounded-lg bg-[#8b1e1a] text-center text-2xl leading-10 text-white">+</div>
                        <h3 class="text-lg font-bold text-[#241514]">{{ $block['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-[#5f4f48]">{{ $block['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="em-tight-section bg-[#120d0e] py-16 text-white sm:py-20">
        <div class="em-container">
            <div class="em-reveal rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur sm:p-8">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#f2b48c]">Smart Campaign Planner</p>
                <h2 class="mt-2 text-3xl font-black sm:text-4xl">Build Campaign Recommendations Instantly</h2>
                <form action="{{ route('quote') }}" method="GET" class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <select name="industry" class="em-hero-select rounded-lg border border-white/20 bg-white/10 px-3 py-3 text-sm text-white">
                        <option value="" selected class="em-hero-placeholder">Select Industry</option>
                        <option>Retail</option><option>FMCG</option><option>Real Estate</option><option>Finance</option>
                    </select>
                    <select name="county" class="em-hero-select rounded-lg border border-white/20 bg-white/10 px-3 py-3 text-sm text-white">
                        <option value="" selected class="em-hero-placeholder">Select Location</option>
                        @foreach ($reach->pluck('county')->filter()->unique()->take(8) as $county)
                            <option>{{ $county }}</option>
                        @endforeach
                    </select>
                    <select name="media_type" class="em-hero-select rounded-lg border border-white/20 bg-white/10 px-3 py-3 text-sm text-white">
                        <option value="" selected class="em-hero-placeholder">Select Media Type</option>
                        @foreach ($mediaTypes as $type)
                            <option>{{ $type['title'] }}</option>
                        @endforeach
                    </select>
                    <select name="budget_range" class="em-hero-select rounded-lg border border-white/20 bg-white/10 px-3 py-3 text-sm text-white">
                        <option value="" selected class="em-hero-placeholder">Select Budget</option>
                        <option>KES 100K - 300K</option><option>KES 300K - 750K</option><option>KES 750K+</option>
                    </select>
                    <button class="em-btn-primary w-full sm:col-span-2 lg:col-span-4" type="submit">Get Recommended Locations</button>
                </form>
            </div>
        </div>
    </section>

    @php
        $ratesStrip = $rates_preview ?? [];
        $ratesTiers = is_array($ratesStrip['tiers'] ?? null) ? $ratesStrip['tiers'] : [];
    @endphp
    <section class="em-tight-section bg-white py-16 sm:py-20">
        <div class="em-container">
            <div class="em-reveal mb-8 max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">{{ $ratesStrip['kicker'] ?? 'Rates Preview' }}</p>
                <h2 class="mt-2 text-3xl font-black text-[#1f1111] sm:text-4xl">{{ $ratesStrip['title'] ?? 'Estimate Campaign Costs Fast' }}</h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($ratesTiers as $tier)
                    @php
                        $href = trim((string) ($tier['cta_href'] ?? ''));
                        $ctaUrl = ($href !== '' && (str_starts_with($href, 'http://') || str_starts_with($href, 'https://'))) ? $href : url($href !== '' ? $href : '/contact-us');
                        $isHi = ! empty($tier['highlighted']);
                    @endphp
                    <article class="em-reveal rounded-2xl border border-[#ead8c8] p-6 {{ $isHi ? 'bg-[#fff6ef]' : '' }}">
                        <p class="text-xs uppercase tracking-[0.16em] text-[#8b1e1a]">{{ $tier['tier_label'] ?? '' }}</p>
                        <p class="mt-2 text-3xl font-black text-[#211312]">{{ $tier['price'] ?? '' }}</p>
                        @if (trim((string) ($tier['description'] ?? '')) !== '')
                            <p class="mt-2 text-sm text-[#5d4d46]">{{ $tier['description'] }}</p>
                        @endif
                        <a href="{{ $ctaUrl }}" class="{{ $isHi ? 'em-btn-primary' : 'em-btn-secondary' }} mt-4 w-full justify-center sm:w-auto">{{ $tier['cta_label'] ?? '' }}</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="em-tight-section bg-[#f9f0e6] py-16 sm:py-20">
        <div class="em-container">
            <div class="em-reveal mb-8 max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">Company Profile</p>
                <h2 class="mt-2 text-3xl font-black text-[#1f1111] sm:text-4xl">Download Capability Documents</h2>
            </div>
            <div class="grid gap-4 lg:grid-cols-2">
                @forelse ($companyProfiles->take(4) as $profile)
                    <article class="em-reveal flex flex-col items-start gap-4 rounded-2xl border border-[#e7d3c0] bg-white p-4 shadow-sm sm:flex-row sm:items-center">
                        <div class="h-24 w-20 shrink-0 rounded-lg bg-[linear-gradient(160deg,#8b1e1a,#dc6337)] p-3 text-xs font-semibold text-white">PDF</div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-base font-bold text-[#201211]">{{ $profile['name'] }}</p>
                            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                <span class="rounded-full bg-[#f6e5d8] px-2 py-1 text-[#8b1e1a]">Company Profile</span>
                                <span class="rounded-full bg-[#f6e5d8] px-2 py-1 text-[#8b1e1a]">{{ rand(120, 900) }} downloads</span>
                            </div>
                        </div>
                        <a href="{{ $profile['url'] }}" target="_blank" rel="noopener noreferrer" class="em-btn-primary w-full justify-center sm:w-auto">Download</a>
                    </article>
                @empty
                    <x-ui.empty-state class="lg:col-span-2" title="Company documents coming soon" message="Profile documents will appear here when uploaded to project storage.">
                        <x-ui.brand-button href="{{ route('contact-us') }}">Request Profile by Email</x-ui.brand-button>
                    </x-ui.empty-state>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-[#5c1514] py-12 text-white sm:py-14">
        <div class="em-container em-reveal flex flex-col items-start justify-between gap-6 lg:flex-row lg:items-center">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#f7bb95]">Campaign Ready</p>
                <h2 class="mt-2 text-3xl font-black sm:text-4xl">Ready To Dominate Your Market Visibility?</h2>
            </div>
            <div class="flex w-full flex-wrap gap-3 lg:w-auto">
                <a href="{{ route('quote') }}" class="em-btn-primary w-full justify-center bg-[#f04a2a] hover:bg-[#ff6f4d] sm:w-auto">Get Quotation</a>
                <a href="{{ route('contact-us') }}" class="em-btn-secondary w-full justify-center border-white/40 bg-white/10 text-white hover:bg-white/20 sm:w-auto">Contact Sales</a>
                <a href="{{ route('smart-campaign-planner') }}" class="em-btn-secondary w-full justify-center border-white/40 bg-white/10 text-white hover:bg-white/20 sm:w-auto">Plan Campaign</a>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = Array.from(document.querySelectorAll('[data-hero-slide]'));
            if (slides.length > 1) {
                let index = 0;
                setInterval(() => {
                    slides[index].classList.remove('opacity-100');
                    slides[index].classList.add('opacity-0');
                    index = (index + 1) % slides.length;
                    slides[index].classList.remove('opacity-0');
                    slides[index].classList.add('opacity-100');
                }, 4500);
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in');
                    }
                });
            }, { threshold: 0.15 });
            document.querySelectorAll('.em-reveal').forEach((element) => observer.observe(element));

            document.querySelectorAll('[data-counter]').forEach((counter) => {
                const target = Number(counter.getAttribute('data-counter') || 0);
                const duration = 1300;
                const start = performance.now();
                const animate = (time) => {
                    const progress = Math.min((time - start) / duration, 1);
                    counter.textContent = Math.floor(progress * target).toString();
                    if (progress < 1) requestAnimationFrame(animate);
                };
                requestAnimationFrame(animate);
            });

            const mapShell = document.getElementById('em-home-coverage-map');
            const pointsJson = @json($profileContent['coverage_map_points'] ?? []);
            const reachJson = @json($profileContent['reach'] ?? []);

            const setMultiText = (selector, text) => {
                document.querySelectorAll(selector).forEach((element) => {
                    element.textContent = text;
                });
            };

            const updateHomeInsight = (payload) => {
                setMultiText('[data-map-town]', `${payload.town} Coverage`);
                setMultiText('[data-map-county]', payload.county || 'Kenya');
                setMultiText('[data-home-town-label]', payload.town || '');
                setMultiText('[data-home-poles]', String(payload.poles || 0));
                setMultiText('[data-home-visibility]', String(payload.visibility || '—'));
                setMultiText('[data-home-traffic]', payload.traffic || 'High');
                setMultiText('[data-home-routes]', `${payload.routes || 6}+`);

                document.querySelectorAll('[data-map-type-copy]').forEach((element) => {
                    element.innerHTML = `<span class="font-semibold text-[#171717]">${payload.mediaType || 'Street Light Ads'}</span> inventory aligned to highway, CBD and corridor sequencing.`;
                });

                const quote = document.querySelector('[data-home-cta-quote]');
                const planner = document.querySelector('[data-home-cta-planner]');
                if (quote instanceof HTMLAnchorElement) {
                    quote.href = `{{ route('quote') }}?location=${encodeURIComponent(payload.town)}&county=${encodeURIComponent(payload.county)}&media_type=${encodeURIComponent(payload.mediaType)}`;
                }
                if (planner instanceof HTMLAnchorElement) {
                    planner.href = `{{ route('smart-campaign-planner') }}?location=${encodeURIComponent(payload.town)}&county=${encodeURIComponent(payload.county)}`;
                }
            };

            const normCountyKey = (name) =>
                String(name ?? '')
                    .trim()
                    .toLowerCase()
                    .replace(/\s+county$/i, '')
                    .trim();

            const deriveInsight = (point, groupedByNorm) => {
                const key = normCountyKey(point.county);
                const poles = groupedByNorm[key]?.poles ?? 0;
                const tier = poles > 200 ? 'Very high' : poles > 120 ? 'High' : poles > 60 ? 'Medium' : 'Emerging';
                const visibility = Math.min(96, Math.round(65 + poles / 25));
                return {
                    town: point.town,
                    county: point.county,
                    mediaType: point.media_type,
                    poles,
                    traffic: tier,
                    visibility,
                    routes: Math.max(3, Math.round(poles / 45)),
                };
            };

            const initHomeCoverageMap = () => {
                if (!mapShell || typeof L === 'undefined') return;
                if (!pointsJson.length) {
                    mapShell.classList.remove('em-map-skeleton');
                    mapShell.innerHTML = `<div class="flex h-full min-h-[220px] flex-col items-center justify-center gap-2 bg-[#fffaf7] px-4 text-center text-sm text-[#6e5e56]"><p class="font-semibold text-[#5c1514]">Coverage map data loading</p><p>Add geography points inside the site profile configuration to visualize Kenya coverage.</p><a href="{{ route('coverage') }}" class="text-[#8b1e1a] font-semibold underline-offset-4 hover:underline">Open coverage intelligence</a></div>`;

                    return;
                }

                mapShell.classList.remove('em-map-skeleton');

                const map = L.map(mapShell).setView([-0.47, 37.85], 6);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                }).addTo(map);

                const groupedByNorm = {};
                reachJson.forEach((row) => {
                    const k = normCountyKey(row.county);
                    if (!groupedByNorm[k]) groupedByNorm[k] = { poles: 0, sites: [] };
                    groupedByNorm[k].poles += Number(row.poles || 0);
                    if (row.site) groupedByNorm[k].sites.push(row.site);
                });

                const mediaColorMap = {
                    'Street Light Ads': '#8B1E1A',
                    'Pavement Ads': '#F04A2A',
                    Billboards: '#A8977A',
                    Activations: '#5c1514',
                };

                let maxCounty = 1;
                Object.values(groupedByNorm).forEach((row) => {
                    maxCounty = Math.max(maxCounty, row.poles || 1);
                });

                if (typeof L.heatLayer === 'function') {
                    const heatPoints = [];
                    pointsJson.forEach((point) => {
                        const w = ((groupedByNorm[normCountyKey(point.county)]?.poles ?? 120) / maxCounty) * 0.8 + 0.15;
                        heatPoints.push([point.lat, point.lng, w]);
                    });
                    L.heatLayer(heatPoints, { radius: 36, blur: 22, maxZoom: 12, gradient: { 0.2: '#fde8dc', 0.5: '#f04a2a', 0.85: '#5c1514' } }).addTo(map);
                }

                const corridors = [
                    { label: 'Nairobi ↔ Nakuru', path: [[-1.2864, 36.8172], [-0.3031, 36.08]] },
                    { label: 'Nakuru ↔ Eldoret', path: [[-0.3031, 36.08], [0.5143, 35.2698]] },
                    { label: 'Kisumu corridor', path: [[-1.2864, 36.8172], [-0.1022, 34.7617]] },
                ];
                corridors.forEach((corridor) => {
                    L.polyline(corridor.path, { color: '#8b1e1a', weight: 3, opacity: 0.35, dashArray: '10 8' }).addTo(map);
                });

                const createIcon = (mediaType) => L.divIcon({
                    className: '',
                    html: `<div class="em-marker-pulse" style="width:15px;height:15px;border-radius:999px;background:${mediaColorMap[mediaType] || '#8B1E1A'};border:2px solid #FFFFFF;box-shadow:0 2px 10px rgba(92,21,20,.5);"></div>`,
                    iconSize: [15, 15],
                    iconAnchor: [7, 7],
                });

                const clusterGroup = L.markerClusterGroup({
                    showCoverageOnHover: false,
                    maxClusterRadius: 48,
                    iconCreateFunction: (cluster) => L.divIcon({
                        html: `<div style="background:#5C1514;color:#fff;border:2px solid #F04A2A;border-radius:999px;min-width:38px;height:38px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;padding:0 6px;">${cluster.getChildCount()}</div>`,
                        className: 'em-cluster-icon',
                        iconSize: [38, 38],
                    }),
                });

                const bounds = [];
                pointsJson.forEach((point) => {
                    const marker = L.marker([point.lat, point.lng], { icon: createIcon(point.media_type) });
                    const countyPoles = groupedByNorm[normCountyKey(point.county)]?.poles ?? 'N/A';
                    marker.bindPopup(
                        `<div style="min-width:190px;font-family:system-ui,sans-serif">
                            <p style="font-weight:800;color:#5C1514;margin:0 0 6px">${point.town}</p>
                            <p style="margin:0 0 4px;font-size:12px"><strong>County:</strong> ${point.county}</p>
                            <p style="margin:0 0 4px;font-size:12px"><strong>County poles:</strong> ${countyPoles}</p>
                            <p style="margin:0 0 8px;font-size:12px"><strong>Media:</strong> ${point.media_type}</p>
                            <a href="{{ route('quote') }}?location=${encodeURIComponent(point.town)}&county=${encodeURIComponent(point.county)}&media_type=${encodeURIComponent(point.media_type)}" style="display:inline-block;background:#8B1E1A;color:#fff;padding:6px 10px;border-radius:8px;font-size:12px;text-decoration:none">Request quote</a>
                        </div>`,
                    );
                    marker.on('click', () => {
                        updateHomeInsight(deriveInsight(point, groupedByNorm));
                        map.flyTo([point.lat, point.lng], Math.max(map.getZoom(), 8), { duration: 0.7 });
                    });
                    clusterGroup.addLayer(marker);
                    bounds.push([point.lat, point.lng]);
                });

                map.addLayer(clusterGroup);
                if (bounds.length) {
                    map.fitBounds(bounds, { padding: [28, 28] });
                }

                if (pointsJson[0]) {
                    updateHomeInsight(deriveInsight(pointsJson[0], groupedByNorm));
                }
            };

            if (mapShell && typeof window.emLoadLeaflet === 'function') {
                mapShell.classList.add('em-map-skeleton');
                const start = () => {
                    window
                        .emLoadLeaflet({ withMarkerCluster: true, withHeat: true })
                        .then(() => requestAnimationFrame(initHomeCoverageMap))
                        .catch(() => {});
                };
                if ('IntersectionObserver' in window) {
                    const io = new IntersectionObserver(
                        (entries) => {
                            entries.forEach((entry) => {
                                if (!entry.isIntersecting) return;
                                io.disconnect();
                                start();
                            });
                        },
                        { rootMargin: '200px 0px', threshold: 0.02 },
                    );
                    io.observe(mapShell);
                } else {
                    start();
                }
            }

            const parallaxItems = document.querySelectorAll('.em-parallax[data-parallax]');
            const onScroll = () => {
                const y = window.scrollY;
                parallaxItems.forEach((item) => {
                    const speed = Number(item.getAttribute('data-parallax'));
                    item.style.transform = `translate3d(0, ${y * speed}px, 0)`;
                });
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        });
    </script>
@endsection
