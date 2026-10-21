<?php

namespace App\Services\Cms;

use App\Models\HomepageSetting;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\Statistic;
use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Throwable;

class HomepageContentService
{
    /** Stored as plain arrays; do not reuse keys for serialized Eloquent collections. */
    public const HOMEPAGE_PAYLOAD_CACHE_KEY = 'cms_homepage_payload_v5';

    /**
     * Homepage “Rates preview” strip — single source of truth for defaults (matches former hard-coded Blade).
     *
     * @return array{kicker: string, title: string, tiers: list<array{tier_label: string, price: string, description: string, cta_label: string, cta_href: string, highlighted: bool}>}
     */
    public static function defaultRatesPreview(): array
    {
        $quoteStarter = route('quote', ['budget_range' => 'KES 100K - 300K'], absolute: false);
        $quoteGrowth = route('quote', ['budget_range' => 'KES 300K - 750K'], absolute: false);
        $contact = route('contact-us', [], absolute: false);

        return [
            'kicker' => 'Rates Preview',
            'title' => 'Estimate Campaign Costs Fast',
            'tiers' => [
                [
                    'tier_label' => 'Starter',
                    'price' => 'KES 120K',
                    'description' => 'Street light placements for local market reach.',
                    'cta_label' => 'Get Estimate',
                    'cta_href' => $quoteStarter,
                    'highlighted' => false,
                ],
                [
                    'tier_label' => 'Growth',
                    'price' => 'KES 450K',
                    'description' => 'Multi-town deployment with CBD + highway impact.',
                    'cta_label' => 'Plan Package',
                    'cta_href' => $quoteGrowth,
                    'highlighted' => true,
                ],
                [
                    'tier_label' => 'Dominance',
                    'price' => 'KES 900K+',
                    'description' => 'National coverage and premium visibility corridors.',
                    'cta_label' => 'Talk to Sales',
                    'cta_href' => $contact,
                    'highlighted' => false,
                ],
            ],
        ];
    }

    /**
     * Get the homepage content payload with sensible defaults.
     *
     * @return array<string, mixed>
     */
    public function getHomepagePayload(): array
    {
        /** @var array{hero: array<string, mixed>, services: array<int, array<string, mixed>>, statistics: array<int, array<string, mixed>>, testimonials: array<int, array<string, mixed>>, featured_portfolio: array<int, array<string, mixed>>} $stored */
        $stored = Cache::remember(self::HOMEPAGE_PAYLOAD_CACHE_KEY, now()->addMinutes(3), function (): array {
            return $this->buildPersistablePayload();
        });

        return $this->hydratePayloadFromCache($stored);
    }

    /**
     * Persist only plain arrays/primitives — never serialize Eloquent collections (breaks across requests / PHP 8.4).
     *
     * @return array{hero: array<string, mixed>, rates_preview: array<string, mixed>, services: array<int, array<string, mixed>>, statistics: array<int, array<string, mixed>>, testimonials: array<int, array<string, mixed>>, featured_portfolio: array<int, array<string, mixed>>}
     */
    private function buildPersistablePayload(): array
    {
        $payload = $this->resolveHomepagePayload();

        return [
            'hero' => $payload['hero'],
            'rates_preview' => $payload['rates_preview'],
            'services' => $payload['services']->map->getAttributes()->values()->all(),
            'statistics' => $payload['statistics']->map->getAttributes()->values()->all(),
            'testimonials' => $payload['testimonials']->map->getAttributes()->values()->all(),
            'featured_portfolio' => $payload['featured_portfolio']->map->getAttributes()->values()->all(),
        ];
    }

    /**
     * @param  array{hero: array<string, mixed>, rates_preview?: array<string, mixed>, services: array<int, array<string, mixed>>, statistics: array<int, array<string, mixed>>, testimonials: array<int, array<string, mixed>>, featured_portfolio: array<int, array<string, mixed>>}  $stored
     * @return array<string, mixed>
     */
    private function hydratePayloadFromCache(array $stored): array
    {
        return [
            'hero' => $stored['hero'],
            'rates_preview' => is_array($stored['rates_preview'] ?? null) && ($stored['rates_preview'] ?? []) !== []
                ? $stored['rates_preview']
                : self::defaultRatesPreview(),
            'services' => Service::hydrate($stored['services'] ?? []),
            'statistics' => Statistic::hydrate($stored['statistics'] ?? []),
            'testimonials' => Testimonial::hydrate($stored['testimonials'] ?? []),
            'featured_portfolio' => PortfolioItem::hydrate($stored['featured_portfolio'] ?? []),
        ];
    }

    /**
     * Effective strip for frontend and admin forms (fills missing tier keys).
     *
     * @return array{kicker: string, title: string, tiers: list<array{tier_label: string, price: string, description: string, cta_label: string, cta_href: string, highlighted: bool}>}
     */
    public function effectiveRatesPreview(?HomepageSetting $setting): array
    {
        return $this->mergeRatesPreview(self::defaultRatesPreview(), $setting);
    }

    /**
     * @param  array{kicker: string, title: string, tiers: list<array<string, mixed>>}  $defaults
     * @return array{kicker: string, title: string, tiers: list<array{tier_label: string, price: string, description: string, cta_label: string, cta_href: string, highlighted: bool}>}
     */
    private function mergeRatesPreview(array $defaults, ?HomepageSetting $setting): array
    {
        $raw = $setting?->rates_preview;
        if (! is_array($raw)) {
            return $defaults;
        }

        $kicker = trim((string) ($raw['kicker'] ?? ''));
        $title = trim((string) ($raw['title'] ?? ''));
        $tiersIn = $raw['tiers'] ?? null;

        if (! is_array($tiersIn) || $tiersIn === []) {
            return $defaults;
        }

        $mergedTiers = [];
        foreach ($tiersIn as $i => $row) {
            if (! is_array($row)) {
                continue;
            }
            $base = $defaults['tiers'][$i] ?? [
                'tier_label' => '',
                'price' => '',
                'description' => '',
                'cta_label' => '',
                'cta_href' => route('contact-us', [], absolute: false),
                'highlighted' => false,
            ];
            $mergedTiers[] = [
                'tier_label' => trim((string) ($row['tier_label'] ?? $base['tier_label'])) ?: $base['tier_label'],
                'price' => trim((string) ($row['price'] ?? $base['price'])) ?: $base['price'],
                'description' => trim((string) ($row['description'] ?? $base['description'])) ?: $base['description'],
                'cta_label' => trim((string) ($row['cta_label'] ?? $base['cta_label'])) ?: $base['cta_label'],
                'cta_href' => trim((string) ($row['cta_href'] ?? $base['cta_href'])) ?: $base['cta_href'],
                'highlighted' => filter_var($row['highlighted'] ?? $base['highlighted'], FILTER_VALIDATE_BOOLEAN),
            ];
        }

        if ($mergedTiers === []) {
            return $defaults;
        }

        return [
            'kicker' => $kicker !== '' ? $kicker : $defaults['kicker'],
            'title' => $title !== '' ? $title : $defaults['title'],
            'tiers' => $mergedTiers,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveHomepagePayload(): array
    {
        $defaultHeroDescription = 'Effective Media combines premium billboard placement, digital screens, and a modern planning backend into one clean ecosystem for brands that need impact.';
        $hero = [
            'badge' => 'Outdoor Media Platform',
            'title' => 'Intelligent Outdoor Advertising, Built for Scale',
            'description' => $defaultHeroDescription,
            'primary_cta_text' => 'Plan a Campaign',
            'primary_cta_link' => '/smart-campaign-planner',
            'secondary_cta_text' => 'Talk to Sales',
            'secondary_cta_link' => '/contact-us',
        ];

        $homepageSetting = null;

        try {
            $homepageSetting = HomepageSetting::query()->first();

            if ($homepageSetting instanceof HomepageSetting) {
                $hero = [
                    'badge' => $homepageSetting->hero_badge,
                    'title' => $homepageSetting->hero_title,
                    'description' => $homepageSetting->hero_description ?: $defaultHeroDescription,
                    'primary_cta_text' => $homepageSetting->primary_cta_text,
                    'primary_cta_link' => $homepageSetting->primary_cta_link,
                    'secondary_cta_text' => $homepageSetting->secondary_cta_text,
                    'secondary_cta_link' => $homepageSetting->secondary_cta_link,
                ];
            }

            $services = Service::query()
                ->select(['id', 'title', 'slug', 'summary', 'sort_order', 'is_active'])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('title')
                ->get();

            $statistics = Statistic::query()
                ->select(['id', 'label', 'value', 'suffix', 'sort_order', 'is_published', 'updated_at'])
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $testimonials = Testimonial::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $featuredPortfolio = $this->dedupePortfolioForHomepage(
                PortfolioItem::query()
                    ->select([
                        'id',
                        'title',
                        'client_name',
                        'category',
                        'campaign_location',
                        'media_type',
                        'description',
                        'image_path',
                        'featured',
                        'is_published',
                        'sort_order',
                    ])
                    ->where('is_published', true)
                    ->where('featured', true)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->limit(24)
                    ->get()
            )->take(12);
        } catch (Throwable) {
            // Keep the landing page available even if DB driver/config is unavailable.
            $homepageSetting = null;
            $services = collect();
            $statistics = collect();
            $testimonials = collect();
            $featuredPortfolio = collect();
        }

        $ratesPreview = $this->mergeRatesPreview(self::defaultRatesPreview(), $homepageSetting);

        return [
            'hero' => $hero,
            'rates_preview' => $ratesPreview,
            'services' => $services,
            'statistics' => $statistics,
            'testimonials' => $testimonials,
            'featured_portfolio' => $featuredPortfolio,
        ];
    }

    /**
     * Remove accidental duplicate featured rows (same visible title + image path) so the homepage grid does not repeat cards.
     *
     * @param  Collection<int, PortfolioItem>  $items
     * @return Collection<int, PortfolioItem>
     */
    private function dedupePortfolioForHomepage(Collection $items): Collection
    {
        return $items
            ->unique('id')
            ->values()
            ->unique(function (PortfolioItem $item): string {
                $title = strtolower(trim(preg_replace('/\s+/', ' ', (string) $item->title)));
                $image = trim((string) ($item->image_path ?? ''));

                return $title.'|'.$image;
            })
            ->values();
    }
}
