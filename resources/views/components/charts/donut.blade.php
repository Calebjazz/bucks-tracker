{{--
    Donut chart with legend. Each slice is an SVG circle stroke with a dash length equal to its share.
    $segments: list of ['label' => 'Transport', 'value' => int, 'color' => '#hex']
--}}
@props(['segments', 'caption' => 'Total spent', 'label' => 'Spending by category'])

@php
    $total = max(1, collect($segments)->sum('value'));
    $radius = 80;
    $circumference = 2 * M_PI * $radius;
    $gap = 2;
    $offset = 0;
    $compact = fn (int $value): string => $value >= 1_000_000 ? number_format($value / 1_000_000, 2).'M' : number_format($value / 1_000).'K';
@endphp

<figure {{ $attributes->class('flex flex-col items-center gap-6 sm:flex-row lg:flex-col') }}>
    <div class="relative size-44 shrink-0">
        <svg viewBox="0 0 200 200" class="donut size-full -rotate-90" role="img" aria-label="{{ $label }}">
            <circle cx="100" cy="100" r="{{ $radius }}" fill="none" stroke="#f1f5f9" stroke-width="22" />
            @foreach ($segments as $segment)
                @php($length = $segment['value'] / $total * $circumference)
                <circle class="donut-segment cursor-pointer focus:outline-none" cx="100" cy="100" r="{{ $radius }}" fill="none" tabindex="0"
                    stroke="{{ $segment['color'] }}" stroke-width="22"
                    stroke-dasharray="{{ max(0, $length - $gap) }} {{ $circumference }}"
                    stroke-dashoffset="{{ -$offset }}"
                    data-tooltip="{{ $segment['label'] }}&#10;TZS {{ number_format($segment['value']) }} · {{ round($segment['value'] / $total * 100) }}%" />
                @php($offset += $length)
            @endforeach
        </svg>
        <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-center">
            <span class="text-xs text-muted">{{ $caption }}</span>
            <span class="mt-0.5 text-lg font-semibold tracking-tight tabular-nums">TZS {{ $compact($total) }}</span>
        </div>
    </div>

    <figcaption class="w-full min-w-0 flex-1">
        <ul class="flex flex-col gap-3">
            @foreach ($segments as $segment)
                <li class="flex items-center gap-3 text-sm">
                    <span class="size-2.5 shrink-0 rounded-sm" style="background-color: {{ $segment['color'] }}"></span>
                    <span class="min-w-0 flex-1 truncate text-ink">{{ $segment['label'] }}</span>
                    <span class="text-muted tabular-nums">{{ round($segment['value'] / $total * 100) }}%</span>
                    <span class="w-20 text-right font-medium tabular-nums">{{ number_format($segment['value']) }}</span>
                </li>
            @endforeach
        </ul>
    </figcaption>
</figure>
