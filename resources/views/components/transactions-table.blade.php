{{--
    Transactions table used on the dashboard and the transactions pages.
    Columns collapse on small screens; the date and category move under the description.
--}}
@props(['transactions', 'categories', 'withView' => false])

<div {{ $attributes->class('overflow-x-auto') }}>
    <table class="w-full text-left text-sm">
        <thead class="border-b border-line bg-slate-50/70 text-xs text-muted">
            <tr>
                <th scope="col" class="px-5 py-3 font-medium">Description</th>
                <th scope="col" class="hidden px-5 py-3 font-medium 2xl:table-cell">Category</th>
                <th scope="col" class="hidden px-5 py-3 font-medium sm:table-cell">Date</th>
                <th scope="col" class="hidden px-5 py-3 font-medium xl:table-cell">Type</th>
                <th scope="col" class="px-5 py-3 text-right font-medium">Amount</th>
                <th scope="col" class="w-12 py-3 pr-3"><span class="sr-only">Actions</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-line">
            @foreach ($transactions as $transaction)
                @php
                    $category = $categories[$transaction['category']];
                    $isIncome = $transaction['type'] === 'income';
                    $editData = json_encode([
                        'title' => 'Edit transaction',
                        'fields' => [
                            'type' => $transaction['type'],
                            'amount' => $transaction['amount'],
                            'description' => $transaction['description'],
                            'category' => $transaction['category'],
                            'date' => $transaction['date']->toDateString(),
                            'notes' => $transaction['notes'],
                        ],
                    ]);
                @endphp
                <tr data-deletable class="transition-colors hover:bg-slate-50/70">
                    <td class="w-full max-w-0 px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <x-category-icon :category="$category" size="sm" />
                            <div class="min-w-0">
                                <p class="truncate font-medium text-ink">{{ $transaction['description'] }}</p>
                                <p class="truncate text-xs text-muted 2xl:hidden"><span class="sm:hidden">{{ $transaction['date']->format('M j') }} · </span>{{ $category['name'] }}</p>
                                @if ($transaction['notes'])
                                    <p class="hidden truncate text-xs text-muted 2xl:block">{{ $transaction['notes'] }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="hidden px-5 py-3.5 whitespace-nowrap text-muted 2xl:table-cell">{{ $category['name'] }}</td>
                    <td class="hidden px-5 py-3.5 whitespace-nowrap text-muted sm:table-cell">
                        <time datetime="{{ $transaction['date']->toDateString() }}">{{ $transaction['date']->format('M j, Y') }}</time>
                    </td>
                    <td class="hidden px-5 py-3.5 xl:table-cell">
                        <x-badge :variant="$isIncome ? 'success' : 'danger'" :icon="$isIncome ? 'arrow-down-left' : 'arrow-up-right'">
                            {{ $isIncome ? 'Income' : 'Expense' }}
                        </x-badge>
                    </td>
                    <td @class(['px-5 py-3.5 text-right font-semibold whitespace-nowrap', 'text-brand-700' => $isIncome, 'text-red-600' => ! $isIncome])>
                        <x-money :amount="$transaction['amount']" signed :type="$transaction['type']" />
                    </td>
                    <td class="py-3.5 pr-3 text-right">
                        <x-dropdown width="w-44">
                            <x-slot:trigger class="size-8 text-slate-400 hover:bg-slate-100 hover:text-ink" aria-label="Actions for {{ $transaction['description'] }}">
                                <x-icon name="more-horizontal" class="size-4" />
                            </x-slot:trigger>

                            @if ($withView)
                                <x-dropdown-item icon="eye" :href="route('transactions.index', ['search' => $transaction['description']])">View details</x-dropdown-item>
                            @endif
                            <x-dropdown-item icon="pencil" data-dialog-open="transaction-modal" :data-dialog-fill="$editData">Edit</x-dropdown-item>
                            <x-dropdown-item icon="trash" danger :data-confirm-delete="$transaction['description']">Delete</x-dropdown-item>
                        </x-dropdown>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
