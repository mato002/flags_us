@extends('layouts.admin')

@section('title', $site->site_name.' | Coverage')
@section('header', 'Site detail')

@section('content')
    @include('admin.partials.page-header', [
        'title' => $site->site_name,
        'description' => $site->county.' · '.$site->town,
        'breadcrumb' => 'Media coverage · Coverage sites',
    ])

    <div class="mb-4 flex flex-wrap gap-2">
        @if ($site->latitude !== null && $site->longitude !== null)
            <a href="https://www.google.com/maps?q={{ $site->latitude }},{{ $site->longitude }}" target="_blank" rel="noopener noreferrer" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Map preview</a>
        @endif
        <a href="{{ route('admin.media.coverage-sites.edit', $site) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold dark:border-white/20 dark:text-white">Edit</a>
        <form method="POST" action="{{ route('admin.media.coverage-sites.destroy', $site) }}" onsubmit="return confirm('Delete this site?');" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-md border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-700 dark:border-rose-500/40 dark:text-rose-300">Delete</button>
        </form>
    </div>

    <dl class="grid gap-4 rounded-lg border border-slate-200 bg-white p-6 text-sm dark:border-white/10 dark:bg-white/5 sm:grid-cols-2">
        <div>
            <dt class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Poles</dt>
            <dd class="mt-1 font-medium text-slate-900 dark:text-white">{{ $site->poles_count }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Media type</dt>
            <dd class="mt-1">{{ $site->media_type ?: '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Latitude</dt>
            <dd class="mt-1">{{ $site->latitude ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Longitude</dt>
            <dd class="mt-1">{{ $site->longitude ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Active</dt>
            <dd class="mt-1">{{ $site->is_active ? 'Yes' : 'No' }}</dd>
        </div>
    </dl>

    <p class="mt-6"><a href="{{ route('admin.media.coverage-sites.index') }}" class="text-sm font-semibold text-[#8b1e1a] underline">← Back to list</a></p>
@endsection
