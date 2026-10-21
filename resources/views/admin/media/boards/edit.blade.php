@extends('layouts.admin')

@section('title', 'Edit board | Effective Media Ops')
@section('header', 'Edit board')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Edit board',
        'description' => $board->location,
        'breadcrumb' => 'Media coverage · Board inventory · Edit',
    ])

    <form method="POST" action="{{ route('admin.media.boards.update', $board) }}" enctype="multipart/form-data" class="space-y-6 rounded-lg border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        @csrf
        @method('PUT')
        @include('admin.media.boards._fields', ['board' => $board])
        <div class="flex flex-wrap gap-2">
            <button type="submit" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Update</button>
            <a href="{{ route('admin.media.boards.show', $board) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm dark:border-white/20 dark:text-white">View</a>
        </div>
    </form>
@endsection
