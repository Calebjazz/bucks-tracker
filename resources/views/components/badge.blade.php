@props(['variant' => 'neutral', 'icon' => null])

@php
    $variants = [
        'neutral' => 'bg-slate-100 text-slate-700 ring-slate-500/10',
        'success' => 'bg-brand-50 text-brand-700 ring-brand-600/20',
        'danger' => 'bg-red-50 text-red-700 ring-red-600/15',
        'warning' => 'bg-amber-50 text-amber-800 ring-amber-600/20',
    ];
@endphp

<span {{ $attributes->class("inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs font-medium whitespace-nowrap ring-1 ring-inset {$variants[$variant]}") }}>
    @if ($icon)<x-icon :name="$icon" class="size-3.5" />@endif
    {{ $slot }}
</span>
