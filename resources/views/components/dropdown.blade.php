{{--
    Usage:
    <x-dropdown>
        <x-slot:trigger aria-label="Open menu" class="…">…</x-slot:trigger>
        <x-dropdown-item icon="pencil">Edit</x-dropdown-item>
    </x-dropdown>
--}}
@props(['align' => 'right', 'width' => 'w-48'])

<div data-dropdown {{ $attributes->class('relative inline-flex') }}>
    <button type="button" data-dropdown-trigger aria-haspopup="menu" aria-expanded="false"
        {{ $trigger->attributes->class('inline-flex items-center justify-center rounded-lg transition-colors') }}>
        {{ $trigger }}
    </button>

    <div data-dropdown-menu data-align="{{ $align }}" role="menu" hidden
        class="fixed z-50 {{ $width }} rounded-xl border border-line bg-white p-1 shadow-lg shadow-slate-900/5">
        {{ $slot }}
    </div>
</div>
