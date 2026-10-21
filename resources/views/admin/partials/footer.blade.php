<footer class="flex shrink-0 items-center justify-between gap-3 border-t border-[#ead8c9]/80 bg-[#fcf9f7]/95 px-4 py-2.5 backdrop-blur-xl sm:px-6 dark:border-white/10 dark:bg-[#141010]/95">
    <p class="text-[11px] text-[#7a665e] dark:text-[#9a9088]">
        <span class="font-semibold text-[#5c1514] dark:text-[#f7b396]">{{ $portalBrandName ?? 'Effective Media' }}</span>
        <span class="mx-1.5 opacity-40">·</span>
        <span>Internal operations</span>
    </p>
    <p class="text-[11px] text-[#a8977a]">
        <a href="{{ route('site.credits') }}" class="underline-offset-2 hover:underline dark:text-[#c9bfb7]" target="_blank" rel="noopener noreferrer">© {{ now()->year }}</a>
        <span class="mx-1.5 hidden opacity-40 sm:inline">|</span>
        <a href="{{ route('home') }}" class="hidden font-semibold text-[#8b1e1a] underline-offset-2 hover:underline sm:inline dark:text-[#f7b396]" target="_blank" rel="noopener noreferrer">Public site</a>
    </p>
</footer>
