@extends('layouts.website')

@section('meta_description', $websitePage['meta_description'] ?? 'Step through our guided quote form for street light ads, billboards, and pavement branding across Kenya. Share your budget, duration, and locations — we respond with a tailored proposal.')

@section('content')
    @php
        $qw = $websitePage['sections'] ?? [];
        $qHero = $qw['hero'] ?? [];
        $qTrust = collect($qw['trust']['items'] ?? [])->take(3);
        $qSummaryCopy = $qw['summary_copy'] ?? [];
        $qFormUi = $qw['form'] ?? [];
        $qSummaryLabels = is_array($qSummaryCopy['labels'] ?? null) ? $qSummaryCopy['labels'] : [];
    @endphp
    @php($qHeroImage = trim((string) ($qHero['image'] ?? '')))
    @php($quoteHeroBackdrop = $qHeroImage !== '' ? $qHeroImage : asset('profile-gallery/profile-page-5.jpg'))
    @php($qAccent = $qHero['accent'] ?? 'warm')
    @php($qHasUploadedHero = $qHeroImage !== '')
    <section class="relative isolate min-h-[min(100vw,480px)] overflow-hidden pb-10 pt-14 text-white sm:min-h-0 lg:pb-12 lg:pt-16">
        <div class="pointer-events-none absolute inset-0 z-0 bg-[#1a1311]" aria-hidden="true">
            <img src="{{ $quoteHeroBackdrop }}" alt="" width="1920" height="1080" class="absolute inset-0 size-full max-w-none object-cover object-[center_35%]" loading="eager" fetchpriority="high" decoding="async">
        </div>
        <div class="absolute inset-0 z-[1] bg-gradient-to-br @if ($qAccent === 'ink' && ! $qHasUploadedHero) from-[#130a0a] via-[#5c1514]/92 to-[#1a0c0c] @else from-[#2a1211]/88 via-[#5c1514]/78 to-[#1a0d0c]/92 @endif"></div>
        @if ($qHasUploadedHero)
            <div class="pointer-events-none absolute inset-0 z-[2] bg-[linear-gradient(115deg,rgba(254,243,232,0.18)_0%,transparent_52%,rgba(92,21,20,0.48)_100%)]"></div>
        @else
            <div class="pointer-events-none absolute inset-0 z-[2] bg-[linear-gradient(115deg,rgba(254,243,232,0.12)_0%,transparent_50%,rgba(92,21,20,0.42)_100%)]"></div>
        @endif
        <div class="em-container relative z-10">
            <div class="max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.26em] text-[#ffc7a8]/95">{{ $qHero['kicker'] ?? 'Executive quote desk' }}</p>
                <h1 class="mt-2 text-[1.75rem] font-black leading-tight tracking-tight text-[#fff8f2] sm:text-[2.1rem] lg:text-[2.35rem]">
                    {{ $qHero['title'] ?? 'Guided quotation for Kenya campaigns' }}
                </h1>
                <p class="mt-4 max-w-2xl text-sm leading-relaxed text-[#fdeee3]/95 sm:text-[15px]">
                    {{ $qHero['description'] ?? 'Move through each step — selections stream into the sticky summary at all times.' }}
                    @if (($qHero['primary_note'] ?? '') !== '')
                        <span class="mt-3 block text-[13px] text-[#ffd9c8]/90">{{ $qHero['primary_note'] }}</span>
                    @endif
                </p>
            </div>

            <ul class="mt-10 grid gap-3 sm:grid-cols-3" role="list">
                @forelse ($qTrust as $item)
                    <li class="flex gap-3 rounded-xl border border-white/15 bg-white/[0.04] px-4 py-3 backdrop-blur-sm">
                        <span class="mt-0.5 inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-lg border border-white/20 bg-white/10 px-2 text-[11px] font-black text-[#ffb69b]" aria-hidden="true">{{ $item['badge'] ?? '•' }}</span>
                        <div>
                            <p class="text-sm font-bold text-white">{{ $item['title'] ?? '' }}</p>
                            <p class="mt-1 text-xs leading-snug text-[#fdeee3]/85">{{ $item['body'] ?? '' }}</p>
                        </div>
                    </li>
                @empty
                    <li class="flex gap-3 rounded-xl border border-white/20 bg-white/[0.06] px-4 py-3 backdrop-blur-sm sm:col-span-3">
                        <p class="text-sm text-[#fdeee3]/95">Configure trust pillars from Admin → Website pages → quote sections JSON.</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </section>

    <section class="scroll-mt-[var(--em-header-offset)] bg-[#f7f4f1] pb-28 pt-10 max-lg:pb-[7.75rem] lg:pb-24 lg:pt-12" id="quote-form" aria-labelledby="quote-form-heading">
        <div class="em-container">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900 shadow-sm" role="alert" aria-live="polite" tabindex="-1" id="quote-form-errors">
                    <p class="font-semibold">Please review the highlighted fields.</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="lg:grid lg:grid-cols-[minmax(0,1fr)_min(100%,360px)] lg:items-start lg:gap-10 xl:gap-14">
                <div>
                    <h2 id="quote-form-heading" class="text-xl font-black text-[#1f1111] sm:text-2xl">Your brief</h2>
                    <p id="quote-form-help" class="mt-2 max-w-2xl text-sm leading-relaxed text-[#57453f]">
                        Use the sections below in order. Every control has a visible label for assistive technology and keyboard navigation.
                    </p>

                    <form id="em-quote-form" method="POST" action="{{ route('quote.store') }}" class="mt-6 space-y-8">
                        @csrf
                        <input type="hidden" name="source" value="website_quote_form">

                        {{-- Contact --}}
                        <fieldset class="em-card em-card-accent rounded-2xl border border-[#e8d4c8] bg-white p-5 shadow-[0_20px_50px_-32px_rgba(92,21,20,0.55)] sm:p-7">
                            <legend class="float-left w-full border-b border-[#f0e3d8] pb-3 text-sm font-black uppercase tracking-[0.12em] text-[#8b1e1a]">1. Contact details</legend>
                            <div class="clear-both grid gap-5 pt-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <label for="qf-full-name" class="mb-1 block text-sm font-bold text-[#3b2525]">Full name <abbr title="required" class="font-black text-rose-600">*</abbr></label>
                                    <input id="qf-full-name" name="full_name" value="{{ old('full_name') }}" type="text" required autocomplete="name" aria-required="true" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] placeholder:text-[#a09088] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                    <p class="mt-1 text-xs text-[#7a6962]" id="qf-full-name-hint">As it should appear on the proposal.</p>
                                </div>
                                <div class="sm:col-span-1">
                                    <label for="qf-company" class="mb-1 block text-sm font-bold text-[#3b2525]">Company or organisation</label>
                                    <input id="qf-company" name="company_name" value="{{ old('company_name') }}" type="text" autocomplete="organization" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                    <p class="mt-1 text-xs text-[#7a6962]" id="qf-company-hint">Optional — helps when billing or issuing LPO references.</p>
                                </div>
                                <div class="sm:col-span-1">
                                    <label for="qf-phone" class="mb-1 block text-sm font-bold text-[#3b2525]">Phone <abbr title="required" class="font-black text-rose-600">*</abbr></label>
                                    <input id="qf-phone" name="phone" value="{{ old('phone') }}" type="tel" required autocomplete="tel" inputmode="tel" aria-required="true" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                    <p class="mt-1 text-xs text-[#7a6962]" id="qf-phone-hint">We may call back on this line for clarification.</p>
                                </div>
                                <div class="sm:col-span-1">
                                    <label for="qf-email" class="mb-1 block text-sm font-bold text-[#3b2525]">Work email <abbr title="required" class="font-black text-rose-600">*</abbr></label>
                                    <input id="qf-email" name="email" value="{{ old('email') }}" type="email" required autocomplete="email" aria-required="true" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                    <p class="mt-1 text-xs text-[#7a6962]" id="qf-email-hint">We send confirmations and drafts here.</p>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Location --}}
                        <fieldset class="em-card rounded-2xl border border-[#e8d4c8] bg-white p-5 shadow-sm sm:p-7">
                            <legend class="float-left w-full border-b border-[#f0e3d8] pb-3 text-sm font-black uppercase tracking-[0.12em] text-[#8b1e1a]">2. Location focus</legend>
                            <div class="clear-both grid gap-5 pt-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <label for="qf-county" class="mb-1 block text-sm font-bold text-[#3b2525]">County or regional focus</label>
                                    @php($cVal = old('county', $prefill['county'] ?? ''))
                                    @php($cOpts = $quoteOptions['counties'])
                                    <select id="qf-county" name="county" aria-describedby="qf-county-hint" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                        <option value="" @selected($cVal === '' || $cVal === null)>Select county (optional)</option>
                                        @foreach ($cOpts as $opt)
                                            @if ($opt === '') @continue @endif
                                            <option value="{{ $opt }}" @selected($cVal === $opt)>{{ $opt }}</option>
                                        @endforeach
                                        @if ($cVal !== '' && $cVal !== null && ! in_array($cVal, $cOpts, true))
                                            <option value="{{ $cVal }}" selected>{{ $cVal }}</option>
                                        @endif
                                    </select>
                                    <p class="mt-1 text-xs text-[#7a6962]" id="qf-county-hint">You can tighten town-level detail in the next field.</p>
                                </div>
                                <div class="sm:col-span-1">
                                    <label for="qf-location" class="mb-1 block text-sm font-bold text-[#3b2525]">Town, route, or corridor</label>
                                    <input id="qf-location" name="location" value="{{ old('location', $prefill['location'] ?? '') }}" type="text" autocomplete="address-level2" aria-describedby="qf-location-hint" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                    <p class="mt-1 text-xs text-[#7a6962]" id="qf-location-hint">Example: Nairobi CBD outbound, Kisumu roundabout cluster, Nakuru highway approach.</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="qf-industry" class="mb-1 block text-sm font-bold text-[#3b2525]">Industry or sector</label>
                                    @php($iVal = old('industry', $prefill['industry'] ?? ''))
                                    @php($iOpts = $quoteOptions['industries'])
                                    <select id="qf-industry" name="industry" class="mt-1 w-full max-w-xl rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                        <option value="" @selected($iVal === '' || $iVal === null)>Select industry (optional)</option>
                                        @foreach ($iOpts as $opt)
                                            @if ($opt === '') @continue @endif
                                            <option value="{{ $opt }}" @selected($iVal === $opt)>{{ $opt }}</option>
                                        @endforeach
                                        @if ($iVal !== '' && $iVal !== null && ! in_array($iVal, $iOpts, true))
                                            <option value="{{ $iVal }}" selected>{{ $iVal }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Campaign --}}
                        <fieldset class="em-card rounded-2xl border border-[#e8d4c8] bg-white p-5 shadow-sm sm:p-7">
                            <legend class="float-left w-full border-b border-[#f0e3d8] pb-3 text-sm font-black uppercase tracking-[0.12em] text-[#8b1e1a]">3. Campaign details</legend>
                            <div class="clear-both grid gap-5 pt-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <label for="qf-objective" class="mb-1 block text-sm font-bold text-[#3b2525]">Primary objective</label>
                                    @php($oVal = old('campaign_objective', $prefill['campaign_objective'] ?? ''))
                                    @php($oOpts = $quoteOptions['campaign_objectives'])
                                    <select id="qf-objective" name="campaign_objective" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                        <option value="" @selected($oVal === '' || $oVal === null)>Select objective (optional)</option>
                                        @foreach ($oOpts as $opt)
                                            @if ($opt === '') @continue @endif
                                            <option value="{{ $opt }}" @selected($oVal === $opt)>{{ $opt }}</option>
                                        @endforeach
                                        @if ($oVal !== '' && $oVal !== null && ! in_array($oVal, $oOpts, true))
                                            <option value="{{ $oVal }}" selected>{{ $oVal }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="sm:col-span-1">
                                    <label for="qf-audience" class="mb-1 block text-sm font-bold text-[#3b2525]">Audience focus</label>
                                    @php($aVal = old('target_audience', $prefill['target_audience'] ?? ''))
                                    @php($aOpts = $quoteOptions['target_audiences'])
                                    <select id="qf-audience" name="target_audience" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                        <option value="" @selected($aVal === '' || $aVal === null)>Select audience (optional)</option>
                                        @foreach ($aOpts as $opt)
                                            @if ($opt === '') @continue @endif
                                            <option value="{{ $opt }}" @selected($aVal === $opt)>{{ $opt }}</option>
                                        @endforeach
                                        @if ($aVal !== '' && $aVal !== null && ! in_array($aVal, $aOpts, true))
                                            <option value="{{ $aVal }}" selected>{{ $aVal }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="qf-media-type" class="mb-1 block text-sm font-bold text-[#3b2525]">Preferred media format</label>
                                    @php($mVal = old('media_type', $prefill['media_type'] ?? ''))
                                    @php($mOpts = $quoteOptions['media_types'])
                                    <select id="qf-media-type" name="media_type" aria-describedby="qf-media-hint" class="mt-1 w-full max-w-xl rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                        <option value="" @selected($mVal === '' || $mVal === null)>Select format (optional)</option>
                                        @foreach ($mOpts as $opt)
                                            @if ($opt === '') @continue @endif
                                            <option value="{{ $opt }}" @selected($mVal === $opt)>{{ $opt }}</option>
                                        @endforeach
                                        @if ($mVal !== '' && $mVal !== null && ! in_array($mVal, $mOpts, true))
                                            <option value="{{ $mVal }}" selected>{{ $mVal }}</option>
                                        @endif
                                    </select>
                                    <p class="mt-1 text-xs text-[#7a6962]" id="qf-media-hint">Unsure? Choose “Mixed formats” and we will advise.</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="qf-slug" class="mb-1 block text-sm font-bold text-[#3b2525]">Internal reference (optional)</label>
                                    <input id="qf-slug" name="campaign_slug" value="{{ old('campaign_slug', $prefill['campaign'] ?? '') }}" type="text" maxlength="255" aria-describedby="qf-slug-hint" class="mt-1 w-full max-w-xl rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                    <p class="mt-1 text-xs text-[#7a6962]" id="qf-slug-hint">Campaign name, procurement code, or PO — helps procurement teams match inbound mail.</p>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Budget / duration --}}
                        <fieldset class="em-card rounded-2xl border border-[#e8d4c8] bg-white p-5 shadow-sm sm:p-7">
                            <legend class="float-left w-full border-b border-[#f0e3d8] pb-3 text-sm font-black uppercase tracking-[0.12em] text-[#8b1e1a]">4. Budget &amp; timeline</legend>
                            <div class="clear-both grid gap-5 pt-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <label for="qf-budget" class="mb-1 block text-sm font-bold text-[#3b2525]">Planned investment band</label>
                                    @php($bVal = old('budget_range', $prefill['budget_range'] ?? $prefill['estimate'] ?? ''))
                                    @php($bOpts = $quoteOptions['budget_ranges'])
                                    <select id="qf-budget" name="budget_range" aria-describedby="qf-budget-hint" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                        <option value="" @selected($bVal === '' || $bVal === null)>Select range (optional)</option>
                                        @foreach ($bOpts as $opt)
                                            @if ($opt === '') @continue @endif
                                            <option value="{{ $opt }}" @selected($bVal === $opt)>{{ $opt }}</option>
                                        @endforeach
                                        @if ($bVal !== '' && $bVal !== null && ! in_array($bVal, $bOpts, true))
                                            <option value="{{ $bVal }}" selected>{{ $bVal }}</option>
                                        @endif
                                    </select>
                                    <p class="mt-1 text-xs text-[#7a6962]" id="qf-budget-hint">Indicative ranges keep expectations aligned ahead of placements.</p>
                                </div>
                                <div class="sm:col-span-1">
                                    <label for="qf-duration" class="mb-1 block text-sm font-bold text-[#3b2525]">Campaign duration</label>
                                    @php($dVal = old('campaign_duration', $prefill['duration'] ?? ''))
                                    @php($dOpts = $quoteOptions['durations'])
                                    <select id="qf-duration" name="campaign_duration" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">
                                        <option value="" @selected($dVal === '' || $dVal === null)>Select duration (optional)</option>
                                        @foreach ($dOpts as $opt)
                                            @if ($opt === '') @continue @endif
                                            <option value="{{ $opt }}" @selected($dVal === $opt)>{{ $opt }}</option>
                                        @endforeach
                                        @if ($dVal !== '' && $dVal !== null && ! in_array($dVal, $dOpts, true))
                                            <option value="{{ $dVal }}" selected>{{ $dVal }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Notes --}}
                        <fieldset class="em-card rounded-2xl border border-[#e8d4c8] bg-white p-5 shadow-sm sm:p-7">
                            <legend class="float-left w-full border-b border-[#f0e3d8] pb-3 text-sm font-black uppercase tracking-[0.12em] text-[#8b1e1a]">5. Notes &amp; creative context</legend>
                            <div class="clear-both pt-6">
                                <label for="qf-message" class="mb-1 block text-sm font-bold text-[#3b2525]">Anything else we should know?</label>
                                <textarea id="qf-message" name="message" rows="6" maxlength="5000" aria-describedby="qf-message-hint" placeholder="Sites you love, corridors to avoid, production deadlines, multilingual artwork…" class="mt-1 w-full rounded-lg border border-[#d7bca7] bg-white px-3 py-2.5 text-sm text-[#1a1010] placeholder:text-[#a09088] focus:border-[#8b1e1a] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a]/25">{{ old('message') }}</textarea>
                                <p class="mt-1 text-xs text-[#7a6962]" id="qf-message-hint">Optional — expands on the dropdown selections above.</p>
                            </div>
                        </fieldset>

                        <div class="hidden flex-col gap-4 border-t border-[#ece2d9] pt-6 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between lg:flex">
                            <p class="max-w-xl text-xs text-[#69564f]" id="quote-submit-legalese">{{ $qFormUi['legalese'] ?? '' }}</p>
                            <div class="flex flex-wrap items-center gap-3">
                                <button type="submit" class="em-btn-primary min-h-[48px] px-10" aria-describedby="quote-submit-legalese quote-form-help">
                                    {{ $qFormUi['submit_label'] ?? 'Transmit brief' }}
                                </button>
                                <a href="{{ route('contact') }}#details" class="text-sm font-semibold text-[#8b1e1a] underline-offset-4 hover:underline">{{ $qFormUi['contact_link_label'] ?? '' }}</a>
                            </div>
                        </div>
                    </form>

                    <div class="pointer-events-none fixed bottom-0 left-0 right-0 z-[35] flex justify-center px-4 pb-[calc(4.25rem+env(safe-area-inset-bottom,0px))] pt-2 lg:hidden">
                        <div class="pointer-events-auto w-full max-w-lg space-y-2 rounded-t-2xl border border-b-0 border-[#e0cdbd] bg-[#fffcf8]/98 p-4 shadow-[0_-8px_32px_rgba(0,0,0,0.08)] backdrop-blur-md">
                            <p class="text-[10px] leading-snug text-[#69564f]">{{ $qFormUi['legalese'] ?? '' }}</p>
                            <button type="submit" form="em-quote-form" class="em-btn-primary flex w-full min-h-[52px] items-center justify-center px-6 text-sm font-black" aria-describedby="quote-form-help">
                                {{ $qFormUi['submit_label'] ?? 'Transmit brief' }}
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Summary column --}}
                <aside class="relative mt-10 lg:mt-0 lg:max-w-none" aria-label="Campaign summary" data-quote-summary-panel>
                    <div class="lg:sticky lg:top-[calc(var(--em-header-offset)+24px)] lg:transition-[top] lg:duration-200">
                        <div class="overflow-hidden rounded-2xl border border-[#dfd0c5] bg-gradient-to-br from-[#fffdfb] via-white to-[#fff4ec] shadow-[0_24px_60px_-40px_rgba(120,52,41,0.55)] ring-1 ring-[#ecdac8]/80">
                            <div class="border-b border-[#f3e7dd] bg-[#5c1514] px-5 py-4 text-white">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#fdeee3]/80">{{ $qSummaryCopy['heading'] ?? 'Live brief mirror' }}</p>
                                <p class="mt-2 text-lg font-black leading-tight text-white">{{ $qSummaryCopy['panel_title'] ?? '' }}</p>
                                <p class="mt-1 text-xs leading-snug text-[#fdeee3]/90">{{ $qSummaryCopy['hint'] ?? 'Selections update instantly as you work through the guided steps.' }}</p>
                            </div>
                            <dl class="space-y-4 px-5 py-6 text-sm text-[#2e1d1d]">
                                <div class="rounded-xl border border-[#f5e9df] bg-white/95 px-3 py-2.5 shadow-sm">
                                    <dt class="text-[11px] font-bold uppercase tracking-wide text-[#8b1e1a]">{{ $qSummaryLabels['county'] ?? 'County priority' }}</dt>
                                    <dd class="mt-1 font-semibold text-[#1f1111]" id="quote-summary-county">—</dd>
                                </div>
                                <div class="rounded-xl border border-[#f5e9df] bg-white/95 px-3 py-2.5 shadow-sm">
                                    <dt class="text-[11px] font-bold uppercase tracking-wide text-[#8b1e1a]">{{ $qSummaryLabels['location'] ?? 'Town / corridor' }}</dt>
                                    <dd class="mt-1 font-semibold text-[#1f1111]" id="quote-summary-location">—</dd>
                                </div>
                                <div class="rounded-xl border border-[#f5e9df] bg-white/95 px-3 py-2.5 shadow-sm">
                                    <dt class="text-[11px] font-bold uppercase tracking-wide text-[#8b1e1a]">Preferred media</dt>
                                    <dd class="mt-1 font-semibold leading-snug text-[#1f1111]" id="quote-summary-media">—</dd>
                                </div>
                                <div class="rounded-xl border border-[#f5e9df] bg-white/95 px-3 py-2.5 shadow-sm">
                                    <dt class="text-[11px] font-bold uppercase tracking-wide text-[#8b1e1a]">{{ $qSummaryLabels['budget'] ?? 'Spend envelope' }}</dt>
                                    <dd class="mt-1 font-semibold leading-snug text-[#1f1111]" id="quote-summary-budget">—</dd>
                                </div>
                                <div class="rounded-xl border border-[#f5e9df] bg-white/95 px-3 py-2.5 shadow-sm">
                                    <dt class="text-[11px] font-bold uppercase tracking-wide text-[#8b1e1a]">{{ $qSummaryLabels['duration'] ?? 'Flight length' }}</dt>
                                    <dd class="mt-1 font-semibold leading-snug text-[#1f1111]" id="quote-summary-duration">—</dd>
                                </div>
                            </dl>
                            <div class="border-t border-[#f5e9df] bg-[#fffcf8] px-5 py-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#69332c]">{{ $qFormUi['aside_help_title'] ?? '' }}</p>
                                <p class="mt-2 text-sm leading-snug text-[#4b3531]">{{ $qFormUi['aside_help_body'] ?? '' }}</p>
                                <a href="{{ $whatsappHrefQuote }}" target="_blank" rel="noopener noreferrer" class="mt-4 flex min-h-[48px] w-full items-center justify-center rounded-xl bg-[#25D366] px-4 text-sm font-bold text-white shadow-sm transition hover:brightness-[1.05]" data-track-event="whatsapp_clicked" data-track-location="quote_summary">
                                    {{ $qFormUi['whatsapp_button'] ?? '' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.emTrackEvent) {
                    window.emTrackEvent('quote_started', {
                        path: window.location.pathname,
                        query: Object.fromEntries(new URLSearchParams(window.location.search).entries()),
                    });
                }

                const form = document.getElementById('em-quote-form');
                if (!form) return;

                const escId = (typeof CSS !== 'undefined' && typeof CSS.escape === 'function')
                    ? (s) => CSS.escape(s)
                    : (s) => s;
                const labelFor = (id) => form.querySelector(`#${escId(id)}`);

                const readSelectCaption = (id) => {
                    const el = labelFor(id);
                    if (!(el instanceof HTMLSelectElement)) return '—';
                    const opt = el.options[el.selectedIndex];
                    if (!opt || opt.value === '') return 'Not specified yet';
                    return opt.textContent?.trim() || '—';
                };

                const readInput = (id) => {
                    const el = labelFor(id);
                    if (!(el instanceof HTMLInputElement) && !(el instanceof HTMLTextAreaElement)) return '—';
                    const v = (el.value || '').trim();

                    return v === '' ? 'Not specified yet' : v;
                };

                const syncSummary = () => {
                    const set = (id, text) => {
                        const n = document.getElementById(id);
                        if (n) n.textContent = text;
                    };
                    set('quote-summary-county', readSelectCaption('qf-county'));
                    set('quote-summary-location', readInput('qf-location'));
                    set('quote-summary-media', readSelectCaption('qf-media-type'));
                    set('quote-summary-budget', readSelectCaption('qf-budget'));
                    set('quote-summary-duration', readSelectCaption('qf-duration'));
                };

                ['input', 'change'].forEach((evt) => form.addEventListener(evt, syncSummary));
                syncSummary();

                const err = document.getElementById('quote-form-errors');
                if (err) err.focus();
            });
        </script>
    @endpush
@endsection
