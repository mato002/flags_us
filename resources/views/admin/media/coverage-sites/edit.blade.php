@extends('layouts.admin')

@section('title', 'Edit coverage site | Effective Media Ops')
@section('header', 'Edit site')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Edit coverage site',
        'description' => $site->site_name,
        'breadcrumb' => 'Media coverage · Coverage sites · Edit',
    ])

    <form method="POST" action="{{ route('admin.media.coverage-sites.update', $site) }}" class="space-y-6 rounded-lg border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        @csrf
        @method('PUT')
        @include('admin.media.coverage-sites._form', ['site' => $site])
        <div class="flex flex-wrap gap-2">
            <button type="submit" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Update</button>
            <a href="{{ route('admin.media.coverage-sites.show', $site) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm dark:border-white/20 dark:text-white">View</a>
        </div>
    </form>
@endsection
