{{-- Included with @include: pass title, optional description, breadcrumb, optional $actions HTML --}}

<div class="mb-6 flex flex-wrap items-start justify-between gap-4">
    <div>
        @isset($breadcrumb)
            <nav class="mb-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#927f72] dark:text-[#c9bfb7]" aria-label="Breadcrumb">
                {{ $breadcrumb }}
            </nav>
        @endisset
        <h1 class="text-2xl font-black tracking-tight text-[#42221f] dark:text-white sm:text-[1.65rem]">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 max-w-2xl text-sm text-[#5c4944] dark:text-[#cbbfb6]">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>
