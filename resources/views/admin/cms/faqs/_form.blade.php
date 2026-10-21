<form method="POST" action="{{ $action }}" class="space-y-5 rounded-lg border border-slate-200 bg-white p-6 text-sm dark:border-white/10 dark:bg-white/5">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="question" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Question</label>
        <input id="question" name="question" value="{{ old('question', $faq?->question) }}" required maxlength="500" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('question')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="answer" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Answer</label>
        <textarea id="answer" name="answer" rows="8" required class="mt-1 w-full rounded border border-slate-300 px-3 py-2 font-mono text-xs dark:border-white/20 dark:bg-transparent dark:text-white">{{ old('answer', $faq?->answer) }}</textarea>
        @error('answer')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="sort_order" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Sort order</label>
            <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $faq?->sort_order ?? 0) }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
            @error('sort_order')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-end">
            <label class="flex items-center gap-2 text-slate-700 dark:text-[#dfd5cd]">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="rounded border-slate-400" @checked(old('is_active', $faq?->is_active ?? true))>
                <span class="text-sm font-semibold">Active</span>
            </label>
        </div>
    </div>

    <div class="flex flex-wrap gap-2">
        <button type="submit" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Save</button>
        <a href="{{ route('admin.cms.faqs.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold dark:border-white/20 dark:text-white">Cancel</a>
    </div>
</form>
