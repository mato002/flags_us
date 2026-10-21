<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class EnforcePortalMaintenanceMode
{
    /**
     * Block public site traffic when portal maintenance mode is enabled (admins bypass).
     *
     * @param  \Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (! Schema::hasTable('portal_setting_groups')) {
                return $next($request);
            }
        } catch (\Throwable) {
            return $next($request);
        }

        if ($request->is('admin', 'admin/*', 'login', 'logout', 'forgot-password', 'reset-password', 'reset-password/*')) {
            return $next($request);
        }

        if ($request->user()) {
            return $next($request);
        }

        /** @var \App\Services\Portal\PortalSettingsService $portal */
        $portal = app(\App\Services\Portal\PortalSettingsService::class);

        try {
            $website = $portal->get('website');
        } catch (\Throwable) {
            return $next($request);
        }

        $enabled = filter_var($website['maintenance_mode'] ?? false, FILTER_VALIDATE_BOOL);

        if ($enabled) {
            abort(503, __('The site is undergoing scheduled maintenance. Please try again soon.'));
        }

        return $next($request);
    }
}
