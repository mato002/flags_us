@extends('layouts.website')

@section('title', $websitePage['title'] ?? 'Coverage | Effective Media')
@section('meta_description', $websitePage['meta_description'] ?? '')

@section('content')
    @include('components.em-leaflet-loader-inline')
    @php
        $reachRows = collect($profileContent['reach'] ?? []);
        $mapPoints = collect($profileContent['coverage_map_points'] ?? []);
        $normCountyKey = static function (?string $c): string {
            $s = strtolower(trim((string) $c));
            return trim(preg_replace('/\s+county$/i', '', $s));
        };
        $countiesCovered = $reachRows->pluck('county')->unique()->count();
        $totalPoles = max((int) $reachRows->sum('poles'), 730);
        $keyTowns = max($mapPoints->count(), 18);
        $countyGroups = $reachRows->groupBy('county');
        $focusPoint = $mapPoints->first();
        $focusPoint = is_array($focusPoint) ? $focusPoint : [];
        $focusCountyRaw = $focusPoint['county'] ?? ($countyGroups->keys()->first() ?: 'Nakuru');
        $focusTown = $focusPoint['town'] ?? 'Nakuru';
        $insightCountyLabel = $focusCountyRaw;
        $insightPoles = 0;
        foreach ($countyGroups as $name => $rows) {
            if ($normCountyKey($name) === $normCountyKey($focusCountyRaw)) {
                $insightCountyLabel = (string) $name;
                $insightPoles = (int) $rows->sum('poles');
                break;
            }
        }
        $s = $websitePage['sections'] ?? [];
        $hero = $s['hero'] ?? [];
        $inv = $s['inventory_stats'] ?? [];
        $mapIntro = $s['map'] ?? [];
        $highways = $s['highways'] ?? [];
        $transport = $s['transport_visibility'] ?? [];
        $closing = $s['closing'] ?? [];
        $countySnap = $s['county_snapshot'] ?? [];
        $hwItems = $highways['items'] ?? [];
    @endphp

    @php($coverageHeroImg = trim((string) ($hero['image'] ?? '')))
    @php($coverageHeroBg = $coverageHeroImg !== '' ? $coverageHeroImg : asset('profile-gallery/profile-page-5.jpg'))
    <section class="relative isolate min-h-[38vh] overflow-hidden bg-[#1c0e0e] md:min-h-[40vh]">
        <div class="pointer-events-none absolute inset-0 z-0 bg-[#1c0e0e]" aria-hidden="true">
            <img src="{{ $coverageHeroBg }}" alt="" width="1920" height="1080" class="h-full min-h-[38vh] w-full object-cover object-[center_35%] opacity-60" loading="eager" fetchpriority="high" decoding="async">
        </div>
        <div class="pointer-events-none absolute inset-0 z-[1] bg-[linear-gradient(120deg,#5c1514cc_38%,transparent_72%)]"></div>
        <div class="em-container relative z-10 py-16 text-[#fef3e8] lg:py-[4.75rem]">
            <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#f04a2a]">{{ $hero['kicker'] ?? '' }}</p>
            <h1 class="mt-4 max-w-4xl text-[2rem] font-black leading-tight sm:text-4xl lg:text-[2.75rem]">{{ $hero['title'] ?? '' }}</h1>
            <p class="mt-6 max-w-3xl text-sm leading-relaxed text-[#e7d9cb] sm:text-[15px]">{{ $hero['description'] ?? '' }}</p>
            <div class="mt-10 flex flex-wrap gap-3">
                @php($pAnchor = trim((string) ($hero['primary_cta_anchor'] ?? '#coverage-map')))
                <a href="{{ $pAnchor !== '' ? $pAnchor : '#coverage-map' }}" class="em-btn-primary inline-flex min-h-[44px] items-center justify-center rounded-full px-6 text-sm font-black shadow-[0_12px_32px_-12px_rgba(92,21,20,0.65)]">{{ $hero['primary_cta_label'] ?? 'Open explorer' }}</a>
                @php($secCov = trim((string) ($hero['secondary_cta_url'] ?? '')))
                @if ($secCov !== '')
                    <a href="{{ str_starts_with($secCov, 'http') ? $secCov : url($secCov) }}" class="inline-flex min-h-[44px] items-center justify-center rounded-full border border-[#ffd9c8]/55 px-6 text-sm font-semibold text-white hover:bg-white/10">{{ $hero['secondary_cta_label'] ?? 'Next' }}</a>
                @endif
            </div>
        </div>
    </section>

    <section class="border-y border-[#ecdac8] bg-white py-12" id="inventory">
        <div class="em-container">
            <div class="max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $inv['label'] ?? 'Registers' }}</p>
                <h2 class="mt-4 text-[1.9rem] font-black text-[#5c1514]">{{ $inv['title'] ?? 'Totals derived from the live ledger' }}</h2>
                @if (($countySnap['description'] ?? '') !== '' || ($countySnap['title'] ?? '') !== '')
                    <p class="mt-3 text-sm font-semibold text-[#5c1514]">{{ $countySnap['title'] ?? '' }}</p>
                    <p class="mt-2 text-sm text-[#5a4a42]">{{ $countySnap['description'] ?? '' }}</p>
                @endif
            </div>
            <div class="mt-10 grid gap-4 md:grid-cols-4">
                <div class="rounded-[1.3rem] border border-[#e7d9cb] bg-gradient-to-br from-[#fef3e8] to-white px-5 py-5 shadow-[0_12px_30px_-24px_rgba(92,21,20,0.20)]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#7f6d62]">Counties serviced</p>
                    <p class="mt-2 text-4xl font-black text-[#5c1514]">{{ $countiesCovered }}</p>
                </div>
                <div class="rounded-[1.3rem] border border-[#e7d9cb] bg-gradient-to-br from-[#fef3e8] to-white px-5 py-5 shadow-[0_12px_30px_-24px_rgba(92,21,20,0.20)]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#7f6d62]">Treated poles (ledger)</p>
                    <p class="mt-2 text-4xl font-black text-[#5c1514]">{{ number_format($totalPoles) }}</p>
                </div>
                <div class="rounded-[1.3rem] border border-[#e7d9cb] bg-gradient-to-br from-[#fef3e8] to-white px-5 py-5 shadow-[0_12px_30px_-24px_rgba(92,21,20,0.20)]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#7f6d62]">Mapped anchors</p>
                    <p class="mt-2 text-4xl font-black text-[#5c1514]">{{ $keyTowns }}</p>
                </div>
                <div class="rounded-[1.3rem] border border-[#8b1e1a]/25 bg-[#5c1514] px-5 py-5 text-[#fef3e8] shadow-inner shadow-black/15">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#ffd9c8]">Need export?</p>
                    <p class="mt-2 text-lg font-semibold leading-snug">Download PDF coverage from document hub or cite this page in tenders.</p>
                    <a href="{{ route('portfolio') }}#documents" class="mt-6 inline-flex text-xs font-black uppercase tracking-wide text-[#f04a2a] underline underline-offset-4 hover:text-[#ffc4b3]">Supporting PDFs →</a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#faf6f1] py-14 lg:py-18" id="coverage-map-anchor">
        <div class="em-container">
            <div class="max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $mapIntro['label'] ?? '' }}</p>
                <h2 class="mt-4 text-[1.9rem] font-black text-[#5c1514]">{{ $mapIntro['title'] ?? 'Interactive GIS' }}</h2>
                <p class="mt-4 text-sm text-[#5a4a42]">{{ $mapIntro['description'] ?? '' }}</p>
            </div>
            <div class="mt-10 grid gap-6 lg:grid-cols-[1.35fr_minmax(0,0.9fr)]">
                <div class="min-w-0 overflow-hidden rounded-[1.5rem] border border-[#ecdac8] bg-white p-3 shadow-[0_28px_60px_-40px_rgba(92,21,20,0.35)]">
                    <div id="coverage-map" class="h-[min(92vw,400px)] min-h-[300px] w-full rounded-xl border border-[#e7d9cb] touch-manipulation md:h-[460px] lg:h-[520px]"></div>
                </div>
                <aside class="min-w-0 rounded-[1.5rem] border border-[#ecdac8] bg-white p-5 shadow-[0_22px_50px_-44px_rgba(92,21,20,0.38)] sm:p-6" id="coverage-insight-panel">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#8b1e1a]">County insight</p>
                    <h3 class="mt-3 text-[1.6rem] font-black text-[#5c1514]" data-county-name>{{ $insightCountyLabel }}</h3>
                    <p class="mt-3 text-sm text-[#5a4a42]">Town: <span class="font-bold text-[#8b1e1a]" data-town-name>{{ $focusTown }}</span></p>
                    <p class="mt-2 text-sm text-[#5a4a42]">Ledger poles (county rollup): <span class="font-bold text-[#8b1e1a]" data-county-poles>{{ $insightPoles }}</span></p>
                    <p class="mt-2 text-sm text-[#5a4a42]">Primary media cue: <span class="font-bold text-[#8b1e1a]" data-media-type>Street Light Ads</span></p>
                    <p class="mt-5 border-l-4 border-[#f04a2a] bg-[#fef3e8] p-4 text-sm leading-relaxed text-[#3d2a26]" data-county-suitability>Signals reflect transport-dominated commuter envelopes — adjust weighting inside the planner assistant.</p>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold" data-county-sites></div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('quote') }}" class="em-btn-primary rounded-full px-5 py-2.5 text-xs font-black" data-request-quote>Draft procurement note</a>
                        <a href="{{ route('smart-campaign-planner') }}" class="rounded-full border border-[#8b1e1a]/35 bg-white px-5 py-2.5 text-xs font-black text-[#5c1514] hover:bg-[#fef3e8]" data-plan-campaign>Planner assistant</a>
                        <button type="button" class="rounded-full border border-dashed border-[#c9b8a1] px-4 py-2 text-[11px] font-semibold uppercase tracking-wide text-[#7f6d62]" data-view-sites>Scroll to ledger</button>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="border-y border-[#ecdac8] bg-white py-14" id="county-cards">
        <div class="em-container">
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($countyGroups as $county => $rows)
                    <div id="county-card-{{ \Illuminate\Support\Str::slug($county) }}" class="rounded-2xl border border-[#e7d9cb] px-6 py-5 shadow-[0_14px_40px_-38px_rgba(92,21,20,0.35)]" data-county-card="{{ $county }}">
                        <h3 class="text-lg font-black text-[#5c1514]">{{ $county }}</h3>
                        <p class="mt-2 text-sm text-[#5a4a42]"><span class="font-bold text-[#8b1e1a]">{{ $rows->sum('poles') }}</span> ledger poles • {{ $rows->count() }} named sites</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($rows->pluck('site')->take(6) as $site)
                                <span class="rounded-full bg-[#fef3e8] px-3 py-1 text-[11px] font-semibold text-[#8b1e1a]">{{ $site }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <details class="mt-10 rounded-[1.5rem] border border-[#ecdac8] bg-[#fffdfb] px-5 py-4">
                <summary class="cursor-pointer text-sm font-black text-[#8b1e1a]">Full site ledger (tabulated)</summary>
                <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
                    <table class="min-w-full text-sm">
                        <thead class="border-b border-[#ecdac8]">
                            <tr class="text-left font-bold text-[#5c1514]">
                                <th class="py-3 pr-4">County</th><th class="py-3 pr-4">Site</th><th class="py-3">Poles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (($profileContent['reach'] ?? []) as $reach)
                                <tr class="border-b border-[#f5ebe3]">
                                    <td class="py-3 pr-4">{{ $reach['county'] }}</td>
                                    <td class="py-3 pr-4">{{ $reach['site'] }}</td>
                                    <td class="py-3 font-bold text-[#8b1e1a]">{{ $reach['poles'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
        </div>
    </section>

    @if (count($hwItems))
        <section class="bg-[#5c1514] py-14 text-[#fef3e8]">
            <div class="em-container grid gap-10 lg:grid-cols-[0.92fr_minmax(0,1.1fr)]">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#f04a2a]">{{ $highways['label'] ?? '' }}</p>
                    <h2 class="mt-5 text-[1.9rem] font-black text-white">{{ $highways['title'] ?? '' }}</h2>
                </div>
                <ul class="divide-y divide-white/15">
                    @foreach ($hwItems as $row)
                        <li class="flex flex-col gap-2 py-5 sm:flex-row sm:items-start sm:justify-between">
                            <p class="text-lg font-black text-[#ffd9c8]">{{ $row['corridor'] ?? '' }}</p>
                            <p class="max-w-xl text-sm text-[#e7d9cb]">{{ $row['note'] ?? '' }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <section class="bg-[#fef3e8]/80 py-14 lg:py-18">
        <div class="em-container grid gap-10 lg:grid-cols-[1.05fr_minmax(0,0.8fr)]">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#8b1e1a]">{{ $transport['label'] ?? '' }}</p>
                <h2 class="mt-5 text-[1.9rem] font-black text-[#5c1514]">{{ $transport['title'] ?? '' }}</h2>
                <p class="mt-6 text-sm leading-relaxed text-[#5a4a42]">{{ $transport['body'] ?? '' }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-[#ecdac8] bg-white p-7 shadow-[0_28px_60px_-40px_rgba(92,21,20,0.30)]">
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#8b1e1a]">{{ $closing['card_kicker'] ?? '' }}</p>
                <p class="mt-4 text-lg font-black text-[#5c1514]">{{ $closing['title'] ?? '' }}</p>
                @if (($closing['description'] ?? '') !== '')
                    <p class="mt-3 text-sm text-[#5a4a42]">{{ $closing['description'] }}</p>
                @endif
                @php($tCta = $transport['cta_url'] ?? '/smart-campaign-planner')
                <a href="{{ str_starts_with((string) $tCta, 'http') ? $tCta : url($tCta) }}" class="mt-4 inline-flex w-full min-h-[44px] items-center justify-center rounded-xl border border-[#c9b8a1] px-4 text-xs font-bold text-[#5c1514] hover:bg-[#fef3e8]">{{ $transport['cta_label'] ?? 'Open assistant' }}</a>
                @php($cq = trim((string) ($closing['primary_url'] ?? '/quote')))
                <a href="{{ str_starts_with($cq, 'http') ? $cq : url($cq) }}" class="em-btn-primary mt-4 inline-flex min-h-[48px] w-full items-center justify-center rounded-2xl px-6 text-sm font-black">{{ $closing['primary_label'] ?? 'Continue' }}</a>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mapEl = document.getElementById('coverage-map');
            if (mapEl && typeof window.emLoadLeaflet === 'function') {
                const points = @json($profileContent['coverage_map_points'] ?? []);
                const reachRows = @json($profileContent['reach'] ?? []);

                const normCountyKey = (name) =>
                    String(name ?? '')
                        .trim()
                        .toLowerCase()
                        .replace(/\s+county$/i, '')
                        .trim();

                const countyNameEl = document.querySelector('[data-county-name]');
                const countyPolesEl = document.querySelector('[data-county-poles]');
                const countySitesEl = document.querySelector('[data-county-sites]');
                const countySuitabilityEl = document.querySelector('[data-county-suitability]');
                const townNameEl = document.querySelector('[data-town-name]');
                const mediaTypeEl = document.querySelector('[data-media-type]');
                const requestQuoteBtn = document.querySelector('[data-request-quote]');
                const planCampaignBtn = document.querySelector('[data-plan-campaign]');
                const viewSitesBtn = document.querySelector('[data-view-sites]');

                const initCoverageMap = () => {
                    const map = L.map(mapEl).setView([-0.7, 37.2], 6);
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    }).addTo(map);

                    const groupedByNorm = {};
                    reachRows.forEach((row) => {
                        const k = normCountyKey(row.county);
                        if (!groupedByNorm[k]) groupedByNorm[k] = { poles: 0, sites: [] };
                        groupedByNorm[k].poles += Number(row.poles || 0);
                        if (row.site) groupedByNorm[k].sites.push(row.site);
                    });

                    let maxCounty = 1;
                    Object.values(groupedByNorm).forEach((row) => {
                        maxCounty = Math.max(maxCounty, row.poles || 1);
                    });
                    if (typeof L.heatLayer === 'function') {
                        const heatPoints = [];
                        points.forEach((point) => {
                            const w = ((groupedByNorm[normCountyKey(point.county)]?.poles ?? 120) / maxCounty) * 0.75 + 0.12;
                            heatPoints.push([point.lat, point.lng, w]);
                        });
                        L.heatLayer(heatPoints, {
                            radius: 34,
                            blur: 20,
                            maxZoom: 12,
                            gradient: { 0.25: '#fef3e8', 0.55: '#f04a2a', 0.9: '#5c1514' },
                        }).addTo(map);
                    }

                    const mediaColorMap = {
                        'Street Light Ads': '#8b1e1a',
                        'Pavement Ads': '#f04a2a',
                        'Billboards': '#78716c',
                    };

                    const createIcon = (mediaType) =>
                        L.divIcon({
                            className: 'em-map-marker',
                            html: `<div style="width:16px;height:16px;border-radius:999px;background:${mediaColorMap[mediaType] || '#8b1e1a'};border:2px solid #FFFFFF;box-shadow:0 2px 8px rgba(139,30,26,.42);"></div>`,
                            iconSize: [16, 16],
                            iconAnchor: [8, 8],
                        });

                    const clusterGroup = L.markerClusterGroup({
                        showCoverageOnHover: false,
                        maxClusterRadius: 45,
                        iconCreateFunction: (cluster) => {
                            const count = cluster.getChildCount();
                            return L.divIcon({
                                html: `<div style="background:#5c1514;color:#fef3e8;border:2px solid #f04a2a;border-radius:999px;width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">${count}</div>`,
                                className: 'em-cluster-icon',
                                iconSize: [36, 36],
                            });
                        },
                    });

                    const updatePanel = (point) => {
                        const key = normCountyKey(point.county);
                        const countyData = groupedByNorm[key] || { poles: 0, sites: [point.town] };
                        countyNameEl.textContent = point.county;
                        townNameEl.textContent = point.town;
                        countyPolesEl.textContent = countyData.poles;
                        mediaTypeEl.textContent = point.media_type;
                        countySuitabilityEl.textContent =
                            'Transport-heavy corridors benefit from repeated faces; pedestrian cores favour pavement adjacency — adjust inside the planner assistant.';
                        countySitesEl.innerHTML = countyData.sites
                            .slice(0, 8)
                            .map((site) => `<span class="rounded-full bg-[#fef3e8] px-3 py-1 text-[11px] font-semibold text-[#8b1e1a]">${site}</span>`)
                            .join('');
                        requestQuoteBtn.href = `{{ route('quote') }}?location=${encodeURIComponent(point.town)}&county=${encodeURIComponent(point.county)}&media_type=${encodeURIComponent(point.media_type)}`;
                        planCampaignBtn.href = `{{ route('smart-campaign-planner') }}?location=${encodeURIComponent(point.town)}&county=${encodeURIComponent(point.county)}`;
                        viewSitesBtn.dataset.countyTarget = point.county;
                        if (window.emTrackEvent) {
                            window.emTrackEvent('map_location_clicked', { town: point.town, county: point.county, media_type: point.media_type });
                        }
                    };

                    const bounds = [];
                    points.forEach((point) => {
                        const countyData = groupedByNorm[normCountyKey(point.county)] || { poles: 0 };
                        const marker = L.marker([point.lat, point.lng], { icon: createIcon(point.media_type) });
                        marker.bindPopup(
                            `<div style="font-family:system-ui;max-width:min(280px, calc(100vw - 48px));">
                                <p style="font-weight:800;color:#8b1e1a;margin:0 0 6px">${point.town}</p>
                                <p style="margin:0 0 4px;font-size:12px;color:#5a4a42"><strong>County:</strong> ${point.county}</p>
                                <p style="margin:0 0 4px;font-size:12px;color:#5a4a42"><strong>Poles:</strong> ${countyData.poles || 'n/a'}</p>
                                <p style="margin:0 0 8px;font-size:12px;color:#5a4a42"><strong>Media:</strong> ${point.media_type}</p>
                                <a href="{{ route('quote') }}?location=${encodeURIComponent(point.town)}&county=${encodeURIComponent(point.county)}&media_type=${encodeURIComponent(point.media_type)}" style="display:inline-block;background:#8b1e1a;color:#fff;padding:6px 10px;border-radius:999px;font-size:12px;text-decoration:none;font-weight:700">Notify planning desk</a>
                            </div>`,
                            { maxWidth: 320, className: 'em-leaflet-popup-tight' },
                        );
                        marker.on('click', () => updatePanel(point));
                        clusterGroup.addLayer(marker);
                        bounds.push([point.lat, point.lng]);
                    });
                    map.addLayer(clusterGroup);
                    if (bounds.length) map.fitBounds(bounds, { padding: [24, 24] });
                    if (points.length) updatePanel(points[0]);

                    const legend = L.control({ position: 'bottomleft' });
                    legend.onAdd = function () {
                        const div = L.DomUtil.create('div');
                        div.style.background = '#fff';
                        div.style.padding = '10px 12px';
                        div.style.borderRadius = '12px';
                        div.style.boxShadow = '0 10px 30px -18px rgba(15,23,42,.55)';
                        div.style.fontSize = '12px';
                        div.innerHTML = `
                            <div style="font-weight:800;color:#8b1e1a;margin-bottom:6px;">Media legend</div>
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;color:#5c1514"><span style="width:10px;height:10px;border-radius:999px;background:#8b1e1a;display:inline-block;"></span> Street lights</div>
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;color:#5c1514"><span style="width:10px;height:10px;border-radius:999px;background:#f04a2a;display:inline-block;"></span> Pavement</div>
                            <div style="display:flex;align-items:center;gap:6px;color:#5c1514"><span style="width:10px;height:10px;border-radius:999px;background:#78716c;display:inline-block;"></span> Billboards</div>
                        `;
                        return div;
                    };
                    legend.addTo(map);

                    viewSitesBtn?.addEventListener('click', () => {
                        const targetCounty = viewSitesBtn.dataset.countyTarget;
                        const countyCard = document.querySelector(`[data-county-card="${targetCounty}"]`);
                        countyCard?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                };

                const startMapWhenVisible = () => {
                    window
                        .emLoadLeaflet({ withMarkerCluster: true, withHeat: true })
                        .then(() => requestAnimationFrame(initCoverageMap))
                        .catch((err) => console.warn('Coverage map failed to load', err));
                };

                if ('IntersectionObserver' in window) {
                    const io = new IntersectionObserver(
                        (entries) => {
                            entries.forEach((entry) => {
                                if (!entry.isIntersecting) return;
                                io.disconnect();
                                startMapWhenVisible();
                            });
                        },
                        { rootMargin: '240px 0px', threshold: 0.02 }
                    );
                    io.observe(mapEl);
                } else {
                    startMapWhenVisible();
                }
            }
        });
    </script>
@endsection
