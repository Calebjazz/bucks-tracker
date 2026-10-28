@props(['icon' => null, 'href' => null, 'danger' => false])

@php
    $classes = 'flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-left text-sm font-medium transition-colors focus:outline-none '
        .($danger ? 'text-red-600 hover:bg-red-50 focus:bg-red-50' : 'text-ink hover:bg-slate-50 focus:bg-slate-50');
@endphp

@if ($href)
    <a href="{{ $href }}" role="menuitem" {{ $attributes->class($classes) }}>
        @if ($icon)<x-icon :name="$icon" @class(['size-4', 'text-slate-400' => ! $danger]) />@endif
        {{ $slot }}
    </a>
@else
    <button type="button" role="menuitem" {{ $attributes->class($classes) }}>
        @if ($icon)<x-icon :name="$icon" @class(['size-4', 'text-slate-400' => ! $danger]) />@endif
        {{ $slot }}
    </button>
@endif
