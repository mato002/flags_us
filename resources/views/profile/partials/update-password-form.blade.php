<section>
    <header>
        <h2 class="text-lg font-semibold text-[#241312] dark:text-white">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-[#7a665e] dark:text-[#c4bbb4]">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-[#5c1514] dark:text-[#f7b396]" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full rounded-lg border-[#ead8c9] bg-white text-[#241312] shadow-sm focus:border-[#8b1e1a] focus:ring-[#8b1e1a] dark:border-white/15 dark:bg-[#231f1f] dark:text-[#f6ede8]" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-[#5c1514] dark:text-[#f7b396]" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full rounded-lg border-[#ead8c9] bg-white text-[#241312] shadow-sm focus:border-[#8b1e1a] focus:ring-[#8b1e1a] dark:border-white/15 dark:bg-[#231f1f] dark:text-[#f6ede8]" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-[#5c1514] dark:text-[#f7b396]" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-lg border-[#ead8c9] bg-white text-[#241312] shadow-sm focus:border-[#8b1e1a] focus:ring-[#8b1e1a] dark:border-white/15 dark:bg-[#231f1f] dark:text-[#f6ede8]" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <x-primary-button class="rounded-lg border border-transparent bg-[#241312] text-white hover:bg-[#5c1514] focus:ring-[#8b1e1a] dark:bg-[#f04a2a] dark:hover:bg-[#8b1e1a]">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-medium text-emerald-700 dark:text-emerald-300"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
