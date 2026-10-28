{{--
    Labelled select. Put <option> (or <optgroup>) elements in the slot.
    Usage: <x-form.select label="Category" name="category" required> <option>…</option> </x-form.select>
--}}
@props(['label', 'name', 'id' => null, 'hint' => null, 'optional' => false, 'srOnlyLabel' => false])

@php
    $id ??= $name;
    $hasError = $errors->has($name);
@endphp

<div class="flex flex-col gap-1.5">
    <label for="{{ $id }}" @class(['text-sm font-medium text-ink', 'sr-only' => $srOnlyLabel])>
        {{ $label }}
        @if ($optional)<span class="font-normal text-muted">(optional)</span>@endif
    </label>

    <div class="relative">
        <select id="{{ $id }}" name="{{ $name }}" data-label="{{ $label }}" aria-describedby="{{ $id }}-error"
            @if ($hasError) aria-invalid="true" @endif
            {{ $attributes->class([
                'block h-10 w-full appearance-none rounded-lg border border-line bg-white pr-9 pl-3 text-sm text-ink shadow-xs transition',
                'hover:border-slate-300 focus:border-brand-600 focus:ring-3 focus:ring-brand-600/15 focus:outline-none',
                'aria-invalid:border-red-500 aria-invalid:focus:ring-red-500/15',
            ]) }}>
            {{ $slot }}
        </select>
        <x-icon name="chevron-down" class="pointer-events-none absolute top-1/2 right-3 size-4 -translate-y-1/2 text-slate-400" />
    </div>

    @if ($hint)
        <p class="text-xs text-muted">{{ $hint }}</p>
    @endif

    <p id="{{ $id }}-error" data-error class="text-xs font-medium text-red-600" @unless ($hasError) hidden @endunless>{{ $errors->first($name) }}</p>
</div>
