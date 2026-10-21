@extends('layouts.website')

@section('title', $websitePage['title'] ?? 'Smart Campaign Planner | Effective Media')
@section('meta_description', $websitePage['meta_description'] ?? '')

@section('content')
    @include('components.em-leaflet-loader-inline')
    @php
        $s = $websitePage['sections'] ?? [];
        $hero = $s['hero'] ?? [];
        $workflowLane = $s['workflow_lane'] ?? [];
        $lane = $workflowLane['steps'] ?? [];
        $assistant = $s['assistant_panel_copy'] ?? [];
        $workspace = $s['workspace'] ?? [];
        $rec = $s['recommendation_panel'] ?? [];
        $plannerHeroFallback = 'https://images.unsplash.com/photo-1576608583800-2dea5261c7ee?auto=format&fit=crop&w=2400&q=85';
        $plannerHeroImg = trim((string) ($hero['image'] ?? ''));
        $plannerHeroSrc = $plannerHeroImg !== '' ? $plannerHeroImg : $plannerHeroFallback;
    @endphp
    <section class="relative isolate flex min-h-[42vh] flex-col justify-center overflow-hidden bg-[#1a100e] text-[#fef3e8] md:min-h-[46vh]">
        <div class="pointer-events-none absolute inset-0 z-0 bg-[#1a100e]" aria-hidden="true">
            <img
                src="{{ $plannerHeroSrc }}"
                alt=""
                width="1920"
                height="1080"
                decoding="async"
                fetchpriority="high"
                loading="eager"
                class="absolute inset-0 z-0 h-full min-h-[46vh] w-full object-cover object-[center_32%]"
            >
        </div>
        <div class="pointer-events-none absolute inset-0 z-[2] bg-[linear-gradient(115deg,rgba(42,18,17,0.62)_0%,rgba(92,21,20,0.48)_48%,rgba(26,10,9,0.74)_100%)]"></div>
        <div class="pointer-events-none absolute inset-0 z-[3] bg-[radial-gradient(circle_at_18%_22%,rgba(240,74,42,0.22),transparent_55%)]"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 z-[4] h-1/3 bg-gradient-to-t from-[#120a0a]/85 to-transparent"></div>
        <div class="em-container relative z-10 py-14 sm:py-16 lg:py-24">
            <div class="max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#f04a2a]">{{ $hero['kicker'] ?? 'Planner' }}</p>
                <h1 class="mt-4 text-[2.1rem] font-black leading-tight sm:text-4xl lg:text-[2.75rem] text-white">{{ $hero['title'] ?? 'Guided briefing assistant' }}</h1>
                <p class="mt-6 max-w-2xl text-sm leading-relaxed text-[#e7d9cb] sm:text-[15px]">{{ $hero['description'] ?? '' }}</p>
            </div>
            @if (($hero['pill'] ?? '') !== '')
                <div class="mt-8 inline-flex max-w-xl items-center gap-3 rounded-[1rem] border border-[#ffd9c8]/30 bg-black/15 px-4 py-3 text-[13px] text-[#fef3e8] backdrop-blur">
                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#f04a2a] text-sm font-black text-white">i</span>
                    <span>{{ $hero['pill'] }}</span>
                </div>
            @endif
        </div>
    </section>

    <section class="relative border-y border-[#ecdac8] bg-white py-14">
        <div class="em-container grid gap-8 lg:grid-cols-[0.92fr_minmax(0,1fr)]">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $workflowLane['label'] ?? '' }}</p>
                <h2 class="mt-4 text-[1.75rem] font-black text-[#5c1514]">{{ $assistant['heading'] ?? 'How the assistant behaves' }}</h2>
                <p class="mt-4 max-w-xl text-sm text-[#5a4a42]">{{ $assistant['body'] ?? '' }}</p>
            </div>
            <ol class="divide-y divide-[#f0e6dc] rounded-[1.5rem] border border-[#ecdac8] bg-[#fef9f3] shadow-[0_14px_40px_-38px_rgba(92,21,20,0.22)]">
                @foreach ($lane as $index => $step)
                    <li class="flex gap-6 px-6 py-5">
                        <div class="mt-1 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#5c1514] font-mono text-sm font-black text-[#ffd9c8]">{{ $index + 1 }}</div>
                        <div>
                            <p class="text-[15px] font-black text-[#5c1514]">{{ $step['title'] ?? '' }}</p>
                            <p class="mt-2 text-sm leading-relaxed text-[#7f6d62]">{{ $step['detail'] ?? '' }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section id="{{ trim((string) ($workspace['section_location_id'] ?? 'location')) ?: 'location' }}" class="em-container pb-28 max-lg:pb-[7.75rem] lg:pb-20">
        @php
            $towns = collect($profileContent['coverage_map_points'] ?? [])->pluck('town')->unique()->sort()->values();
            $counties = collect($profileContent['coverage_map_points'] ?? [])->pluck('county')->unique()->sort()->values();
            $industries = collect($profileContent['industries'] ?? [])->values();
            $mapPoints = collect($profileContent['coverage_map_points'] ?? [])->values();
        @endphp
        <div class="grid gap-6 pb-24 lg:grid-cols-[1.1fr,0.9fr] lg:pb-20">
            <div id="calculator" class="em-card p-5 sm:p-6">
                <h2 class="text-xl font-bold text-[#5c1514]">{{ $workspace['form_title'] ?? '' }}</h2>
                <form class="mt-4 grid gap-4" id="planner-form">
                    <div class="grid gap-4 md:grid-cols-2">
                        <select class="min-h-[44px] w-full rounded-md border border-[#d7bca7] px-3 py-2 text-sm" name="county" data-county-select>
                            <option value="">Select county</option>
                            @foreach ($counties as $county)
                                <option value="{{ $county }}" @selected(request('county') === $county)>{{ $county }}</option>
                            @endforeach
                        </select>
                        <select class="min-h-[44px] w-full rounded-md border border-[#d7bca7] px-3 py-2 text-sm disabled:bg-[#f5f1ec] disabled:text-[#8b7d73]" name="location" data-town-select disabled>
                            <option value="">Select town</option>
                            @foreach ($towns as $town)
                                <option value="{{ $town }}" @selected(request('location') === $town)>{{ $town }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-xs text-[#7f6d62]" data-town-help>Select a county first to load mapped towns.</p>
                    <div class="grid gap-4 md:grid-cols-2">
                        <select class="min-h-[44px] w-full rounded-md border border-[#d7bca7] px-3 py-2 text-sm" name="industry">
                            <option value="">Select industry</option>
                            @foreach ($industries as $industry)
                                <option value="{{ $industry }}">{{ $industry }}</option>
                            @endforeach
                        </select>
                        <select class="min-h-[44px] w-full rounded-md border border-[#d7bca7] px-3 py-2 text-sm" name="campaign_objective">
                            <option value="">Campaign objective</option>
                            <option value="Brand Awareness">Brand Awareness</option>
                            <option value="Product Launch">Product Launch</option>
                            <option value="Lead Generation">Lead Generation</option>
                            <option value="Store Traffic">Store Traffic</option>
                        </select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <select class="min-h-[44px] w-full rounded-md border border-[#d7bca7] px-3 py-2 text-sm" name="target_audience">
                            <option value="">Target audience</option>
                            <option value="Commuters">Commuters</option>
                            <option value="Urban Families">Urban Families</option>
                            <option value="Business Owners">Business Owners</option>
                            <option value="Youth">Youth</option>
                        </select>
                        <select class="min-h-[44px] w-full rounded-md border border-[#d7bca7] px-3 py-2 text-sm" name="budget_range">
                            <option value="">Budget range</option>
                            <option value="KES 100,000 - 300,000">KES 100,000 - 300,000</option>
                            <option value="KES 300,000 - 700,000">KES 300,000 - 700,000</option>
                            <option value="KES 700,000+">KES 700,000+</option>
                        </select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <select class="min-h-[44px] w-full rounded-md border border-[#d7bca7] px-3 py-2 text-sm" name="campaign_duration">
                            <option value="">Campaign duration</option>
                            <option value="1 Month">1 Month</option>
                            <option value="3 Months">3 Months</option>
                            <option value="6 Months+">6 Months+</option>
                        </select>
                        <select class="min-h-[44px] w-full rounded-md border border-[#d7bca7] px-3 py-2 text-sm" name="media_type">
                            <option value="">Preferred media type</option>
                            <option value="Street Light Ads">Street Light Ads</option>
                            <option value="Billboards">Billboards</option>
                            <option value="Pavement Ads">Pavement Ads</option>
                            <option value="Office Branding">Office Branding</option>
                        </select>
                    </div>
                    <button class="em-btn-primary hidden min-h-[48px] w-full justify-center sm:w-auto lg:inline-flex lg:w-fit" type="submit" data-track-event="campaign_planner_used">{{ $workspace['submit_label'] ?? 'Run scenario' }}</button>
                </form>
                <div class="mt-6 rounded-lg border border-[#e2cdb9] bg-white p-3">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">{{ $workspace['map_explorer_label'] ?? '' }}</p>
                    <div id="planner-map" class="h-[min(88vw,360px)] min-h-[240px] w-full rounded-md touch-manipulation sm:h-[min(70vw,22rem)] md:h-80"></div>
                </div>
            </div>

            <div id="recommendation" class="space-y-4">
                <div id="planner-result-card" class="overflow-hidden rounded-[1.65rem] border border-[#ecdac8] bg-gradient-to-br from-[#fef9f3] via-white to-[#fff7f2] p-6 shadow-[0_38px_80px_-60px_rgba(92,21,20,0.38)] ring-1 ring-[#f0e0d4]">
                    <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-[#8b1e1a]">{{ $rec['rail_label'] ?? '' }}</p>
                    <h3 class="mt-2 text-2xl font-black text-[#5c1514]" data-planner-headline>{{ $rec['headline_placeholder'] ?? '' }}</h3>
                    <div class="mt-5 flex flex-wrap gap-2 text-[11px] font-bold uppercase tracking-wide text-[#5c1514]">
                        <span class="rounded-full border border-[#e7d9cb] bg-white px-3 py-1" data-planner-chip-county>Nakuru County</span>
                        <span class="rounded-full border border-[#e7d9cb] bg-white px-3 py-1" data-planner-chip-town>Nakuru Town</span>
                        <span class="rounded-full border border-[#f04a2a]/25 bg-[#fef3e8] px-3 py-1" data-planner-chip-media>Street Light Ads</span>
                    </div>
                    <p class="mt-6 text-sm font-semibold leading-relaxed text-[#3d2a26]" data-planner-summary>{{ $rec['summary_seed'] ?? '' }}</p>
                    <div class="mt-6 grid gap-3 md:grid-cols-2">
                        <div class="rounded-2xl border border-[#ecdac8] bg-white p-5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#7f6d62]">{{ $rec['focus_heading'] ?? 'Suggested focus' }}</p>
                            <p class="mt-2 text-sm font-bold text-[#5c1514]" data-result-locations>Nakuru CBD, Kenyatta Avenue, Total Interchange</p>
                        </div>
                        <div class="rounded-2xl border border-[#f04a2a]/25 bg-[#fff5f1] p-5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#8b1e1a]">Indicative budget band</p>
                            <p class="mt-2 text-sm font-bold text-[#5c1514]" data-result-cost>KES 300,000 - 700,000</p>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('quote') }}" class="em-btn-primary inline-flex min-h-[46px] items-center rounded-full px-7 text-xs font-black" id="planner-generate-quote">{{ $rec['export_quote_label'] ?? '' }}</a>
                        <a href="{{ $whatsappHrefQuote }}" class="inline-flex min-h-[46px] items-center rounded-full border border-[#8b1e1a]/35 bg-white px-6 text-xs font-semibold text-[#5c1514] hover:bg-[#fef3e8]" target="_blank" rel="noopener noreferrer" data-track-event="whatsapp_clicked" data-planner-whatsapp>{{ $rec['whatsapp_label'] ?? '' }}</a>
                    </div>
                    <p class="mt-5 text-xs leading-relaxed text-[#7f6d62]" data-result-map-note>{{ $rec['map_hint'] ?? '' }}</p>
                </div>
                <div class="rounded-[1.35rem] border border-[#ecdac8] bg-white p-5 shadow-[0_12px_32px_-28px_rgba(92,21,20,0.25)]">
                    <h3 class="text-sm font-black uppercase tracking-[0.14em] text-[#8b1e1a]">{{ $rec['detail_corridors'] ?? '' }}</h3>
                    <p class="mt-3 text-sm text-[#5a4a42]" data-result-locations-detail>Nakuru CBD, Kenyatta Avenue, Total Interchange</p>
                </div>
                <div class="rounded-[1.35rem] border border-[#ecdac8] bg-white p-5 shadow-[0_12px_32px_-28px_rgba(92,21,20,0.25)]">
                    <h3 class="text-sm font-black uppercase tracking-[0.14em] text-[#8b1e1a]">{{ $rec['detail_media'] ?? '' }}</h3>
                    <p class="mt-3 text-sm text-[#5a4a42]" data-result-media>Street Light Ads</p>
                </div>
                <div class="rounded-[1.35rem] border border-[#ecdac8] bg-[#fef9f3] p-5">
                    <h3 class="text-sm font-black uppercase tracking-[0.14em] text-[#8b1e1a]">{{ $rec['detail_logic'] ?? '' }}</h3>
                    <p class="mt-3 text-sm text-[#5a4a42]" data-result-why>Reasoning updates per objective selection — commuter repetition vs prestige vs tactical proximity.</p>
                </div>
            </div>
        </div>

        <div class="pointer-events-none fixed bottom-0 left-0 right-0 z-[35] flex justify-center px-4 pb-[calc(4.25rem+env(safe-area-inset-bottom,0px))] pt-2 lg:hidden">
            <button type="submit" form="planner-form" class="pointer-events-auto w-full max-w-lg min-h-[52px] rounded-xl bg-[#8b1e1a] px-6 py-3 text-sm font-black text-white shadow-[0_-4px_24px_rgba(0,0,0,0.12)] ring-1 ring-black/10 transition hover:bg-[#f04a2a] active:brightness-95" data-track-event="campaign_planner_used">{{ $workspace['submit_label'] ?? 'Run scenario' }}</button>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('planner-form');
            const quoteLink = document.getElementById('planner-generate-quote');
            const mapEl = document.getElementById('planner-map');
            if (!form || !quoteLink) return;

            const countySelect = form.querySelector('[name="county"]');
            const locationSelect = form.querySelector('[name="location"]');
            const mediaTypeSelect = form.querySelector('[name="media_type"]');
            const townHelp = form.querySelector('[data-town-help]');
            const mapPoints = @json($mapPoints);

            const normCountyKey = (name) =>
                String(name ?? '')
                    .trim()
                    .toLowerCase()
                    .replace(/\s+county$/i, '')
                    .trim();

            const pointsInCounty = (county) =>
                county ? mapPoints.filter((point) => normCountyKey(point.county) === normCountyKey(county)) : mapPoints;

            const syncTownOptions = (county, preferredTown = '') => {
                if (!locationSelect) return;
                locationSelect.disabled = !county;
                const current = preferredTown || locationSelect.value;
                const filteredTowns = county
                    ? pointsInCounty(county).map((point) => point.town)
                    : mapPoints.map((point) => point.town);
                const uniqueTowns = [...new Set(filteredTowns)].sort((a, b) => a.localeCompare(b));

                locationSelect.innerHTML = '<option value="">Select town</option>';
                uniqueTowns.forEach((town) => {
                    const option = document.createElement('option');
                    option.value = town;
                    option.textContent = town;
                    locationSelect.appendChild(option);
                });

                if (current && uniqueTowns.includes(current)) {
                    locationSelect.value = current;
                } else if (uniqueTowns.length > 0) {
                    locationSelect.value = uniqueTowns[0];
                } else {
                    locationSelect.value = '';
                }

                if (townHelp) {
                    if (!county) {
                        townHelp.textContent = 'Select a county first to load mapped towns.';
                        townHelp.classList.remove('text-[#8b1e1a]');
                        townHelp.classList.add('text-[#7f6d62]');
                    } else if (uniqueTowns.length === 0) {
                        townHelp.textContent = `No mapped towns yet for ${county}.`;
                        townHelp.classList.remove('text-[#7f6d62]');
                        townHelp.classList.add('text-[#8b1e1a]');
                    } else {
                        townHelp.textContent = `${uniqueTowns.length} mapped town(s) available in ${county}.`;
                        townHelp.classList.remove('text-[#8b1e1a]');
                        townHelp.classList.add('text-[#7f6d62]');
                    }
                }
            };

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const data = new FormData(form);
                const location = data.get('location') || '';
                const county = data.get('county') || 'Nakuru';
                const mediaType = data.get('media_type') || 'Street Light Ads';
                const budgetRange = data.get('budget_range') || 'KES 300,000 - 700,000';
                const objective = data.get('campaign_objective') || 'Brand Awareness';
                const matchingPoints = pointsInCounty(county);
                const fallbackTown = matchingPoints[0]?.town || 'Nakuru';
                const selectedTown = location || fallbackTown;
                const corridors = `${selectedTown} CBD · ${county} highway corridor · high-traffic junctions`;

                const headline = document.querySelector('[data-planner-headline]');
                const chipCounty = document.querySelector('[data-planner-chip-county]');
                const chipTown = document.querySelector('[data-planner-chip-town]');
                const chipMedia = document.querySelector('[data-planner-chip-media]');
                const summary = document.querySelector('[data-planner-summary]');

                if (headline) headline.textContent = `${selectedTown} · ${county}`;
                if (chipCounty) chipCounty.textContent = /county/i.test(county) ? county : `${county} County`;
                if (chipTown) chipTown.textContent = selectedTown;
                if (chipMedia) chipMedia.textContent = mediaType;
                if (summary) {
                    summary.textContent = `${mediaType} led plan for ${objective.toLowerCase()} — weighted to commuter choke points in ${selectedTown}. Indicative spend bands adjust with pole count and print scope.`;
                }

                document.querySelectorAll('[data-result-locations]').forEach((element) => {
                    element.textContent = corridors;
                });
                document.querySelectorAll('[data-result-locations-detail]').forEach((element) => {
                    element.textContent = corridors;
                });
                document.querySelectorAll('[data-result-media]').forEach((element) => {
                    element.textContent = mediaType;
                });
                document.querySelectorAll('[data-result-cost]').forEach((element) => {
                    element.textContent = budgetRange;
                });
                document.querySelectorAll('[data-result-why]').forEach((element) => {
                    element.textContent = `Recommended for ${objective} with strong repetitive visibility and commuter reach in ${county}.`;
                });

                quoteLink.href = `{{ route('quote') }}?location=${encodeURIComponent(selectedTown)}&county=${encodeURIComponent(county)}&media_type=${encodeURIComponent(mediaType)}&budget_range=${encodeURIComponent(budgetRange)}&campaign_objective=${encodeURIComponent(objective)}`;

                document.getElementById('planner-result-card')?.scrollIntoView({ behavior: 'smooth', block: 'start' });

                if (window.emTrackEvent) {
                    window.emTrackEvent('campaign_planner_used', Object.fromEntries(data.entries()));
                }
            });

            syncTownOptions(countySelect?.value || '', locationSelect?.value || '');

            const initPlannerMap = () => {
                if (!mapEl || !mapPoints.length) return;

                const map = L.map(mapEl).setView([-0.7, 37.2], 6);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                }).addTo(map);

                const bounds = [];
                const townMarkerMap = new Map();
                mapPoints.forEach((point) => {
                    const marker = L.circleMarker([point.lat, point.lng], {
                        radius: 7,
                        color: '#5C1514',
                        weight: 2,
                        fillColor: '#F04A2A',
                        fillOpacity: 0.9,
                    }).addTo(map);
                    marker.bindPopup(`<strong>${point.town}</strong><br>${point.county}<br>${point.media_type}`);
                    marker.on('click', () => {
                        if (countySelect) countySelect.value = point.county;
                        if (locationSelect) locationSelect.value = point.town;
                        if (mediaTypeSelect) mediaTypeSelect.value = point.media_type;
                        syncTownOptions(point.county, point.town);
                        form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                    });
                    bounds.push([point.lat, point.lng]);
                    townMarkerMap.set(`${point.county}::${point.town}`, marker);
                });

                map.fitBounds(bounds, { padding: [24, 24] });

                countySelect?.addEventListener('change', () => {
                    const selectedCounty = countySelect.value;
                    syncTownOptions(selectedCounty);

                    if (!selectedCounty) {
                        map.fitBounds(bounds, { padding: [24, 24] });
                        return;
                    }

                    const countyPoints = pointsInCounty(selectedCounty);
                    if (countyPoints.length) {
                        map.fitBounds(countyPoints.map((point) => [point.lat, point.lng]), { padding: [24, 24], maxZoom: 10 });
                    }
                });

                locationSelect?.addEventListener('change', () => {
                    const key = `${countySelect?.value || ''}::${locationSelect.value}`;
                    const marker = townMarkerMap.get(key);
                    if (marker) {
                        marker.openPopup();
                        map.setView(marker.getLatLng(), 11);
                    }
                });
            };

            if (mapEl && typeof window.emLoadLeaflet === 'function') {
                const start = () =>
                    window
                        .emLoadLeaflet({ withMarkerCluster: false })
                        .then(() => requestAnimationFrame(initPlannerMap))
                        .catch((err) => console.warn('Planner map failed to load', err));

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
                    io.observe(mapEl);
                } else {
                    start();
                }
            }
        });
    </script>
@endsection
