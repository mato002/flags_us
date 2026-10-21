<?php

namespace App\Providers;

use App\Services\Portal\PortalSettingsService;
use App\Support\CachedSiteProfile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('portal_setting_groups')) {
                $sessionMinutes = (int) (app(PortalSettingsService::class)->get('users_security')['session_lifetime_minutes'] ?? 0);
                if ($sessionMinutes > 0) {
                    config(['session.lifetime' => $sessionMinutes]);
                }
            }
        } catch (\Throwable) {
            //
        }

        $websiteShellViews = [
            'layouts.website',
            'layouts.admin',
            'layouts.auth-portal',
            'pages.quote',
            'pages.quote-success',
            'pages.contact-us',
            'pages.smart-campaign-planner',
        ];

        View::composer($websiteShellViews, static function (\Illuminate\Contracts\View\View $view): void {
            $profile = CachedSiteProfile::content();

            $portalSettings = app(PortalSettingsService::class);
            $snapshot = $portalSettings->getPublicSnapshot();

            $portalGeneral = $snapshot['general'];
            $portalWebsite = $snapshot['website'];

            $mergedContact = $portalSettings->mergeYamlContacts($snapshot['contact'], is_array($profile) ? $profile : null);

            $phonesList = $mergedContact['phones'] ?? [];
            $emailsList = $mergedContact['emails'] ?? [];

            $profileContact = [
                'phones' => is_array($phonesList) ? $phonesList : [],
                'email' => is_array($emailsList) && isset($emailsList[0]) ? (string) $emailsList[0] : '',
                'office' => is_array($mergedContact['office_locations'] ?? null) && isset($mergedContact['office_locations'][0])
                    ? (string) $mergedContact['office_locations'][0]
                    : 'Nakuru, Kenya',
            ];

            $socialLinks = array_filter($mergedContact['social'] ?? []);

            $primaryRaw = $profileContact['phones'][0] ?? '0725646642';
            $primaryPhoneDigits = preg_replace('/\D+/', '', (string) $primaryRaw);
            if (str_starts_with($primaryPhoneDigits, '0')) {
                $primaryPhoneDigits = '254'.substr($primaryPhoneDigits, 1);
            } elseif ($primaryPhoneDigits !== '' && ! str_starts_with($primaryPhoneDigits, '254')) {
                $primaryPhoneDigits = '254'.$primaryPhoneDigits;
            }
            $telHref = $primaryPhoneDigits !== '' ? 'tel:+'.$primaryPhoneDigits : 'tel:+254725646642';

            $waDigits = preg_replace('/\D+/', '', (string) ($mergedContact['whatsapp_e164'] ?? ''));
            if ($waDigits === '') {
                $waDigits = '254725646642';
            }

            $waQuoteMsg = rawurlencode('Hi Effective Media, I need a campaign quote.');
            $waExploreMsg = rawurlencode('Hi Effective Media, I am interested in outdoor advertising. Please share available sites and rates.');
            $whatsappHrefQuote = 'https://wa.me/'.$waDigits.'?text='.$waQuoteMsg;
            $whatsappHrefExplore = 'https://wa.me/'.$waDigits.'?text='.$waExploreMsg;

            $logoPath = $portalGeneral['logo_path'] ?? null;
            $brandLogoSrc = is_string($logoPath) && $logoPath !== ''
                ? asset('storage/'.$logoPath)
                : route('brand-asset.view', ['filename' => 'logo.jpg']);

            $faviconPath = $portalGeneral['favicon_path'] ?? null;
            $portalFaviconHref = is_string($faviconPath) && $faviconPath !== ''
                ? asset('storage/'.$faviconPath)
                : null;

            $primaryColor = $portalGeneral['primary_color'] ?? '#8b1e1a';
            $accentColor = $portalGeneral['accent_color'] ?? '#f04a2a';
            $deepMaroon = $portalGeneral['deep_maroon_color'] ?? '#5c1514';

            $metaPixelId = is_string(($snapshot['integrations']['meta_pixel_id'] ?? null))
                ? trim((string) $snapshot['integrations']['meta_pixel_id'])
                : '';

            $view->with([
                'profileContact' => $profileContact,
                'socialLinks' => $socialLinks,
                'telHref' => $telHref,
                'portalWebsite' => $portalWebsite,
                'portalGeneral' => $portalGeneral,
                'portalBrandName' => $portalGeneral['company_name'] ?? 'Effective Media',
                'brandLogoSrc' => $brandLogoSrc,
                'portalFaviconHref' => $portalFaviconHref,
                'portalMetaDescription' => (string) ($portalWebsite['meta_description'] ?? ''),
                'portalMetaKeywords' => (string) ($portalWebsite['meta_keywords'] ?? ''),
                'portalOgImage' => (string) ($portalWebsite['og_image'] ?? ''),
                'portalTheme' => [
                    'primary' => $primaryColor,
                    'accent' => $accentColor,
                    'deep' => $deepMaroon,
                    'font_heading' => (string) ($portalGeneral['font_heading'] ?? 'Figtree'),
                    'font_body' => (string) ($portalGeneral['font_body'] ?? 'Figtree'),
                ],
                'whatsappHrefQuote' => $whatsappHrefQuote,
                'whatsappHrefExplore' => $whatsappHrefExplore,
                'portalMetaPixelId' => $metaPixelId,
            ]);
        });
    }
}
