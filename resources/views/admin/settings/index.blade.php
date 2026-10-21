@extends('layouts.admin')

@section('title', 'Portal configuration | Effective Media')
@section('header', 'Configuration center')

@section('content')
    @php
        $statusCopy = match (session('status')) {
            'portal-settings-saved' => 'Section saved successfully.',
            'portal-cache-cleared' => 'Application cache cleared.',
            'portal-cache-partial' => 'Cache clear finished with warnings — check logs.',
            'portal-optimized' => 'Optimization completed.',
            'portal-optimize-failed' => 'Optimization could not run — check server permissions.',
            'portal-backup-logged' => 'Backup timestamp recorded. Configure a backup driver for real archives.',
            default => null,
        };
        $nav = [
            'general' => 'General branding',
            'contact' => 'Contact information',
            'website' => 'Website',
            'planner' => 'Smart Campaign Planner',
            'media_coverage' => 'Media coverage',
            'quotations' => 'Quotation settings',
            'documents' => 'Document center',
            'notifications' => 'Notifications',
            'users_security' => 'Users & security',
            'integrations' => 'Integrations',
            'performance' => 'Performance',
            'backups' => 'Backups',
            'audit_logs' => 'Audit logs',
        ];
    @endphp

    @if ($statusCopy)
        <div class="admin-glass-card mb-6 border-emerald-200/80 bg-emerald-50/90 px-4 py-3 text-sm font-semibold text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/15 dark:text-emerald-100" role="status">
            {{ $statusCopy }}
        </div>
    @endif

    <div class="flex flex-col gap-8 lg:flex-row lg:items-start lg:gap-10" x-data="{ navOpen: false }">
        <aside class="admin-glass-card shrink-0 p-4 lg:sticky lg:top-4 lg:w-64 lg:self-start">
            <div class="flex items-center justify-between gap-2 lg:block">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#8b1e1a] dark:text-[#f7b396]">Sections</p>
                <button type="button" class="rounded-md border border-[#dbc6b8] px-2 py-1 text-xs font-semibold text-[#5c1514] dark:border-white/15 dark:text-[#f2ebe6] lg:hidden" @click="navOpen = !navOpen" aria-expanded="false" :aria-expanded="navOpen ? 'true' : 'false'">
                    <span x-text="navOpen ? 'Hide' : 'Show'"></span>
                </button>
            </div>
            <nav class="mt-3 flex flex-col gap-1 border-t border-[#ead8c9] pt-3 dark:border-white/10" :class="navOpen ? 'flex' : 'hidden lg:flex'">
                @foreach ($nav as $slug => $label)
                    <a href="#{{ $slug }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-[#3f2f2d] hover:bg-[#f4e9df] dark:text-[#e8dbd4] dark:hover:bg-white/10">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="min-w-0 flex-1 space-y-10 pb-28">
            @include('admin.settings.panels.general', ['groups' => $groups])
            @include('admin.settings.panels.contact', ['groups' => $groups])
            @include('admin.settings.panels.website', ['groups' => $groups])
            @include('admin.settings.panels.planner', ['groups' => $groups])
            @include('admin.settings.panels.media_coverage', ['groups' => $groups])
            @include('admin.settings.panels.quotations', ['groups' => $groups])
            @include('admin.settings.panels.documents', ['groups' => $groups])
            @include('admin.settings.panels.notifications', ['groups' => $groups])
            @include('admin.settings.panels.users_security', ['groups' => $groups])
            @include('admin.settings.panels.integrations', ['groups' => $groups])
            @include('admin.settings.panels.performance', ['groups' => $groups])
            @include('admin.settings.panels.backups', ['groups' => $groups])
            @include('admin.settings.panels.audit_logs', ['audits' => $audits])
        </div>
    </div>

    <div class="pointer-events-none fixed bottom-6 right-6 z-20 hidden max-w-xs rounded-xl border border-[#ecdac8] bg-white/95 px-4 py-3 text-xs font-medium text-[#5c4944] shadow-lg backdrop-blur dark:border-white/10 dark:bg-[#161212]/95 dark:text-[#ddd5cf] lg:block">
        <p class="font-bold text-[#5c1514] dark:text-[#f7b396]">Save per section</p>
        <p class="mt-1 leading-relaxed opacity-90">Each card has its own <span class="font-semibold">Save</span> action. Sensitive keys stay encrypted at rest.</p>
    </div>
@endsection
