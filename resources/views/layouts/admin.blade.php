<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="admin-html" class="h-[100dvh] scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin | '.($portalBrandName ?? 'Effective Media'))</title>
    @if (! empty($portalFaviconHref))
        <link rel="icon" href="{{ $portalFaviconHref }}">
    @endif
    <script>
        if (localStorage.getItem('em-admin-dark') === '1') {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<style>
    :root {
        --em-font-heading: {{ json_encode((string) ($portalTheme['font_heading'] ?? 'Figtree')) }};
        --em-font-body: {{ json_encode((string) ($portalTheme['font_body'] ?? 'Figtree')) }};
    }
    body {
        font-family: var(--em-font-body), ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Liberation Sans", sans-serif;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--em-font-heading), var(--em-font-body), ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Liberation Sans", sans-serif;
    }
</style>
@php
    use App\Models\ProfileDownloadRequest;
    use App\Models\QuoteRequest;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Schema;

    $pendingQuoteCount = 0;
    $pendingDownloadsCount = 0;
    $topbarAlerts = [];

    if (Schema::hasTable('quote_requests')) {
        $pendingQuoteCount = QuoteRequest::query()->where('created_at', '>=', Carbon::now()->subDays(7))->count();
        foreach (QuoteRequest::query()->latest()->limit(3)->get() as $q) {
            $topbarAlerts[] = [
                'title' => 'Quote request',
                'detail' => trim(($q->company_name ?: $q->full_name).' · '.($q->county ?: $q->location ?: 'Outdoor lead')),
                'sort' => optional($q->created_at)?->timestamp ?? 0,
            ];
        }
    }

    if (Schema::hasTable('profile_download_requests')) {
        $pendingDownloadsCount = ProfileDownloadRequest::query()->where('created_at', '>=', Carbon::now()->subDays(7))->count();
        foreach (ProfileDownloadRequest::query()->latest()->limit(3)->get() as $d) {
            $topbarAlerts[] = [
                'title' => 'Profile download',
                'detail' => trim(($d->company_name ?: $d->full_name).' · '.$d->email),
                'sort' => optional($d->created_at)?->timestamp ?? 0,
            ];
        }
    }

    usort($topbarAlerts, static fn (array $a, array $b): int => ($b['sort'] ?? 0) <=> ($a['sort'] ?? 0));
    $topbarAlerts = array_slice(array_map(static function (array $row): array {
        unset($row['sort']);

        return $row;
    }, $topbarAlerts), 0, 5);
@endphp
<body class="flex h-[100dvh] flex-col overflow-hidden bg-[#efe8e0] text-[#261a18] antialiased transition-colors duration-300 dark:bg-[#0f0e0e] dark:text-[#f2ebe6] [&_select]:dark:bg-[#231f1f] [&_select]:dark:text-[#f6ede8]">
    <div class="flex min-h-0 flex-1 flex-col overflow-hidden lg:flex-row">
        <aside class="fixed inset-y-0 left-0 z-30 hidden w-[270px] overflow-hidden lg:block" aria-label="Sidebar">
            @include('admin.partials.sidebar')
        </aside>

        <div class="flex min-h-0 flex-1 flex-col lg:min-h-0 lg:pl-[270px]">
            @include('admin.partials.topbar')

            <main class="min-h-0 flex-1 overflow-y-auto overflow-x-hidden overscroll-y-contain px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                @php
                    $layoutFlashStatus = session('status');
                    $layoutFlashMessage = match ($layoutFlashStatus) {
                        'profile-updated', 'password-updated' => null,
                        'verification-link-sent' => __('A new verification link has been sent to your email address.'),
                        default => is_string($layoutFlashStatus) ? $layoutFlashStatus : null,
                    };
                @endphp
                @if ($layoutFlashMessage)
                    <div class="admin-glass-card mb-6 border-emerald-200/80 bg-emerald-50/90 px-4 py-3 text-sm font-semibold text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/15 dark:text-emerald-100" role="status">
                        {{ $layoutFlashMessage }}
                    </div>
                @endif
                @if (session('flash_warning'))
                    <div class="admin-glass-card mb-6 border-amber-300/90 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-950 dark:border-amber-500/35 dark:bg-amber-500/15 dark:text-amber-100" role="status">
                        {{ session('flash_warning') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="admin-glass-card mb-6 border-rose-200/80 bg-rose-50/90 px-4 py-3 text-sm font-semibold text-rose-800 dark:border-rose-500/30 dark:bg-rose-500/15 dark:text-rose-100" role="alert">
                        <p class="mb-2">Please correct the highlighted fields.</p>
                        <ul class="list-inside list-disc text-xs font-medium">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </main>

            @include('admin.partials.footer')
        </div>
    </div>

    <div
        id="admin-sidebar-drawer"
        class="fixed inset-y-0 left-0 z-50 w-[min(100%,288px)] -translate-x-full transform border-r border-white/10 bg-[#161212] shadow-2xl transition-transform duration-300 lg:hidden"
        role="dialog"
        aria-modal="true"
        aria-label="Navigation"
        aria-hidden="true"
    >
        @include('admin.partials.sidebar')
    </div>
    <div id="admin-sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-black/50 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const html = document.getElementById('admin-html');
            const drawer = document.getElementById('admin-sidebar-drawer');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            const toggles = document.querySelectorAll('[data-admin-sidebar-toggle]');

            const setDark = (on) => {
                if (!html) return;
                html.classList.toggle('dark', on);
                localStorage.setItem('em-admin-dark', on ? '1' : '0');
                document.querySelectorAll('[data-admin-theme-toggle]').forEach((btn) => btn.setAttribute('aria-pressed', on ? 'true' : 'false'));
                document.querySelectorAll('[data-admin-theme-icon-sun]').forEach((el) => el.classList.toggle('hidden', !on));
                document.querySelectorAll('[data-admin-theme-icon-moon]').forEach((el) => el.classList.toggle('hidden', on));
            };

            const stored = localStorage.getItem('em-admin-dark');
            if (stored === '1') {
                setDark(true);
            }

            document.querySelectorAll('[data-admin-theme-toggle]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    setDark(!html.classList.contains('dark'));
                });
            });

            const closeDrawer = () => {
                if (!drawer || !backdrop) return;
                drawer.classList.add('-translate-x-full');
                drawer.setAttribute('aria-hidden', 'true');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            const openDrawer = () => {
                if (!drawer || !backdrop) return;
                drawer.classList.remove('-translate-x-full');
                drawer.setAttribute('aria-hidden', 'false');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            toggles.forEach((btn) => btn.addEventListener('click', openDrawer));
            backdrop?.addEventListener('click', closeDrawer);

            drawer?.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.matchMedia('(max-width:1023px)').matches) {
                        closeDrawer();
                    }
                });
            });

            const searchOpen = document.querySelector('[data-admin-search-open]');
            const searchModal = document.querySelector('[data-admin-search-modal]');
            const searchClose = document.querySelector('[data-admin-search-close]');
            const searchInput = document.getElementById('admin-search-mobile');

            const closeSearch = () => {
                if (!(searchModal instanceof HTMLElement)) return;
                searchModal.classList.add('hidden');
                searchModal.classList.remove('flex');
                searchModal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            };

            const openSearch = () => {
                if (!(searchModal instanceof HTMLElement)) return;
                searchModal.classList.remove('hidden');
                searchModal.classList.add('flex');
                searchModal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
                window.setTimeout(() => searchInput?.focus(), 30);
            };

            searchOpen?.addEventListener('click', openSearch);
            searchClose?.addEventListener('click', closeSearch);
            searchModal?.addEventListener('click', (e) => {
                if (e.target === searchModal) closeSearch();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeSearch();
            });
        });
    </script>
</body>
</html>
