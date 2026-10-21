@php($p = $groups['planner'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')

<section id="planner" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Smart Campaign Planner</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Recommendation weighting · maps provider · rollout notes.</p>
    </div>

    <form action="{{ route('admin.system.settings.update', 'planner') }}" method="post" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Demographic weight (0–1)
            <input type="number" step="0.01" min="0" max="1" name="demographic_weight" value="{{ old('demographic_weight', $p['demographic_weight'] ?? '0') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Traffic weight (0–1)
            <input type="number" step="0.01" min="0" max="1" name="traffic_weight" value="{{ old('traffic_weight', $p['traffic_weight'] ?? '0') }}" class="{{ $fc }}">
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            <input type="hidden" name="budget_optimizer" value="0">
            <input type="checkbox" name="budget_optimizer" value="1" @checked(old('budget_optimizer', $p['budget_optimizer'] ?? false)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            Budget optimization
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            <input type="hidden" name="ai_recommendations" value="0">
            <input type="checkbox" name="ai_recommendations" value="1" @checked(old('ai_recommendations', $p['ai_recommendations'] ?? false)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            AI-assisted recommendations <span class="text-xs font-normal opacity-75">requires OpenAI key</span>
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Maps provider
            <select name="maps_provider" class="{{ $fc }}">
                @foreach (['openstreetmap' => 'OpenStreetMap tiles', 'google' => 'Google Maps JavaScript API'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('maps_provider', $p['maps_provider'] ?? '') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Default planner zoom (5–18)
            <input type="number" min="5" max="18" name="default_map_zoom" value="{{ old('default_map_zoom', $p['default_map_zoom'] ?? 7) }}" class="{{ $fc }}">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Operator notes / guardrails
            <textarea name="planner_notes" rows="4" class="{{ $fc }}">{{ old('planner_notes', $p['planner_notes'] ?? '') }}</textarea>
        </label>

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save planner</button>
        </div>
    </form>
</section>
