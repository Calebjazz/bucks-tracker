@props(['inverted' => false, 'withText' => true])

<span {{ $attributes->class('inline-flex items-center gap-2.5') }}>
    <span class="flex size-8 items-center justify-center rounded-lg bg-brand-600 text-white shadow-xs">
        <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4 17l5-5 4 3 7-8" />
            <path d="M15 7h5v5" />
        </svg>
    </span>
    @if ($withText)
        <span @class(['text-[17px] font-semibold tracking-tight', 'text-white' => $inverted, 'text-ink' => ! $inverted])>Expense<span @class(['text-brand-400' => $inverted, 'text-brand-600' => ! $inverted])>Flow</span></span>
    @endif
</span>
