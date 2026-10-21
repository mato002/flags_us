@extends('layouts.admin')

@section('title', $board->location.' | Board')
@section('header', 'Board detail')

@section('content')
    @include('admin.partials.page-header', [
        'title' => $board->location,
        'description' => ($board->reference_code ? '#'.$board->reference_code.' · ' : '').'Availability: '.$board->availability_status,
        'breadcrumb' => 'Media coverage · Board inventory',
    ])

    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.media.boards.edit', $board) }}" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Edit</a>
        <form method="POST" action="{{ route('admin.media.boards.destroy', $board) }}" onsubmit="return confirm('Delete this board?');" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-md border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-700 dark:border-rose-500/40 dark:text-rose-300">Delete</button>
        </form>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            @if ($board->photo_url)
                <img src="{{ $board->photo_url }}" alt="" class="w-full rounded-lg border border-slate-200 dark:border-white/10">
            @else
                <div class="flex h-48 items-center justify-center rounded-lg border border-dashed border-slate-300 text-sm text-slate-500 dark:border-white/15">No photo</div>
            @endif
        </div>
        <dl class="space-y-4 rounded-lg border border-slate-200 bg-white p-6 text-sm dark:border-white/10 dark:bg-white/5 lg:col-span-2">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Size</dt>
                    <dd class="mt-1">{{ $board->size ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Illumination</dt>
                    <dd class="mt-1">{{ $board->illumination ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Price</dt>
                    <dd class="mt-1">{{ $board->price !== null ? number_format((float) $board->price, 2) : '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Active</dt>
                    <dd class="mt-1">{{ $board->is_active ? 'Yes' : 'No' }}</dd>
                </div>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Traffic notes</dt>
                <dd class="mt-1 text-slate-800 dark:text-[#dfd5cd]">{{ $board->traffic_notes ?: '—' }}</dd>
            </div>
        </dl>
    </div>

    <p class="mt-6"><a href="{{ route('admin.media.boards.index') }}" class="text-sm font-semibold text-[#8b1e1a] underline">← Back to list</a></p>
@endsection
