@extends('layouts.admin')

@section('title', 'Users | Effective Media Ops')
@section('header', 'Users')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Portal users',
        'description' => 'Assign Spatie roles — additional profile fields stay in each user’s account page.',
        'breadcrumb' => 'System · Users',
    ])

    <form method="GET" class="mb-4 rounded-lg border border-slate-200 bg-white p-4 text-sm dark:border-white/10 dark:bg-white/5">
        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Search</label>
        <div class="mt-1 flex flex-wrap gap-2">
            <input type="search" name="search" value="{{ request('search') }}" class="min-w-0 flex-1 rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
            <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white dark:bg-[#f04a2a]">Search</button>
        </div>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-[#221b1b] dark:text-[#dfd5cd]">
                <tr>
                    <th class="px-4 py-3 font-semibold">Name</th>
                    <th class="px-4 py-3 font-semibold">Email</th>
                    <th class="px-4 py-3 font-semibold">Roles</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                    <tr class="border-t border-slate-200 dark:border-white/10">
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $u->name }}</td>
                        <td class="px-4 py-3">{{ $u->email }}</td>
                        <td class="px-4 py-3 text-xs">{{ $u->roles->pluck('name')->join(', ') ?: '—' }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.system.users.edit', $u) }}" class="font-semibold text-[#8b1e1a] underline">Edit roles</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-slate-500">No matching users.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection
