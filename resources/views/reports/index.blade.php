{{--
    Reports. The range selector is a GET form (?range=…). With mock data the numbers stay the same;
    a controller would use the range to filter transactions by date.
--}}
@php
    $mock = require resource_path('data/mock.php');
    $categories = $mock['categories'];
    $summary = $mock['summary'];
    $october = collect($mock['transactions'])->filter(fn ($item) => $item['date']->month === 10);

    $ranges = [
        'this-month' => 'This month',
        'last-month' => 'Last month',
        'last-3-months' => 'Last 3 months',
        'last-6-months' => 'Last 6 months',
        'this-year' => 'This year',
    ];
    $range = array_key_exists(request('range'), $ranges) ? request('range') : 'this-month';

    $breakdown = collect($mock['spending'])->map(fn (int $amount, string $slug): array => [
        'category' => $categories[$slug],
        'amount' => $amount,
        'share' => $amount / $summary['expenses'] * 100,
    ]);

    $rows = $october->groupBy('category')->map(fn ($items, $slug): array => [
        'category' => $categories[$slug],
        'count' => $items->count(),
        'total' => $items->sum('amount'),
        'average' => (int) round($items->avg('amount')),
    ])->sortBy([['category.type', 'desc'], ['total', 'desc']]);
@endphp

<x-layouts.app title="Reports" description="Understand your habits and see how your money moves over time.">
    <x-slot:actions>
        <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
            <x-form.select label="Date range" name="range" sr-only-label onchange="this.form.requestSubmit()" class="w-44">
                @foreach ($ranges as $value => $text)
                    <option value="{{ $value }}" @selected($range === $value)>{{ $text }}</option>
                @endforeach
            </x-form.select>
            <noscript><x-button type="submit" variant="secondary">Apply</x-button></noscript>
        </form>
        <x-button icon="download" data-export="#report-table" data-filename="expenseflow-report-{{ $range }}.csv">Export report</x-button>
    </x-slot:actions>

    {{-- Summary --}}
    <div class="grid gap-4 md:grid-cols-3">
        <x-stat-card label="Total income" icon="arrow-down-left" :amount="$summary['income']" :change="$summary['income_change']" comparison="vs previous period" />
        <x-stat-card label="Total expenses" icon="arrow-up-right" :amount="$summary['expenses']" :change="$summary['expenses_change']" :good-when-up="false" comparison="vs previous period" />
        <x-stat-card label="Net savings" icon="piggy-bank" :amount="$summary['net']">
            <p class="mt-2 text-xs text-muted">
                You saved <span class="font-semibold text-brand-700">{{ $summary['savings_rate'] }}%</span> of your income
            </p>
        </x-stat-card>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-5">
        <x-card title="Spending trend" description="Weekly expenses over the last 8 weeks" class="lg:col-span-3">
            <x-charts.line :data="$mock['weeks']" label="Weekly spending, last 8 weeks" />
        </x-card>

        <x-card title="Category breakdown" description="Share of total spending" class="lg:col-span-2">
            <ul class="flex flex-col gap-4">
                @foreach ($breakdown as $item)
                    <li>
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <span class="flex min-w-0 items-center gap-2">
                                <span class="size-2.5 shrink-0 rounded-sm" style="background-color: {{ $item['category']['color'] }}"></span>
                                <span class="truncate">{{ $item['category']['name'] }}</span>
                            </span>
                            <span class="shrink-0 tabular-nums"><span class="font-medium">{{ number_format($item['amount']) }}</span> <span class="text-muted">· {{ round($item['share']) }}%</span></span>
                        </div>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full" style="width: {{ $item['share'] }}%; background-color: {{ $item['category']['color'] }}"></div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </x-card>
    </div>

    {{-- Summary table --}}
    <x-card title="Transaction summary" description="{{ $mock['period']['range'] }}" :padded="false" class="mt-6">
        <div class="overflow-x-auto">
            <table id="report-table" class="w-full text-left text-sm">
                <thead class="border-b border-line bg-slate-50/70 text-xs text-muted">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-medium">Category</th>
                        <th scope="col" class="hidden px-5 py-3 font-medium sm:table-cell">Type</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Transactions</th>
                        <th scope="col" class="hidden px-5 py-3 text-right font-medium md:table-cell">Average</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Total (TZS)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($rows as $row)
                        @php($isIncome = $row['category']['type'] === 'income')
                        <tr class="hover:bg-slate-50/70">
                            <th scope="row" class="px-5 py-3 font-medium">
                                <span class="flex items-center gap-3"><x-category-icon :category="$row['category']" size="sm" />{{ $row['category']['name'] }}</span>
                            </th>
                            <td class="hidden px-5 py-3 sm:table-cell">
                                <x-badge :variant="$isIncome ? 'success' : 'danger'">{{ $isIncome ? 'Income' : 'Expense' }}</x-badge>
                            </td>
                            <td class="px-5 py-3 text-right text-muted tabular-nums">{{ $row['count'] }}</td>
                            <td class="hidden px-5 py-3 text-right text-muted tabular-nums md:table-cell">{{ number_format($row['average']) }}</td>
                            <td @class(['px-5 py-3 text-right font-semibold tabular-nums', 'text-brand-700' => $isIncome, 'text-red-600' => ! $isIncome])>{{ $isIncome ? '+' : '−' }}{{ number_format($row['total']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-line bg-slate-50/70 font-semibold">
                    <tr>
                        <th scope="row" class="px-5 py-3">Net savings</th>
                        <td class="hidden sm:table-cell"></td>
                        <td class="px-5 py-3 text-right tabular-nums">{{ $rows->sum('count') }}</td>
                        <td class="hidden md:table-cell"></td>
                        <td class="px-5 py-3 text-right text-brand-700 tabular-nums">+{{ number_format($summary['net']) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-card>
</x-layouts.app>
