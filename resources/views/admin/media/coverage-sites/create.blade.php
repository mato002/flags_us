@extends('layouts.admin')

@section('title', 'Add coverage site | Effective Media Ops')
@section('header', 'Coverage site')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Add coverage site',
        'description' => 'Store coordinates for map previews and sales alignment.',
        'breadcrumb' => 'Media coverage · Coverage sites · Create',
    ])

    <form method="POST" action="{{ route('admin.media.coverage-sites.store') }}" class="space-y-6 rounded-lg border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        @csrf
        @include('admin.media.coverage-sites._form', ['site' => null])
        <div class="flex flex-wrap gap-2">
            <button type="submit" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Save</button>
            <a href="{{ route('admin.media.coverage-sites.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm dark:border-white/20 dark:text-white">Cancel</a>
        </div>
    </form>
@endsection
