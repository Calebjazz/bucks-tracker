@props(['icon' => 'inbox', 'title', 'description' => null])

<div {{ $attributes->class('flex flex-col items-center px-6 py-14 text-center') }}>
    <span class="flex size-12 items-center justify-center rounded-full bg-slate-100 text-slate-500 ring-8 ring-slate-50">
        <x-icon :name="$icon" class="size-6" />
    </span>
    <h3 class="mt-5 text-base font-semibold text-ink">{{ $title }}</h3>
    @if ($description)
        <p class="mt-1.5 max-w-sm text-sm text-muted">{{ $description }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div class="mt-6 flex flex-wrap justify-center gap-3">{{ $slot }}</div>
    @endif
</div>
