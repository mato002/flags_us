@extends('layouts.admin')

@section('title', $title.' | Effective Media Ops')
@section('header', $title)

@section('content')
    @include('admin.partials.page-header', [
        'title' => $title,
        'description' => $description,
        'breadcrumb' => 'Portal · '.$title,
    ])

    <div class="admin-glass-card rounded-xl border border-dashed border-[#dbc6b8] bg-white/60 p-10 text-center text-sm text-[#5c4944] dark:border-white/10 dark:bg-white/10 dark:text-[#dfd5cd]">
        <p>This module is staged for deeper workflow integration.</p>
        <p class="mt-3 text-xs text-[#8a7f78] dark:text-[#a89f98]">Technical key <span class="font-mono">{{ $moduleKey }}</span></p>
    </div>
@endsection
