@extends('layouts.admin')

@section('title', 'Profile download leads | Effective Media Ops')
@section('header', 'Profile captures')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Profile download funnel',
        'description' => 'Leads collected before serving secure profile PDFs — export or archive handled here only.',
        'breadcrumb' => 'Sales · Profile downloads',
    ])

    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.profile-downloads.export', request()->query()) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white">Export CSV</a>
    </div>

    <form method="GET" class="mb-4 grid gap-3 rounded-lg border border-slate-200 bg-white p-4 text-sm dark:border-white/10 dark:bg-white/5 lg:grid-cols-5">
        <div>
            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">State</label>
            <select name="state" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent dark:text-white">
                <option value="active" @selected(request('state', 'active') === 'active')>Active captures</option>
                <option value="archived" @selected(request('state') === 'archived')>Archived</option>
                <option value="all" @selected(request('state') === 'all')>All downloads</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Keyword</label>
            <input type="search" name="search" value="{{ request('search') }}" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent dark:text-white">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Document</label>
            <select name="document" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent dark:text-white">
                <option value="">All files</option>
                @foreach ($documents as $doc)
                    <option value="{{ $doc }}" @selected(request('document') === $doc)>{{ $doc }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent dark:text-white">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent dark:text-white">
        </div>
        <div class="flex flex-wrap gap-2 lg:col-span-5">
            <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white dark:bg-[#f04a2a]">Apply</button>
            <a href="{{ route('admin.profile-downloads.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold dark:border-white/20 dark:text-white">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600 dark:bg-[#221b1b] dark:text-[#dfd5cd]">
                <tr>
                    <th class="px-4 py-3 font-semibold">Lead</th>
                    <th class="px-4 py-3 font-semibold">Contact</th>
                    <th class="px-4 py-3 font-semibold">Document</th>
                    <th class="px-4 py-3 font-semibold">Received</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($downloads as $download)
                    <tr class="border-t border-slate-100 dark:border-white/10">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $download->full_name }}</p>
                            <p class="text-xs text-slate-500">{{ $download->company_name ?: '—' }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-700 dark:text-[#dfd5cd]">
                            {{ $download->phone }}<br><span class="text-xs">{{ $download->email }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $download->filename }}</td>
                        <td class="px-4 py-3 text-xs">{{ $download->created_at?->diffForHumans() }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.profile-downloads.show', $download) }}" class="rounded border border-slate-300 px-2 py-1 text-xs font-semibold dark:border-white/20">View</a>
                            @if ($download->archived_at)
                                <form method="POST" action="{{ route('admin.profile-downloads.restore', $download) }}" class="inline">@csrf<button class="ml-1 text-[11px] font-semibold text-emerald-600 underline">Restore</button></form>
                            @else
                                <form method="POST" action="{{ route('admin.profile-downloads.archive', $download) }}" class="inline">@csrf<button class="ml-1 text-[11px] font-semibold text-slate-500 underline">Archive</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No matching rows.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $downloads->links() }}</div>
@endsection
