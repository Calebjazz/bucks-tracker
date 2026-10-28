{{--
    A white surface with an optional header.
    Usage: <x-card title="Recent transactions" description="…"> <x-slot:actions>…</x-slot:actions> … </x-card>
    Pass :padded="false" when the body is a table that should touch the edges.
--}}
@props(['title' => null, 'description' => null, 'padded' => true])

<section {{ $attributes->class('flex flex-col rounded-xl border border-line bg-white shadow-xs') }}>
    @if ($title)
        <header class="flex flex-wrap items-start justify-between gap-3 border-b border-line px-5 py-4">
            <div class="min-w-0">
                <h2 class="text-base font-semibold text-ink">{{ $title }}</h2>
                @if ($description)
                    <p class="mt-0.5 text-sm text-muted">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
            @endisset
        </header>
    @endif

    <div @class(['flex-1', 'p-5' => $padded])>
        {{ $slot }}
    </div>
</section>
