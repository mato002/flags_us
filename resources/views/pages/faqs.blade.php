@extends('layouts.website')

@section('title')
    {{ $websitePage['title'] ?? '' }}
@endsection

@section('meta_description')
    {{ $websitePage['meta_description'] ?? '' }}
@endsection

@section('content')
    @php($faqHeroBg = asset('profile-gallery/profile-page-3.jpg'))
    <section class="relative isolate min-h-[32vh] overflow-hidden md:min-h-[34vh]">
        <div class="pointer-events-none absolute inset-0 z-0 bg-[#1a1210]" aria-hidden="true">
            <img src="{{ $faqHeroBg }}" alt="" width="2000" height="1125" class="h-full min-h-[32vh] w-full object-cover object-center opacity-95 md:min-h-[34vh]" loading="eager" fetchpriority="high" decoding="async">
        </div>
        <div class="pointer-events-none absolute inset-0 z-[1] bg-[linear-gradient(115deg,rgba(92,21,20,0.9),rgba(92,21,20,0.75),rgba(23,23,23,0.66))]"></div>
        <div class="em-container relative z-10 py-16 lg:py-20">
            <x-ui.section-heading label="FAQs" title="Frequently Asked Questions" description="Organised by topic — from pricing and artworks to deployments, upkeep, invoicing and flight length. Profile FAQs appear under General." light="true" />
        </div>
    </section>

    @php
        $faqLibrary = [
            'Pricing' => [
                ['q' => 'How is street‑light pricing structured?', 'a' => 'Rates are pole‑based packages with tiers for deployment duration (1, 3, 6 months and longer). Bulk corridors and sequencing attract better blended rates—we quote after county and corridor selection.'],
                ['q' => 'Do billboard costs include printing?', 'a' => 'Print, mount and proofing can be bundled or billed separately depending on substrate and turnaround. Estimates spell out fabrication so finance teams can reconcile POs cleanly.'],
            ],
            'Artwork' => [
                ['q' => 'What artwork formats do you need?', 'a' => 'Vector PDF or AI exports with outlined fonts preferred; raster art at print resolution plus 10mm bleed. We issue a sizing grid per SKU so designers lock safe zones before production.'],
                ['q' => 'Who approves final creative?', 'a' => 'Client sign‑off triggers production. Effective Media verifies legibility from typical viewing distances and lighting before release to print partners.'],
            ],
            'Sites' => [
                ['q' => 'How quickly can we light up inventory?', 'a' => 'Corridors inside mapped municipalities typically deploy faster than wholly new procurements. We confirm blackout dates around events or county works before locking flight dates.'],
                ['q' => 'Can we refresh locations mid‑flight?', 'a' => 'Yes—subject to availability and contractual notice. Charges reflect demount/remount logistics and printing if creative changes materially.'],
            ],
            'Maintenance' => [
                ['q' => 'What upkeep is covered operationally?', 'a' => 'Scheduled inspections, lamp/power checks and storm damage ticketing are routed through Effective Media crews or partner electricians depending on locality. SLA terms sit in master agreements.'],
                ['q' => 'Who handles vandalised faces?', 'a' => 'We document site condition on install and escalate repairs; replacement charges follow insurance or maintenance clauses agreed per campaign.'],
            ],
            'Billing' => [
                ['q' => 'Which payment rhythms do you accept?', 'a' => 'Local corporates often pay 50–100% upfront on flight start with balance at midpoint for longer durations. Institutional clients can negotiate milestone billing tied to trafficking reports.'],
                ['q' => 'Do invoices include withholding tax notes?', 'a' => 'Yes—where statutory deductions apply they are surfaced line‑by‑line with supporting references for AP teams.'],
            ],
            'Campaign Duration' => [
                ['q' => 'What durations perform best?', 'a' => 'Urban commuter routes gain frequency by month three; FMCG bursts may compress to 4–6 weeks with higher pole density instead of elongating a thin spread. Planner outputs suggest mixes by objective.'],
                ['q' => 'Extensions and renewals', 'a' => 'Rolling renewals honour pole hold fees when inventory is contested. Renewal quotes reference live availability rather than blindly repricing incumbent packages.'],
            ],
        ];
        $profileFaqs = collect($profileContent['faqs'] ?? [])->take(24);
    @endphp

    <section class="em-container space-y-10 py-14 lg:py-20">
        @if ($profileFaqs->isNotEmpty())
            <section class="scroll-mt-28" aria-labelledby="faq-general">
                <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 id="faq-general" class="text-xl font-black text-[#5c1514] sm:text-2xl">General (from profile)</h2>
                        <p class="mt-1 text-sm text-[#6a5a53]">Pulled directly from Effective Media briefing materials maintained in YAML.</p>
                    </div>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    @foreach ($profileFaqs as $faq)
                        <details class="em-card em-card-accent group p-5 open:border-[#cfab96]">
                            <summary class="cursor-pointer text-base font-bold text-[#5c1514] marker:text-[#8b1e1a]">{{ $faq['question'] }}</summary>
                            <p class="mt-3 text-sm leading-relaxed text-[#4f4f4f]">{{ $faq['answer'] }}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        @endif

        @foreach ($faqLibrary as $category => $items)
            <section class="scroll-mt-28" id="faq-{{ \Illuminate\Support\Str::slug($category) }}" aria-labelledby="faq-cat-{{ \Illuminate\Support\Str::slug($category) }}">
                <div class="mb-5">
                    <h2 id="faq-cat-{{ \Illuminate\Support\Str::slug($category) }}" class="text-xl font-black text-[#5c1514] sm:text-2xl">{{ $category }}</h2>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    @foreach ($items as $row)
                        <details class="em-card em-card-accent p-5">
                            <summary class="cursor-pointer text-base font-bold text-[#5c1514]">{{ $row['q'] }}</summary>
                            <p class="mt-3 text-sm leading-relaxed text-[#4f4f4f]">{{ $row['a'] }}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        @endforeach
    </section>
@endsection
