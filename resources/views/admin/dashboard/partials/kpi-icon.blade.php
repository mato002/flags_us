@php $icon = $icon ?? 'campaign'; @endphp
<div class="flex items-center justify-center">
    @switch($icon)
        @case('map')
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7v13zM21 13.382v4.764a1 1 0 01-.553.894l-6 3.382M15 21V7m0 14l6-3.382M15 21V7"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a2 2 0 110-4 2 2 0 010 4z"/></svg>
            @break
        @case('boards')
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16v14H4V5zm4 16h8M9 21h6"/><path stroke-linecap="round" stroke-width="2" d="M8 11h8M8 15h6"/></svg>
            @break
        @case('leads')
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-7-9H7a4 4 0 101.208 8.04M21 21l-6-6M9 21H7a6 6 0 01-6-6v-.5"/><path stroke-linecap="round" stroke-width="2" d="M12 21v-2"/></svg>
            @break
        @case('quotes')
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H9l-4 4v14a2 2 0 002 2z"/></svg>
            @break
        @case('download')
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2m-4-8l-4 4m0 0l-4-4m4 4V4"/></svg>
            @break
        @case('eye')
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12s4.5-8 10-8 10 8 10 8-4.5 8-10 8S2 12 2 12z"/></svg>
            @break
        @default
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
    @endswitch
</div>
