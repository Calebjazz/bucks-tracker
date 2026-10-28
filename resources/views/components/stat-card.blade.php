{{--
    Summary metric card.
    change: percentage vs. the previous period, e.g. 11.3 or -15.0.
    goodWhenUp: true for income/balance, false for expenses (spending less is good).
--}}
@props(['label', 'amount', 'icon', 'change' => null, 'goodWhenUp' => true, 'comparison' => 'vs last month'])

@php
    $isUp = $change !== null && $change >= 0;
    $isGood = $isUp === $goodWhenUp;
@endphp

<div {{ $attributes->class('flex flex-col rounded-xl border border-line bg-white p-5 shadow-xs') }}>
    <div class="flex items-center justify-between gap-3">
        <p class="text-sm font-medium text-muted">{{ $label }}</p>
        <span class="flex size-9 items-center justify-center rounded-lg bg-slate-50 text-slate-600 ring-1 ring-line">
            <x-icon :name="$icon" class="size-4.5" />
        </span>
    </div>

    <p class="mt-3 flex items-baseline gap-1.5 tabular-nums">
        <span class="text-sm font-medium text-muted">TZS</span>
        <span class="text-2xl font-semibold tracking-tight text-ink">{{ number_format($amount) }}</span>
    </p>

    @if ($change !== null)
        <p class="mt-2 flex items-center gap-1.5 text-xs text-muted">
            <span @class(['inline-flex items-center gap-0.5 rounded px-1 py-0.5 font-semibold', 'bg-brand-50 text-brand-700' => $isGood, 'bg-red-50 text-red-700' => ! $isGood])>
                <x-icon :name="$isUp ? 'arrow-up-right' : 'arrow-down-right'" class="size-3.5" />
                {{ number_format(abs($change), 1) }}%
            </span>
            {{ $comparison }}
        </p>
    @endif

    {{ $slot }}
</div>
