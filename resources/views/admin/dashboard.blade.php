@extends('layouts.admin')

@section('title', 'Operations Command Center | Effective Media')
@section('header', 'Command center')

@section('content')
    @include('components.em-leaflet-loader-inline')
    @php
        $kpiMeta = [
            'active_campaigns' => ['icon' => 'campaign', 'tone' => 'from-[#5c1514] to-[#8b1e1a]'],
            'counties' => ['icon' => 'map', 'tone' => 'from-[#a8977a] to-[#5c1514]'],
            'total_boards' => ['icon' => 'boards', 'tone' => 'from-[#f04a2a] to-[#5c1514]'],
            'monthly_leads' => ['icon' => 'leads', 'tone' => 'from-[#8b1e1a] to-[#f04a2a]'],
            'pending_quotes' => ['icon' => 'quotes', 'tone' => 'from-[#5c1514] to-[#a8977a]'],
            'profile_downloads' => ['icon' => 'download', 'tone' => 'from-[#241312] to-[#5c1514]'],
            'website_visitors' => ['icon' => 'eye', 'tone' => 'from-[#f04a2a] to-[#a8977a]'],
        ];
    @endphp

    <div class="space-y-8">
        {{-- KPI row --}}
        <section>
            <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-sm font-black uppercase tracking-[0.2em] text-[#8b1e1a] dark:text-[#f7b396]">Live operations</h2>
                    <p class="mt-1 max-w-2xl text-sm text-[#5c4a45] dark:text-[#c4bbb4]">KPIs refreshed from your Laravel data model, coverage config, and inbound lead capture.</p>
                </div>
                <span class="rounded-full border border-[#ead8c9] bg-white/70 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-[#5c1514] dark:border-white/10 dark:bg-white/10 dark:text-[#f4e3c4]">Today · {{ now()->format('M j') }}</span>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-7">
                @foreach ($kpis as $slug => $kpi)
                    @php $meta = $kpiMeta[$slug] ?? $kpiMeta['active_campaigns']; @endphp
                    <div class="admin-glass-card admin-glass-card-hover group relative overflow-hidden p-4">
                        <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br {{ $meta['tone'] }} opacity-[0.12] blur-2xl transition group-hover:opacity-25 dark:opacity-[0.18]"></div>
                        <div class="relative flex items-start justify-between gap-2">
                            <div class="inline-flex rounded-xl bg-gradient-to-br {{ $meta['tone'] }} p-2.5 text-white shadow-lg shadow-[#8b1e1a]/25">
                                @include('admin.dashboard.partials.kpi-icon', ['icon' => $meta['icon']])
                            </div>
                            @if(isset($kpi['trend']) && $kpi['trend'] !== null)
                                <span class="rounded-full px-2 py-0.5 text-[11px] font-bold {{ $kpi['trend'] >= 0 ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300' }}">
                                    {{ $kpi['trend'] >= 0 ? '↑' : '↓' }} {{ abs($kpi['trend']) }}%
                                </span>
                            @else
                                <span class="text-[11px] font-semibold text-[#a8977a]">—</span>
                            @endif
                        </div>
                        <p class="relative mt-3 text-[11px] font-bold uppercase tracking-wider text-[#a8977a]">{{ $kpi['label'] }}</p>
                        <p class="relative mt-1 text-3xl font-black tracking-tight text-[#241312] dark:text-white">
                            <span data-admin-counter data-target="{{ $kpi['value'] }}">0</span>
                            @if ($slug === 'counties')
                                <span class="text-xl font-black text-[#8b1e1a] dark:text-[#f7b396]">+</span>
                            @endif
                        </p>
                        <p class="relative mt-1 text-[11px] text-[#7a665e] dark:text-[#9e918b]">{{ $kpi['sub'] }}</p>
                        <div class="relative mt-3 hidden justify-end opacity-90 sm:flex">
                            <x-admin.sparkline class="opacity-95" :values="$kpi['spark'] ?? []"/>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="grid gap-8 xl:grid-cols-12">
            {{-- Coverage map --}}
            <section class="admin-glass-card xl:col-span-7 xl:overflow-hidden">
                <div class="flex flex-wrap items-start justify-between gap-3 border-b border-[#f0e4da] p-5 dark:border-white/10">
                    <div>
                        <h3 class="text-base font-black text-[#2a1716] dark:text-white">Kenya coverage intelligence</h3>
                        <p class="mt-1 text-xs text-[#7a665e] dark:text-[#bcb3ae]">Active towns from profile map points · bubble size scales with county poles.</p>
                    </div>
                    <span class="rounded-full bg-[#fdf6ef] px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-[#8b1e1a] dark:bg-white/10 dark:text-[#f7b396]">{{ $mapPoints->count() }} sites mapped</span>
                </div>
                <div class="relative h-[320px] w-full md:h-[380px]" id="admin-coverage-map" data-map-points='@json($mapPoints)' data-reach-counties='@json($reachByCounty)'></div>
                <div class="grid gap-3 border-t border-[#f0e4da] p-4 sm:grid-cols-3 dark:border-white/10">
                    @foreach ($reachByCounty->take(3) as $county => $data)
                        <div class="rounded-xl border border-[#f0e4da] bg-[#fffaf7] px-3 py-2 dark:border-white/10 dark:bg-white/5">
                            <p class="text-xs font-bold text-[#5c1514] dark:text-[#f7b396]">{{ $county }}</p>
                            <p class="text-lg font-black text-[#241312] dark:text-white">{{ $data['poles'] }}+</p>
                            <p class="text-[10px] text-[#7a665e]">{{ $data['sites'] }} sites</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Recent leads --}}
            <section class="admin-glass-card flex flex-col xl:col-span-5">
                <div class="border-b border-[#f0e4da] p-5 dark:border-white/10">
                    <h3 class="text-base font-black text-[#2a1716] dark:text-white">Recent inbound leads</h3>
                    <p class="mt-1 text-xs text-[#7a665e] dark:text-[#bcb3ae]">Latest quote inquiries with inferred service intent.</p>
                </div>
                <div class="max-h-[360px] flex-1 overflow-x-auto overflow-y-auto overscroll-contain [-webkit-overflow-scrolling:touch]">
                    <table class="min-w-[22rem] w-full text-left text-sm">
                        <thead class="sticky top-0 bg-[#fffaf7]/95 text-[11px] font-bold uppercase tracking-wider text-[#8b1e1a] backdrop-blur dark:bg-[#161212]/95 dark:text-[#f7b396]">
                            <tr>
                                <th class="px-4 py-2">Company</th>
                                <th class="px-4 py-2">Location</th>
                                <th class="hidden sm:table-cell px-4 py-2">Service</th>
                                <th class="px-4 py-2 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#f3e9e1] dark:divide-white/5">
                            @forelse ($recentLeads as $lead)
                                <tr class="transition hover:bg-[#fdf8f5] dark:hover:bg-white/5">
                                    <td class="px-4 py-2.5">
                                        <a href="{{ route('admin.quotes.show', $lead) }}" class="font-semibold text-[#241312] hover:text-[#8b1e1a] dark:text-white">{{ $lead->company_name ?: $lead->full_name }}</a>
                                    </td>
                                    <td class="px-4 py-2.5 text-[#60534d] dark:text-[#c9bfb7]">{{ $lead->county ?: $lead->location ?: '—' }}</td>
                                    <td class="hidden sm:table-cell px-4 py-2.5 text-[#60534d] dark:text-[#c9bfb7]">{{ $lead->media_type ?: 'Outdoor' }}</td>
                                    <td class="px-4 py-2.5 text-right"><span class="rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-bold text-amber-900 dark:bg-amber-500/25 dark:text-amber-100">New</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-[#7a665e]">No quote leads yet · promote the public quote funnel.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @can('quotes.manage')
                    <div class="border-t border-[#f0e4da] p-3 dark:border-white/10">
                        <a href="{{ route('admin.quotes.index') }}" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#5c1514] to-[#f04a2a] py-2.5 text-xs font-black uppercase tracking-wide text-white shadow-lg shadow-[#8b1e1a]/30 transition hover:brightness-110">Open full pipeline</a>
                    </div>
                @endcan
            </section>
        </div>

        {{-- Row 3: uploads, kanban, approvals --}}
        <div class="grid gap-8 lg:grid-cols-3">
            <section class="admin-glass-card p-5">
                <h3 class="text-base font-black text-[#2a1716] dark:text-white">Recent campaign uploads</h3>
                <p class="mt-1 text-xs text-[#7a665e] dark:text-[#bcb3ae]">Latest portfolio motions for review.</p>
                <ul class="mt-4 space-y-3">
                    @forelse ($recentPortfolio as $item)
                        <li class="flex items-center justify-between gap-3 rounded-xl border border-[#f0e4da] bg-[#fffaf8] px-3 py-2.5 transition hover:border-[#f04a2a]/40 dark:border-white/10 dark:bg-white/5">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-[#241312] dark:text-white">{{ $item->title }}</p>
                                <p class="truncate text-[11px] text-[#7a665e]">{{ $item->client_name ?: 'Client TBD' }} · {{ optional($item->updated_at)?->diffForHumans() }}</p>
                            </div>
                            @can('cms.portfolio.manage')
                                <a href="{{ route('admin.cms.portfolio.edit', $item) }}" class="shrink-0 rounded-lg border border-[#ead8c9] px-2 py-1 text-[11px] font-bold uppercase tracking-wide text-[#8b1e1a] hover:bg-[#fff5f5] dark:border-white/10 dark:text-[#f7b396]">Edit</a>
                            @endcan
                        </li>
                    @empty
                        <li class="rounded-xl border border-dashed border-[#e5d6c9] px-4 py-6 text-center text-sm text-[#7a665e] dark:border-white/10">Publish campaign visuals from Portfolio CMS.</li>
                    @endforelse
                </ul>
            </section>

            <section class="admin-glass-card overflow-hidden p-5">
                <h3 class="text-base font-black text-[#2a1716] dark:text-white">Quote pipeline</h3>
                <p class="mt-1 text-xs text-[#7a665e] dark:text-[#bcb3ae]">Auto-segmented by recency · upgrade to weighted scoring anytime.</p>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    @foreach ([
                        ['key' => 'hot', 'label' => 'Hot (72h)', 'tone' => 'border-rose-200 bg-rose-50/70 dark:bg-rose-500/10 dark:border-rose-500/30'],
                        ['key' => 'follow_up', 'label' => 'Follow-up', 'tone' => 'border-amber-200 bg-amber-50/70 dark:bg-amber-500/10 dark:border-amber-500/25'],
                        ['key' => 'nurture', 'label' => 'Nurture', 'tone' => 'border-sky-200 bg-sky-50/70 dark:bg-sky-500/10 dark:border-sky-500/25'],
                    ] as $col)
                        @php $items = $quotePipeline[$col['key']] ?? collect(); @endphp
                        <div class="{{ $col['tone'] }} flex max-h-[280px] flex-col rounded-2xl border p-3">
                            <div class="mb-2 flex items-center justify-between">
                                <p class="text-[11px] font-black uppercase tracking-wide text-[#5c1514] dark:text-[#f7b396]">{{ $col['label'] }}</p>
                                <span class="rounded-full bg-white/70 px-1.5 text-[11px] font-bold text-[#241312] dark:bg-black/20 dark:text-white">{{ $items->count() }}</span>
                            </div>
                            <div class="flex flex-1 flex-col gap-2 overflow-auto pr-1">
                                @forelse ($items as $q)
                                    <a href="{{ route('admin.quotes.show', $q) }}" class="rounded-xl border border-white/70 bg-white/80 px-2.5 py-2 text-[11px] font-semibold text-[#352521] shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-white/5 dark:bg-[#231f1f] dark:text-[#efe8e3]">
                                        <span class="block truncate">{{ $q->company_name ?: $q->full_name }}</span>
                                        <span class="block truncate text-[10px] font-normal opacity-75">{{ $q->budget_range ?: 'Budget TBD' }}</span>
                                    </a>
                                @empty
                                    <p class="text-center text-[11px] text-[#886d66] opacity-75">Quiet stage</p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-glass-card p-5">
                <h3 class="text-base font-black text-[#2a1716] dark:text-white">Pending approvals</h3>
                <p class="mt-1 text-xs text-[#7a665e] dark:text-[#bcb3ae]">Portfolio drafts gated before public launch.</p>
                <ul class="mt-4 space-y-2">
                    @forelse ($pendingApprovals as $draft)
                        <li class="flex items-start justify-between gap-2 rounded-xl border border-[#fce4ec] bg-gradient-to-br from-[#fff5f8] to-white px-3 py-2 dark:border-white/10 dark:from-[#2a1f21] dark:to-transparent">
                            <div>
                                <p class="text-sm font-bold text-[#241312] dark:text-white">{{ $draft->title }}</p>
                                <p class="text-[11px] text-[#7a665e]">Awaiting QA · {{ optional($draft->updated_at)?->diffForHumans() }}</p>
                            </div>
                            @can('cms.portfolio.manage')
                                <a href="{{ route('admin.cms.portfolio.edit', $draft) }}" class="shrink-0 text-[11px] font-bold uppercase text-[#f04a2a]">Review</a>
                            @endcan
                        </li>
                    @empty
                        <li class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50/70 px-4 py-5 text-center text-sm text-emerald-900 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-50">Nice · no blocking drafts.</li>
                    @endforelse
                </ul>
            </section>
        </div>

        {{-- Analytics charts --}}
        <section id="admin-dashboard-charts" class="admin-glass-card p-5">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h3 class="text-base font-black text-[#2a1716] dark:text-white">Website analytics & capture</h3>
                    <p class="mt-1 text-xs text-[#7a665e] dark:text-[#bcb3ae]">Rolling 7-day pulse · connect GA / Meta offline conversions when ready.</p>
                </div>
            </div>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-[#f0e4da] bg-[#fffdf9] p-4 dark:border-white/10 dark:bg-white/5">
                    <p class="text-[11px] font-black uppercase tracking-wider text-[#8b1e1a] dark:text-[#f7b396]">Profile downloads</p>
                    <div class="h-56 pt-4"><canvas id="chart-downloads" height="200"></canvas></div>
                </div>
                <div class="rounded-2xl border border-[#f0e4da] bg-[#fffdf9] p-4 dark:border-white/10 dark:bg-white/5">
                    <p class="text-[11px] font-black uppercase tracking-wider text-[#8b1e1a] dark:text-[#f7b396]">Estimated traffic pulse</p>
                    <div class="h-56 pt-4"><canvas id="chart-traffic" height="200"></canvas></div>
                </div>
                <div class="rounded-2xl border border-[#f0e4da] bg-[#fffdf9] p-4 dark:border-white/10 dark:bg-white/5">
                    <p class="text-[11px] font-black uppercase tracking-wider text-[#8b1e1a] dark:text-[#f7b396]">Quote requests</p>
                    <div class="h-56 pt-4"><canvas id="chart-quotes" height="200"></canvas></div>
                </div>
                <div class="rounded-2xl border border-[#f0e4da] bg-[#fffdf9] p-4 dark:border-white/10 dark:bg-white/5">
                    <p class="text-[11px] font-black uppercase tracking-wider text-[#8b1e1a] dark:text-[#f7b396]">Quote funnel taps (CTA est.)</p>
                    <div class="h-56 pt-4"><canvas id="chart-cta" height="200"></canvas></div>
                </div>
            </div>
        </section>

        <div class="grid gap-8 xl:grid-cols-12">
            <section class="space-y-4 xl:col-span-8">
                <div class="mb-2 flex items-center justify-between gap-3">
                    <h3 class="text-base font-black text-[#2a1716] dark:text-white">CMS modules</h3>
                    <span class="text-[11px] font-semibold uppercase tracking-wide text-[#a8977a]">Hydrated from database</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    @forelse ($cmsModules as $module)
                        <div class="admin-glass-card admin-glass-card-hover flex flex-col p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-black text-[#241312] dark:text-white">{{ $module['label'] }}</p>
                                    <p class="mt-1 text-xs text-[#7a665e] dark:text-[#bcb3ae]">{{ $module['description'] }}</p>
                                </div>
                                <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-emerald-900 dark:border-emerald-500/40 dark:bg-emerald-500/15 dark:text-emerald-100">{{ $module['status'] }}</span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3 text-[11px]">
                                @if(isset($module['count']))
                                    <div class="rounded-xl bg-[#f9f5f2] px-3 py-2 dark:bg-white/5">
                                        <span class="text-[#a8977a]">Records</span>
                                        <p class="text-lg font-black text-[#241312] dark:text-white">{{ $module['count'] }}</p>
                                    </div>
                                @endif
                                @if(isset($module['active']))
                                    <div class="rounded-xl bg-[#f9f5f2] px-3 py-2 dark:bg-white/5">
                                        <span class="text-[#a8977a]">Active</span>
                                        <p class="text-lg font-black text-[#241312] dark:text-white">{{ $module['active'] }}</p>
                                    </div>
                                @endif
                                <div class="col-span-2 rounded-xl bg-[#f9f5f2] px-3 py-2 dark:bg-white/5">
                                    <span class="text-[#a8977a]">Last updated</span>
                                    <p class="font-semibold text-[#241312] dark:text-white">{{ $module['updated'] ? \Carbon\Carbon::parse($module['updated'])->diffForHumans() : '—' }}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @can($module['permission'])
                                    @if(($module['route'] ?? null) !== null)
                                        <a href="{{ route($module['route']) }}" class="flex-1 rounded-xl border border-[#ead8c9] px-3 py-2 text-center text-xs font-bold uppercase tracking-wide text-[#8b1e1a] transition hover:bg-[#fff8f5] dark:border-white/10 dark:text-[#f7b396] dark:hover:bg-white/5">Open</a>
                                    @endif
                                @endcan
                                @if (($module['key'] ?? '') === 'portfolio')
                                    @can('cms.portfolio.manage')
                                        <a href="{{ route('admin.cms.portfolio.create') }}" class="flex-1 rounded-xl bg-gradient-to-r from-[#5c1514] to-[#f04a2a] px-3 py-2 text-center text-xs font-black uppercase tracking-wide text-white shadow-md transition hover:brightness-110">Quick add</a>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="admin-glass-card p-8 text-center text-sm text-[#7a665e]">No CMS modules available for your role.</div>
                    @endforelse
                </div>
            </section>

            <section class="admin-glass-card p-5 xl:col-span-4">
                <h3 class="text-base font-black text-[#2a1716] dark:text-white">Activity stream</h3>
                <p class="mt-1 text-xs text-[#7a665e] dark:text-[#bcb3ae]">Quotes, downloads, and portfolio edits.</p>
                <ul class="mt-5 max-h-[640px] space-y-4 overflow-auto pr-1">
                    @forelse ($activities as $act)
                        <li class="relative border-l border-[#e8dacf] pb-4 pl-4 dark:border-white/10">
                            <span class="absolute -left-[5px] top-1.5 h-2 w-2 rounded-full bg-[#f04a2a] ring-4 ring-[#fcf9f7] dark:ring-[#1c1818]"></span>
                            <p class="text-[11px] font-black uppercase tracking-wide text-[#8b1e1a] dark:text-[#f7b396]">{{ $act->label }}</p>
                            <p class="mt-1 text-sm font-semibold text-[#241312] dark:text-white">{{ $act->detail }}</p>
                            <p class="mt-0.5 text-[11px] text-[#a8977a]">{{ optional($act->at)?->diffForHumans() }}</p>
                        </li>
                    @empty
                        <li class="text-center text-sm text-[#7a665e]">Quiet feed · activity will populate as inbound grows.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-admin-counter]').forEach((el) => {
                    const target = Number(el.dataset.target || 0);
                    const dur = 900;
                    const start = performance.now();
                    const step = (t) => {
                        const p = Math.min(1, (t - start) / dur);
                        el.textContent = Math.round(target * p).toLocaleString();
                        if (p < 1) requestAnimationFrame(step);
                    };
                    requestAnimationFrame(step);
                });

                const runWhenVisible = (el, run) => {
                    if (!el || typeof run !== 'function') return;
                    if (!('IntersectionObserver' in window)) {
                        run();
                        return;
                    }
                    const io = new IntersectionObserver(
                        (entries) => {
                            entries.forEach((entry) => {
                                if (!entry.isIntersecting) return;
                                io.disconnect();
                                run();
                            });
                        },
                        { rootMargin: '160px 0px', threshold: 0.02 },
                    );
                    io.observe(el);
                };

                const initAdminMap = () => {
                    try {
                        const mapEl = document.getElementById('admin-coverage-map');
                        const pointsRaw = mapEl?.dataset.mapPoints;
                        const reachRaw = mapEl?.dataset.reachCounties;
                        window.__emAdminMap = null;

                        if (!mapEl || !pointsRaw || typeof L === 'undefined') return;

                        const points = JSON.parse(pointsRaw);
                        const reach = reachRaw ? JSON.parse(reachRaw) : {};

                        const radiusFromCounty = (county) => {
                            const n = Number(reach[county]?.poles || 0);
                            const scaled = Math.sqrt(n) / 2.6;
                            return Math.min(Math.max(scaled + 6, 8), 30);
                        };

                        const map = L.map(mapEl).setView([-0.35, 38], 6);
                        window.__emAdminMap = map;
                        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors',
                        }).addTo(map);

                        const bounds = [];
                        points.forEach((p) => {
                            const marker = L.circleMarker([p.lat, p.lng], {
                                radius: radiusFromCounty(p.county) / 5 + 6,
                                color: '#8B1E1A',
                                weight: 2,
                                fillColor: '#F04A2A',
                                fillOpacity: 0.82,
                            });
                            marker.bindPopup(`<strong>${p.town}</strong><br/>${p.county}<br/>${p.media_type}`);
                            marker.addTo(map);
                            bounds.push([p.lat, p.lng]);
                        });
                        if (bounds.length > 1) {
                            map.fitBounds(bounds, { padding: [14, 14] });
                        }
                        window.addEventListener('resize', () => map.invalidateSize());
                    } catch (e) {
                        console.warn('Admin coverage map', e);
                    }
                };

                const initAdminCharts = () => {
                    try {
                        const labels = @json($chartLabels);
                        const scales = () => {
                            const dark = document.documentElement.classList.contains('dark');
                            const axis = dark ? '#d7cdc6' : '#5c4740';
                            const grid = dark ? 'rgba(255,255,255,.08)' : 'rgba(44,34,31,.06)';

                            return {
                                x: { ticks: { color: axis, font: { size: 10 } }, grid: { color: grid } },
                                y: { ticks: { color: axis, font: { size: 10 } }, grid: { color: grid }, beginAtZero: true },
                            };
                        };

                        const chartOptions = () => ({
                            plugins: { legend: { display: false } },
                            scales: scales(),
                        });

                        const mk = (id, cfg) => {
                            const canvas = document.getElementById(id);
                            return canvas ? new Chart(canvas, { ...cfg, options: chartOptions() }) : null;
                        };

                        mk('chart-downloads', {
                            type: 'line',
                            data: {
                                labels,
                                datasets: [{
                                    label: 'Downloads',
                                    data: @json($chartSeries['downloads']),
                                    borderColor: '#f04a2a',
                                    backgroundColor: 'rgba(240,74,42,0.12)',
                                    fill: true,
                                    tension: 0.42,
                                    borderWidth: 2,
                                }],
                            },
                        });

                        mk('chart-traffic', {
                            type: 'bar',
                            data: {
                                labels,
                                datasets: [{
                                    label: 'Traffic pulse',
                                    data: @json($chartSeries['traffic']),
                                    borderRadius: 8,
                                    backgroundColor: '#a8977a',
                                }],
                            },
                        });

                        mk('chart-quotes', {
                            type: 'line',
                            data: {
                                labels,
                                datasets: [{
                                    label: 'Quotes',
                                    data: @json($chartSeries['quotes']),
                                    borderColor: '#8b1e1a',
                                    backgroundColor: 'rgba(139,30,26,0.12)',
                                    fill: true,
                                    tension: 0.42,
                                    borderWidth: 2,
                                }],
                            },
                        });

                        mk('chart-cta', {
                            type: 'line',
                            data: {
                                labels,
                                datasets: [{
                                    label: 'CTA taps',
                                    data: @json($chartSeries['cta']),
                                    borderColor: '#5c1514',
                                    backgroundColor: 'rgba(92,21,20,0.12)',
                                    fill: true,
                                    tension: 0.42,
                                    borderWidth: 2,
                                }],
                            },
                        });

                        document.querySelectorAll('[data-admin-theme-toggle]').forEach((btn) => {
                            btn.addEventListener('click', () => setTimeout(() => {
                                document.querySelectorAll('canvas[id^=\"chart-\"]').forEach((canvas) => {
                                    const chart = Chart.getChart(canvas);
                                    if (chart) {
                                        chart.options.scales = scales();
                                        chart.update();
                                    }
                                });
                                window.__emAdminMap?.invalidateSize?.();
                            }, 100));
                        });
                    } catch (e) {
                        console.warn('Admin charts', e);
                    }
                };

                runWhenVisible(document.getElementById('admin-coverage-map'), () => {
                    if (typeof window.emLoadLeaflet !== 'function') return;
                    window
                        .emLoadLeaflet({ withMarkerCluster: false })
                        .then(() => requestAnimationFrame(initAdminMap))
                        .catch((err) => console.warn('Leaflet load failed', err));
                });

                runWhenVisible(document.getElementById('admin-dashboard-charts'), () => {
                    if (typeof Chart !== 'undefined') {
                        requestAnimationFrame(initAdminCharts);
                        return;
                    }
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
                    script.crossOrigin = '';
                    script.onload = () => requestAnimationFrame(initAdminCharts);
                    script.onerror = () => console.warn('Chart.js failed to load');
                    document.body.appendChild(script);
                });
            });
        </script>
    @endpush
@endsection
