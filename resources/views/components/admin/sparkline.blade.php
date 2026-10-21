@props([
    'values' => [],
    'stroke' => '#f04a2a',
])

@php
    $vals = collect($values)->map(fn ($v) => (float) $v)->values()->all();
    $w = 112;
    $h = 36;
    $pad = 2;
    $count = max(count($vals), 2);
    $max = max($vals ?: [1]);
    $min = min($vals ?: [0]);
    $range = max(0.0001, $max - $min);
    $lineD = '';
    $areaD = '';
    foreach ($vals as $i => $v) {
        $x = $pad + (($count - 1) > 0 ? ($i / ($count - 1)) * ($w - $pad * 2) : ($w / 2));
        $y = $pad + (($max - $v) / $range) * ($h - $pad * 2);
        $cmd = $i === 0 ? 'M' : 'L';
        $lineD .= "{$cmd}{$x} {$y} ";
        $areaD .= ($i === 0 ? "M{$x} {$y}" : " L{$x} {$y}");
    }
    $areaD .= " L".($w - $pad).' '.($h - $pad).' L'.$pad.' '.($h - $pad).' Z';
    $uid = 'emSpark'.md5($lineD);
@endphp

<svg {{ $attributes->merge(['class' => 'overflow-visible']) }} viewBox="0 0 {{ $w }} {{ $h }}" width="{{ $w }}" height="{{ $h }}" role="img" aria-hidden="true">
    <defs>
        <linearGradient id="{{ $uid }}g" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#f04a2a" stop-opacity="0.28"/>
            <stop offset="100%" stop-color="#8b1e1a" stop-opacity="0"/>
        </linearGradient>
    </defs>
    @if ($lineD !== '')
        <path d="{{ trim($areaD) }}" fill="url(#{{ $uid }}g)" class="transition-opacity"/>
        <path d="{{ trim($lineD) }}" fill="none" stroke="{{ $stroke }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" vector-effect="non-scaling-stroke"/>
    @endif
</svg>
