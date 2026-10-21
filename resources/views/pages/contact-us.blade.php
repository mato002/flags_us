@extends('layouts.website')

@section('title', $websitePage['title'] ?? null)
@section('meta_description', $websitePage['meta_description'] ?? '')

@section('content')
    @include('components.em-leaflet-loader-inline')

    @php($contactHeroBg = asset('profile-gallery/profile-page-5.jpg'))
    <section class="relative isolate min-h-[32vh] overflow-hidden md:min-h-[34vh]">
        <div class="pointer-events-none absolute inset-0 z-0 bg-[#1a1210]" aria-hidden="true">
            <img src="{{ $contactHeroBg }}" alt="" width="1600" height="900" class="h-full min-h-[32vh] w-full object-cover object-center md:min-h-[34vh]" loading="eager" fetchpriority="high" decoding="async">
        </div>
        <div class="pointer-events-none absolute inset-0 z-[1] bg-[linear-gradient(115deg,rgba(92,21,20,0.9),rgba(92,21,20,0.75),rgba(23,23,23,0.66))]"></div>
        <div class="em-container relative z-10 py-16 lg:py-20">
            <x-ui.section-heading label="Contact Us" title="Let's Plan Your Next Outdoor Campaign" description="Reach the desk directly — general enquiries submit here without leaving the site. Prefer a structured brief? Jump to the full quotation form anytime." light="true" />
        </div>
    </section>

    <section id="details" class="em-container py-14">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui.contact-card label="Phone" :value="implode(' / ', $profileContent['contacts']['phones'] ?? [])" />
            <x-ui.contact-card label="Email" :value="$profileContent['contacts']['email'] ?? ''" />
            <x-ui.contact-card label="Location" :value="$profileContent['contacts']['office'] ?? 'Nakuru, Kenya'" />
            <x-ui.contact-card label="Working Hours" value="Mon - Sat, 8:00am - 6:00pm" />
        </div>
    </section>

    <section class="em-container pb-16 lg:pb-24">
        <div class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
            <div id="form" class="em-card em-card-accent scroll-mt-[calc(var(--em-header-offset)+12px)] p-6 sm:p-8">
                <h2 class="text-xl font-bold text-[#5c1514]">Enquiry form</h2>
                <p class="mt-2 text-sm leading-relaxed text-[#57453f]">Same pipeline as quotations: your message saves to our desk and notifies the team immediately. Fields marked <span class="font-semibold text-[#8b1e1a]">*</span> are required.</p>

                @if ($errors->any())
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('quote.store') }}" class="mt-6 grid gap-4">
                    @csrf
                    <input type="hidden" name="source" value="website_contact_form">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <label for="cx-name" class="mb-1 block text-xs font-bold uppercase tracking-wide text-[#5c1514]">Full name <span class="text-rose-600">*</span></label>
                            <input id="cx-name" name="full_name" value="{{ old('full_name') }}" type="text" required autocomplete="name" class="w-full rounded-md border border-[#d7bca7] px-3 py-2.5 text-sm focus:border-[#8b1e1a] focus:outline-none focus:ring-1 focus:ring-[#8b1e1a]">
                        </div>
                        <div class="sm:col-span-1">
                            <label for="cx-phone" class="mb-1 block text-xs font-bold uppercase tracking-wide text-[#5c1514]">Phone <span class="text-rose-600">*</span></label>
                            <input id="cx-phone" name="phone" value="{{ old('phone') }}" type="tel" required autocomplete="tel" class="w-full rounded-md border border-[#d7bca7] px-3 py-2.5 text-sm focus:border-[#8b1e1a] focus:outline-none focus:ring-1 focus:ring-[#8b1e1a]">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="cx-email" class="mb-1 block text-xs font-bold uppercase tracking-wide text-[#5c1514]">Email <span class="text-rose-600">*</span></label>
                            <input id="cx-email" name="email" value="{{ old('email') }}" type="email" required autocomplete="email" class="w-full rounded-md border border-[#d7bca7] px-3 py-2.5 text-sm focus:border-[#8b1e1a] focus:outline-none focus:ring-1 focus:ring-[#8b1e1a]">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="cx-company" class="mb-1 block text-xs font-bold uppercase tracking-wide text-[#5c1514]">Company</label>
                            <input id="cx-company" name="company_name" value="{{ old('company_name') }}" type="text" class="w-full rounded-md border border-[#d7bca7] px-3 py-2.5 text-sm focus:border-[#8b1e1a] focus:outline-none focus:ring-1 focus:ring-[#8b1e1a]">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="cx-topic" class="mb-1 block text-xs font-bold uppercase tracking-wide text-[#5c1514]">Topic</label>
                            <input id="cx-topic" name="campaign_objective" value="{{ old('campaign_objective') }}" type="text" placeholder="e.g. site availability, partnership, servicing" class="w-full rounded-md border border-[#d7bca7] px-3 py-2.5 text-sm focus:border-[#8b1e1a] focus:outline-none focus:ring-1 focus:ring-[#8b1e1a]">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="cx-msg" class="mb-1 block text-xs font-bold uppercase tracking-wide text-[#5c1514]">Message</label>
                            <textarea id="cx-msg" name="message" rows="6" placeholder="Tell us what you need. If you already know county/town/media type, drop it here." class="w-full rounded-md border border-[#d7bca7] px-3 py-2.5 text-sm focus:border-[#8b1e1a] focus:outline-none focus:ring-1 focus:ring-[#8b1e1a]">{{ old('message') }}</textarea>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 border-t border-[#f0e3d8] pt-5">
                        <button type="submit" class="em-btn-primary min-h-[44px] px-6" data-track-event="contact_submit">Submit enquiry</button>
                        <a href="{{ route('quote') }}#quote-form" class="text-sm font-semibold text-[#8b1e1a] underline-offset-4 hover:underline">Need line-item pricing → full quote form</a>
                    </div>
                </form>
            </div>

            <div class="em-card em-card-accent p-6">
                <h2 class="text-xl font-bold text-[#5c1514]">Office Details</h2>
                <div class="mt-5 space-y-3 text-sm text-[#505050]">
                    <p><span class="font-semibold text-[#8b1e1a]">Company:</span> {{ $portalBrandName ?? 'Effective Media' }}</p>
                    <p><span class="font-semibold text-[#8b1e1a]">Address:</span> {{ $profileContent['contacts']['office'] ?? 'Nakuru, Kenya' }}</p>
                    <p><span class="font-semibold text-[#8b1e1a]">Email:</span> {{ $profileContent['contacts']['email'] ?? '' }}</p>
                    <p><span class="font-semibold text-[#8b1e1a]">Phone:</span> {{ implode(' / ', $profileContent['contacts']['phones'] ?? []) }}</p>
                    <p><span class="font-semibold text-[#8b1e1a]">Website:</span> {{ $profileContent['contacts']['website'] ?? '' }}</p>
                </div>
                <a href="{{ $whatsappHrefQuote }}" target="_blank" rel="noopener noreferrer" class="mt-6 inline-flex w-full min-h-[44px] items-center justify-center rounded-md border border-[#128C7E]/40 bg-[#128C7E]/10 px-4 py-3 text-sm font-bold text-[#0d6e64] transition hover:bg-[#128C7E]/15" data-track-event="whatsapp_clicked">Message us on WhatsApp</a>
            </div>
        </div>
    </section>

    <section id="location" class="em-container pb-28 max-lg:pb-[7.75rem] lg:pb-24">
        <div class="em-card em-card-accent p-6">
            <h2 class="text-xl font-bold text-[#5c1514]">Find Us on Map</h2>
            <p class="mt-2 text-sm text-[#555]">Interactive office location map powered by Leaflet and OpenStreetMap.</p>
            <div id="office-map" class="mt-4 min-h-[260px] h-[min(24rem,52vh)] w-full touch-manipulation rounded-lg border border-[#d8bdaa] sm:h-96"></div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mapElement = document.getElementById('office-map');
            if (!mapElement || typeof window.emLoadLeaflet !== 'function') return;

            const initOfficeMap = () => {
                const officeCoords = [-1.286389, 36.817223];
                const map = L.map(mapElement).setView(officeCoords, 13);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                }).addTo(map);

                const brandName = @json($portalBrandName ?? 'Effective Media');

                L.marker(officeCoords)
                    .addTo(map)
                    .bindPopup(`<strong>${brandName}</strong><br>Nairobi, Kenya`)
                    .openPopup();
            };

            const start = () =>
                window
                    .emLoadLeaflet({ withMarkerCluster: false })
                    .then(() => requestAnimationFrame(initOfficeMap))
                    .catch((err) => console.warn('Office map failed to load', err));

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
                io.observe(mapElement);
            } else {
                start();
            }
        });
    </script>
@endsection
