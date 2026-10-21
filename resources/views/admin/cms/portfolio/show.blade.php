@extends('layouts.admin')

@section('title', $item->title.' | Portfolio CMS')
@section('header', 'Portfolio detail')

@section('content')
    @include('admin.partials.page-header', [
        'title' => $item->title,
        'description' => $item->campaign_location ?: 'Locate this asset visually on-location with your field teams.',
        'breadcrumb' => 'CMS · Portfolio · Detail',
    ])

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            @if ($item->image_url)
                <img src="{{ $item->image_url }}" alt="" class="mb-4 w-full rounded-lg object-cover">
            @endif
            <dl class="space-y-2 text-sm text-slate-700 dark:text-[#dfd5cd]">
                <div><dt class="font-semibold text-slate-900 dark:text-white">Client</dt><dd>{{ $item->client_name ?: '—' }}</dd></div>
                <div><dt class="font-semibold text-slate-900 dark:text-white">Category</dt><dd>{{ $item->category ?: '—' }}</dd></div>
                <div><dt class="font-semibold text-slate-900 dark:text-white">Media type</dt><dd>{{ $item->media_type ?: '—' }}</dd></div>
                <div><dt class="font-semibold text-slate-900 dark:text-white">Featured</dt><dd>{{ $item->featured ? 'Yes' : 'No' }}</dd></div>
                <div><dt class="font-semibold text-slate-900 dark:text-white">Published</dt><dd>{{ $item->is_published ? 'Live' : 'Draft' }}</dd></div>
            </dl>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-[#cbbfb6]">Narrative</h2>
            <p class="mt-3 whitespace-pre-line text-sm text-slate-700 dark:text-[#dfd5cd]">{{ $item->description ?: 'No storyline captured yet.' }}</p>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('admin.cms.portfolio.edit', $item) }}" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white">Edit asset</a>
        <a href="{{ route('admin.cms.portfolio.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 dark:border-white/20 dark:text-white dark:hover:bg-white/10">Back</a>
    </div>
@endsection
