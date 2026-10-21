@extends('layouts.admin')

@section('title', 'Edit user | Effective Media Ops')
@section('header', 'User roles')

@section('content')
    @include('admin.partials.page-header', [
        'title' => $user->name,
        'description' => $user->email,
        'breadcrumb' => 'System · Users · Edit',
    ])

    <form method="POST" action="{{ route('admin.system.users.update', $user) }}" class="space-y-6 rounded-lg border border-slate-200 bg-white p-6 text-sm dark:border-white/10 dark:bg-white/5">
        @csrf
        @method('PATCH')

        <fieldset>
            <legend class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Roles</legend>
            <div class="mt-3 space-y-2">
                @foreach ($roles as $role)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-slate-400" @checked($user->roles->contains('name', $role->name))>
                        <span>{{ $role->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('roles')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
            @error('roles.*')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
        </fieldset>

        <div class="flex flex-wrap gap-2">
            <button type="submit" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Save roles</button>
            <a href="{{ route('admin.system.users.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm dark:border-white/20 dark:text-white">Cancel</a>
        </div>
    </form>
@endsection
