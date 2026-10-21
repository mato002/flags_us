@extends('layouts.admin')

@section('title', 'Board inventory | Effective Media Ops')
@section('header', 'Boards')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Board inventory',
        'description' => 'Sites, illumination, pricing, and maintenance readiness.',
        'breadcrumb' => 'Media coverage · Board inventory',
    ])

    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.media.boards.create') }}" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Add board</a>
    </div>

    <form method="GET" class="mb-4 flex flex-wrap gap-3 rounded-lg border border-slate-200 bg-white p-4 text-sm dark:border-white/10 dark:bg-white/5">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Location or reference…" class="min-w-0 flex-1 rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        <input type="text" name="availability" value="{{ request('availability') }}" placeholder="Availability filter" class="min-w-[10rem] rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white dark:bg-[#f04a2a]">Apply</button>
        <a href="{{ route('admin.media.boards.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-xs dark:border-white/20 dark:text-white">Reset</a>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-[#221b1b] dark:text-[#dfd5cd]">
                <tr>
                    <th class="px-4 py-3 font-semibold">Photo</th>
                    <th class="px-4 py-3 font-semibold">Location</th>
                    <th class="px-4 py-3 font-semibold">Availability</th>
                    <th class="px-4 py-3 font-semibold">Maintenance</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($boards as $board)
                    <tr class="border-t border-slate-200 dark:border-white/10">
                        <td class="px-4 py-3">
                            @if ($board->photo_url)
                                <img src="{{ $board->photo_url }}" alt="" class="h-12 w-16 rounded border border-slate-200 object-cover dark:border-white/10">
                            @else
                                <span class="text-xs text-slate-400">No photo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $board->location }}</td>
                        <td class="px-4 py-3">{{ $board->availability_status }}</td>
                        <td class="px-4 py-3">{{ $board->maintenance_status }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.media.boards.show', $board) }}" class="font-semibold text-slate-600 underline dark:text-[#f7b396]">View</a>
                            <a href="{{ route('admin.media.boards.edit', $board) }}" class="ml-2 font-semibold text-[#8b1e1a] underline">Edit</a>
                            <form method="POST" action="{{ route('admin.media.boards.destroy', $board) }}" class="inline" onsubmit="return confirm('Remove this board record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 text-rose-600 underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-slate-500">No boards tracked yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $boards->links() }}</div>
@endsection
