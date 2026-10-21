<section>
    <header>
        <h2 class="text-lg font-semibold text-[#241312] dark:text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-[#7a665e] dark:text-[#c4bbb4]">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-[#5c1514] dark:text-[#f7b396]" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-lg border-[#ead8c9] bg-white text-[#241312] shadow-sm focus:border-[#8b1e1a] focus:ring-[#8b1e1a] dark:border-white/15 dark:bg-[#231f1f] dark:text-[#f6ede8]" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-[#5c1514] dark:text-[#f7b396]" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-lg border-[#ead8c9] bg-white text-[#241312] shadow-sm focus:border-[#8b1e1a] focus:ring-[#8b1e1a] dark:border-white/15 dark:bg-[#231f1f] dark:text-[#f6ede8]" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-[#241312] dark:text-[#f6ede8]">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" type="submit" class="rounded-md text-sm font-semibold text-[#8b1e1a] underline decoration-[#8b1e1a]/40 underline-offset-2 hover:text-[#5c1514] focus:outline-none focus:ring-2 focus:ring-[#8b1e1a] focus:ring-offset-2 dark:text-[#f7b396] dark:hover:text-white">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-emerald-700 dark:text-emerald-300">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <x-primary-button class="rounded-lg border border-transparent bg-[#241312] text-white hover:bg-[#5c1514] focus:ring-[#8b1e1a] dark:bg-[#f04a2a] dark:hover:bg-[#8b1e1a]">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
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
