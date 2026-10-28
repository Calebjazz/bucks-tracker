{{--
    Grouped bar chart: income vs. expenses per month, built with plain HTML + CSS heights.
    $data: list of ['label' => 'Oct', 'full' => 'October 2026', 'income' => int, 'expense' => int]
--}}
@props(['data', 'height' => 'h-60', 'label' => 'Income versus expenses by month', 'legend' => true])

@php
    $max = max(1, collect($data)->flatMap(fn (array $month): array => [$month['income'], $month['expense']])->max());
    $rawStep = $max / 5;
    $magnitude = 10 ** floor(log10($rawStep));
    $step = collect([1, 2, 2.5, 5, 10])->first(fn (float $n): bool => $n * $magnitude >= $rawStep) * $magnitude;
    $top = $step * ceil($max / $step);
    $ticks = array_map(fn (int $i): float => $i * $step, range(0, (int) round($top / $step)));

    $short = fn (float $value): string => match (true) {
        $value >= 1_000_000 => rtrim(rtrim(number_format($value / 1_000_000, 2), '0'), '.').'M',
        $value >= 1_000 => round($value / 1_000).'K',
        default => '0',
    };
@endphp

<figure {{ $attributes->class('flex flex-col gap-4') }}>
    @if ($legend)
        <figcaption class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs font-medium text-muted">
            <span class="inline-flex items-center gap-2"><span class="size-2.5 rounded-sm bg-brand-600"></span>Income</span>
            <span class="inline-flex items-center gap-2"><span class="size-2.5 rounded-sm bg-red-400"></span>Expenses</span>
        </figcaption>
    @endif

    <div class="flex gap-3" aria-hidden="true">
        {{-- Y axis --}}
        <div class="relative {{ $height }} w-8 shrink-0 text-right text-[11px] text-slate-400 tabular-nums">
            @foreach ($ticks as $tick)
                <span class="absolute right-0 translate-y-1/2" style="bottom: {{ $tick / $top * 100 }}%">{{ $short($tick) }}</span>
            @endforeach
        </div>

        <div class="min-w-0 flex-1">
            <div class="relative {{ $height }}">
                {{-- Grid lines --}}
                @foreach ($ticks as $tick)
                    <div @class(['absolute inset-x-0 border-t', 'border-slate-300' => $tick == 0, 'border-dashed border-slate-200' => $tick != 0]) style="bottom: {{ $tick / $top * 100 }}%"></div>
                @endforeach

                {{-- Bars --}}
                <div class="chart-bars relative flex h-full items-end">
                    @foreach ($data as $month)
                        <div class="bar-group flex h-full flex-1 items-end justify-center gap-0.5 rounded-md outline-offset-0 hover:bg-slate-500/5 focus-visible:bg-slate-500/5" tabindex="0"
                            data-tooltip="{{ $month['full'] }}&#10;Income: TZS {{ number_format($month['income']) }}&#10;Expenses: TZS {{ number_format($month['expense']) }}">
                            <div class="w-2.5 rounded-t-[4px] bg-brand-600 sm:w-4" style="height: {{ $month['income'] / $top * 100 }}%"></div>
                            <div class="w-2.5 rounded-t-[4px] bg-red-400 sm:w-4" style="height: {{ $month['expense'] / $top * 100 }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- X axis --}}
            <div class="mt-2 flex text-center text-[11px] font-medium text-slate-500">
                @foreach ($data as $month)
                    <span class="flex-1">{{ $month['label'] }}</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Screen readers get the same numbers as a table. --}}
    <table class="sr-only">
        <caption>{{ $label }}</caption>
        <thead><tr><th scope="col">Month</th><th scope="col">Income</th><th scope="col">Expenses</th></tr></thead>
        <tbody>
            @foreach ($data as $month)
                <tr><th scope="row">{{ $month['full'] }}</th><td>TZS {{ number_format($month['income']) }}</td><td>TZS {{ number_format($month['expense']) }}</td></tr>
            @endforeach
        </tbody>
    </table>
</figure>
