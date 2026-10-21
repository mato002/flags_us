@php($u = $groups['users_security'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')

<section id="users_security" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Users &amp; security</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Session lifetime, MFA policy, passwords, audits retention.</p>
    </div>

    <form action="{{ route('admin.system.settings.update', 'users_security') }}" method="post" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Session lifetime (minutes)
            <input type="number" min="5" max="43200" name="session_lifetime_minutes" value="{{ old('session_lifetime_minutes', $u['session_lifetime_minutes'] ?? 120) }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Password minimum length
            <input type="number" min="8" max="128" name="password_min_length" value="{{ old('password_min_length', $u['password_min_length'] ?? 10) }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Activity log retention (days)
            <input type="number" min="7" max="730" name="activity_log_retention_days" value="{{ old('activity_log_retention_days', $u['activity_log_retention_days'] ?? 90) }}" class="{{ $fc }}">
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4] lg:col-span-2">
            <input type="hidden" name="two_factor_required" value="0">
            <input type="checkbox" name="two_factor_required" value="1" @checked(old('two_factor_required', $u['two_factor_required'] ?? false)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            Require MFA for privileged portals <span class="text-xs font-normal opacity-75">(wire Fortify/Jetstream to enforce)</span>
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            <input type="hidden" name="password_require_uppercase" value="0">
            <input type="checkbox" name="password_require_uppercase" value="1" @checked(old('password_require_uppercase', $u['password_require_uppercase'] ?? true)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            Require uppercase letters
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            <input type="hidden" name="password_require_number" value="0">
            <input type="checkbox" name="password_require_number" value="1" @checked(old('password_require_number', $u['password_require_number'] ?? true)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            Require digits
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4] lg:col-span-2">
            <input type="hidden" name="password_require_symbol" value="0">
            <input type="checkbox" name="password_require_symbol" value="1" @checked(old('password_require_symbol', $u['password_require_symbol'] ?? false)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            Require symbols
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Security notes / compliance cues
            <textarea name="security_notes" rows="3" class="{{ $fc }}">{{ old('security_notes', $u['security_notes'] ?? '') }}</textarea>
        </label>

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save security posture</button>
        </div>
    </form>
</section>
