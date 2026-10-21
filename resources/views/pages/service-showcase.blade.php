@extends('layouts.website')

@section('title', $service['title'] . ' | Effective Media')

@section('meta_description', $service['description'])

@section('content')
    @php($svcHeroImg = trim((string) ($service['hero_image'] ?? '')))
    @php($svcHeroBg = $svcHeroImg !== '' ? $svcHeroImg : asset('profile-gallery/profile-page-3.jpg'))
    <section class="relative isolate min-h-[40vh] overflow-hidden bg-[#0f0c0c] py-16 text-white lg:min-h-0 lg:py-24">
        <div class="pointer-events-none absolute inset-0 z-0" aria-hidden="true">
            <img src="{{ $svcHeroBg }}" alt="" width="1920" height="960" class="h-full min-h-[40vh] w-full object-cover object-center lg:min-h-full" loading="eager" fetchpriority="high" decoding="async">
        </div>
        <div class="pointer-events-none absolute inset-0 z-[1] bg-[linear-gradient(120deg,rgba(10,10,10,0.84),rgba(92,21,20,0.78),rgba(10,10,10,0.82))]"></div>
        <div class="em-container relative z-10 grid items-center gap-10 lg:grid-cols-2">
            <div class="space-y-5">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#f0c3ac]">{{ $service['kicker'] }}</p>
                <h1 class="text-4xl font-black leading-tight sm:text-5xl">{{ $service['headline'] }}</h1>
                <p class="max-w-2xl text-sm leading-7 text-[#ead6cb] sm:text-base">{{ $service['description'] }}</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('quote', ['media_type' => $service['title'], 'campaign_slug' => $serviceSlug]) }}" class="inline-flex rounded-md bg-white px-5 py-3 text-sm font-bold text-[#5c1514]">Get Quote</a>
                    <a href="{{ route('smart-campaign-planner', ['media_type' => $service['title']]) }}" class="inline-flex rounded-md border border-white/50 px-5 py-3 text-sm font-bold text-white">Plan Campaign</a>
                    <a href="{{ route('what-we-do') }}" class="inline-flex rounded-md border border-white/50 px-5 py-3 text-sm font-bold text-white">Back to Services</a>
                </div>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-6 backdrop-blur">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f4be9f]">Service Metrics</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    @foreach ($service['metrics'] as $metric)
                        <div>
                            <p class="text-2xl font-black text-[#ffd6c1]">{{ $metric['value'] }}</p>
                            <p class="text-xs uppercase tracking-[0.14em] text-[#f2e0d7]">{{ $metric['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#f7efe8] py-16 lg:py-20">
        <div class="em-container grid gap-8 lg:grid-cols-3">
            <article class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="text-lg font-black text-[#2c1716]">Ideal Use Cases</h2>
                <ul class="mt-4 space-y-2 text-sm text-[#4f3e3b]">
                    @foreach ($service['use_cases'] as $item)
                        <li>- {{ $item }}</li>
                    @endforeach
                </ul>
            </article>
            <article class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="text-lg font-black text-[#2c1716]">Recommended Industries</h2>
                <ul class="mt-4 space-y-2 text-sm text-[#4f3e3b]">
                    @foreach ($service['industries'] as $item)
                        <li>- {{ $item }}</li>
                    @endforeach
                </ul>
            </article>
            <article class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="text-lg font-black text-[#2c1716]">Campaign Example</h2>
                <p class="mt-4 text-sm leading-7 text-[#4f3e3b]">{{ $service['campaign_example'] }}</p>
            </article>
        </div>
    </section>

    <section class="bg-[#5c1514] py-14 text-white">
        <div class="em-container flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f1c2a9]">Next Step</p>
                <h2 class="mt-1 text-2xl font-black">Request Available Sites and Pricing</h2>
            </div>
            <a href="{{ route('quote', ['media_type' => $service['title'], 'campaign_slug' => $serviceSlug]) }}" class="inline-flex rounded-md bg-white px-5 py-3 text-sm font-bold text-[#5c1514]">Start Quotation</a>
        </div>
    </section>
@endsection
