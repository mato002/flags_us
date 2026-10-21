<?php

namespace App\Services\Portal;

use App\Models\HomepageSetting;
use App\Models\PortalSettingAudit;
use App\Models\PortalSettingGroup;
use App\Models\User;
use App\Services\Cms\HomepageContentService;
use App\Support\PortalSensitiveCrypto;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class PortalSettingsService
{
    private function portalTableReady(): bool
    {
        try {
            return Schema::hasTable('portal_setting_groups');
        } catch (Throwable) {
            return false;
        }
    }

    private function auditsTableReady(): bool
    {
        try {
            return Schema::hasTable('portal_setting_audits');
        } catch (Throwable) {
            return false;
        }
    }

    public const EDITABLE_GROUPS = [
        'general',
        'contact',
        'website',
        'planner',
        'media_coverage',
        'quotations',
        'documents',
        'notifications',
        'users_security',
        'integrations',
        'performance',
        'backups',
    ];

    public const CACHE_KEY_GROUP = 'portal_setting_group:v1:%s';

    public const CACHE_KEY_PUBLIC_SNAPSHOT = 'portal_settings_public_snapshot:v1';

    /**
     * @return array<string, mixed>
     */
    public static function defaultsFor(string $slug): array
    {
        return match ($slug) {
            'general' => [
                'company_name' => 'Effective Media',
                'logo_path' => null,
                'favicon_path' => null,
                'tagline' => 'Outdoor advertising infrastructure across Kenya',
                'primary_color' => '#8b1e1a',
                'accent_color' => '#f04a2a',
                'deep_maroon_color' => '#5c1514',
                'font_heading' => 'Figtree',
                'font_body' => 'Figtree',
            ],
            'contact' => [
                'phones' => [],
                'emails' => [],
                'office_locations' => [],
                'whatsapp_display' => '',
                'whatsapp_e164' => '',
                'social' => [
                    'facebook' => '',
                    'instagram' => '',
                    'linkedin' => '',
                    'x' => '',
                    'youtube' => '',
                ],
            ],
            'website' => [
                'hero_badge' => 'A Media Network Across Kenya',
                'hero_headline' => 'Your Brand. Seen Across Kenya.',
                'hero_subtext' => 'Strategic outdoor advertising infrastructure across highways, CBDs, transport corridors and urban centers.',
                'cta_plan_label' => 'Plan Campaign',
                'cta_plan_url' => '/smart-campaign-planner',
                'cta_coverage_label' => 'Explore Coverage',
                'cta_coverage_url' => '/coverage',
                'cta_download_label' => 'Download Profile',
                'maintenance_mode' => false,
                'sticky_navbar' => true,
                'floating_action_buttons' => true,
                'meta_title' => '',
                'meta_description' => '',
                'meta_keywords' => '',
                'og_image' => '',
                'google_site_verification' => '',
                'bing_site_verification' => '',
                'analytics_ga4_id' => '',
                'analytics_gtm_id' => '',
            ],
            'planner' => [
                'demographic_weight' => 0.35,
                'traffic_weight' => 0.45,
                'budget_optimizer' => true,
                'ai_recommendations' => false,
                'maps_provider' => 'openstreetmap',
                'default_map_zoom' => 7,
                'planner_notes' => '',
            ],
            'media_coverage' => [
                'default_counties' => '',
                'default_constituencies' => '',
                'default_wards' => '',
                'map_theme' => 'em-maroon-satellite',
                'board_categories' => [],
                'marker_style' => 'pin-maroon',
                'coverage_notes' => '',
            ],
            'quotations' => [
                'quote_prefix' => 'EMQ',
                'invoice_prefix' => 'EMI',
                'vat_percentage' => 16.0,
                'currency_code' => 'KES',
                'quote_expiry_days' => 21,
                'pdf_company_legal_name' => '',
                'pdf_footer_note' => '',
                'pdf_show_watermark' => false,
            ],
            'documents' => [
                'lead_capture_enabled' => true,
                'pdf_watermark_text' => '',
                'max_upload_mb' => 15,
                'auto_thumbnails' => true,
                'documents_notes' => '',
            ],
            'notifications' => [
                'smtp_host' => '',
                'smtp_port' => 587,
                'smtp_username' => '',
                'smtp_encryption' => 'tls',
                'smtp_password' => '',
                'mail_from_address' => '',
                'mail_from_name' => '',
                'admin_alert_email' => '',
                'quote_email_template_slug' => 'quotes.default',
                'whatsapp_notifications_enabled' => false,
                'sms_notifications_enabled' => false,
                'sms_sender_id' => '',
            ],
            'users_security' => [
                'session_lifetime_minutes' => 120,
                'two_factor_required' => false,
                'password_min_length' => 10,
                'password_require_uppercase' => true,
                'password_require_number' => true,
                'password_require_symbol' => false,
                'activity_log_retention_days' => 90,
                'security_notes' => '',
            ],
            'integrations' => [
                'google_maps_api_key' => '',
                'openai_api_key' => '',
                'openai_model' => 'gpt-4o-mini',
                'meta_pixel_id' => '',
                'cloudinary_cloud_name' => '',
                'cloudinary_api_key' => '',
                'cloudinary_api_secret' => '',
                'aws_access_key_id' => '',
                'aws_secret_access_key' => '',
                'aws_default_region' => 'af-south-1',
                'aws_bucket' => '',
                'integrations_notes' => '',
            ],
            'performance' => [
                'image_optimization' => true,
                'lazy_loading' => true,
                'cache_public_pages_seconds' => 60,
                'performance_notes' => '',
            ],
            'backups' => [
                'frequency' => 'daily',
                'retention_days' => 14,
                'last_manual_backup_at' => null,
                'backups_notes' => '',
            ],
            default => [],
        };
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $slug): array
    {
        $defaults = static::defaultsFor($slug);

        if (! $this->portalTableReady()) {
            return $defaults;
        }

        $payload = Cache::remember(
            sprintf(self::CACHE_KEY_GROUP, $slug),
            now()->addMinutes(15),
            function () use ($slug): array {
                $row = PortalSettingGroup::query()->where('slug', $slug)->first();

                return is_array($row?->data) ? $row->data : [];
            }
        );

        $merged = array_replace_recursive($defaults, $payload);

        return $slug === 'integrations' || $slug === 'notifications'
            ? $this->decryptSensitiveGroup($merged, $slug)
            : $merged;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getAllForAdmin(): array
    {
        $out = [];

        foreach (self::EDITABLE_GROUPS as $slug) {
            $out[$slug] = $this->getMaskedForForms($slug);
        }

        return $out;
    }

    /**
     * Strip secret values while indicating presence for the admin UI.
     *
     * @return array<string, mixed>
     */
    public function getMaskedForForms(string $slug): array
    {
        $data = $this->get($slug);

        $secretFields = match ($slug) {
            'integrations' => ['google_maps_api_key', 'openai_api_key', 'cloudinary_api_secret', 'aws_secret_access_key'],
            'notifications' => ['smtp_password'],
            default => [],
        };

        foreach ($secretFields as $field) {
            $val = $data[$field] ?? '';
            if (is_string($val) && $val !== '') {
                $data[$field] = '';
                $data['_meta'][$field.'_stored'] = true;
            }
        }

        return $data;
    }

    /**
     * Lightweight merge for front-of-house views (excluding secrets).
     *
     * @return array<string, mixed>
     */
    public function getPublicSnapshot(): array
    {
        return Cache::remember(self::CACHE_KEY_PUBLIC_SNAPSHOT, now()->addSeconds(120), function (): array {
            return [
                'general' => $this->pickPublicGeneral(),
                'contact' => $this->pickPublicContact(),
                'website' => $this->get('website'),
                'performance' => [
                    'lazy_loading' => (bool) ($this->get('performance')['lazy_loading'] ?? true),
                    'image_optimization' => (bool) ($this->get('performance')['image_optimization'] ?? true),
                ],
                'documents' => [
                    'lead_capture_enabled' => (bool) ($this->get('documents')['lead_capture_enabled'] ?? true),
                ],
                'quotations' => $this->get('quotations'),
                'integrations' => [
                    'maps_provider' => (string) ($this->get('planner')['maps_provider'] ?? 'openstreetmap'),
                    /** Does not expose API keys */
                    'has_google_maps_key' => $this->integrationKeyPresent('google_maps_api_key'),
                    'meta_pixel_id' => (string) ($this->get('integrations')['meta_pixel_id'] ?? ''),
                ],
            ];
        });
    }

    private function integrationKeyPresent(string $key): bool
    {
        $row = $this->get('integrations');

        return isset($row[$key]) && is_string($row[$key]) && $row[$key] !== '';
    }

    /**
     * @return array<string, mixed>
     */
    private function pickPublicGeneral(): array
    {
        $g = $this->get('general');

        return [
            'company_name' => $g['company_name'] ?? 'Effective Media',
            'tagline' => $g['tagline'] ?? '',
            'primary_color' => $g['primary_color'] ?? '#8b1e1a',
            'accent_color' => $g['accent_color'] ?? '#f04a2a',
            'deep_maroon_color' => $g['deep_maroon_color'] ?? '#5c1514',
            'font_heading' => $g['font_heading'] ?? 'Figtree',
            'font_body' => $g['font_body'] ?? 'Figtree',
            'logo_path' => $g['logo_path'] ?? null,
            'favicon_path' => $g['favicon_path'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function pickPublicContact(): array
    {
        $c = $this->get('contact');

        return [
            'phones' => $c['phones'] ?? [],
            'emails' => $c['emails'] ?? [],
            'office_locations' => $c['office_locations'] ?? [],
            'whatsapp_display' => $c['whatsapp_display'] ?? '',
            'whatsapp_e164' => $c['whatsapp_e164'] ?? '',
            'social' => $c['social'] ?? [],
        ];
    }

    public function forgetCaches(?string $slug = null): void
    {
        Cache::forget(self::CACHE_KEY_PUBLIC_SNAPSHOT);
        Cache::forget('effective_media_site_profile_v1');
        Cache::forget(HomepageContentService::HOMEPAGE_PAYLOAD_CACHE_KEY);
        Cache::forget('cms_homepage_payload_v2');
        Cache::forget('admin_dashboard_aggregate_v1');
        Cache::forget('admin_dashboard_aggregate_v2');

        if ($slug !== null) {
            Cache::forget(sprintf(self::CACHE_KEY_GROUP, $slug));

            return;
        }

        foreach (self::EDITABLE_GROUPS as $s) {
            Cache::forget(sprintf(self::CACHE_KEY_GROUP, $s));
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function stripMetaKeys(array $data): array
    {
        unset($data['_meta']);

        foreach ($data as $k => $v) {
            if (str_starts_with((string) $k, '_')) {
                unset($data[$k]);
            }
            if ($k === 'social' && is_array($v)) {
                foreach (array_keys($v) as $sk) {
                    if (str_starts_with((string) $sk, '_')) {
                        unset($data['social'][$sk]);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $merged
     * @param  array<string, mixed>  $current
     * @param  array<string, mixed>  $incoming
     * @return array<string, mixed>
     */
    private function restoreSecretsWhenBlank(array $merged, array $current, array $incoming, string $slug): array
    {
        $secretKeys = match ($slug) {
            'integrations' => ['google_maps_api_key', 'openai_api_key', 'cloudinary_api_secret', 'aws_secret_access_key'],
            'notifications' => ['smtp_password'],
            default => [],
        };

        foreach ($secretKeys as $key) {
            if (! array_key_exists($key, $incoming)) {
                continue;
            }

            $raw = $incoming[$key];

            if (is_string($raw) && $raw === '') {
                if (array_key_exists($key, $current)) {
                    $merged[$key] = $current[$key];
                } else {
                    $merged[$key] = '';
                }
            }
        }

        return $merged;
    }

    /**
     * @param  array<string, mixed>  $incoming
     */
    public function saveGroup(string $slug, array $incoming, ?User $user = null): void
    {
        if (! $this->portalTableReady()) {
            return;
        }

        $current = $this->getUnmaskedFromDatabase($slug);
        $defaults = static::defaultsFor($slug);

        $incoming = $this->stripMetaKeys($incoming);
        $merged = array_replace_recursive($defaults, $current, $incoming);

        if ($slug === 'integrations' || $slug === 'notifications') {
            $merged = $this->restoreSecretsWhenBlank($merged, $current, $incoming, $slug);
            $merged = $this->encryptSensitiveGroup($merged, $slug, $incoming);
            $merged = $this->ensureSecretsPersistEncrypted($merged, $slug);
        }

        unset($merged['_meta']);

        PortalSettingGroup::query()->updateOrCreate(
            ['slug' => $slug],
            ['data' => $merged]
        );

        $this->forgetCaches($slug);

        if ($slug === 'website') {
            $this->syncHomepageRow($merged);
        }

        $this->writeAudit($user, $slug, 'updated', [
            'fields' => array_keys($incoming),
        ]);
    }

    /**
     * Raw DB payload without per-group cache.
     *
     * @return array<string, mixed>
     */
    private function getUnmaskedFromDatabase(string $slug): array
    {
        if (! $this->portalTableReady()) {
            return [];
        }

        $row = PortalSettingGroup::query()->where('slug', $slug)->first();

        $payload = is_array($row?->data) ? $row->data : [];

        return $slug === 'integrations' || $slug === 'notifications'
            ? $this->decryptSensitiveGroup($payload, $slug)
            : $payload;
    }

    /**
     * @param  array<string, mixed>  $merged
     * @param  array<string, mixed>  $incoming
     * @return array<string, mixed>
     */
    private function encryptSensitiveGroup(array $merged, string $slug, array $incoming): array
    {
        $secretKeys = match ($slug) {
            'integrations' => ['google_maps_api_key', 'openai_api_key', 'cloudinary_api_secret', 'aws_secret_access_key'],
            'notifications' => ['smtp_password'],
            default => [],
        };

        foreach ($secretKeys as $key) {
            if (! array_key_exists($key, $incoming)) {
                continue;
            }

            $value = $incoming[$key];
            if ($value === null || $value === '') {
                continue;
            }

            $enc = PortalSensitiveCrypto::encrypt((string) $value);
            if ($enc !== null) {
                $merged[$key] = $enc;
            }
        }

        return $merged;
    }

    /**
     * @param  array<string, mixed>  $merged
     * @return array<string, mixed>
     */
    private function ensureSecretsPersistEncrypted(array $merged, string $slug): array
    {
        $secretKeys = match ($slug) {
            'integrations' => ['google_maps_api_key', 'openai_api_key', 'cloudinary_api_secret', 'aws_secret_access_key'],
            'notifications' => ['smtp_password'],
            default => [],
        };

        foreach ($secretKeys as $key) {
            $value = $merged[$key] ?? null;

            if (! is_string($value) || $value === '') {
                continue;
            }

            if (! PortalSensitiveCrypto::isEncrypted($value)) {
                $merged[$key] = PortalSensitiveCrypto::encrypt($value) ?? '';
            }
        }

        return $merged;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function decryptSensitiveGroup(array $data, string $slug): array
    {
        $secretKeys = match ($slug) {
            'integrations' => ['google_maps_api_key', 'openai_api_key', 'cloudinary_api_secret', 'aws_secret_access_key'],
            'notifications' => ['smtp_password'],
            default => [],
        };

        foreach ($secretKeys as $key) {
            if (! isset($data[$key]) || ! is_string($data[$key])) {
                continue;
            }

            $dec = PortalSensitiveCrypto::decrypt($data[$key]);
            if ($dec !== null) {
                $data[$key] = $dec;
            }
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $website
     */
    private function syncHomepageRow(array $website): void
    {
        try {
            if (! Schema::hasTable('homepage_settings')) {
                return;
            }
        } catch (Throwable) {
            return;
        }

        try {
            $row = HomepageSetting::query()->first() ?? new HomepageSetting;
            $row->fill([
                'hero_badge' => (string) ($website['hero_badge'] ?? $row->hero_badge),
                'hero_title' => (string) ($website['hero_headline'] ?? $row->hero_title),
                'hero_description' => (string) ($website['hero_subtext'] ?? $row->hero_description),
                'primary_cta_text' => (string) ($website['cta_plan_label'] ?? $row->primary_cta_text),
                'primary_cta_link' => (string) ($website['cta_plan_url'] ?? $row->primary_cta_link),
                'secondary_cta_text' => (string) ($website['cta_coverage_label'] ?? $row->secondary_cta_text),
                'secondary_cta_link' => (string) ($website['cta_coverage_url'] ?: ($row->secondary_cta_link ?? '/coverage')),
            ]);
            $row->save();
        } catch (Throwable) {
            // Safe no-op if schema mismatch in edge environments.
        }
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    public function writeAudit(?User $user, ?string $slug, string $action, array $metadata = []): void
    {
        if (! $this->auditsTableReady()) {
            return;
        }

        try {
            PortalSettingAudit::query()->create([
                'user_id' => $user?->id,
                'slug' => $slug,
                'action' => $action,
                'metadata' => $metadata,
                'ip_address' => request()->ip(),
            ]);
        } catch (Throwable) {
            //
        }
    }

    /**
     * @return array<int, string>
     */
    public static function splitLines(?string $raw): array
    {
        if ($raw === null || trim($raw) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $raw) ?: [])));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function normalizeContactPayload(array $payload): array
    {
        $social = [
            'facebook' => (string) ($payload['social']['facebook'] ?? ''),
            'instagram' => (string) ($payload['social']['instagram'] ?? ''),
            'linkedin' => (string) ($payload['social']['linkedin'] ?? ''),
            'x' => (string) ($payload['social']['x'] ?? ''),
            'youtube' => (string) ($payload['social']['youtube'] ?? ''),
        ];

        return [
            'phones' => static::splitLines($payload['phones_raw'] ?? ''),
            'emails' => static::splitLines($payload['emails_raw'] ?? ''),
            'office_locations' => static::splitLines($payload['office_locations_raw'] ?? ''),
            'whatsapp_display' => (string) ($payload['whatsapp_display'] ?? ''),
            'whatsapp_e164' => (string) ($payload['whatsapp_e164'] ?? ''),
            'social' => $social,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function normalizeMediaCoveragePayload(array $payload): array
    {
        $cats = static::splitLines($payload['board_categories_raw'] ?? '');

        return [
            'default_counties' => (string) ($payload['default_counties'] ?? ''),
            'default_constituencies' => (string) ($payload['default_constituencies'] ?? ''),
            'default_wards' => (string) ($payload['default_wards'] ?? ''),
            'map_theme' => (string) ($payload['map_theme'] ?? ''),
            'board_categories' => $cats,
            'marker_style' => (string) ($payload['marker_style'] ?? ''),
            'coverage_notes' => (string) ($payload['coverage_notes'] ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>|null  $yamlProfile
     * @return array<string, mixed>
     */
    public function mergeYamlContacts(array $portalContact, ?array $yamlProfile): array
    {
        $yamlContacts = is_array($yamlProfile) ? ($yamlProfile['contacts'] ?? []) : [];
        if (! is_array($yamlContacts)) {
            $yamlContacts = [];
        }

        $yamlSocial = is_array($yamlProfile) ? ($yamlProfile['social'] ?? []) : [];
        if (! is_array($yamlSocial)) {
            $yamlSocial = [];
        }

        $phones = $portalContact['phones'] ?? [];
        if ($phones === []) {
            $phones = $yamlContacts['phones'] ?? [];
        }

        $emails = $portalContact['emails'] ?? [];
        if ($emails === []) {
            $primary = $yamlContacts['email'] ?? null;
            $emails = $primary ? [(string) $primary] : [];
        }

        $offices = $portalContact['office_locations'] ?? [];
        if ($offices === []) {
            $office = $yamlContacts['office'] ?? null;
            $offices = $office ? [(string) $office] : [];
        }

        $social = array_filter(array_replace(
            $yamlSocial,
            array_filter($portalContact['social'] ?? [], static fn ($v) => $v !== null && $v !== '')
        ));

        $whatsappE164 = $portalContact['whatsapp_e164'] ?? '';
        if ($whatsappE164 === '') {
            $whatsappE164 = (string) ($yamlContacts['whatsapp_e164'] ?? '');
        }

        return [
            'phones' => is_array($phones) ? $phones : [],
            'emails' => is_array($emails) ? $emails : [],
            'office_locations' => is_array($offices) ? $offices : [],
            'whatsapp_display' => (string) ($portalContact['whatsapp_display'] ?? ''),
            'whatsapp_e164' => $whatsappE164,
            'social' => $social,
        ];
    }

    /**
     * Used when applying maintenance mode booleans coming from unchecked boxes.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, string>  $keys
     * @return array<string, mixed>
     */
    public static function coerceBooleans(array $data, array $keys): array
    {
        foreach ($keys as $key) {
            $data[$key] = filter_var(Arr::get($data, $key), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        }

        return $data;
    }
}
