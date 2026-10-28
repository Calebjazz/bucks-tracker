{{-- Categories, split into income and expense groups. --}}
@php
    $mock = require resource_path('data/mock.php');
    $transactions = collect($mock['transactions']);
    $groups = collect($mock['categories'])->groupBy('type');

    $iconChoices = ['briefcase', 'laptop', 'trending-up', 'gift', 'piggy-bank', 'coins', 'home', 'utensils', 'car', 'zap', 'shopping-cart', 'tv', 'heart', 'graduation-cap', 'smartphone', 'shirt', 'plane', 'receipt'];

    $sections = [
        'expense' => ['title' => 'Expense categories', 'description' => 'Where your money goes.'],
        'income' => ['title' => 'Income categories', 'description' => 'Where your money comes from.'],
    ];
@endphp

<x-layouts.app title="Categories" description="Organise your transactions so your reports make sense.">
    <x-slot:actions>
        <x-button icon="plus" data-dialog-open="category-modal">Add category</x-button>
    </x-slot:actions>

    <div class="flex flex-col gap-10">
        @foreach ($sections as $type => $section)
            <section aria-labelledby="{{ $type }}-categories">
                <div class="mb-4 flex items-end justify-between gap-4">
                    <div>
                        <h2 id="{{ $type }}-categories" class="flex items-center gap-2 text-base font-semibold">
                            {{ $section['title'] }}
                            <x-badge>{{ $groups[$type]->count() }}</x-badge>
                        </h2>
                        <p class="mt-0.5 text-sm text-muted">{{ $section['description'] }}</p>
                    </div>
                </div>

                <ul class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($groups[$type] as $category)
                        @php
                            $items = $transactions->where('category', $category['slug']);
                            $editData = json_encode(['title' => 'Edit category', 'fields' => ['name' => $category['name'], 'type' => $category['type'], 'icon' => $category['icon']]]);
                        @endphp
                        <li data-deletable class="group flex items-center gap-4 rounded-xl border border-line bg-white p-4 shadow-xs transition hover:border-slate-300 hover:shadow-sm">
                            <x-category-icon :category="$category" size="lg" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold text-ink">{{ $category['name'] }}</p>
                                <p class="mt-0.5 text-sm text-muted">
                                    {{ $items->count() }} {{ Str::plural('transaction', $items->count()) }} ·
                                    <x-money :amount="$items->sum('amount')" />
                                </p>
                            </div>
                            <x-dropdown width="w-40">
                                <x-slot:trigger class="size-8 text-slate-400 hover:bg-slate-100 hover:text-ink" aria-label="Actions for {{ $category['name'] }}">
                                    <x-icon name="more-horizontal" class="size-4" />
                                </x-slot:trigger>
                                <x-dropdown-item icon="pencil" data-dialog-open="category-modal" :data-dialog-fill="$editData">Edit</x-dropdown-item>
                                <x-dropdown-item icon="trash" danger :data-confirm-delete="$category['name']" data-confirm-note="Its {{ $items->count() }} transactions will be moved to “Uncategorised”.">Delete</x-dropdown-item>
                            </x-dropdown>
                        </li>
                    @endforeach

                    <li>
                        <button type="button" data-dialog-open="category-modal" data-dialog-fill='{"title":"Add {{ $type }} category","fields":{"type":"{{ $type }}"}}'
                            class="flex h-full min-h-20 w-full items-center justify-center gap-2 rounded-xl border border-dashed border-slate-300 p-4 text-sm font-medium text-muted transition-colors hover:border-brand-500 hover:bg-brand-50/40 hover:text-brand-700">
                            <x-icon name="plus" class="size-4" /> New {{ $type }} category
                        </button>
                    </li>
                </ul>
            </section>
        @endforeach
    </div>

    {{-- Create / edit category --}}
    <x-modal id="category-modal" title="Add category" description="Categories group similar transactions together.">
        <form method="POST" action="#" novalidate data-demo-form data-toast="Category saved successfully.">
            @csrf
            <div class="flex flex-col gap-5 px-6 py-5">
                <x-form.input label="Name" name="name" placeholder="e.g. Groceries" maxlength="40" required />

                <x-form.type-toggle name="type" checked="expense" />

                <fieldset>
                    <legend class="mb-1.5 text-sm font-medium text-ink">Icon</legend>
                    <div class="grid grid-cols-6 gap-2 sm:grid-cols-9">
                        @foreach ($iconChoices as $icon)
                            <label class="flex aspect-square cursor-pointer items-center justify-center rounded-lg border border-line text-slate-500 transition hover:border-slate-300 hover:text-ink has-checked:border-brand-600 has-checked:bg-brand-50 has-checked:text-brand-700 has-checked:ring-2 has-checked:ring-brand-600/20 has-focus-visible:outline-2 has-focus-visible:outline-brand-600 has-aria-invalid:border-red-300">
                                <input type="radio" name="icon" value="{{ $icon }}" class="sr-only" required aria-describedby="icon-error" data-required-message="Pick an icon for this category." @checked($loop->first)>
                                <x-icon :name="$icon" class="size-5" />
                                <span class="sr-only">{{ str_replace('-', ' ', $icon) }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p id="icon-error" data-error class="mt-1.5 text-xs font-medium text-red-600" hidden></p>
                </fieldset>
            </div>

            <div class="flex flex-col-reverse gap-2 border-t border-line bg-slate-50/70 px-6 py-4 sm:flex-row sm:justify-end">
                <x-button variant="secondary" data-dialog-close>Cancel</x-button>
                <x-button type="submit" icon="check">Save category</x-button>
            </div>
        </form>
    </x-modal>
</x-layouts.app>
