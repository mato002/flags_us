@php
    $route = request()->route()?->getName() ?? '';
    $openCms = str_starts_with($route, 'admin.cms');
    $openMedia = str_starts_with($route, 'admin.media.');
    $openCoverageExtras = in_array($route, ['admin.coverage.counties-towns', 'admin.coverage.map-data'], true);
    $openSales = str_starts_with($route, 'admin.quotes')
        || str_starts_with($route, 'admin.sales.')
        || (str_starts_with($route, 'admin.profile-downloads') && $route !== 'admin.profile-downloads.export');
    $openFinance = str_starts_with($route, 'admin.finance.');
    $openOps = str_starts_with($route, 'admin.operations.');
    $portfolioRoute = str_starts_with($route, 'admin.cms.portfolio');
    $openCampaignOps = $openOps;
    $openSystem = str_starts_with($route, 'admin.system.');
@endphp

<div class="flex h-full flex-col border-r border-white/10 bg-[#161212]/95 backdrop-blur-xl dark:border-white/5 dark:bg-[#0c0a0a]/98">
    <div class="border-b border-white/10 px-4 py-5 dark:border-white/5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl outline-none ring-offset-2 ring-offset-[#161212] focus-visible:ring-2 focus-visible:ring-[#f04a2a]">
            <img src="{{ $brandLogoSrc ?? route('brand-asset.view', ['filename' => 'logo.jpg']) }}" alt="" class="h-11 w-11 rounded-xl border border-[#ecdac8]/25 object-cover shadow-lg">
            <div class="min-w-0">
                <p class="truncate text-sm font-black uppercase tracking-[0.14em] text-white">{{ $portalBrandName ?? 'Effective Media' }}</p>
                <p class="truncate text-[11px] font-semibold text-[#f7b396]">Operations Portal</p>
            </div>
        </a>
        <div class="mt-4 rounded-xl border border-white/10 bg-gradient-to-br from-white/10 to-transparent px-3 py-2.5">
            <p class="truncate text-xs font-semibold text-white">{{ auth()->user()?->name }}</p>
            <p class="truncate text-[10px] text-[#c9bfb7]">{{ auth()->user()?->roles?->pluck('name')->join(' · ') ?: 'Administrator' }}</p>
        </div>
    </div>

    <nav class="flex-1 space-y-4 overflow-y-auto px-3 py-4 pb-6">
        @can('admin.dashboard.view')
            <details class="admin-nav-group" open>
                <summary class="admin-nav-summary">Dashboard</summary>
                <div class="admin-nav-items">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ $route === 'admin.dashboard' ? 'is-active' : '' }}">
                        <x-admin.nav-icon name="dashboard"/>
                        Command Center
                    </a>
                </div>
            </details>
        @endcan

        @if ($openCms
            || auth()->user()?->can('cms.homepage.manage')
            || auth()->user()?->can('cms.pages.manage')
            || auth()->user()?->can('cms.services.manage')
            || auth()->user()?->can('cms.portfolio.manage')
            || auth()->user()?->can('cms.faq.manage')
            || auth()->user()?->can('cms.testimonials.manage')
            || auth()->user()?->can('cms.statistics.manage')
            || auth()->user()?->can('cms.library.manage'))
            <details class="admin-nav-group" {{ $openCms ? 'open' : '' }}>
                <summary class="admin-nav-summary">Website CMS</summary>
                <div class="admin-nav-items">
                    @can('cms.homepage.manage')
                        <a href="{{ route('admin.cms.homepage.edit') }}" class="admin-nav-link {{ $route === 'admin.cms.homepage.edit' ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="homepage"/>
                            Homepage
                        </a>
                    @endcan
                    @can('cms.pages.manage')
                        <a href="{{ route('admin.cms.pages.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.cms.pages') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="clients"/>
                            Website pages
                        </a>
                    @endcan
                    @can('cms.services.manage')
                        <a href="{{ route('admin.cms.services.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.cms.services') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="services"/>
                            Services
                        </a>
                    @endcan
                    @can('cms.portfolio.manage')
                        <a href="{{ route('admin.cms.portfolio.index') }}" class="admin-nav-link {{ $portfolioRoute ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="portfolio"/>
                            Portfolio
                        </a>
                    @endcan
                    @can('cms.faq.manage')
                        <a href="{{ route('admin.cms.faqs.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.cms.faqs') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="faq"/>
                            FAQs
                        </a>
                    @endcan
                    @can('cms.testimonials.manage')
                        <a href="{{ route('admin.cms.testimonials.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.cms.testimonials') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="testimonials"/>
                            Testimonials
                        </a>
                    @endcan
                    @can('cms.statistics.manage')
                        <a href="{{ route('admin.cms.statistics.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.cms.statistics') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="statistics"/>
                            Statistics
                        </a>
                    @endcan
                    @can('cms.library.manage')
                        <a href="{{ route('admin.cms.library.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.cms.library') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="folder"/>
                            Documents
                        </a>
                    @endcan
                </div>
            </details>
        @endif

        @if ($openMedia || $openCoverageExtras || auth()->user()?->can('media.coverage.manage') || auth()->user()?->can('media.boards.manage') || auth()->user()?->can('operations.modules.view'))
            <details class="admin-nav-group" {{ ($openMedia || $openCoverageExtras) ? 'open' : '' }}>
                <summary class="admin-nav-summary">Media Coverage</summary>
                <div class="admin-nav-items">
                    @can('media.coverage.manage')
                        <a href="{{ route('admin.media.coverage-sites.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.media.coverage-sites') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="map"/>
                            Coverage Network
                        </a>
                    @endcan
                    @can('operations.modules.view')
                        <a href="{{ route('admin.coverage.counties-towns') }}" class="admin-nav-link {{ $route === 'admin.coverage.counties-towns' ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="map"/>
                            Counties
                        </a>
                        <a href="{{ route('admin.coverage.map-data') }}" class="admin-nav-link {{ $route === 'admin.coverage.map-data' ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="globe"/>
                            Campaign Routes
                        </a>
                    @endcan
                    @can('media.boards.manage')
                        <a href="{{ route('admin.media.boards.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.media.boards') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="board"/>
                            Pole Locations
                        </a>
                    @endcan
                </div>
            </details>
        @endif

        @if ($openSales || auth()->user()?->can('quotes.manage') || auth()->user()?->can('quotes.leads.manage') || auth()->user()?->can('documents.manage'))
            <details class="admin-nav-group" {{ $openSales ? 'open' : '' }}>
                <summary class="admin-nav-summary">Sales</summary>
                <div class="admin-nav-items">
                    @can('quotes.manage')
                        <a href="{{ route('admin.quotes.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.quotes') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="quotes"/>
                            Quote Requests
                        </a>
                    @endcan
                    @can('quotes.leads.manage')
                        <a href="{{ route('admin.sales.leads.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.sales.leads') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="clients"/>
                            Sales Leads
                        </a>
                    @endcan
                    @can('documents.manage')
                        <a href="{{ route('admin.profile-downloads.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.profile-downloads') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="download"/>
                            Profile Downloads
                        </a>
                    @endcan
                </div>
            </details>
        @endif

        @if ($openFinance || auth()->user()?->can('invoices.manage'))
            <details class="admin-nav-group" {{ $openFinance ? 'open' : '' }}>
                <summary class="admin-nav-summary">Finance</summary>
                <div class="admin-nav-items">
                    @can('invoices.manage')
                        <a href="{{ route('admin.finance.invoices') }}" class="admin-nav-link {{ $route === 'admin.finance.invoices' ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="invoice"/>
                            Invoices
                        </a>
                        <a href="{{ route('admin.finance.receipts') }}" class="admin-nav-link {{ $route === 'admin.finance.receipts' ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="invoice"/>
                            Receipts
                        </a>
                    @endcan
                </div>
            </details>
        @endif

        @if ($openCampaignOps || auth()->user()?->can('campaigns.manage') || auth()->user()?->can('cms.portfolio.manage'))
            <details class="admin-nav-group" {{ ($openCampaignOps || $portfolioRoute) ? 'open' : '' }}>
                <summary class="admin-nav-summary">Campaign Operations</summary>
                <div class="admin-nav-items">
                    @can('cms.portfolio.manage')
                        <a href="{{ route('admin.cms.portfolio.index') }}" class="admin-nav-link {{ $portfolioRoute ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="portfolio"/>
                            Campaign Assets
                        </a>
                    @endcan
                    @can('campaigns.manage')
                        <a href="{{ route('admin.operations.campaigns') }}" class="admin-nav-link {{ $route === 'admin.operations.campaigns' ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="quotes"/>
                            Active Campaigns
                        </a>
                        <a href="{{ route('admin.operations.client-work') }}" class="admin-nav-link {{ $route === 'admin.operations.client-work' ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="clients"/>
                            Client Delivery Desk
                        </a>
                        <a href="{{ route('admin.operations.maintenance-logs') }}" class="admin-nav-link {{ $route === 'admin.operations.maintenance-logs' ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="statistics"/>
                            Deployment Tracking
                        </a>
                    @endcan
                </div>
            </details>
        @endif

        @if ($openSystem || auth()->user()?->can('users.manage') || auth()->user()?->can('roles.view') || auth()->user()?->can('settings.manage'))
            <details class="admin-nav-group" {{ $openSystem ? 'open' : '' }}>
                <summary class="admin-nav-summary">System</summary>
                <div class="admin-nav-items">
                    @can('users.manage')
                        <a href="{{ route('admin.system.users.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.system.users') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="users"/>
                            Users
                        </a>
                    @endcan
                    @can('roles.view')
                        <a href="{{ route('admin.system.roles.index') }}" class="admin-nav-link {{ str_starts_with($route, 'admin.system.roles') ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="users"/>
                            Roles
                        </a>
                    @endcan
                    @can('settings.manage')
                        <a href="{{ route('admin.system.settings') }}" class="admin-nav-link {{ $route === 'admin.system.settings' ? 'is-active' : '' }}">
                            <x-admin.nav-icon name="settings"/>
                            Settings
                        </a>
                    @endcan
                </div>
            </details>
        @endif

        <details class="admin-nav-group" {{ str_starts_with($route, 'profile.') ? 'open' : '' }}>
            <summary class="admin-nav-summary">Account</summary>
            <div class="admin-nav-items">
                <a href="{{ route('profile.edit') }}" class="admin-nav-link {{ str_starts_with($route, 'profile.') ? 'is-active' : '' }}">
                    <x-admin.nav-icon name="settings"/>
                    Profile & password
                </a>
                <a href="{{ route('home') }}" class="admin-nav-link" target="_blank" rel="noopener noreferrer">
                    <x-admin.nav-icon name="globe"/>
                    Public website
                </a>
            </div>
        </details>
    </nav>

    <div class="border-t border-white/10 p-4 text-[10px] leading-relaxed text-[#92857c] dark:border-white/5">
        Internal operations · Authorized users only.
    </div>
</div>
