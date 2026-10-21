<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortalSettingAudit;
use App\Services\Portal\PortalSettingsRules;
use App\Services\Portal\PortalSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
class PortalSettingsController extends Controller
{
    /**
     * @var array<string, array<int, string>>
     */
    private array $booleanFields = [
        'website' => ['maintenance_mode', 'sticky_navbar', 'floating_action_buttons'],
        'planner' => ['budget_optimizer', 'ai_recommendations'],
        'quotations' => ['pdf_show_watermark'],
        'documents' => ['lead_capture_enabled', 'auto_thumbnails'],
        'notifications' => ['whatsapp_notifications_enabled', 'sms_notifications_enabled'],
        'users_security' => ['two_factor_required', 'password_require_uppercase', 'password_require_number', 'password_require_symbol'],
        'performance' => ['image_optimization', 'lazy_loading'],
        'general' => ['remove_logo', 'remove_favicon'],
    ];

    public function index(PortalSettingsService $portalSettings): View
    {
        $groups = $portalSettings->getAllForAdmin();

        $audits = PortalSettingAudit::query()
            ->latest()
            ->with('user:id,name,email')
            ->limit(60)
            ->get();

        return view('admin.settings.index', [
            'groups' => $groups,
            'audits' => $audits,
            'editableSlugs' => PortalSettingsService::EDITABLE_GROUPS,
        ]);
    }

    public function update(Request $request, PortalSettingsService $portalSettings, string $group): RedirectResponse
    {
        abort_unless(in_array($group, PortalSettingsService::EDITABLE_GROUPS, true), 404);

        $rules = PortalSettingsRules::forGroup($group);
        abort_if($rules === [], 404);

        $validator = Validator::make($request->all(), $rules);
        $validated = $validator->validate();

        $payload = match ($group) {
            'contact' => PortalSettingsService::normalizeContactPayload($validated),
            'media_coverage' => PortalSettingsService::normalizeMediaCoveragePayload($validated),
            'general' => $this->handleGeneralPayload($request, $validated, $portalSettings),
            default => PortalSettingsService::coerceBooleans(
                $validated,
                $this->booleanFields[$group] ?? []
            ),
        };

        if ($group === 'quotations' && isset($payload['currency_code'])) {
            $payload['currency_code'] = strtoupper((string) $payload['currency_code']);
        }

        $portalSettings->saveGroup($group, $payload, $request->user());

        return redirect()
            ->route('admin.system.settings')
            ->withFragment($group)
            ->with('status', 'portal-settings-saved');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function handleGeneralPayload(Request $request, array $validated, PortalSettingsService $portalSettings): array
    {
        $existing = $portalSettings->get('general');

        $data = Arr::except($validated, ['logo', 'favicon', 'remove_logo', 'remove_favicon']);
        $data = PortalSettingsService::coerceBooleans($data, ['remove_logo', 'remove_favicon']);

        $disk = Storage::disk('public');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('portal-branding', 'public');
            if (is_string($path)) {
                $data['logo_path'] = $path;
            }
        }

        if (! empty($data['remove_logo'])) {
            $old = $existing['logo_path'] ?? null;
            if (is_string($old) && $old !== '' && $disk->exists($old)) {
                $disk->delete($old);
            }
            $data['logo_path'] = null;
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('portal-branding', 'public');
            if (is_string($path)) {
                $data['favicon_path'] = $path;
            }
        }

        if (! empty($data['remove_favicon'])) {
            $old = $existing['favicon_path'] ?? null;
            if (is_string($old) && $old !== '' && $disk->exists($old)) {
                $disk->delete($old);
            }
            $data['favicon_path'] = null;
        }

        unset($data['remove_logo'], $data['remove_favicon']);

        return $data;
    }

    public function action(Request $request, PortalSettingsService $portalSettings, string $action): RedirectResponse
    {
        $allow = ['clear-cache', 'optimize', 'manual-backup'];

        abort_unless(in_array($action, $allow, true), 404);

        $message = match ($action) {
            'clear-cache' => $this->runClearCache($portalSettings, $request),
            'optimize' => $this->runOptimize($portalSettings, $request),
            'manual-backup' => $this->runManualBackupStub($portalSettings, $request),
            default => 'unsupported',
        };

        return back()
            ->withFragment('performance')
            ->with('status', $message);
    }

    private function runClearCache(PortalSettingsService $portalSettings, Request $request): string
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
        } catch (\Throwable) {
            return 'portal-cache-partial';
        }

        $portalSettings->forgetCaches();
        $portalSettings->writeAudit($request->user(), null, 'action:clear-cache', []);

        return 'portal-cache-cleared';
    }

    private function runOptimize(PortalSettingsService $portalSettings, Request $request): string
    {
        try {
            Artisan::call('optimize');
        } catch (\Throwable) {
            return 'portal-optimize-failed';
        }

        $portalSettings->forgetCaches();
        $portalSettings->writeAudit($request->user(), null, 'action:optimize', []);

        return 'portal-optimized';
    }

    private function runManualBackupStub(PortalSettingsService $portalSettings, Request $request): string
    {
        $snapshot = $portalSettings->get('backups');
        $snapshot['last_manual_backup_at'] = now()->toIso8601String();

        $portalSettings->saveGroup('backups', $snapshot, $request->user());

        return 'portal-backup-logged';
    }
}
