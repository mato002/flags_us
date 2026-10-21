@extends('layouts.website')

@section('title', $titlePrefix . ' ' . $town . ' | Effective Media')

@section('content')
    @php($townHeroBg = asset('profile-gallery/profile-page-2.jpg'))
    <section class="relative isolate min-h-[36vh] overflow-hidden md:min-h-[38vh]">
        <div class="pointer-events-none absolute inset-0 z-0 bg-[#1c0e0e]" aria-hidden="true">
            <img src="{{ $townHeroBg }}" alt="" width="1920" height="1080" class="h-full min-h-[36vh] w-full object-cover object-center opacity-55 md:min-h-[38vh]" loading="eager" fetchpriority="high" decoding="async">
        </div>
        <div class="pointer-events-none absolute inset-0 z-[1] bg-[linear-gradient(120deg,#5c1514d9_38%,transparent_74%)]"></div>
        <div class="pointer-events-none absolute inset-0 z-[2] bg-[linear-gradient(180deg,rgba(12,8,8,0.72)_0%,rgba(92,21,20,0.55)_42%,rgba(28,14,14,0.88)_100%)]"></div>
        <div class="em-container relative z-10 py-16 lg:py-20">
            <x-ui.section-heading :label="$county . ' County'" :title="$titlePrefix . ' ' . $town" description="Location-focused outdoor media placements designed for visibility, repetition, and measurable reach." light="true" />
        </div>
    </section>

    <section class="em-container py-14">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui.stats-card label="Town" :value="$town" />
            <x-ui.stats-card label="County" :value="$county" />
            <x-ui.stats-card label="Estimated Poles" :value="$townPoles" suffix="+" />
            <x-ui.stats-card label="Media Types" :value="$mediaTypes->count()" />
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($mediaTypes as $type)
                <div class="em-card em-card-accent p-5">
                    <h3 class="text-lg font-bold text-[#5c1514]">{{ $type }}</h3>
                    <p class="mt-2 text-sm text-[#5a5a5a]">Available in and around {{ $town }} with strategic placements for high-traffic visibility.</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 em-card p-6">
            <h2 class="text-xl font-bold text-[#5c1514]">Campaign Benefits</h2>
            <ul class="mt-4 space-y-2 text-sm text-[#4f4f4f]">
                @foreach (collect($benefits)->take(5) as $benefit)
                    <li>- {{ $benefit['title'] }}: {{ $benefit['detail'] }}</li>
                @endforeach
            </ul>
            <a href="{{ route('quote', ['location' => $town, 'county' => $county, 'media_type' => $mediaTypes->first()]) }}" class="em-btn-primary mt-5 inline-flex">Request Quote</a>
        </div>
    </section>
@endsection
