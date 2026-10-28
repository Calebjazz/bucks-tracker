{{--
    A native <dialog>. Open it from any button with data-dialog-open="the-id".
    Usage: <x-modal id="transaction-modal" title="Add transaction"> …form… </x-modal>
--}}
@props(['id', 'title', 'description' => null, 'width' => 'max-w-lg'])

<dialog id="{{ $id }}" aria-labelledby="{{ $id }}-title"
    {{ $attributes->class("m-auto max-h-[calc(100dvh-2rem)] w-[calc(100%-2rem)] {$width} overflow-y-auto rounded-2xl border border-line bg-white p-0 text-ink shadow-2xl shadow-slate-900/20") }}>
    <div class="flex items-start justify-between gap-4 border-b border-line px-6 py-4">
        <div>
            <h2 id="{{ $id }}-title" data-dialog-title class="text-base font-semibold">{{ $title }}</h2>
            @if ($description)
                <p class="mt-0.5 text-sm text-muted">{{ $description }}</p>
            @endif
        </div>
        <button type="button" data-dialog-close class="-mr-2 rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-ink" aria-label="Close dialog">
            <x-icon name="x" class="size-5" />
        </button>
    </div>

    {{ $slot }}
</dialog>
