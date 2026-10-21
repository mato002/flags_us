@php
    $canQuotes = auth()->user()?->can('quotes.manage');
    $canPortfolio = auth()->user()?->can('cms.portfolio.manage');
@endphp

<header class="relative z-40 shrink-0 border-b border-[#ead8c9]/70 bg-[#fcf9f7]/85 backdrop-blur-xl dark:border-white/10 dark:bg-[#141010]/85">
    <div class="flex items-center gap-2.5 px-4 py-2.5 sm:px-6 sm:py-3 lg:gap-6">
        <button type="button" class="inline-flex rounded-xl border border-[#d9c4af] bg-white p-2.5 text-[#5c1514] shadow-sm transition hover:border-[#8b1e1a] hover:text-[#8b1e1a] lg:hidden dark:border-white/10 dark:bg-white/10 dark:text-[#f4e3c4]" aria-controls="admin-sidebar-drawer" data-admin-sidebar-toggle>
            <span class="sr-only">Open sidebar</span>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div class="min-w-0 flex-1">
            <h1 class="truncate text-[15px] font-black tracking-tight text-[#2a1716] sm:text-lg dark:text-white">@yield('header', 'Dashboard')</h1>
            <p class="hidden truncate text-xs text-[#7a665e] sm:block dark:text-[#bfb3ab]">{{ now()->format('l · M j, Y · H:i') }}</p>
        </div>

        @if ($canQuotes)
            <button type="button" class="inline-flex min-h-[44px] items-center gap-2 rounded-xl border border-[#e5d6c9] bg-white px-3 py-2 text-xs font-semibold text-[#5c1514] shadow-sm lg:hidden dark:border-white/10 dark:bg-white/10 dark:text-[#f4e3c4]" data-admin-search-open>
                <svg class="h-5 w-5 shrink-0 text-[#a8977a]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span class="truncate">{{ request('search') ? 'Filtered' : 'Search' }}</span>
            </button>
            <form action="{{ route('admin.quotes.index') }}" method="get" class="hidden max-w-xs flex-1 lg:block xl:max-w-md">
                <label class="sr-only" for="admin-search">Search leads</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[#a8977a]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input id="admin-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search quotes, counties, contacts…" class="min-h-[44px] w-full rounded-xl border border-[#e5d6c9] bg-white py-2.5 pl-10 pr-4 text-sm text-[#3a2824] placeholder:text-[#a89b94] shadow-inner transition focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#f04a2a]/35 dark:border-white/10 dark:bg-[#1c1818] dark:text-[#efe8e3] dark:placeholder:text-[#776a63]"/>
                </div>
            </form>
        @endif

        <div class="flex items-center gap-1.5 sm:gap-2">
            <details class="relative">
                <summary class="relative flex cursor-pointer list-none items-center justify-center rounded-xl border border-[#e5d6c9] bg-white p-2.5 text-[#5c1514] shadow-sm transition hover:border-[#8b1e1a] dark:border-white/10 dark:bg-white/10 dark:text-[#f4e3c4] [&::-webkit-details-marker]:hidden">
                    <span class="sr-only">Notifications</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if(($pendingQuoteCount ?? 0) > 0 || ($pendingDownloadsCount ?? 0) > 0)
                        <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-[#f04a2a] ring-2 ring-white dark:ring-[#141010]"></span>
                    @endif
                </summary>
                <div class="absolute right-0 z-50 mt-2 w-[min(20rem,calc(100vw-1.25rem))] max-w-[calc(100vw-1rem)] origin-top-right rounded-2xl border border-[#ead8c9] bg-white/95 p-3 shadow-2xl backdrop-blur-xl dark:border-white/10 dark:bg-[#1a1616]/95">
                    <p class="px-2 text-xs font-bold uppercase tracking-wider text-[#8b1e1a]">Operational alerts</p>
                    <ul class="mt-2 max-h-64 space-y-2 overflow-auto text-sm">
                        @forelse($topbarAlerts ?? [] as $alert)
                            <li class="rounded-xl border border-[#f0e4da] bg-[#fffaf6] px-3 py-2 dark:border-white/10 dark:bg-white/5">
                                <span class="font-semibold text-[#2a1716] dark:text-white">{{ $alert['title'] }}</span>
                                <span class="block text-xs text-[#6b5e58] dark:text-[#c4bbb4]">{{ $alert['detail'] }}</span>
                            </li>
                        @empty
                            <li class="rounded-xl bg-[#f7f3ef] px-3 py-4 text-center text-xs text-[#7a665e] dark:bg-white/5 dark:text-[#bfb3ab]">You are clear · no queued alerts.</li>
                        @endforelse
                    </ul>
                </div>
            </details>

            <details class="relative">
                <summary class="inline-flex min-h-[44px] cursor-pointer list-none items-center gap-2 rounded-xl border border-[#e5d6c9] bg-gradient-to-r from-[#5c1514] to-[#8b1e1a] px-3 py-2 text-xs font-bold uppercase tracking-wide text-white shadow-[0_10px_30px_-12px_rgb(139,30,26,0.8)] transition hover:brightness-110 [&::-webkit-details-marker]:hidden">
                    <span class="hidden sm:inline">Quick actions</span><span class="sm:hidden">Menu</span>
                    <svg class="h-4 w-4 text-[#f7d4c1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="absolute right-0 z-50 mt-2 w-[min(18rem,calc(100vw-1.25rem))] max-w-[calc(100vw-1rem)] origin-top-right overflow-hidden rounded-2xl border border-[#ead8c9] bg-white shadow-xl dark:border-white/10 dark:bg-[#1a1616]">
                    @if ($canPortfolio)
                        <a href="{{ route('admin.cms.portfolio.create') }}" class="flex items-center gap-2 px-4 py-3 text-sm font-semibold text-[#3a2824] transition hover:bg-[#f9f3ee] dark:text-[#efe8e3] dark:hover:bg-white/5">Add campaign</a>
                        <a href="{{ route('admin.cms.portfolio.index') }}" class="flex items-center gap-2 border-t border-[#f0e4da] px-4 py-3 text-sm font-semibold text-[#3a2824] transition hover:bg-[#f9f3ee] dark:border-white/5 dark:text-[#efe8e3] dark:hover:bg-white/5">Manage portfolio grid</a>
                    @endif
                    <a href="{{ route('quote') }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 border-t border-[#f0e4da] px-4 py-3 text-sm font-semibold text-[#3a2824] transition hover:bg-[#f9f3ee] dark:border-white/5 dark:text-[#efe8e3] dark:hover:bg-white/5">Generate quote form</a>
                    <a href="{{ route('coverage') }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 border-t border-[#f0e4da] px-4 py-3 text-sm font-semibold text-[#3a2824] transition hover:bg-[#f9f3ee] dark:border-white/5 dark:text-[#efe8e3] dark:hover:bg-white/5">Coverage map preview</a>
                </div>
            </details>

            <button type="button" class="rounded-xl border border-[#e5d6c9] bg-white p-2.5 text-[#5c1514] shadow-sm transition hover:border-[#8b1e1a] dark:border-white/10 dark:bg-white/10 dark:text-[#f4e3c4]" data-admin-theme-toggle aria-pressed="false" title="Toggle dark mode">
                <span data-admin-theme-icon-sun class="hidden h-5 w-5"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 3v2m0 14v2M4 12H2m20 0h-2m-3.314-9.657L17.657 6.343M6.343 17.657l-1.414 1.414m12.728 0l-1.414-1.414M6.343 6.343L4.93 4.93"/></svg></span>
                <span data-admin-theme-icon-moon class="h-5 w-5"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"/></svg></span>
            </button>

            <details class="relative">
                <summary class="flex cursor-pointer list-none items-center gap-2 rounded-2xl border border-[#e5d6c9] bg-white py-1.5 pl-1.5 pr-3 shadow-sm transition hover:border-[#8b1e1a] [&::-webkit-details-marker]:hidden dark:border-white/10 dark:bg-white/10">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#5c1514] to-[#f04a2a] text-sm font-black text-white">{{ strtoupper(mb_substr(auth()->user()->name ?? '?', 0, 1)) }}</span>
                    <span class="hidden max-w-[120px] truncate text-xs font-semibold text-[#3a2824] sm:inline dark:text-[#efe8e3]">{{ auth()->user()?->email }}</span>
                </summary>
                <div class="absolute right-0 z-50 mt-2 w-[min(14rem,calc(100vw-1.25rem))] max-w-[calc(100vw-1rem)] origin-top-right overflow-hidden rounded-2xl border border-[#ead8c9] bg-white shadow-2xl dark:border-white/10 dark:bg-[#1a1616]">
                    <div class="border-b border-[#f0e4da] px-4 py-3 dark:border-white/10">
                        <p class="truncate text-sm font-bold text-[#2a1716] dark:text-white">{{ auth()->user()?->name }}</p>
                        <p class="truncate text-xs text-[#7a665e] dark:text-[#bfb3ab]">{{ auth()->user()?->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm font-semibold text-[#3a2824] transition hover:bg-[#f9f3ee] dark:text-[#efe8e3] dark:hover:bg-white/5">Profile settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 text-left text-sm font-semibold text-[#8b1e1a] transition hover:bg-[#fff5f5] dark:hover:bg-white/5">Sign out</button>
                    </form>
                </div>
            </details>
        </div>
    </div>

    @if ($canQuotes)
        <div class="fixed inset-0 z-[60] hidden items-start justify-center bg-black/55 p-4 pt-20 backdrop-blur-sm lg:hidden" data-admin-search-modal aria-hidden="true">
            <div class="w-full max-w-xl rounded-2xl border border-[#ead8c9] bg-white p-4 shadow-2xl dark:border-white/10 dark:bg-[#1a1616]">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#8b1e1a] dark:text-[#f7b396]">Search quotes</p>
                    <button type="button" class="rounded-lg border border-[#e5d6c9] px-2.5 py-1.5 text-xs font-semibold text-[#5c1514] dark:border-white/10 dark:text-[#f4e3c4]" data-admin-search-close>Close</button>
                </div>
                <form action="{{ route('admin.quotes.index') }}" method="get" class="mt-3">
                    <label class="sr-only" for="admin-search-mobile">Search leads</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[#a8977a]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input id="admin-search-mobile" type="search" name="search" value="{{ request('search') }}" placeholder="Quotes, counties, contacts…" class="min-h-[48px] w-full rounded-xl border border-[#e5d6c9] bg-white py-3 pl-10 pr-4 text-sm text-[#3a2824] placeholder:text-[#a89b94] shadow-inner transition focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#f04a2a]/35 dark:border-white/10 dark:bg-[#1c1818] dark:text-[#efe8e3] dark:placeholder:text-[#776a63]"/>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <button type="submit" class="flex-1 min-h-[44px] rounded-xl bg-[#8b1e1a] text-xs font-bold text-white">Search</button>
                        <a href="{{ route('admin.quotes.index') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-[#e5d6c9] bg-white px-4 text-xs font-bold text-[#5c1514] dark:border-white/10 dark:bg-white/10 dark:text-[#f4e3c4]">Clear</a>
                    </div>
                </form>
            </div>
        </div>
    @endif
</header>
