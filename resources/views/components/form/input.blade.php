{{--
    Labelled text input with hint + error message.
    Usage: <x-form.input label="Email address" name="email" type="email" icon="mail" required />

    It is already wired for Laravel: old('email') refills the value after a failed
    submit and @error('email') shows the server-side validation message.
--}}
@props(['label', 'name', 'type' => 'text', 'id' => null, 'value' => null, 'hint' => null, 'icon' => null, 'prefix' => null, 'optional' => false])

@php
    $id ??= $name;
    $hasError = $errors->has($name);
    $isPassword = $type === 'password';
    $describedBy = trim(($hint ? "{$id}-hint " : '')."{$id}-error");
@endphp

<div class="flex flex-col gap-1.5">
    <div class="flex items-center justify-between gap-2">
        <label for="{{ $id }}" class="text-sm font-medium text-ink">
            {{ $label }}
            @if ($optional)<span class="font-normal text-muted">(optional)</span>@endif
        </label>
        {{ $aside ?? '' }}
    </div>

    <div class="relative">
        @if ($icon)
            <x-icon :name="$icon" class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-slate-400" />
        @endif
        @if ($prefix)
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm font-medium text-muted">{{ $prefix }}</span>
        @endif

        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
            @unless ($isPassword) value="{{ old($name, $value) }}" @endunless
            data-label="{{ $label }}"
            aria-describedby="{{ $describedBy }}"
            @if ($hasError) aria-invalid="true" @endif
            {{ $attributes->class([
                'block h-10 w-full rounded-lg border border-line bg-white px-3 text-sm text-ink shadow-xs transition placeholder:text-slate-400',
                'hover:border-slate-300 focus:border-brand-600 focus:ring-3 focus:ring-brand-600/15 focus:outline-none',
                'disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-500',
                'aria-invalid:border-red-500 aria-invalid:focus:ring-red-500/15',
                'pl-9' => $icon,
                'pl-12' => $prefix,
                'pr-10' => $isPassword,
            ]) }}>

        @if ($isPassword)
            <button type="button" data-password-toggle aria-label="Show password" aria-pressed="false"
                class="absolute inset-y-0 right-0 flex items-center rounded-r-lg px-3 text-slate-400 hover:text-ink">
                <x-icon name="eye" class="size-4" />
                <x-icon name="eye-off" class="hidden size-4" />
            </button>
        @endif
    </div>

    @if ($hint)
        <p id="{{ $id }}-hint" class="text-xs text-muted">{{ $hint }}</p>
    @endif

    <p id="{{ $id }}-error" data-error class="text-xs font-medium text-red-600" @unless ($hasError) hidden @endunless>{{ $errors->first($name) }}</p>
</div>
