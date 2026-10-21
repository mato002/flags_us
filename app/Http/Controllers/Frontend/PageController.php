<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use App\Models\ProfileDownloadRequest;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Services\Cms\HomepageContentService;
use App\Services\Cms\WebsitePageSectionsService;
use App\Services\Portal\PortalSettingsService;
use App\Services\Seo\WebsiteSeoService;
use App\Support\CachedSiteProfile;
use App\Support\PublicServicePresentation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PageController extends Controller
{
    /**
     * Outdoor media services showcased on the website.
     *
     * @var array<int, string>
     */
    private array $defaultServices = [
        'Billboards',
        'Digital Screens',
        'Street Pole Advertising',
        'Vehicle Branding',
        'Airport Branding',
        'Petrol Station Branding',
        'Retail Branding',
        '3D Signage',
        'LED Signage',
        'Wall Branding',
        'Bus Shelter Advertising',
    ];

    public function __construct(
        private readonly HomepageContentService $homepageContentService,
        private readonly WebsitePageSectionsService $websitePageSectionsService,
        private readonly WebsiteSeoService $websiteSeoService,
    ) {}

    /**
     * @return array{websitePage: array<string, mixed>, pageSeo: array<string, mixed>, seoJsonLd: list<array<string, mixed>>}
     */
    private function cmsSeoPayload(string $slug, array $seoOverrides = [], array $extraJsonLd = []): array
    {
        $websitePage = $this->websitePageSectionsService->resolve($slug);
        $pageSeo = $this->websiteSeoService->resolve($websitePage, $seoOverrides);
        $seoJsonLd = array_values(array_filter([
            ...$this->websiteSeoService->organizationJsonLd(),
            ...$extraJsonLd,
        ]));

        return [
            'websitePage' => $websitePage,
            'pageSeo' => $pageSeo,
            'seoJsonLd' => $seoJsonLd,
        ];
    }

    public function home(): View
    {
        $payload = $this->homepageContentService->getHomepagePayload();
        $companyProfiles = $this->getCompanyProfiles();
        $profileContent = CachedSiteProfile::content();

        $services = $payload['services']->isNotEmpty() ? $payload['services'] : collect(
            array_map(static fn (string $service): array => ['title' => $service], $this->defaultServices)
        );

        return view('pages.home', [
            ...$payload,
            ...$this->cmsSeoPayload('home'),
            'services' => $services,
            'companyProfiles' => $companyProfiles,
            'profileContent' => $profileContent,
        ]);
    }

    public function whoWeAre(): View
    {
        return view('pages.who-we-are', [
            ...$this->cmsSeoPayload('who-we-are'),
            'profileContent' => CachedSiteProfile::content(),
            'companyProfiles' => $this->getCompanyProfiles(),
        ]);
    }

    public function whatWeDo(): View
    {
        $services = $this->publicServicesForExplorer();

        return view('pages.what-we-do', [
            ...$this->cmsSeoPayload('what-we-do'),
            'serviceExplorer' => PublicServicePresentation::map($services),
            'companyProfiles' => $this->getCompanyProfiles(),
            'profileContent' => CachedSiteProfile::content(),
        ]);
    }

    public function serviceShowcase(string $serviceSlug): View
    {
        $profileContent = CachedSiteProfile::content();
        $serviceDetails = $profileContent['service_details'] ?? [];

        $serviceLibrary = [
            'street-light-advertising' => [
                'title' => 'Street Light Advertising',
                'kicker' => 'Signature Media Infrastructure',
                'headline' => 'Command Everyday Visibility Across Commuter Corridors',
                'description' => Arr::get($serviceDetails, 'Street Light Advertising.description', 'Street Light Box campaigns deliver repeated, route-level brand exposure where people move daily.'),
                'hero_image' => Arr::get($serviceDetails, 'Street Light Advertising.image') ? asset('profile-gallery/'.Arr::get($serviceDetails, 'Street Light Advertising.image')) : 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=1800&q=80',
                'metrics' => [
                    ['label' => 'Installed Poles', 'value' => '730+'],
                    ['label' => 'Counties', 'value' => '7+'],
                    ['label' => 'Daily Visibility Window', 'value' => '12-16h'],
                ],
                'use_cases' => ['Product launches', 'Brand recall campaigns', 'Commuter route targeting'],
                'industries' => ['FMCG', 'Telecom', 'Financial Services', 'Education'],
                'campaign_example' => 'Commuter-focused FMCG campaign: 30 poles across Nakuru CBD for 3 months.',
            ],
            'billboards' => [
                'title' => 'Billboards',
                'kicker' => 'Large Format Dominance',
                'headline' => 'Scale Awareness at Highway and Landmark Speed',
                'description' => Arr::get($serviceDetails, 'Billboards.description', 'Billboards establish strong top-of-mind awareness by owning high-traffic road frontage and strategic urban points.'),
                'hero_image' => Arr::get($serviceDetails, 'Billboards.image') ? asset('profile-gallery/'.Arr::get($serviceDetails, 'Billboards.image')) : 'https://images.unsplash.com/photo-1494522358652-f30e61a60313?auto=format&fit=crop&w=1800&q=80',
                'metrics' => [
                    ['label' => 'Highway Nodes', 'value' => '40+'],
                    ['label' => 'Urban Anchors', 'value' => '20+'],
                    ['label' => 'Visibility Range', 'value' => 'Long Distance'],
                ],
                'use_cases' => ['City launch domination', 'Route takeovers', 'Brand authority building'],
                'industries' => ['Real Estate', 'Retail', 'Automotive', 'Insurance'],
                'campaign_example' => 'Retail expansion blitz around interchanges with 24/7 visibility impact.',
            ],
            'pavement-ads' => [
                'title' => 'Pavement Ads',
                'kicker' => 'Street-Level Visibility',
                'headline' => 'Capture Pedestrian and Mixed Traffic Attention',
                'description' => Arr::get($serviceDetails, 'Pavement Ads.description', 'Pavement advertising brings brands closer to daily urban activity and point-of-decision movement.'),
                'hero_image' => Arr::get($serviceDetails, 'Pavement Ads.image') ? asset('profile-gallery/'.Arr::get($serviceDetails, 'Pavement Ads.image')) : 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=1800&q=80',
                'metrics' => [
                    ['label' => 'CBD Zones', 'value' => '18+'],
                    ['label' => 'Pedestrian Reach', 'value' => 'High'],
                    ['label' => 'Deployment Speed', 'value' => 'Fast'],
                ],
                'use_cases' => ['Weekend offers', 'Retail footfall', 'Event support'],
                'industries' => ['Retail', 'Hospitality', 'Events', 'SMEs'],
                'campaign_example' => 'CBD weekend retail campaign driving in-store footfall and offer recall.',
            ],
            'office-branding' => [
                'title' => 'Office Branding',
                'kicker' => 'Built Environment Identity',
                'headline' => 'Turn Workspaces Into Brand Trust Signals',
                'description' => Arr::get($serviceDetails, 'Office Branding.description', 'Office branding aligns space, message, and customer confidence through coherent visual systems.'),
                'hero_image' => Arr::get($serviceDetails, 'Office Branding.image') ? asset('profile-gallery/'.Arr::get($serviceDetails, 'Office Branding.image')) : 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=1800&q=80',
                'metrics' => [
                    ['label' => 'Branch Rollouts', 'value' => '50+'],
                    ['label' => 'Execution Scope', 'value' => 'Interior + Exterior'],
                    ['label' => 'Brand Consistency', 'value' => 'High'],
                ],
                'use_cases' => ['Branch refresh', 'Wayfinding systems', 'Experience upgrades'],
                'industries' => ['Banking', 'Healthcare', 'Education', 'Corporate'],
                'campaign_example' => 'Multi-branch identity refresh with standardized customer touchpoints.',
            ],
            'activations-roadshows' => [
                'title' => 'Activations & Roadshows',
                'kicker' => 'On-Ground Campaign Execution',
                'headline' => 'Create Momentum Through Live Brand Interaction',
                'description' => Arr::get($serviceDetails, 'Activations & Roadshows.description', 'Roadshows and activations combine movement, audience interaction, and conversion opportunities in real-world environments.'),
                'hero_image' => Arr::get($serviceDetails, 'Activations & Roadshows.image') ? asset('profile-gallery/'.Arr::get($serviceDetails, 'Activations & Roadshows.image')) : 'https://images.unsplash.com/photo-1472653431158-6364773b2a56?auto=format&fit=crop&w=1800&q=80',
                'metrics' => [
                    ['label' => 'Counties Activated', 'value' => '12+'],
                    ['label' => 'Audience Engagement', 'value' => 'Live'],
                    ['label' => 'Campaign Lift', 'value' => 'Fast'],
                ],
                'use_cases' => ['Sampling tours', 'Launch caravans', 'Awareness road drives'],
                'industries' => ['Beverages', 'Consumer Goods', 'Fintech', 'Telecom'],
                'campaign_example' => 'Regional sampling circuit with route support media and lead capture.',
            ],
            'roll-up-banners' => [
                'title' => 'Roll-up Banners',
                'kicker' => 'Portable Support Media',
                'headline' => 'Deploy Campaign Messaging Anywhere, Fast',
                'description' => Arr::get($serviceDetails, 'Roll-up Banners.description', 'Roll-up systems support launches, events, and field teams with clean, portable brand visibility.'),
                'hero_image' => Arr::get($serviceDetails, 'Roll-up Banners.image') ? asset('profile-gallery/'.Arr::get($serviceDetails, 'Roll-up Banners.image')) : 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=1800&q=80',
                'metrics' => [
                    ['label' => 'Deployment Time', 'value' => 'Minutes'],
                    ['label' => 'Event Utility', 'value' => 'High'],
                    ['label' => 'Cost Efficiency', 'value' => 'Strong'],
                ],
                'use_cases' => ['Trade fairs', 'POS support', 'Corporate events'],
                'industries' => ['SMEs', 'NGOs', 'Events', 'Corporate'],
                'campaign_example' => 'Trade expo support kit ensuring consistent booth-level visibility.',
            ],
        ];

        abort_unless(array_key_exists($serviceSlug, $serviceLibrary), 404);

        $service = $serviceLibrary[$serviceSlug];
        $pageSeo = $this->websiteSeoService->resolve(null, [
            'title' => $service['title'].' | Effective Media',
            'description' => (string) ($service['description'] ?? ''),
            'og_image' => (string) ($service['hero_image'] ?? ''),
            'og_type' => 'website',
        ]);
        $serviceSchema = $this->websiteSeoService->serviceJsonLd($service);
        if (($serviceSchema['image'] ?? '') === '') {
            unset($serviceSchema['image']);
        }

        return view('pages.service-showcase', [
            'service' => $service,
            'serviceSlug' => $serviceSlug,
            'profileContent' => $profileContent,
            'pageSeo' => $pageSeo,
            'seoJsonLd' => array_values(array_filter([
                ...$this->websiteSeoService->organizationJsonLd(),
                $serviceSchema,
                $this->websiteSeoService->breadcrumbJsonLd([
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'What We Do', 'url' => route('what-we-do')],
                    ['name' => $service['title'], 'url' => route('services.show', ['serviceSlug' => $serviceSlug])],
                ]),
            ])),
        ]);
    }

    public function portfolio(): View
    {
        $items = PortfolioItem::query()
            ->select([
                'id',
                'title',
                'client_name',
                'category',
                'campaign_location',
                'media_type',
                'description',
                'image_path',
                'gallery_paths',
                'sort_order',
                'is_published',
            ])
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(48)
            ->get()
            ->unique(function (PortfolioItem $item): string {
                $key = $this->portfolioImageKey((string) ($item->image_path ?? ''));

                return $key !== '' ? $key : 'id:'.$item->id;
            })
            ->values();

        $portfolioGrid = $this->buildPortfolioGrid($items);

        return view('pages.portfolio', [
            ...$this->cmsSeoPayload('portfolio'),
            'items' => $items,
            'portfolioGrid' => $portfolioGrid,
            'companyProfiles' => $this->getCompanyProfiles(),
            'profileContent' => CachedSiteProfile::content(),
        ]);
    }

    /**
     * Flatten primary portfolio rows plus unique gallery images into masonry rows.
     *
     * @param  Collection<int, PortfolioItem>  $items
     * @return Collection<int, array{type: string, item: PortfolioItem, gallery_index?: int, gallery_path?: string}>
     */
    private function buildPortfolioGrid(Collection $items): Collection
    {
        $seenKeys = [];
        $rows = collect();

        foreach ($items as $item) {
            $mainKey = $this->portfolioImageKey((string) ($item->image_path ?? ''));
            if ($mainKey !== '') {
                $seenKeys[$mainKey] = true;
            }

            $rows->push(['type' => 'primary', 'item' => $item]);

            $gallery = is_array($item->gallery_paths) ? $item->gallery_paths : [];
            foreach ($gallery as $idx => $raw) {
                $path = is_string($raw) ? trim($raw) : '';
                if ($path === '') {
                    continue;
                }

                $key = $this->portfolioImageKey($path);
                if ($key === '' || $key === $mainKey || isset($seenKeys[$key])) {
                    continue;
                }

                $seenKeys[$key] = true;

                $rows->push([
                    'type' => 'gallery',
                    'item' => $item,
                    'gallery_index' => (int) $idx,
                    'gallery_path' => $path,
                ]);
            }
        }

        return $rows;
    }

    private function portfolioImageKey(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $part = parse_url($path, PHP_URL_PATH);

            return strtolower((string) ($part !== null && $part !== '' ? $part : $path));
        }

        return strtolower(ltrim($path, '/'));
    }

    public function coverage(): View
    {
        return view('pages.coverage', [
            ...$this->cmsSeoPayload('coverage'),
            'profileContent' => CachedSiteProfile::content(),
        ]);
    }

    public function campaignPlanner(): View
    {
        return view('pages.smart-campaign-planner', [
            ...$this->cmsSeoPayload('smart-campaign-planner'),
            'profileContent' => CachedSiteProfile::content(),
        ]);
    }

    public function contactUs(): View
    {
        return view('pages.contact-us', [
            ...$this->cmsSeoPayload('contact-us', [
                'canonical' => route('contact-us'),
            ]),
            'profileContent' => CachedSiteProfile::content(),
        ]);
    }

    public function quote(Request $request): View
    {
        return view('pages.quote', [
            ...$this->cmsSeoPayload('quote'),
            'profileContent' => CachedSiteProfile::content(),
            'prefill' => $request->only([
                'location',
                'county',
                'industry',
                'campaign_objective',
                'target_audience',
                'budget_range',
                'duration',
                'media_type',
                'campaign',
                'poles',
                'printing',
                'estimate',
            ]),
            'quoteOptions' => [
                'counties' => config('quote.counties', []),
                'industries' => config('quote.industries', []),
                'campaign_objectives' => config('quote.campaign_objectives', []),
                'target_audiences' => config('quote.target_audiences', []),
                'media_types' => config('quote.media_types', []),
                'budget_ranges' => config('quote.budget_ranges', []),
                'durations' => config('quote.durations', []),
            ],
        ]);
    }

    public function storeQuote(Request $request): RedirectResponse
    {
        $this->normalizeEmptyQuoteSelects($request);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'county' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'campaign_objective' => ['nullable', 'string', 'max:255'],
            'target_audience' => ['nullable', 'string', 'max:255'],
            'media_type' => ['nullable', 'string', 'max:255'],
            'campaign_duration' => ['nullable', 'string', 'max:255'],
            'budget_range' => ['nullable', 'string', 'max:255'],
            'campaign_slug' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
            'source' => ['nullable', 'string', 'max:100'],
        ]);

        if (Schema::hasTable('quote_requests')) {
            QuoteRequest::query()->create([
                ...$validated,
                'source' => $validated['source'] ?? 'website_quote_form',
                'status' => 'new',
            ]);
        }

        $contacts = CachedSiteProfile::content()['contacts'] ?? [];
        $adminEmail = (is_array($contacts) ? ($contacts['email'] ?? null) : null) ?: config('mail.from.address');
        if (is_string($adminEmail) && $adminEmail !== '') {
            Mail::raw(
                "New quote request from {$validated['full_name']} ({$validated['email']}, {$validated['phone']}). Location: ".($validated['location'] ?? 'N/A').', Media: '.($validated['media_type'] ?? 'N/A').', Budget: '.($validated['budget_range'] ?? 'N/A'),
                static function ($message) use ($adminEmail): void {
                    $message->to($adminEmail)->subject('New Quote Request - Effective Media');
                }
            );
        }

        return redirect()
            ->route('quote.success')
            ->with('quote_submitted_flash', true);
    }

    public function quoteSuccess(): View
    {
        $filename = trim((string) config('quote.default_profile_pdf'));
        $profileViewRoute = $filename !== ''
            ? route('company-profile.view', ['filename' => $filename])
            : null;
        $profileDownloadRoute = $filename !== ''
            ? route('company-profile.download', ['filename' => $filename])
            : null;

        return view('pages.quote-success', [
            'profilePdfViewUrl' => $profileViewRoute,
            'profilePdfDownloadUrl' => $profileDownloadRoute,
            'quoteJustSubmitted' => (bool) session()->pull('quote_submitted_flash'),
            'pageSeo' => $this->websiteSeoService->resolve(null, [
                'title' => 'Quote request received | Effective Media',
                'description' => 'Thanks for your outdoor advertising quote brief. Speak to our team on WhatsApp or open the company profile while we prepare your proposal.',
                'robots' => (string) config('seo.noindex_robots'),
                'canonical' => route('quote.success'),
            ]),
            'seoJsonLd' => $this->websiteSeoService->organizationJsonLd(),
        ]);
    }

    private function normalizeEmptyQuoteSelects(Request $request): void
    {
        $keys = [
            'county',
            'industry',
            'campaign_objective',
            'target_audience',
            'media_type',
            'campaign_duration',
            'budget_range',
        ];
        foreach ($keys as $key) {
            if ($request->input($key) === '') {
                $request->merge([$key => null]);
            }
        }
    }

    public function faqs(): View
    {
        $profileContent = CachedSiteProfile::content();
        $profileFaqs = collect($profileContent['faqs'] ?? [])
            ->map(static fn ($row): array => [
                'question' => (string) ($row['question'] ?? ''),
                'answer' => (string) ($row['answer'] ?? ''),
            ])
            ->filter(static fn (array $row): bool => $row['question'] !== '' && $row['answer'] !== '')
            ->take(24)
            ->values()
            ->all();

        $libraryFaqs = [
            ['q' => 'How is street‑light pricing structured?', 'a' => 'Rates are pole‑based packages with tiers for deployment duration (1, 3, 6 months and longer). Bulk corridors and sequencing attract better blended rates—we quote after county and corridor selection.'],
            ['q' => 'Do billboard costs include printing?', 'a' => 'Print, mount and proofing can be bundled or billed separately depending on substrate and turnaround. Estimates spell out fabrication so finance teams can reconcile POs cleanly.'],
            ['q' => 'What artwork formats do you need?', 'a' => 'Vector PDF or AI exports with outlined fonts preferred; raster art at print resolution plus 10mm bleed. We issue a sizing grid per SKU so designers lock safe zones before production.'],
            ['q' => 'How quickly can we light up inventory?', 'a' => 'Corridors inside mapped municipalities typically deploy faster than wholly new procurements. We confirm blackout dates around events or county works before locking flight dates.'],
            ['q' => 'What upkeep is covered operationally?', 'a' => 'Scheduled inspections, lamp/power checks and storm damage ticketing are routed through Effective Media crews or partner electricians depending on locality. SLA terms sit in master agreements.'],
            ['q' => 'Which payment rhythms do you accept?', 'a' => 'Local corporates often pay 50–100% upfront on flight start with balance at midpoint for longer durations. Institutional clients can negotiate milestone billing tied to trafficking reports.'],
            ['q' => 'What durations perform best?', 'a' => 'Urban commuter routes gain frequency by month three; FMCG bursts may compress to 4–6 weeks with higher pole density instead of elongating a thin spread. Planner outputs suggest mixes by objective.'],
        ];

        $faqSchema = $this->websiteSeoService->faqPageJsonLd(array_merge($profileFaqs, $libraryFaqs));

        return view('pages.faqs', [
            ...$this->cmsSeoPayload('faqs', [], $faqSchema ? [$faqSchema] : []),
            'profileContent' => $profileContent,
        ]);
    }

    public function viewCompanyProfile(string $filename): BinaryFileResponse
    {
        $resolvedPath = $this->resolveCompanyProfilePathByFilename($filename);

        abort_unless($resolvedPath !== null, 404);

        return response()->file($resolvedPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($resolvedPath).'"',
        ]);
    }

    public function downloadCompanyProfile(string $filename): BinaryFileResponse
    {
        $resolvedPath = $this->resolveCompanyProfilePathByFilename($filename);

        abort_unless($resolvedPath !== null, 404);

        return response()->download($resolvedPath, basename($resolvedPath), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function captureProfileDownload(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'filename' => ['required', 'string', 'max:255'],
        ]);

        $leadCapture = true;

        try {
            $leadCapture = (bool) (app(PortalSettingsService::class)
                ->getPublicSnapshot()['documents']['lead_capture_enabled'] ?? true);
        } catch (\Throwable) {
            $leadCapture = true;
        }

        if ($leadCapture && Schema::hasTable('profile_download_requests')) {
            ProfileDownloadRequest::query()->create([
                ...$validated,
                'source' => 'profile_download',
            ]);
        }

        return redirect()->route('company-profile.download', [
            'filename' => $validated['filename'],
        ]);
    }

    public function townLanding(string $town, string $type): View
    {
        $profileContent = CachedSiteProfile::content();
        $normalizedTown = Str::title(str_replace('-', ' ', $town));
        $points = collect($profileContent['coverage_map_points'] ?? []);
        $reach = collect($profileContent['reach'] ?? []);

        $townPoints = $points->filter(
            static fn (array $point): bool => strcasecmp($point['town'] ?? '', $normalizedTown) === 0
        );

        $county = $townPoints->first()['county'] ?? ($reach->first()['county'] ?? 'Kenya');
        $mediaTypes = $townPoints->pluck('media_type')->unique()->values();
        $townPoles = $reach
            ->filter(static fn (array $row): bool => str_contains(
                strtolower(($row['site'] ?? '').' '.($row['county'] ?? '')),
                strtolower($normalizedTown)
            ))
            ->sum('poles');

        $titlePrefix = match ($type) {
            'street-light-ads' => 'Street Light Advertising in',
            'billboard-advertising' => 'Billboard Advertising in',
            default => 'Advertise in',
        };

        $title = $titlePrefix.' '.$normalizedTown.' | Effective Media';
        $description = sprintf(
            '%s %s, %s County. Outdoor media placements designed for visibility, repetition, and measurable reach across local corridors.',
            $titlePrefix,
            $normalizedTown,
            $county
        );

        return view('pages.town-landing', [
            'town' => $normalizedTown,
            'county' => $county,
            'mediaTypes' => $mediaTypes->isNotEmpty() ? $mediaTypes : collect(['Street Light Ads', 'Billboards']),
            'townPoles' => $townPoles > 0 ? $townPoles : 20,
            'benefits' => $profileContent['why_choose_us'] ?? [],
            'titlePrefix' => $titlePrefix,
            'pageSeo' => $this->websiteSeoService->resolve(null, [
                'title' => $title,
                'description' => $description,
                'og_type' => 'website',
            ]),
            'seoJsonLd' => array_values(array_filter([
                ...$this->websiteSeoService->organizationJsonLd(),
                $this->websiteSeoService->breadcrumbJsonLd([
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Coverage', 'url' => route('coverage')],
                    ['name' => $normalizedTown],
                ]),
            ])),
        ]);
    }

    public function viewBrandAsset(string $filename): BinaryFileResponse
    {
        $resolvedPath = $this->resolveBrandAssetPath($filename);

        abort_unless($resolvedPath !== null, 404);

        return response()->file($resolvedPath);
    }

    /**
     * @return Collection<int, Service>
     */
    private function publicServicesForExplorer(): Collection
    {
        $services = Service::query()
            ->select([
                'id',
                'title',
                'slug',
                'format_key',
                'summary',
                'public_sections',
                'image_path',
                'sort_order',
                'is_active',
                'is_visible_public',
            ])
            ->where('is_active', true)
            ->where('is_visible_public', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        if ($services->isNotEmpty()) {
            return $services;
        }

        return $this->explorerFallbackModels();
    }

    /**
     * @return Collection<int, Service>
     */
    private function explorerFallbackModels(): Collection
    {
        /** @var list<array<string, mixed>> $stubs */
        $stubs = [
            ['title' => 'Street Light Advertising', 'slug' => 'preview-street', 'format_key' => 'street_light'],
            ['title' => 'Billboards', 'slug' => 'preview-billboards', 'format_key' => 'billboard'],
            ['title' => 'Pavement Ads', 'slug' => 'preview-pavement', 'format_key' => 'pavement'],
            ['title' => 'Office Branding', 'slug' => 'preview-office', 'format_key' => 'office'],
            ['title' => 'Activations & Roadshows', 'slug' => 'preview-activations', 'format_key' => 'activation'],
            ['title' => 'Roll-Up Banners', 'slug' => 'preview-rollups', 'format_key' => 'rollup'],
        ];

        return collect($stubs)->map(function (array $attrs): Service {
            $model = new Service;
            $model->forceFill(array_merge([
                'summary' => null,
                'public_sections' => null,
                'image_path' => null,
                'is_active' => true,
                'is_visible_public' => true,
                'sort_order' => 0,
            ], $attrs));

            return $model;
        });
    }

    /**
     * Collect company profile PDFs from common project folders.
     *
     * @return Collection<int, array{name:string,url:string,path:string}>
     */
    private function getCompanyProfiles(): Collection
    {
        $key = 'site_company_profile_pdfs_v2';

        $cached = Cache::get($key);
        if (is_array($cached)) {
            return collect(array_values($cached));
        }

        if ($cached !== null) {
            Cache::forget($key);
        }

        $items = $this->scanFilesystemCompanyProfiles()->values()->all();
        Cache::put($key, $items, now()->addMinutes(30));

        return collect($items);
    }

    /**
     * @return Collection<int, array{name:string,url:string,path:string}>
     */
    private function scanFilesystemCompanyProfiles(): Collection
    {
        $rootLevelProfiles = collect(File::files(base_path()))
            ->filter(static fn (\SplFileInfo $file): bool => strtolower($file->getExtension()) === 'pdf');

        $directories = collect([
            public_path(),
            public_path('company-profiles'),
            public_path('profiles'),
            storage_path('app/public'),
            storage_path('app/public/company-profiles'),
        ])->filter(static fn (string $directory): bool => File::isDirectory($directory));

        $profiles = $rootLevelProfiles->map(fn (\SplFileInfo $file): array => $this->transformProfileFile($file));

        foreach ($directories as $directory) {
            $profiles = $profiles->merge(
                collect(File::allFiles($directory))
                    ->filter(static fn (\SplFileInfo $file): bool => strtolower($file->getExtension()) === 'pdf')
                    ->map(fn (\SplFileInfo $file): array => $this->transformProfileFile($file))
            );
        }

        return $profiles
            ->filter(static fn (array $profile): bool => $profile['url'] !== '#')
            ->unique('path')
            ->values();
    }

    /**
     * @return array{name:string,url:string,path:string}
     */
    private function transformProfileFile(\SplFileInfo $file): array
    {
        $absolutePath = $file->getRealPath() ?: $file->getPathname();
        $normalizedPath = str_replace('\\', '/', $absolutePath);
        $publicPathPrefix = str_replace('\\', '/', public_path());
        $storagePublicPathPrefix = str_replace('\\', '/', storage_path('app/public'));
        $isPublicFile = str_starts_with($normalizedPath, $publicPathPrefix);
        $isStoragePublicFile = str_starts_with($normalizedPath, $storagePublicPathPrefix);
        $relativePublicPath = ltrim(str_replace($publicPathPrefix, '', $normalizedPath), '/');
        $relativeStoragePath = ltrim(str_replace($storagePublicPathPrefix, '', $normalizedPath), '/');

        return [
            'name' => Str::of(pathinfo($file->getFilename(), PATHINFO_FILENAME))->replace(['-', '_'], ' ')->title()->toString(),
            'path' => $absolutePath,
            'url' => $isPublicFile
                ? asset($relativePublicPath)
                : ($isStoragePublicFile
                    ? asset('storage/'.$relativeStoragePath)
                    : route('company-profile.view', ['filename' => $file->getFilename()])),
        ];
    }

    private function resolveCompanyProfilePathByFilename(string $filename): ?string
    {
        $safeFilename = basename($filename);

        if ($safeFilename === '' || strtolower(pathinfo($safeFilename, PATHINFO_EXTENSION)) !== 'pdf') {
            return null;
        }

        $candidateDirectories = [
            base_path(),
            public_path(),
            public_path('company-profiles'),
            public_path('profiles'),
            storage_path('app/public'),
            storage_path('app/public/company-profiles'),
        ];

        foreach ($candidateDirectories as $directory) {
            if (! File::isDirectory($directory)) {
                continue;
            }

            $candidatePath = $directory.DIRECTORY_SEPARATOR.$safeFilename;

            if (File::exists($candidatePath)) {
                return $candidatePath;
            }
        }

        return null;
    }

    private function resolveBrandAssetPath(string $filename): ?string
    {
        $safeFilename = basename($filename);
        $extension = strtolower(pathinfo($safeFilename, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

        if ($safeFilename === '' || ! in_array($extension, $allowedExtensions, true)) {
            return null;
        }

        $candidateDirectories = [
            base_path(),
            public_path(),
            public_path('images'),
            public_path('assets'),
            storage_path('app/public'),
            storage_path('app/public/images'),
            storage_path('app/public/portal-branding'),
        ];

        foreach ($candidateDirectories as $directory) {
            if (! File::isDirectory($directory)) {
                continue;
            }

            $candidatePath = $directory.DIRECTORY_SEPARATOR.$safeFilename;

            if (File::exists($candidatePath)) {
                return $candidatePath;
            }
        }

        return null;
    }
}
