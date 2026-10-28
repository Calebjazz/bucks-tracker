@props(['label', 'name', 'id' => null, 'value' => null, 'optional' => false])

@php
    $id ??= $name;
    $hasError = $errors->has($name);
@endphp

<div class="flex flex-col gap-1.5">
    <label for="{{ $id }}" class="text-sm font-medium text-ink">
        {{ $label }}
        @if ($optional)<span class="font-normal text-muted">(optional)</span>@endif
    </label>

    <textarea id="{{ $id }}" name="{{ $name }}" data-label="{{ $label }}" aria-describedby="{{ $id }}-error"
        @if ($hasError) aria-invalid="true" @endif
        {{ $attributes->merge(['rows' => 3])->class([
            'block w-full resize-y rounded-lg border border-line bg-white px-3 py-2.5 text-sm text-ink shadow-xs transition placeholder:text-slate-400',
            'hover:border-slate-300 focus:border-brand-600 focus:ring-3 focus:ring-brand-600/15 focus:outline-none',
            'aria-invalid:border-red-500 aria-invalid:focus:ring-red-500/15',
        ]) }}>{{ old($name, $value) }}</textarea>

    <p id="{{ $id }}-error" data-error class="text-xs font-medium text-red-600" @unless ($hasError) hidden @endunless>{{ $errors->first($name) }}</p>
</div>
