{{--
    Income / Expense segmented control built from two radio buttons (works without JavaScript).
    Usage: <x-form.type-toggle name="type" checked="expense" />
--}}
@props(['name' => 'type', 'checked' => 'expense', 'label' => 'Type'])

<fieldset class="flex flex-col gap-1.5">
    <legend class="mb-1.5 text-sm font-medium text-ink">{{ $label }}</legend>

    <div class="grid grid-cols-2 gap-1 rounded-lg bg-slate-100 p-1">
        <label class="flex cursor-pointer items-center justify-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-muted transition hover:text-ink has-checked:bg-white has-checked:text-red-600 has-checked:shadow-sm has-focus-visible:outline-2 has-focus-visible:outline-brand-600">
            <input type="radio" name="{{ $name }}" value="expense" class="sr-only" @checked($checked === 'expense')>
            <x-icon name="arrow-up-right" class="size-4" />
            Expense
        </label>
        <label class="flex cursor-pointer items-center justify-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-muted transition hover:text-ink has-checked:bg-white has-checked:text-brand-700 has-checked:shadow-sm has-focus-visible:outline-2 has-focus-visible:outline-brand-600">
            <input type="radio" name="{{ $name }}" value="income" class="sr-only" @checked($checked === 'income')>
            <x-icon name="arrow-down-left" class="size-4" />
            Income
        </label>
    </div>
</fieldset>
