@extends('layouts.website')

@section('title', $websitePage['title'] ?? 'Who We Are | Effective Media')
@section('meta_description', $websitePage['meta_description'] ?? '')

@section('content')
    @php
        $s = $websitePage['sections'] ?? [];
        $reach = collect($profileContent['reach'] ?? []);
        $counties = max($reach->pluck('county')->filter()->unique()->count(), 1);
        $locations = max($reach->count(), 1);
        $poles = max((int) $reach->sum('poles'), 1);
        $firstCompanyProfile = $companyProfiles->first();
        $hero = $s['hero'] ?? [];
        $wwaHeroImg = trim((string) ($hero['image'] ?? ''));
        $wwaHeroBg = $wwaHeroImg !== '' ? $wwaHeroImg : asset('profile-gallery/profile-page-2.jpg');
        $trust = $s['trust_metrics'] ?? [];
        $ops = $s['operational_capability'] ?? [];
        $missionBlock = $s['mission'] ?? [];
        $deployment = $s['deployment_workflow'] ?? [];
        $maintenance = $s['maintenance_capability'] ?? [];
        $why = $s['why_brands'] ?? [];
        $proof = $s['proof_strip'] ?? [];
        $closing = $s['closing_cta'] ?? [];
        $storyIntro = $profileContent['about']['company'] ?? '';
        if (!empty(($s['company_story']['intro'] ?? ''))) {
            $storyIntro = $s['company_story']['intro'];
        }
    @endphp

    <section class="relative isolate min-h-[36vh] overflow-hidden border-b border-[#e8d9ce] bg-[#fffaf6]">
        <div class="pointer-events-none absolute inset-0 z-0" aria-hidden="true">
            <img src="{{ $wwaHeroBg }}" alt="" width="1920" height="1080" class="h-full min-h-[36vh] w-full object-cover object-center opacity-38" loading="eager" fetchpriority="high" decoding="async">
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,250,246,0.92)_0%,rgba(255,250,246,0.88)_42%,rgba(255,250,246,0.96)_100%)]"></div>
        </div>
        <div class="em-container relative z-10 grid gap-10 py-16 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] lg:items-center lg:py-20">
            <div>
                @if (($hero['layout'] ?? '') === 'editorial_split')
                    <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#8b1e1a]">{{ $hero['kicker'] ?? 'Who we are' }}</p>
                    <h1 class="mt-3 text-[2.05rem] font-black leading-[1.05] tracking-tight text-[#1a1311] sm:text-4xl lg:text-[2.85rem]">{{ $hero['title'] ?? '' }}</h1>
                    <p class="mt-5 max-w-xl text-sm leading-relaxed text-[#4a3b36] sm:text-[15px]">{{ $hero['description'] ?? '' }}</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ url($hero['primary_cta_url'] ?? '/coverage') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-[#1a1311] px-5 py-2.5 text-sm font-bold text-white">{{ $hero['primary_cta_label'] ?? 'See coverage map' }}</a>
                        @if (($hero['secondary_cta_label'] ?? '') !== '')
                            @php($secHref = $hero['secondary_cta_url'] ?? '/portfolio')
                            <a href="{{ str_starts_with((string) $secHref, 'http') ? $secHref : url($secHref) }}" class="inline-flex min-h-[44px] items-center rounded-lg border border-[#cfa994] px-5 py-2.5 text-sm font-semibold text-[#5c1514]">{{ $hero['secondary_cta_label'] }}</a>
                        @endif
                    </div>
                @endif
                <dl class="mt-10 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-[#ead5c7] bg-white px-4 py-4">
                        <dt class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#93665a]">{{ $trust['note'] ?? 'Live register + claims' }}</dt>
                        <dd class="mt-2 text-2xl font-black text-[#1a1311]">{{ number_format(max(730, $poles)) }}+</dd>
                        <dd class="text-xs text-[#6b584f]">Treated poles / faces in motion</dd>
                    </div>
                    <div class="rounded-xl border border-[#ead5c7] bg-white px-4 py-4">
                        <dd class="text-2xl font-black text-[#1a1311]">{{ $counties }}+</dd>
                        <dd class="text-xs text-[#6b584f]">County anchors</dd>
                    </div>
                    <div class="rounded-xl border border-[#ead5c7] bg-white px-4 py-4">
                        <dd class="text-2xl font-black text-[#1a1311]">{{ $locations }}+</dd>
                        <dd class="text-xs text-[#6b584f]">Named sites serviced</dd>
                    </div>
                </dl>
            </div>
            <div class="relative">
                <div class="relative z-10 aspect-[4/5] overflow-hidden rounded-[1.65rem] border border-[#e4cfbf] shadow-[0_38px_80px_-52px_rgb(92,21,20,0.75)] lg:aspect-[11/13]">
                    <img src="{{ $hero['image'] ?? asset('profile-gallery/profile-page-2.jpg') }}" alt="Effective Media operations" loading="lazy" decoding="async" class="h-full w-full object-cover">
                </div>
                <div class="absolute -bottom-6 left-6 right-6 z-20 rounded-2xl border border-[#f0dfd2] bg-white/95 p-4 shadow-lg backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#8b1e1a]">{{ $trust['title'] ?? 'Nationwide continuity' }}</p>
                    <p class="mt-2 text-sm text-[#493631]">{{ $trust['subtitle'] ?? 'Single operator discipline from artwork handoff through maintenance rotations.' }}</p>
                </div>
            </div>
        </div>
    </section>

    @if (($websitePage['body'] ?? '') !== '')
        <section class="em-container py-10">
            <div class="prose prose-sm mx-auto max-w-3xl text-[#392a26] prose-headings:font-black prose-a:text-[#8b1e1a]">
                {!! $websitePage['body'] !!}
            </div>
        </section>
    @endif

    <section class="border-y border-[#efe1d6] bg-white" id="mission-vision">
        <div class="em-container py-14 lg:py-18">
            <div class="max-w-xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#8b1e1a]">{{ $missionBlock['label'] ?? 'Mission' }}</p>
                <h2 class="mt-2 text-2xl font-black text-[#1a1311] sm:text-3xl">{{ $missionBlock['title'] ?? '' }}</h2>
            </div>
            <div class="mt-10 grid gap-8 lg:grid-cols-[1.1fr_minmax(0,0.9fr)] lg:gap-14">
                <div class="space-y-8">
                    <div class="rounded-2xl border-l-8 border-[#8b1e1a] bg-[#fdf7f3] px-6 py-6">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#6d3f36]">{{ $missionBlock['mission_title'] ?? 'Mission' }}</p>
                        <p class="mt-3 text-[15px] leading-relaxed text-[#392a26]">{{ $profileContent['about']['mission'] ?? '' }}</p>
                    </div>
                    <div class="rounded-2xl border border-[#ecdac8] px-6 py-6">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#6d3f36]">{{ $missionBlock['vision_title'] ?? 'Vision' }}</p>
                        <p class="mt-3 text-[15px] leading-relaxed text-[#392a26]">{{ $profileContent['about']['vision'] ?? '' }}</p>
                    </div>
                </div>
                <div>
                    @php($cstory = $s['company_story'] ?? [])
                    <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#8b1e1a]">{{ $cstory['label'] ?? 'Company story' }}</p>
                    <h3 class="mt-3 text-xl font-black text-[#1a1311]">{{ $cstory['title'] ?? 'Momentum through discipline' }}</h3>
                    <p class="mt-4 text-sm leading-relaxed text-[#493631]">{{ $storyIntro }}</p>
                    <ol class="mt-8 border-l border-[#e3cdbd]">
                        @foreach (($profileContent['about']['story_timeline'] ?? []) as $entry)
                            <li class="relative py-5 pl-8">
                                <span class="absolute left-[-5px] top-7 inline-block h-[9px] w-[9px] rounded-full bg-[#8b1e1a]"></span>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#93594c]">{{ $entry['period'] ?? '' }}</p>
                                <p class="mt-2 text-[15px] font-bold text-[#1a1311]">{{ $entry['title'] ?? '' }}</p>
                                <p class="mt-1 text-sm text-[#5c4a43]">{{ $entry['detail'] ?? '' }}</p>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#171111] py-14 text-[#fdeee5] lg:py-18" id="operations">
        <div class="em-container grid gap-10 lg:grid-cols-[0.95fr_minmax(0,1.1fr)] lg:gap-14">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#f8b596]">{{ $ops['label'] ?? 'Capability' }}</p>
                <h2 class="mt-4 text-[1.95rem] font-black leading-[1.1]">{{ $ops['title'] ?? '' }}</h2>
                <p class="mt-6 text-sm leading-relaxed text-[#e9d9cf]">{{ $ops['body'] ?? '' }}</p>
            </div>
            <ul class="divide-y divide-white/15">
                @foreach (($ops['bullets'] ?? []) as $bullet)
                    <li class="flex gap-4 py-4">
                        <span class="mt-1 h-10 w-1 rounded-full bg-gradient-to-b from-[#f04a2a] to-[#5c1514]" aria-hidden="true"></span>
                        <span class="text-sm leading-relaxed text-[#f6e9e1]">{{ $bullet }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="bg-[#f7efe7] py-14 lg:py-18" id="workflow">
        <div class="em-container">
            <div class="max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#8b1e1a]">{{ $deployment['label'] ?? 'Workflow' }}</p>
                <h2 class="mt-4 text-[1.95rem] font-black text-[#1a1311]">{{ $deployment['title'] ?? '' }}</h2>
            </div>
            <div class="mt-12 hidden gap-6 lg:flex">
                @foreach (($deployment['steps'] ?? []) as $index => $step)
                    <div class="relative flex flex-1 flex-col rounded-2xl border border-[#e2cbb8] bg-white p-6">
                        @php($stepCount = count($deployment['steps'] ?? []))
                        @if ($stepCount > 0 && $index < $stepCount - 1)
                            <div class="absolute right-[-0.875rem] top-14 hidden h-0.5 w-7 bg-gradient-to-r from-[#cfa994] to-transparent lg:block"></div>
                        @endif
                        <p class="font-mono text-xs text-[#8b1e1a]">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</p>
                        <h3 class="mt-5 text-[15px] font-black">{{ $step['title'] ?? '' }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-[#4d3c36]">{{ $step['detail'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 space-y-4 lg:hidden">
                @foreach (($deployment['steps'] ?? []) as $index => $step)
                    <div class="rounded-2xl border border-[#e2cbb8] bg-white p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#8b1e1a]">Phase {{ $index + 1 }}</p>
                        <h3 class="mt-3 text-[15px] font-black">{{ $step['title'] ?? '' }}</h3>
                        <p class="mt-2 text-sm text-[#4d3c36]">{{ $step['detail'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-t border-[#efe4da] bg-white py-14" id="maintenance">
        <div class="em-container grid gap-10 lg:grid-cols-[0.92fr_minmax(0,1fr)] lg:gap-14">
            @php($maintImgRaw = trim((string) ($maintenance['image'] ?? 'profile-gallery/profile-page-6.jpg')))
            @php($maintImgSrc = str_starts_with($maintImgRaw, 'http') ? $maintImgRaw : asset($maintImgRaw))
            <img src="{{ $maintImgSrc }}" alt="{{ $maintenance['title'] ?? 'Maintenance' }}" class="h-[360px] w-full rounded-[1.5rem] object-cover lg:h-full lg:max-h-[420px]">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#8b1e1a]">{{ $maintenance['label'] ?? '' }}</p>
                <h2 class="mt-4 text-[1.9rem] font-black text-[#1a1311]">{{ $maintenance['title'] ?? '' }}</h2>
                <p class="mt-5 text-sm leading-relaxed text-[#443530]">{{ $maintenance['body'] ?? '' }}</p>
                <div class="mt-8 grid gap-3">
                    @foreach (($maintenance['points'] ?? []) as $pt)
                        <div class="flex gap-4 rounded-xl border border-[#e8d9ce] px-5 py-4">
                            <span class="text-lg">✔</span>
                            <p class="text-sm text-[#3c2d28]">{{ $pt }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @php($whyItems = is_array(($why['items'] ?? null)) ? $why['items'] : [])
    @if (($whyItems ?: ($profileContent['why_choose_us'] ?? [])) !== [])
        <section class="bg-[#fff9f5] py-14" id="why-brands">
            <div class="em-container">
                <div class="max-w-4xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#8b1e1a]">{{ $why['label'] ?? '' }}</p>
                    <h2 class="mt-4 text-[1.9rem] font-black text-[#1a1311]">{{ $why['title'] ?? '' }}</h2>
                </div>
                <div class="mt-10 grid gap-5 md:grid-cols-3">
                    @foreach (($whyItems ?: ($profileContent['why_choose_us'] ?? [])) as $idx => $point)
                        @php($title = is_array($point) ? ($point['title'] ?? $point['headline'] ?? '') : '')
                        @php($detail = is_array($point) ? ($point['detail'] ?? $point['body'] ?? '') : '')
                        @if ($title !== '')
                            <figure class="{{ $idx % 3 === 1 ? 'md:translate-y-6' : '' }} rounded-[1.4rem] border border-[#e6d6c9] bg-white p-7 shadow-[0_24px_60px_-40px_rgb(139,58,52,0.35)]">
                                <figcaption class="text-[15px] font-black leading-snug text-[#1a1311]">{{ $title }}</figcaption>
                                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.18em] text-[#95675c]">Reason {{ $loop->iteration }}</p>
                                <p class="mt-3 text-sm leading-relaxed text-[#5c483f]">{{ $detail }}</p>
                            </figure>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="border-y border-[#ead5c9] bg-gradient-to-br from-[#5c1514] via-[#4f1815] to-[#2f0f10] py-14 text-white">
        <div class="em-container flex flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#ffd7c6]">{{ $proof['label'] ?? 'Proof' }}</p>
                <h2 class="mt-4 text-2xl font-black sm:text-3xl">{{ $proof['title'] ?? '' }}</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ url($proof['cta_url'] ?? '/portfolio') }}" class="inline-flex min-h-[46px] items-center justify-center rounded-xl bg-white px-6 text-sm font-black text-[#5c1514]">{{ $proof['cta_label'] ?? 'Open portfolio' }}</a>
                <a href="{{ url($proof['secondary_url'] ?? '/coverage') }}" class="inline-flex min-h-[46px] items-center justify-center rounded-xl border border-white/40 px-6 text-sm font-semibold text-white">{{ $proof['secondary_label'] ?? 'Map footprint' }}</a>
            </div>
        </div>
    </section>

    <section class="em-container py-14 text-center">
        <h2 class="text-2xl font-black text-[#1a1311] sm:text-3xl">{{ $closing['title'] ?? '' }}</h2>
        <p class="mx-auto mt-4 max-w-2xl text-sm text-[#5a453d]">{{ $closing['description'] ?? '' }}</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <x-ui.brand-button href="{{ url($closing['primary_url'] ?? '/quote') }}">{{ $closing['primary_label'] ?? 'Start a quote' }}</x-ui.brand-button>
            <x-ui.brand-button href="{{ url($closing['secondary_url'] ?? '/contact-us') }}" variant="secondary">{{ $closing['secondary_label'] ?? 'Talk to sales' }}</x-ui.brand-button>
            @if (!empty($firstCompanyProfile['url']))
                <a href="{{ $firstCompanyProfile['url'] }}" class="inline-flex min-h-[44px] items-center justify-center rounded-md border border-[#d7bca7] px-5 text-sm font-semibold text-[#5c1514]">Download profile</a>
            @endif
        </div>
    </section>
@endsection
