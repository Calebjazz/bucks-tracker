{{--
    Add / edit transaction form inside a dialog.
    Open with data-dialog-open="transaction-modal". Pre-fill with data-dialog-fill='{"fields": {...}}'.
    Later: point the form at route('transactions.store') and remove data-demo-form.
--}}
@props(['categories'])

@php
    $grouped = collect($categories)->groupBy('type');
@endphp

<x-modal id="transaction-modal" title="Add transaction" description="Record money coming in or going out.">
    <form method="POST" action="#" novalidate data-demo-form data-toast="Transaction saved successfully.">
        @csrf

        <div class="flex flex-col gap-5 px-6 py-5">
            <x-form.type-toggle name="type" checked="expense" />

            <div class="grid gap-5 sm:grid-cols-2">
                <x-form.input label="Amount" name="amount" type="number" prefix="TZS" min="1" step="any" inputmode="decimal" placeholder="0" required />
                <x-form.input label="Date" name="date" type="date" :value="now()->toDateString()" required />
            </div>

            <x-form.input label="Description" name="description" placeholder="e.g. Weekly groceries" maxlength="80" required />

            <x-form.select label="Category" name="category" data-category-select required data-required-message="Choose a category for this transaction.">
                <option value="">Select a category</option>
                <optgroup label="Expense categories" data-type="expense">
                    @foreach ($grouped['expense'] as $category)
                        <option value="{{ $category['slug'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Income categories" data-type="income">
                    @foreach ($grouped['income'] as $category)
                        <option value="{{ $category['slug'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </optgroup>
            </x-form.select>

            <x-form.textarea label="Notes" name="notes" optional placeholder="Add any details you want to remember" maxlength="255" />
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-line bg-slate-50/70 px-6 py-4 sm:flex-row sm:justify-end">
            <x-button variant="secondary" data-dialog-close>Cancel</x-button>
            <x-button type="submit" icon="check">Save transaction</x-button>
        </div>
    </form>
</x-modal>
