{{--
    Transactions list — also used for /income and /expenses (the route passes $type).

    The filters are a normal GET form, so they show up in the URL (?search=…&category=…).
    Right now the filtering below runs on the mock array. In the real app a controller
    will do the same with Eloquent, e.g.:
        Transaction::query()->when($request->search, fn ($q, $s) => $q->where('description', 'like', "%{$s}%"))->paginate(10);

    Preview the other states with ?state=loading, ?state=error or ?state=empty.
--}}
@php
    $mock = require resource_path('data/mock.php');
    $categories = $mock['categories'];
    $type = $type ?? (request('type') ?: null);
    $state = request('state');

    $filters = [
        'search' => trim((string) request('search')),
        'category' => request('category'),
        'from' => request('from'),
        'to' => request('to'),
    ];

    $filtered = collect($state === 'empty' ? [] : $mock['transactions'])
        ->when($type, fn ($items) => $items->where('type', $type))
        ->when($filters['search'], fn ($items, $search) => $items->filter(fn ($item) => str_contains(strtolower($item['description']), strtolower($search))))
        ->when($filters['category'], fn ($items, $category) => $items->where('category', $category))
        ->when($filters['from'], fn ($items, $from) => $items->filter(fn ($item) => $item['date']->toDateString() >= $from))
        ->when($filters['to'], fn ($items, $to) => $items->filter(fn ($item) => $item['date']->toDateString() <= $to))
        ->values();

    $perPage = 10;
    $total = $filtered->count();
    $lastPage = max(1, (int) ceil($total / $perPage));
    $page = min(max(1, (int) request('page', 1)), $lastPage);
    $rows = $filtered->forPage($page, $perPage);
    $hasFilters = collect($filters)->filter()->isNotEmpty() || (request()->routeIs('transactions.index') && filled(request('type')));

    $pageTitle = match ($type) {
        'income' => 'Income',
        'expense' => 'Expenses',
        default => 'Transactions',
    };
    $pageDescription = match ($type) {
        'income' => 'Every shilling that came in, in one place.',
        'expense' => 'Everything you spent, ready to review.',
        default => 'Search, filter and manage all of your income and expenses.',
    };

    $baseRoute = match ($type) {
        'income' => route('income'),
        'expense' => route('expenses'),
        default => route('transactions.index'),
    };

    $addFill = json_encode(['title' => 'Add '.($type ?? 'transaction'), 'fields' => ['type' => $type ?? 'expense']]);
    $visibleCategories = collect($categories)->when($type, fn ($items) => $items->where('type', $type));
@endphp

<x-layouts.app :title="$pageTitle" :description="$pageDescription">
    <x-slot:actions>
        <x-button icon="plus" data-dialog-open="transaction-modal" :data-dialog-fill="$addFill">Add {{ $type ?? 'transaction' }}</x-button>
    </x-slot:actions>

    @if ($type)
        <div class="mb-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-line bg-white p-4 shadow-xs">
                <p class="text-sm text-muted">Total {{ $type === 'income' ? 'received' : 'spent' }}</p>
                <p @class(['mt-1 text-xl font-semibold', 'text-brand-700' => $type === 'income', 'text-red-600' => $type === 'expense'])><x-money :amount="$filtered->sum('amount')" /></p>
            </div>
            <div class="rounded-xl border border-line bg-white p-4 shadow-xs">
                <p class="text-sm text-muted">Transactions</p>
                <p class="mt-1 text-xl font-semibold tabular-nums">{{ $total }}</p>
            </div>
            <div class="rounded-xl border border-line bg-white p-4 shadow-xs">
                <p class="text-sm text-muted">Average amount</p>
                <p class="mt-1 text-xl font-semibold"><x-money :amount="$total ? (int) round($filtered->avg('amount')) : 0" /></p>
            </div>
        </div>
    @endif

    <x-card :padded="false">
        {{-- Filters --}}
        <form method="GET" action="{{ $baseRoute }}" class="grid gap-3 border-b border-line p-4 sm:grid-cols-2 lg:grid-cols-[minmax(0,1.6fr)_repeat(4,minmax(0,1fr))_auto] lg:items-end">
            <div class="flex flex-col gap-1.5 sm:col-span-2 lg:col-span-1">
                <label for="search" class="text-xs font-medium text-muted">Search</label>
                <div class="relative">
                    <x-icon name="search" class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-slate-400" />
                    <input id="search" type="search" name="search" value="{{ $filters['search'] }}" placeholder="Search by description"
                        class="h-10 w-full rounded-lg border border-line bg-white pr-3 pl-9 text-sm shadow-xs placeholder:text-slate-400 hover:border-slate-300 focus:border-brand-600 focus:ring-3 focus:ring-brand-600/15 focus:outline-none">
                </div>
            </div>

            @unless (request()->routeIs('income', 'expenses'))
                <div class="flex flex-col gap-1.5">
                    <label for="filter-type" class="text-xs font-medium text-muted">Type</label>
                    <div class="relative">
                        <select id="filter-type" name="type" class="h-10 w-full appearance-none rounded-lg border border-line bg-white pr-9 pl-3 text-sm shadow-xs hover:border-slate-300 focus:border-brand-600 focus:ring-3 focus:ring-brand-600/15 focus:outline-none">
                            <option value="">All types</option>
                            <option value="income" @selected($type === 'income')>Income</option>
                            <option value="expense" @selected($type === 'expense')>Expense</option>
                        </select>
                        <x-icon name="chevron-down" class="pointer-events-none absolute top-1/2 right-3 size-4 -translate-y-1/2 text-slate-400" />
                    </div>
                </div>
            @endunless

            <div class="flex flex-col gap-1.5">
                <label for="filter-category" class="text-xs font-medium text-muted">Category</label>
                <div class="relative">
                    <select id="filter-category" name="category" class="h-10 w-full appearance-none rounded-lg border border-line bg-white pr-9 pl-3 text-sm shadow-xs hover:border-slate-300 focus:border-brand-600 focus:ring-3 focus:ring-brand-600/15 focus:outline-none">
                        <option value="">All categories</option>
                        @foreach ($visibleCategories as $category)
                            <option value="{{ $category['slug'] }}" @selected($filters['category'] === $category['slug'])>{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                    <x-icon name="chevron-down" class="pointer-events-none absolute top-1/2 right-3 size-4 -translate-y-1/2 text-slate-400" />
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="filter-from" class="text-xs font-medium text-muted">From</label>
                <input id="filter-from" type="date" name="from" value="{{ $filters['from'] }}" class="h-10 w-full rounded-lg border border-line bg-white px-3 text-sm shadow-xs hover:border-slate-300 focus:border-brand-600 focus:ring-3 focus:ring-brand-600/15 focus:outline-none">
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="filter-to" class="text-xs font-medium text-muted">To</label>
                <input id="filter-to" type="date" name="to" value="{{ $filters['to'] }}" class="h-10 w-full rounded-lg border border-line bg-white px-3 text-sm shadow-xs hover:border-slate-300 focus:border-brand-600 focus:ring-3 focus:ring-brand-600/15 focus:outline-none">
            </div>

            <div class="flex gap-2 sm:col-span-2 lg:col-span-1">
                <x-button type="submit" variant="secondary" icon="filter" class="flex-1 lg:flex-none">Apply</x-button>
                @if ($hasFilters)
                    <x-button variant="ghost" :href="$baseRoute">Reset</x-button>
                @endif
            </div>
        </form>

        {{-- Results --}}
        @if ($state === 'loading')
            <div class="divide-y divide-line" aria-busy="true" aria-label="Loading transactions">
                @foreach (range(1, 6) as $i)
                    <div class="flex animate-pulse items-center gap-4 px-5 py-4">
                        <div class="size-9 rounded-lg bg-slate-200"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-3 w-1/3 rounded bg-slate-200"></div>
                            <div class="h-2.5 w-1/5 rounded bg-slate-100"></div>
                        </div>
                        <div class="hidden h-3 w-24 rounded bg-slate-100 md:block"></div>
                        <div class="h-3 w-20 rounded bg-slate-200"></div>
                    </div>
                @endforeach
            </div>
        @elseif ($state === 'error')
            <x-empty-state icon="alert-triangle" title="We couldn’t load your transactions" description="Something went wrong on our side. Check your connection and try again — your data is safe.">
                <x-button variant="secondary" icon="refresh-cw" :href="$baseRoute">Try again</x-button>
            </x-empty-state>
        @elseif ($total === 0 && $hasFilters)
            <x-empty-state icon="search" title="No transactions match your filters" description="Try a different search term or widen the date range.">
                <x-button variant="secondary" :href="$baseRoute">Clear filters</x-button>
            </x-empty-state>
        @elseif ($total === 0)
            <x-empty-state icon="receipt" title="No transactions yet" description="Once you record income or expenses they’ll show up here, ready to search and filter.">
                <x-button icon="plus" data-dialog-open="transaction-modal" :data-dialog-fill="$addFill">Add your first transaction</x-button>
            </x-empty-state>
        @else
            <x-transactions-table :transactions="$rows" :categories="$categories" />

            {{-- Pagination --}}
            <nav class="flex flex-col items-center justify-between gap-3 border-t border-line px-5 py-3.5 sm:flex-row" aria-label="Pagination">
                <p class="text-sm text-muted">
                    Showing <span class="font-medium text-ink">{{ ($page - 1) * $perPage + 1 }}</span>–<span class="font-medium text-ink">{{ min($page * $perPage, $total) }}</span>
                    of <span class="font-medium text-ink">{{ $total }}</span> results
                </p>
                <div class="flex items-center gap-1">
                    @if ($page > 1)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}" class="inline-flex h-9 items-center gap-1 rounded-lg border border-line bg-white px-3 text-sm font-medium hover:bg-slate-50" rel="prev">
                            <x-icon name="chevron-left" class="size-4" /> Previous
                        </a>
                    @else
                        <span class="inline-flex h-9 cursor-not-allowed items-center gap-1 rounded-lg border border-line px-3 text-sm font-medium text-slate-300" aria-disabled="true">
                            <x-icon name="chevron-left" class="size-4" /> Previous
                        </span>
                    @endif

                    @foreach (range(1, $lastPage) as $number)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $number]) }}" @if ($number === $page) aria-current="page" @endif
                            @class([
                                'hidden size-9 items-center justify-center rounded-lg text-sm font-medium sm:inline-flex',
                                'bg-brand-600 text-white' => $number === $page,
                                'text-muted hover:bg-slate-100 hover:text-ink' => $number !== $page,
                            ])>{{ $number }}</a>
                    @endforeach

                    @if ($page < $lastPage)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}" class="inline-flex h-9 items-center gap-1 rounded-lg border border-line bg-white px-3 text-sm font-medium hover:bg-slate-50" rel="next">
                            Next <x-icon name="chevron-right" class="size-4" />
                        </a>
                    @else
                        <span class="inline-flex h-9 cursor-not-allowed items-center gap-1 rounded-lg border border-line px-3 text-sm font-medium text-slate-300" aria-disabled="true">
                            Next <x-icon name="chevron-right" class="size-4" />
                        </span>
                    @endif
                </div>
            </nav>
        @endif
    </x-card>

    <x-transaction-modal :categories="$categories" />
</x-layouts.app>
