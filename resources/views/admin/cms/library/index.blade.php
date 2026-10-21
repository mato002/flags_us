@extends('layouts.admin')

@section('title', 'Documents library | Effective Media Ops')
@section('header', 'Documents')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Document library',
        'description' => 'Upload collateral, control lead-gated downloads, and retire outdated files.',
        'breadcrumb' => 'Website CMS · Documents',
    ])

    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.cms.library.create') }}" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Upload document</a>
    </div>

    <form method="GET" class="mb-4 flex flex-wrap gap-3 rounded-lg border border-slate-200 bg-white p-4 text-sm dark:border-white/10 dark:bg-white/5">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search title or description…" class="min-w-0 flex-1 rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        <select name="active" class="rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
            <option value="">All</option>
            <option value="1" @selected(request('active') === '1')>Active only</option>
            <option value="0" @selected(request('active') === '0')>Inactive</option>
        </select>
        <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white dark:bg-[#f04a2a]">Apply</button>
        <a href="{{ route('admin.cms.library.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold dark:border-white/20 dark:text-white">Reset</a>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-[#221b1b] dark:text-[#dfd5cd]">
                <tr>
                    <th class="px-4 py-3 font-semibold">Title</th>
                    <th class="px-4 py-3 font-semibold">Lead gate</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $doc)
                    <tr class="border-t border-slate-200 dark:border-white/10">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $doc->title }}</p>
                            @if ($doc->description)
                                <p class="text-xs text-slate-500">{{ Str::limit($doc->description, 80) }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $doc->requires_lead_capture ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">{{ $doc->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ asset('storage/'.ltrim($doc->file_path, '/')) }}" target="_blank" rel="noopener" class="mr-2 font-semibold text-slate-600 underline dark:text-[#f7b396]">Preview</a>
                            <a href="{{ route('admin.cms.library.edit', ['library' => $doc]) }}" class="font-semibold text-[#8b1e1a] underline">Edit</a>
                            <form method="POST" action="{{ route('admin.cms.library.destroy', ['library' => $doc]) }}" class="inline" onsubmit="return confirm('Delete this document?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 text-rose-600 underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-slate-500">No documents yet. Upload the first file to populate download links.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $documents->links() }}</div>
@endsection
