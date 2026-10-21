<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-[#241312] dark:text-white">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-[#7a665e] dark:text-[#c4bbb4]">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="rounded-lg bg-rose-600 hover:bg-rose-700 focus:ring-rose-500 dark:bg-rose-500 dark:hover:bg-rose-600"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 dark:bg-[#1c1818]">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-[#241312] dark:text-white">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-[#7a665e] dark:text-[#c4bbb4]">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full max-w-md rounded-lg border-[#ead8c9] bg-white text-[#241312] shadow-sm focus:border-[#8b1e1a] focus:ring-[#8b1e1a] dark:border-white/15 dark:bg-[#231f1f] dark:text-[#f6ede8]"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex flex-wrap justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" type="button" class="rounded-lg border-[#ead8c9] bg-white text-[#241312] hover:bg-[#fdf8f5] dark:border-white/15 dark:bg-white/10 dark:text-white dark:hover:bg-white/15">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="rounded-lg bg-rose-600 hover:bg-rose-700 dark:bg-rose-500 dark:hover:bg-rose-600">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
