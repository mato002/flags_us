<?php

namespace App\Services\Portal;

final class PortalSettingsRules
{
    /**
     * Laravel validation rules keyed by portal group slug.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function forGroup(string $slug): array
    {
        return match ($slug) {
            'general' => [
                'company_name' => ['nullable', 'string', 'max:120'],
                'tagline' => ['nullable', 'string', 'max:255'],
                'primary_color' => ['nullable', 'regex:/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'],
                'accent_color' => ['nullable', 'regex:/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'],
                'deep_maroon_color' => ['nullable', 'regex:/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'],
                'font_heading' => ['nullable', 'string', 'max:80'],
                'font_body' => ['nullable', 'string', 'max:80'],
                'logo' => ['nullable', 'file', 'image', 'max:3072'],
                'favicon' => ['nullable', 'file', 'mimes:ico,png,svg,webp', 'max:1024'],
                'remove_logo' => ['sometimes', 'boolean'],
                'remove_favicon' => ['sometimes', 'boolean'],
            ],
            'contact' => [
                'phones_raw' => ['nullable', 'string', 'max:5000'],
                'emails_raw' => ['nullable', 'string', 'max:5000'],
                'whatsapp_display' => ['nullable', 'string', 'max:64'],
                'whatsapp_e164' => ['nullable', 'string', 'max:20'],
                'office_locations_raw' => ['nullable', 'string', 'max:5000'],
                'social.facebook' => ['nullable', 'url', 'max:512'],
                'social.instagram' => ['nullable', 'url', 'max:512'],
                'social.linkedin' => ['nullable', 'url', 'max:512'],
                'social.x' => ['nullable', 'url', 'max:512'],
                'social.youtube' => ['nullable', 'url', 'max:512'],
            ],
            'website' => [
                'hero_badge' => ['nullable', 'string', 'max:120'],
                'hero_headline' => ['nullable', 'string', 'max:160'],
                'hero_subtext' => ['nullable', 'string', 'max:2000'],
                'cta_plan_label' => ['nullable', 'string', 'max:80'],
                'cta_plan_url' => ['nullable', 'string', 'max:512'],
                'cta_coverage_label' => ['nullable', 'string', 'max:80'],
                'cta_coverage_url' => ['nullable', 'string', 'max:512'],
                'cta_download_label' => ['nullable', 'string', 'max:80'],
                'maintenance_mode' => ['sometimes', 'boolean'],
                'sticky_navbar' => ['sometimes', 'boolean'],
                'floating_action_buttons' => ['sometimes', 'boolean'],
                'meta_title' => ['nullable', 'string', 'max:120'],
                'meta_description' => ['nullable', 'string', 'max:320'],
                'meta_keywords' => ['nullable', 'string', 'max:500'],
                'og_image' => ['nullable', 'string', 'max:512'],
                'google_site_verification' => ['nullable', 'string', 'max:120'],
                'bing_site_verification' => ['nullable', 'string', 'max:120'],
                'analytics_ga4_id' => ['nullable', 'string', 'max:32'],
                'analytics_gtm_id' => ['nullable', 'string', 'max:32'],
            ],
            'planner' => [
                'demographic_weight' => ['nullable', 'numeric', 'between:0,1'],
                'traffic_weight' => ['nullable', 'numeric', 'between:0,1'],
                'budget_optimizer' => ['sometimes', 'boolean'],
                'ai_recommendations' => ['sometimes', 'boolean'],
                'maps_provider' => ['nullable', 'in:openstreetmap,google'],
                'default_map_zoom' => ['nullable', 'integer', 'between:5,18'],
                'planner_notes' => ['nullable', 'string', 'max:5000'],
            ],
            'media_coverage' => [
                'default_counties' => ['nullable', 'string', 'max:5000'],
                'default_constituencies' => ['nullable', 'string', 'max:5000'],
                'default_wards' => ['nullable', 'string', 'max:5000'],
                'map_theme' => ['nullable', 'string', 'max:120'],
                'board_categories_raw' => ['nullable', 'string', 'max:5000'],
                'marker_style' => ['nullable', 'string', 'max:120'],
                'coverage_notes' => ['nullable', 'string', 'max:5000'],
            ],
            'quotations' => [
                'quote_prefix' => ['nullable', 'string', 'max:16'],
                'invoice_prefix' => ['nullable', 'string', 'max:16'],
                'vat_percentage' => ['nullable', 'numeric', 'between:0,50'],
                'currency_code' => ['nullable', 'string', 'size:3'],
                'quote_expiry_days' => ['nullable', 'integer', 'between:1,365'],
                'pdf_company_legal_name' => ['nullable', 'string', 'max:180'],
                'pdf_footer_note' => ['nullable', 'string', 'max:1000'],
                'pdf_show_watermark' => ['sometimes', 'boolean'],
            ],
            'documents' => [
                'lead_capture_enabled' => ['sometimes', 'boolean'],
                'pdf_watermark_text' => ['nullable', 'string', 'max:120'],
                'max_upload_mb' => ['nullable', 'integer', 'between:1,50'],
                'auto_thumbnails' => ['sometimes', 'boolean'],
                'documents_notes' => ['nullable', 'string', 'max:2000'],
            ],
            'notifications' => [
                'smtp_host' => ['nullable', 'string', 'max:120'],
                'smtp_port' => ['nullable', 'integer', 'between:1,65535'],
                'smtp_username' => ['nullable', 'string', 'max:120'],
                'smtp_encryption' => ['nullable', 'in:none,tls,ssl,starttls'],
                'smtp_password' => ['nullable', 'string', 'max:200'],
                'mail_from_address' => ['nullable', 'email', 'max:180'],
                'mail_from_name' => ['nullable', 'string', 'max:120'],
                'admin_alert_email' => ['nullable', 'email', 'max:180'],
                'quote_email_template_slug' => ['nullable', 'string', 'max:120'],
                'whatsapp_notifications_enabled' => ['sometimes', 'boolean'],
                'sms_notifications_enabled' => ['sometimes', 'boolean'],
                'sms_sender_id' => ['nullable', 'string', 'max:32'],
            ],
            'users_security' => [
                'session_lifetime_minutes' => ['nullable', 'integer', 'between:5,43200'],
                'two_factor_required' => ['sometimes', 'boolean'],
                'password_min_length' => ['nullable', 'integer', 'between:8,128'],
                'password_require_uppercase' => ['sometimes', 'boolean'],
                'password_require_number' => ['sometimes', 'boolean'],
                'password_require_symbol' => ['sometimes', 'boolean'],
                'activity_log_retention_days' => ['nullable', 'integer', 'between:7,730'],
                'security_notes' => ['nullable', 'string', 'max:2000'],
            ],
            'integrations' => [
                'google_maps_api_key' => ['nullable', 'string', 'max:512'],
                'openai_api_key' => ['nullable', 'string', 'max:512'],
                'openai_model' => ['nullable', 'string', 'max:80'],
                'meta_pixel_id' => ['nullable', 'string', 'max:64'],
                'cloudinary_cloud_name' => ['nullable', 'string', 'max:120'],
                'cloudinary_api_key' => ['nullable', 'string', 'max:120'],
                'cloudinary_api_secret' => ['nullable', 'string', 'max:200'],
                'aws_access_key_id' => ['nullable', 'string', 'max:128'],
                'aws_secret_access_key' => ['nullable', 'string', 'max:200'],
                'aws_default_region' => ['nullable', 'string', 'max:32'],
                'aws_bucket' => ['nullable', 'string', 'max:128'],
                'integrations_notes' => ['nullable', 'string', 'max:2000'],
            ],
            'performance' => [
                'image_optimization' => ['sometimes', 'boolean'],
                'lazy_loading' => ['sometimes', 'boolean'],
                'cache_public_pages_seconds' => ['nullable', 'integer', 'between:0,86400'],
                'performance_notes' => ['nullable', 'string', 'max:2000'],
            ],
            'backups' => [
                'frequency' => ['nullable', 'in:hourly,daily,weekly,manual'],
                'retention_days' => ['nullable', 'integer', 'between:1,365'],
                'backups_notes' => ['nullable', 'string', 'max:2000'],
            ],
            default => [],
        };
    }
}
