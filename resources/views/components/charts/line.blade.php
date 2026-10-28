{{--
    Single-series line chart. The line is an SVG stretched to the container;
    labels, dots and hover targets are HTML so they stay crisp at any width.
    $data: list of ['label' => 'Oct 6', 'full' => 'Week of Oct 6', 'value' => int]
--}}
@props(['data', 'height' => 'h-60', 'label' => 'Spending trend', 'color' => '#dc2626'])

@php
    $count = count($data);
    $max = max(1, collect($data)->max('value'));
    $rawStep = $max / 4;
    $magnitude = 10 ** floor(log10($rawStep));
    $step = collect([1, 2, 2.5, 5, 10])->first(fn (float $n): bool => $n * $magnitude >= $rawStep) * $magnitude;
    $top = $step * ceil($max / $step);
    $ticks = array_map(fn (int $i): float => $i * $step, range(0, (int) round($top / $step)));

    $x = fn (int $index): float => $count > 1 ? $index / ($count - 1) * 100 : 50;
    $y = fn (int $value): float => $value / $top * 100;
    $points = collect($data)->map(fn (array $point, int $index): string => round($x($index), 2).','.round(100 - $y($point['value']), 2))->implode(' L');

    $short = fn (float $value): string => match (true) {
        $value >= 1_000_000 => rtrim(rtrim(number_format($value / 1_000_000, 2), '0'), '.').'M',
        $value >= 1_000 => round($value / 1_000).'K',
        default => '0',
    };
@endphp

<figure {{ $attributes->class('flex gap-3') }}>
    <div class="relative {{ $height }} w-10 shrink-0 text-right text-[11px] text-slate-400 tabular-nums" aria-hidden="true">
        @foreach ($ticks as $tick)
            <span class="absolute right-0 translate-y-1/2" style="bottom: {{ $tick / $top * 100 }}%">{{ $short($tick) }}</span>
        @endforeach
    </div>

    <div class="min-w-0 flex-1 overflow-x-clip">
        <div class="relative {{ $height }}">
            @foreach ($ticks as $tick)
                <div @class(['absolute inset-x-0 border-t', 'border-slate-300' => $tick == 0, 'border-dashed border-slate-200' => $tick != 0]) style="bottom: {{ $tick / $top * 100 }}%"></div>
            @endforeach

            <div class="absolute inset-0 mx-3">
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="absolute inset-0 size-full overflow-visible" aria-hidden="true">
                    <path d="M{{ $points }} L100,100 L0,100 Z" fill="{{ $color }}" fill-opacity="0.07" />
                    <path d="M{{ $points }}" fill="none" stroke="{{ $color }}" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" vector-effect="non-scaling-stroke" />
                </svg>

                @foreach ($data as $index => $point)
                    <div class="group absolute inset-y-0 -translate-x-1/2 focus:outline-none" tabindex="0"
                        style="left: {{ $x($index) }}%; width: {{ $count > 1 ? 100 / ($count - 1) : 100 }}%"
                        data-tooltip="{{ $point['full'] }}&#10;Spent: TZS {{ number_format($point['value']) }}">
                        <div class="absolute inset-y-0 left-1/2 hidden border-l border-slate-300 group-hover:block group-focus-visible:block"></div>
                        <div class="absolute left-1/2 size-2.5 -translate-x-1/2 translate-y-1/2 rounded-full border-2 border-white ring-1 ring-slate-300 group-hover:size-3 group-focus-visible:size-3" style="bottom: {{ $y($point['value']) }}%; background-color: {{ $color }}"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative mx-3 mt-2 h-4 text-[11px] font-medium text-slate-500" aria-hidden="true">
            @foreach ($data as $index => $point)
                <span @class([
                    'absolute whitespace-nowrap',
                    '-translate-x-1/2' => $index > 0 && ! $loop->last,
                    '-translate-x-full' => $loop->last,
                    'hidden sm:block' => $index % 2 === 1,
                ]) style="left: {{ $x($index) }}%">{{ $point['label'] }}</span>
            @endforeach
        </div>
    </div>

    <table class="sr-only">
        <caption>{{ $label }}</caption>
        <thead><tr><th scope="col">Period</th><th scope="col">Spent</th></tr></thead>
        <tbody>
            @foreach ($data as $point)
                <tr><th scope="row">{{ $point['full'] }}</th><td>TZS {{ number_format($point['value']) }}</td></tr>
            @endforeach
        </tbody>
    </table>
</figure>
