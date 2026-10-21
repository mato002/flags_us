<x-auth-portal-layout>
    <div class="flex min-h-screen flex-col lg:flex-row">
        <!-- LEFT: branding / hero -->
        <aside class="relative order-2 hidden min-h-[340px] flex-shrink-0 overflow-hidden lg:order-1 lg:flex lg:min-h-screen lg:w-[min(52%,720px)] lg:max-w-[720px]">
            <div class="absolute inset-0" aria-hidden="true">
                <div
                    class="auth-portal-hero-slide absolute inset-0 bg-cover bg-center"
                    style="background-image: url('https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&amp;fit=crop&amp;w=1920&amp;q=80')"
                ></div>
                <div
                    class="auth-portal-hero-slide absolute inset-0 bg-cover bg-center"
                    style="background-image: url('https://images.unsplash.com/photo-1494522358652-f30e61a60313?auto=format&amp;fit=crop&amp;w=1920&amp;q=80')"
                ></div>
                <div
                    class="auth-portal-hero-slide absolute inset-0 bg-cover bg-center"
                    style="background-image: url('https://images.unsplash.com/photo-1449824913935-59a10b8d2000?auto=format&amp;fit=crop&amp;w=1920&amp;q=80')"
                ></div>
                <div
                    class="auth-portal-hero-slide absolute inset-0 bg-cover bg-center"
                    style="background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&amp;fit=crop&amp;w=1920&amp;q=80')"
                ></div>
                <div
                    class="auth-portal-hero-slide absolute inset-0 bg-cover bg-center"
                    style="background-image: url('https://images.unsplash.com/photo-1519501025264-65ba15a82390?auto=format&amp;fit=crop&amp;w=1920&amp;q=80')"
                ></div>
            </div>

            <!-- Maroon overlay + vignette -->
            <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(135deg,rgb(92,21,20,0.92)_0%,rgb(42,14,13,0.88)_42%,rgb(15,11,13,0.82)_100%)]"></div>
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_30%_20%,rgb(240,74,42,0.22),transparent_55%)]"></div>
            <div class="auth-portal-grid pointer-events-none absolute inset-0 opacity-70 mix-blend-overlay"></div>

            <!-- Ambient glow orbs -->
            <div class="auth-portal-glow-orb pointer-events-none absolute -left-[20%] top-[10%] h-[340px] w-[340px] rounded-full bg-[radial-gradient(circle,rgb(240,74,42,0.35),transparent_68%)] blur-3xl"></div>
            <div class="auth-portal-glow-orb pointer-events-none absolute bottom-[5%] right-[-15%] h-[280px] w-[280px] rounded-full bg-[radial-gradient(circle,rgb(168,151,122,0.25),transparent_70%)] blur-3xl [animation-delay:-4s]"></div>

            <!-- Light streak -->
            <div class="auth-portal-light-streak pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -left-1/2 top-[18%] h-[120%] w-[40%] bg-gradient-to-r from-transparent via-white/25 to-transparent"></div>
            </div>

            <div class="relative z-10 flex h-full flex-col justify-between px-6 py-10 sm:px-10 sm:py-12 lg:px-12 lg:py-14">
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 rounded-lg border border-white/10 bg-white/5 px-3 py-2 backdrop-blur-sm transition hover:border-white/20 hover:bg-white/10">
                        <img src="{{ $brandLogoSrc ?? route('brand-asset.view', ['filename' => 'logo.jpg']) }}" alt="{{ $portalBrandName ?? 'Effective Media' }}" class="h-11 w-11 rounded-md border border-white/20 object-cover shadow-md">
                        <span class="text-sm font-bold tracking-wide text-white">{{ $portalBrandName ?? 'Effective Media' }}</span>
                    </a>

                    <h1 class="mt-8 max-w-lg text-2xl font-black leading-tight tracking-tight text-white sm:text-3xl lg:text-4xl">
                        {{ $portalBrandName ?? 'Effective Media' }} Operations Portal
                    </h1>
                    <p class="mt-4 max-w-md text-sm leading-relaxed text-[#f4e3c4]/90">
                        Manage campaigns, quotations, portfolio assets, coverage intelligence and media operations from one centralized platform.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <div class="rounded-lg border border-white/15 bg-black/25 px-4 py-3 backdrop-blur-md">
                            <p class="text-2xl font-black text-[#f4e3c4]">730+</p>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-white/70">Poles</p>
                        </div>
                        <div class="rounded-lg border border-white/15 bg-black/25 px-4 py-3 backdrop-blur-md">
                            <p class="text-2xl font-black text-[#f4e3c4]">18+</p>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-white/70">Towns</p>
                        </div>
                        <div class="rounded-lg border border-white/15 bg-black/25 px-4 py-3 backdrop-blur-md">
                            <p class="text-2xl font-black text-[#f4e3c4]">7+</p>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-white/70">Counties</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex flex-wrap gap-2 lg:mt-14">
                    @php
                        $badges = [
                            [
                                'label' => 'Campaign Management',
                                'paths' => ['M12 14l9-5-9-5-9 5 9 5zm0 0v6'],
                            ],
                            [
                                'label' => 'Quotation Center',
                                'paths' => ['M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ],
                            [
                                'label' => 'Coverage Intelligence',
                                'paths' => [
                                    'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z',
                                    'M15 11a3 3 0 11-6 0 3 3 0 016 0z',
                                ],
                            ],
                            [
                                'label' => 'Portfolio CMS',
                                'paths' => [
                                    'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14',
                                    'M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z',
                                ],
                            ],
                            [
                                'label' => 'Client Operations',
                                'paths' => [
                                    'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197',
                                ],
                            ],
                        ];
                    @endphp
                    @foreach ($badges as $badge)
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-wide text-white/90 backdrop-blur-sm">
                            <svg class="h-4 w-4 shrink-0 text-[#f7b396]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                @foreach ($badge['paths'] as $pathD)
                                    <path d="{{ $pathD }}"/>
                                @endforeach
                            </svg>
                            {{ $badge['label'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- RIGHT: login -->
        <main class="relative order-1 flex min-h-[100dvh] flex-1 flex-col justify-center bg-[#0f0c0d] px-4 py-10 sm:px-8 sm:py-14 lg:order-2 lg:min-h-0 lg:px-12 lg:py-16">
            <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
                <div class="absolute left-1/2 top-[-20%] h-[420px] w-[420px] -translate-x-1/2 rounded-full bg-[radial-gradient(circle,rgb(139,30,26,0.18),transparent_65%)] blur-3xl"></div>
            </div>

            <div class="relative mx-auto w-full max-w-md">
                <div class="rounded-3xl border border-white/10 bg-white/[0.07] p-8 shadow-[0_24px_80px_-30px_rgba(0,0,0,0.85)] backdrop-blur-xl sm:p-10">
                    <div class="mb-8 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-white/15 bg-white/10 shadow-inner">
                            <img src="{{ $brandLogoSrc ?? route('brand-asset.view', ['filename' => 'logo.jpg']) }}" alt="" class="h-11 w-11 rounded-lg object-cover" width="44" height="44">
                        </div>
                        <h2 class="mt-6 text-2xl font-black tracking-tight text-white">Welcome Back</h2>
                        <p class="mt-2 text-sm text-[#c4b8b0]">Sign in to continue</p>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 rounded-lg border border-emerald-500/35 bg-emerald-500/10 px-3 py-2 text-sm font-medium text-emerald-100" role="status">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5" data-auth-login-form>
                        @csrf

                        <div>
                            <label for="email" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#a8977a]">Email</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[#8b1e1a]">
                                    <svg class="h-5 w-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="w-full rounded-xl border border-white/15 bg-[#1a1414] py-3.5 pl-11 pr-4 text-sm text-white placeholder:text-[#6b605c] shadow-inner transition focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#f04a2a]/35"
                                    placeholder="you@company.com"
                                >
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-300"/>
                        </div>

                        <div>
                            <label for="password" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#a8977a]">Password</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[#8b1e1a]">
                                    <svg class="h-5 w-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    class="w-full rounded-xl border border-white/15 bg-[#1a1414] py-3.5 pl-11 pr-4 text-sm text-white placeholder:text-[#6b605c] shadow-inner transition focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#f04a2a]/35"
                                    placeholder="••••••••"
                                >
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-300"/>
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2.5 select-none">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    class="h-4 w-4 rounded border-white/25 bg-[#1a1414] text-[#8b1e1a] shadow-inner focus:ring-2 focus:ring-[#f04a2a]/45 focus:ring-offset-0 focus:ring-offset-transparent"
                                >
                                <span class="text-sm text-[#e8dfd8]">{{ __('Remember me') }}</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#f7b396] underline-offset-2 transition hover:text-[#f4e3c4] hover:underline">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <button
                            type="submit"
                            data-auth-login-submit
                            class="group relative flex w-full items-center justify-center gap-2 overflow-hidden rounded-xl bg-gradient-to-r from-[#5c1514] via-[#7a201c] to-[#f04a2a] py-3.5 text-sm font-bold text-white shadow-[0_12px_32px_-8px_rgb(139,30,26,0.65)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_40px_-10px_rgb(240,74,42,0.55)] active:translate-y-0 disabled:cursor-not-allowed disabled:opacity-65 disabled:hover:translate-y-0"
                        >
                            <span data-auth-login-label>{{ __('Log in') }}</span>
                            <svg data-auth-login-spinner class="hidden h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>

                    <div class="mt-8 rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-left text-xs leading-relaxed text-[#b5a9a1]">
                        <p class="font-bold uppercase tracking-[0.14em] text-[#a8977a]">Portal support</p>
                        <p class="mt-2">Need access or locked out? Reach the operations desk via
                            <a href="mailto:{{ config('effective_media_profile.contacts.email', 'info@effectivemedia.co.ke') }}" class="font-semibold text-[#f7b396] underline-offset-2 hover:underline">{{ config('effective_media_profile.contacts.email', 'info@effectivemedia.co.ke') }}</a>
                            or call
                            @php($loginPhone = config('effective_media_profile.contacts.phones.0', '0725 646 642'))
                            <a href="tel:{{ preg_replace('/\s+/', '', $loginPhone) }}" class="font-semibold text-[#f7b396] underline-offset-2 hover:underline">{{ $loginPhone }}</a>.
                        </p>
                    </div>

                    <div class="mt-8 space-y-4 border-t border-white/10 pt-6 text-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#c4b8b0] transition hover:text-white">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Website
                        </a>
                        <p class="text-[11px] leading-relaxed text-[#7a6e68]">
                            Secure connection. Use a strong password and never share your portal credentials. Session activity may be monitored for security.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-auth-login-form]');
            const submit = document.querySelector('[data-auth-login-submit]');
            const label = document.querySelector('[data-auth-login-label]');
            const spinner = document.querySelector('[data-auth-login-spinner]');
            if (!form || !submit || !label || !spinner) return;

            form.addEventListener('submit', () => {
                submit.disabled = true;
                label.textContent = 'Signing in…';
                spinner.classList.remove('hidden');
            });
        });
    </script>
</x-auth-portal-layout>
