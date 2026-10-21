<?php

namespace App\Services\Seo;

use App\Models\CmsPage;
use App\Services\Cms\WebsitePageSectionsService;
use App\Services\Portal\PortalSettingsService;
use App\Support\CachedSiteProfile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

final class WebsiteSeoService
{
    public function absoluteUrl(?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }

        if (str_starts_with($url, '//')) {
            return (request()->isSecure() ? 'https:' : 'http:').$url;
        }

        if (preg_match('#^https?://#i', $url) === 1) {
            return $url;
        }

        return url($url);
    }

    public function canonicalForRequest(): string
    {
        $path = '/'.ltrim(request()->path(), '/');
        if ($path === '//') {
            $path = '/';
        }

        // Prefer clean canonical without query string (UTM, etc.).
        return url($path === '/' ? '/' : $path);
    }

    /**
     * @param  array<string, mixed>|null  $websitePage
     * @param  array<string, mixed>  $overrides
     * @return array{
     *     title: string,
     *     description: string,
     *     og_image: string,
     *     og_title: string,
     *     og_description: string,
     *     og_type: string,
     *     canonical: string,
     *     robots: string,
     *     keywords: string
     * }
     */
    public function resolve(?array $websitePage = null, array $overrides = []): array
    {
        $portal = app(PortalSettingsService::class)->getPublicSnapshot();
        $website = is_array($portal['website'] ?? null) ? $portal['website'] : [];
        $general = is_array($portal['general'] ?? null) ? $portal['general'] : [];
        $brand = (string) ($general['company_name'] ?? 'Effective Media');

        $portalTitle = trim((string) ($website['meta_title'] ?? ''));
        $portalDescription = trim((string) ($website['meta_description'] ?? ''));
        $portalKeywords = trim((string) ($website['meta_keywords'] ?? ''));
        $portalOg = trim((string) ($website['og_image'] ?? ''));

        $pageTitle = trim((string) ($websitePage['title'] ?? ''));
        $pageDescription = trim((string) ($websitePage['meta_description'] ?? ''));
        $pageOg = trim((string) ($websitePage['og_image'] ?? ''));
        $pageRobots = trim((string) ($websitePage['robots'] ?? ''));
        $pageCanonical = trim((string) ($websitePage['canonical_url'] ?? ''));

        $title = trim((string) ($overrides['title'] ?? ''));
        if ($title === '') {
            $title = $pageTitle !== '' ? $pageTitle : ($portalTitle !== '' ? $portalTitle : $brand);
        }

        $description = trim((string) ($overrides['description'] ?? ''));
        if ($description === '') {
            $description = $pageDescription !== ''
                ? $pageDescription
                : ($portalDescription !== '' ? $portalDescription : (string) config('seo.default_description'));
        }

        $ogImage = trim((string) ($overrides['og_image'] ?? ''));
        if ($ogImage === '') {
            $ogImage = $pageOg !== '' ? $pageOg : $portalOg;
        }

        $robots = trim((string) ($overrides['robots'] ?? ''));
        if ($robots === '') {
            $robots = $pageRobots !== '' ? $pageRobots : (string) config('seo.default_robots');
        }

        $canonical = trim((string) ($overrides['canonical'] ?? ''));
        if ($canonical === '') {
            $canonical = $pageCanonical !== '' ? $pageCanonical : $this->canonicalForRequest();
        }

        $ogTitle = trim((string) ($overrides['og_title'] ?? $title));
        $ogDescription = trim((string) ($overrides['og_description'] ?? $description));
        $ogType = trim((string) ($overrides['og_type'] ?? 'website'));
        $keywords = trim((string) ($overrides['keywords'] ?? $portalKeywords));

        return [
            'title' => $title,
            'description' => Str::limit($description, 320, ''),
            'og_image' => $this->absoluteUrl($ogImage),
            'og_title' => $ogTitle,
            'og_description' => Str::limit($ogDescription, 320, ''),
            'og_type' => $ogType !== '' ? $ogType : 'website',
            'canonical' => $this->absoluteUrl($canonical),
            'robots' => $robots,
            'keywords' => $keywords,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function organizationJsonLd(): array
    {
        $portal = app(PortalSettingsService::class)->getPublicSnapshot();
        $website = is_array($portal['website'] ?? null) ? $portal['website'] : [];
        $general = is_array($portal['general'] ?? null) ? $portal['general'] : [];
        $contact = is_array($portal['contact'] ?? null) ? $portal['contact'] : [];

        $name = (string) ($general['company_name'] ?? config('seo.organization.name', 'Effective Media'));
        $logoPath = $general['logo_path'] ?? null;
            $logo = is_string($logoPath) && $logoPath !== ''
                ? asset('storage/'.$logoPath)
                : route('brand-asset.view', ['filename' => 'logo.jpg']);

        $phones = is_array($contact['phones'] ?? null) ? array_values(array_filter($contact['phones'])) : [];
        $emails = is_array($contact['emails'] ?? null) ? array_values(array_filter($contact['emails'])) : [];
        $offices = is_array($contact['office_locations'] ?? null) ? array_values(array_filter($contact['office_locations'])) : [];
        $social = is_array($contact['social'] ?? null) ? array_filter($contact['social']) : [];

        $sameAs = array_values(array_unique(array_merge(
            array_values($social),
            config('seo.organization.same_as', [])
        )));

        $org = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'legalName' => (string) config('seo.organization.legal_name', $name),
            'url' => url('/'),
            'logo' => $logo,
            'description' => (string) (
                ($website['meta_description'] ?? '') !== ''
                    ? $website['meta_description']
                    : config('seo.organization.description')
            ),
            'areaServed' => (string) config('seo.organization.area_served', 'Kenya'),
        ];

        if ($phones !== []) {
            $org['telephone'] = array_map(static fn ($p) => (string) $p, $phones);
        }
        if ($emails !== []) {
            $org['email'] = (string) $emails[0];
        }
        if ($offices !== []) {
            $org['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => (string) $offices[0],
                'addressCountry' => 'KE',
            ];
        }
        if ($sameAs !== []) {
            $org['sameAs'] = $sameAs;
        }

        $websiteNode = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $name,
            'url' => url('/'),
            'publisher' => [
                '@type' => 'Organization',
                'name' => $name,
                'logo' => $logo,
            ],
            'inLanguage' => 'en-KE',
        ];

        return [$org, $websiteNode];
    }

    /**
     * @param  list<array{question?: string, answer?: string, q?: string, a?: string}>  $faqs
     * @return array<string, mixed>|null
     */
    public function faqPageJsonLd(array $faqs): ?array
    {
        $entities = [];
        foreach ($faqs as $faq) {
            $q = trim((string) ($faq['question'] ?? $faq['q'] ?? ''));
            $a = trim((string) ($faq['answer'] ?? $faq['a'] ?? ''));
            if ($q === '' || $a === '') {
                continue;
            }
            $entities[] = [
                '@type' => 'Question',
                'name' => $q,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $a,
                ],
            ];
        }

        if ($entities === []) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }

    /**
     * @param  list<array{name: string, url?: string}>  $crumbs
     * @return array<string, mixed>|null
     */
    public function breadcrumbJsonLd(array $crumbs): ?array
    {
        $items = [];
        $position = 1;
        foreach ($crumbs as $crumb) {
            $name = trim((string) ($crumb['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $item = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $name,
            ];
            $url = trim((string) ($crumb['url'] ?? ''));
            if ($url !== '') {
                $item['item'] = $this->absoluteUrl($url);
            }
            $items[] = $item;
            $position++;
        }

        if (count($items) < 2) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * @param  array<string, mixed>  $service
     * @return array<string, mixed>
     */
    public function serviceJsonLd(array $service): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => (string) ($service['title'] ?? 'Outdoor Advertising'),
            'description' => (string) ($service['description'] ?? ''),
            'provider' => [
                '@type' => 'Organization',
                'name' => 'Effective Media',
                'url' => url('/'),
            ],
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Kenya',
            ],
            'url' => $this->canonicalForRequest(),
            'image' => $this->absoluteUrl((string) ($service['hero_image'] ?? '')),
        ];
    }

    public function publicUrlForCmsSlug(string $slug): string
    {
        $map = [
            'home' => 'home',
            'who-we-are' => 'who-we-are',
            'what-we-do' => 'what-we-do',
            'portfolio' => 'portfolio',
            'coverage' => 'coverage',
            'smart-campaign-planner' => 'smart-campaign-planner',
            'faqs' => 'faqs',
            'contact-us' => 'contact-us',
            'quote' => 'quote',
        ];

        $route = $map[$slug] ?? null;
        if ($route !== null && Route::has($route)) {
            return route($route);
        }

        return url('/'.$slug);
    }

    /**
     * @return list<array{loc: string, lastmod: ?string, changefreq: string, priority: string}>
     */
    public function sitemapEntries(): array
    {
        $entries = [];
        $now = now()->toAtomString();

        foreach (config('seo.sitemap_static_routes', []) as $routeName) {
            if (! is_string($routeName) || ! Route::has($routeName)) {
                continue;
            }

            $slug = $routeName === 'home' ? 'home' : $routeName;
            $page = CmsPage::query()->where('slug', $slug)->first();
            if ($page && ! $page->is_published) {
                continue;
            }

            $entries[] = [
                'loc' => route($routeName),
                'lastmod' => $page?->updated_at?->toAtomString() ?? $now,
                'changefreq' => (string) (
                    config('seo.sitemap_change_freq.'.$routeName)
                    ?? config('seo.sitemap_change_freq.default', 'monthly')
                ),
                'priority' => (string) (
                    config('seo.sitemap_priority.'.$routeName)
                    ?? config('seo.sitemap_priority.default', '0.7')
                ),
            ];
        }

        foreach (config('seo.sitemap_service_slugs', []) as $serviceSlug) {
            if (! is_string($serviceSlug) || $serviceSlug === '') {
                continue;
            }
            $entries[] = [
                'loc' => route('services.show', ['serviceSlug' => $serviceSlug]),
                'lastmod' => $now,
                'changefreq' => (string) config('seo.sitemap_change_freq.service', 'monthly'),
                'priority' => (string) config('seo.sitemap_priority.service', '0.8'),
            ];
        }

        $profile = CachedSiteProfile::content();
        $towns = collect($profile['coverage_towns'] ?? [])
            ->merge(collect($profile['coverage_map_points'] ?? [])->pluck('town'))
            ->filter(static fn ($t) => is_string($t) && trim($t) !== '')
            ->map(static fn ($t) => Str::slug((string) $t))
            ->unique()
            ->values();

        foreach ($towns as $townSlug) {
            foreach (['landing.advertise-in', 'landing.street-light-ads', 'landing.billboard-advertising'] as $routeName) {
                if (! Route::has($routeName)) {
                    continue;
                }
                $entries[] = [
                    'loc' => route($routeName, ['town' => $townSlug]),
                    'lastmod' => $now,
                    'changefreq' => (string) config('seo.sitemap_change_freq.landing', 'monthly'),
                    'priority' => (string) config('seo.sitemap_priority.landing', '0.6'),
                ];
            }
        }

        // Deduplicate by loc.
        $seen = [];
        $unique = [];
        foreach ($entries as $entry) {
            $loc = $entry['loc'];
            if (isset($seen[$loc])) {
                continue;
            }
            $seen[$loc] = true;
            $unique[] = $entry;
        }

        return $unique;
    }

    /**
     * Bust page-section cache when SEO columns change.
     */
    public function forgetPageCaches(): void
    {
        WebsitePageSectionsService::forgetCache();
    }
}
