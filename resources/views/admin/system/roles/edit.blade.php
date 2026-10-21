@extends('layouts.admin')

@section('title', 'Edit '.$role->display_label.' | Access Control')
@section('header', 'Edit role')

@section('content')
    <div class="mb-6">
        <nav class="mb-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#927f72] dark:text-[#c9bfb7]">System · Access control · Edit</nav>
        <h1 class="text-2xl font-black tracking-tight text-[#42221f] dark:text-white">Edit {{ $role->display_label }}</h1>
        <p class="mt-2 max-w-2xl text-sm text-[#5c4944] dark:text-[#cbbfb6]">Refresh titles, narration, or accent hues. Sensitive profiles remain protected automatically.</p>
    </div>

    @if ($role->name === 'super_admin' && ! auth()->user()->hasRole('super_admin'))
        <div class="admin-glass-card mb-6 rounded-xl border border-amber-400/60 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-900 dark:border-amber-500/35 dark:bg-amber-500/10 dark:text-amber-100">
            Elevated safeguards apply to Super Administrator listings.
        </div>
    @endif

    <form method="POST" action="{{ route('admin.system.roles.update', $role) }}" class="max-w-xl space-y-5 rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-[#161212]/80">
        @csrf
        @method('PATCH')
        @if ($role->is_system)
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-[#cbbfb6]">
                System role keys stay fixed to protect automation and historical assignments.
            </div>
        @else
            <div>
                <label for="name" class="block text-xs font-bold uppercase text-slate-500 dark:text-[#cbbfb6]">Internal key</label>
                <input id="name" name="name" value="{{ old('name', $role->name) }}" required pattern="[a-z][a-z0-9_]*" class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-sm dark:border-white/20 dark:bg-transparent dark:text-white">
                @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
        @endif
        <div>
            <label for="display_name" class="block text-xs font-bold uppercase text-slate-500 dark:text-[#cbbfb6]">Display title</label>
            <input id="display_name" name="display_name" value="{{ old('display_name', $role->display_name ?: $role->display_label) }}" required class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-white/20 dark:bg-transparent dark:text-white">
            @error('display_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="description" class="block text-xs font-bold uppercase text-slate-500 dark:text-[#cbbfb6]">Role description</label>
            <textarea id="description" name="description" rows="3" class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-white/20 dark:bg-transparent dark:text-white">{{ old('description', $role->description) }}</textarea>
            @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="badge_color" class="block text-xs font-bold uppercase text-slate-500 dark:text-[#cbbfb6]">Accent color (hex)</label>
            <input id="badge_color" name="badge_color" value="{{ old('badge_color', $role->badge_color) }}" placeholder="#8b1e1a" class="mt-2 w-full max-w-[12rem] rounded-lg border border-slate-300 px-3 py-2 font-mono text-sm dark:border-white/20 dark:bg-transparent dark:text-white">
            @error('badge_color')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="submit" class="rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#f04a2a]">Save changes</button>
            <a href="{{ route('admin.system.roles.show', $role) }}" class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-bold dark:border-white/20 dark:text-white">Cancel</a>
        </div>
    </form>
@endsection
