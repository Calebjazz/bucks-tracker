{{--
    Usage: <x-button>Save</x-button>
           <x-button variant="secondary" icon="download" href="/reports">Export</x-button>
    Variants: primary | secondary | ghost | danger. Sizes: sm | md | lg.
--}}
@props(['variant' => 'primary', 'size' => 'md', 'href' => null, 'icon' => null])

@php
    $sizes = [
        'sm' => 'h-8 gap-1.5 px-3 text-sm',
        'md' => 'h-10 gap-2 px-4 text-sm',
        'lg' => 'h-12 gap-2 px-6 text-base',
    ];

    $variants = [
        'primary' => 'bg-brand-600 text-white shadow-xs hover:bg-brand-700 active:bg-brand-800',
        'secondary' => 'border border-line bg-white text-ink shadow-xs hover:border-slate-300 hover:bg-slate-50 active:bg-slate-100',
        'ghost' => 'text-muted hover:bg-slate-100 hover:text-ink active:bg-slate-200',
        'danger' => 'bg-red-600 text-white shadow-xs hover:bg-red-700 active:bg-red-800',
    ];

    $classes = "inline-flex shrink-0 items-center justify-center rounded-lg font-medium whitespace-nowrap transition-colors disabled:pointer-events-none disabled:opacity-50 {$sizes[$size]} {$variants[$variant]}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon)<x-icon :name="$icon" class="size-4" />@endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button'])->class($classes) }}>
        @if ($icon)<x-icon :name="$icon" class="size-4" />@endif
        {{ $slot }}
    </button>
@endif
