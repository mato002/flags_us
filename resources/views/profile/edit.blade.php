@extends('layouts.admin')

@section('title', 'Account profile | Effective Media')
@section('header', 'Account profile')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6 pb-10">
        <div class="admin-glass-card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="admin-glass-card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="admin-glass-card p-6 sm:p-8 border-rose-200/60 dark:border-rose-500/20">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
