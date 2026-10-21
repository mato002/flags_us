@extends('layouts.admin')

@section('title', 'Website Pages | Effective Media')
@section('header', 'Website pages')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Website Page Builder',
        'description' => 'Choose a page to edit content, SEO, sections, and publishing state.',
        'breadcrumb' => 'Website CMS · Page Builder',
    ])

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($slugs as $slug)
            @php($page = $pages[$slug] ?? null)
            <a href="{{ route('admin.cms.pages.edit', ['slug' => $slug]) }}" class="rounded-xl border border-slate-200 bg-white p-4 text-sm shadow-sm transition hover:border-[#8b1e1a]/40 dark:border-white/10 dark:bg-white/[0.04] dark:hover:border-[#f04a2a]/40">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500 dark:text-[#cbbfb6]">{{ str_replace('-', ' ', $slug) }}</p>
                    <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ ($page?->is_published ?? true) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-200' : 'bg-amber-100 text-amber-900 dark:bg-amber-500/20 dark:text-amber-200' }}">
                        {{ ($page?->is_published ?? true) ? 'Published' : 'Draft' }}
                    </span>
                </div>
                <p class="mt-2 font-semibold text-slate-900 dark:text-white">Open Visual Builder</p>
                <p class="mt-1 text-xs text-slate-600 dark:text-[#bfb4ad]">Slug: {{ $slug }}</p>
                <p class="mt-1 text-[11px] text-slate-500 dark:text-[#a89f98]">
                    Last updated: {{ optional($page?->updated_at)->format('M j, Y H:i') ?? 'Not edited yet' }}
                </p>
            </a>
        @endforeach
    </div>
@endsection
