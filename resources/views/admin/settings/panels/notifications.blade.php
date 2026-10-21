@php($n = $groups['notifications'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')

<section id="notifications" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Notifications</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">SMTP, internal alerts, and messaging toggles.</p>
    </div>

    <form action="{{ route('admin.system.settings.update', 'notifications') }}" method="post" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            SMTP host
            <input type="text" name="smtp_host" value="{{ old('smtp_host', $n['smtp_host'] ?? '') }}" class="{{ $fc }}" autocomplete="off">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            SMTP port
            <input type="number" name="smtp_port" value="{{ old('smtp_port', $n['smtp_port'] ?? 587) }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            SMTP encryption
            <select name="smtp_encryption" class="{{ $fc }}">
                @foreach (['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None', 'starttls' => 'STARTTLS'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('smtp_encryption', $n['smtp_encryption'] ?? 'tls') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            SMTP username
            <input type="text" name="smtp_username" value="{{ old('smtp_username', $n['smtp_username'] ?? '') }}" class="{{ $fc }}" autocomplete="username">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            SMTP password @if(!empty($n['_meta']['smtp_password_stored']))<span class="text-xs font-normal text-emerald-700 dark:text-emerald-300">— stored securely; leave blank to keep</span>@endif
            <input type="password" name="smtp_password" value="" class="{{ $fc }}" autocomplete="current-password">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            From email
            <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $n['mail_from_address'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            From name
            <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $n['mail_from_name'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Admin alerts email (quote spikes, profile downloads…)
            <input type="email" name="admin_alert_email" value="{{ old('admin_alert_email', $n['admin_alert_email'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Email template slug namespace
            <input type="text" name="quote_email_template_slug" value="{{ old('quote_email_template_slug', $n['quote_email_template_slug'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            SMS sender ID
            <input type="text" name="sms_sender_id" value="{{ old('sms_sender_id', $n['sms_sender_id'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4] lg:col-span-2">
            <input type="hidden" name="whatsapp_notifications_enabled" value="0">
            <input type="checkbox" name="whatsapp_notifications_enabled" value="1" @checked(old('whatsapp_notifications_enabled', $n['whatsapp_notifications_enabled'] ?? false)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            WhatsApp push notifications pipeline
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4] lg:col-span-2">
            <input type="hidden" name="sms_notifications_enabled" value="0">
            <input type="checkbox" name="sms_notifications_enabled" value="1" @checked(old('sms_notifications_enabled', $n['sms_notifications_enabled'] ?? false)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            SMS notifications pipeline
        </label>

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save notifications</button>
        </div>
    </form>
</section>
