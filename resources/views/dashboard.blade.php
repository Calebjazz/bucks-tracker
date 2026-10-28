{{--
    Dashboard. Data comes from resources/data/mock.php for now.
    Tip: open /dashboard?state=empty to preview what a brand-new user sees.
--}}
@php
    $mock = require resource_path('data/mock.php');
    $summary = $mock['summary'];
    $categories = $mock['categories'];
    $isEmpty = request('state') === 'empty';
    $topBudgets = collect($mock['budgets'])->sortByDesc('percent')->take(3);
@endphp

<x-layouts.app title="Overview" description="Good to see you, {{ explode(' ', $mock['user']['name'])[0] }}. Here’s your money at a glance.">
    <x-slot:actions>
        <x-dropdown width="w-56" align="right">
            <x-slot:trigger class="h-10 gap-2 border border-line bg-white px-3.5 text-sm font-medium text-ink shadow-xs hover:bg-slate-50">
                <x-icon name="calendar" class="size-4 text-slate-500" />
                {{ $mock['period']['range'] }}
                <x-icon name="chevron-down" class="size-4 text-slate-400" />
            </x-slot:trigger>
            <x-dropdown-item icon="check">This month</x-dropdown-item>
            <x-dropdown-item>Last month</x-dropdown-item>
            <x-dropdown-item>Last 3 months</x-dropdown-item>
            <x-dropdown-item>Year to date</x-dropdown-item>
        </x-dropdown>
        <x-button icon="plus" data-dialog-open="transaction-modal">Add transaction</x-button>
    </x-slot:actions>

    {{-- Summary cards --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Total balance" icon="wallet" :amount="$isEmpty ? 0 : $summary['balance']" :change="$isEmpty ? null : $summary['balance_change']" />
        <x-stat-card label="Income this month" icon="arrow-down-left" :amount="$isEmpty ? 0 : $summary['income']" :change="$isEmpty ? null : $summary['income_change']" />
        <x-stat-card label="Expenses this month" icon="arrow-up-right" :amount="$isEmpty ? 0 : $summary['expenses']" :change="$isEmpty ? null : $summary['expenses_change']" :good-when-up="false" />
        <x-stat-card label="Remaining budget" icon="target" :amount="$isEmpty ? 0 : $summary['budget_remaining']">
            @unless ($isEmpty)
                <div class="mt-3">
                    <x-progress :value="$summary['budget_spent']" :max="$summary['budget_total']" :status="$summary['budget_status']" label="Monthly budget used" />
                    <p class="mt-2 text-xs text-muted">{{ round($summary['budget_percent']) }}% used · {{ $mock['period']['days_left'] }} days left</p>
                </div>
            @else
                <p class="mt-2 text-xs text-muted">No budget set yet</p>
            @endunless
        </x-stat-card>
    </div>

    @if ($isEmpty)
        {{-- Empty state for brand-new users --}}
        <x-card class="mt-6">
            <x-empty-state icon="wallet" title="Let’s set up your finances" description="Record your first transaction to unlock charts, budgets and insights. It only takes a few seconds.">
                <x-button icon="plus" data-dialog-open="transaction-modal" data-dialog-fill='{"title":"Add income","fields":{"type":"income"}}'>Add your first income</x-button>
                <x-button variant="secondary" icon="target" :href="route('budgets.index')">Create a budget</x-button>
            </x-empty-state>

            <ol class="grid gap-4 border-t border-line pt-6 sm:grid-cols-3">
                @foreach ([['Add income', 'Log your salary or any money you receive.', 'arrow-down-left'], ['Track expenses', 'Record what you spend as it happens.', 'receipt'], ['Set a budget', 'Give each category a monthly limit.', 'target']] as [$stepTitle, $stepText, $stepIcon])
                    <li class="flex gap-3 rounded-lg bg-slate-50 p-4">
                        <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-white text-brand-600 ring-1 ring-line"><x-icon :name="$stepIcon" class="size-4" /></span>
                        <div>
                            <p class="text-sm font-semibold">{{ $loop->iteration }}. {{ $stepTitle }}</p>
                            <p class="mt-0.5 text-sm text-muted">{{ $stepText }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </x-card>
    @else
        {{-- Charts --}}
        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <x-card title="Income vs. expenses" description="Last six months" class="lg:col-span-2">
                <x-slot:actions>
                    <x-button variant="ghost" size="sm" :href="route('reports.index')">View report <x-icon name="arrow-right" class="size-4" /></x-button>
                </x-slot:actions>
                <x-charts.bar :data="$mock['months']" />
            </x-card>

            <x-card title="Spending by category" description="{{ $mock['period']['label'] }}">
                <x-charts.donut :segments="$mock['donut']" />
            </x-card>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            {{-- Recent transactions --}}
            <x-card title="Recent transactions" description="Your latest activity" :padded="false" class="lg:col-span-2">
                <x-slot:actions>
                    <x-button variant="ghost" size="sm" :href="route('transactions.index')">View all <x-icon name="arrow-right" class="size-4" /></x-button>
                </x-slot:actions>
                <x-transactions-table :transactions="array_slice($mock['transactions'], 0, 6)" :categories="$categories" with-view />
            </x-card>

            <div class="flex flex-col gap-6">
                {{-- Quick actions --}}
                <x-card title="Quick actions">
                    <div class="grid grid-cols-3 gap-3 lg:grid-cols-1 xl:grid-cols-3">
                        <button type="button" data-dialog-open="transaction-modal" data-dialog-fill='{"title":"Add expense","fields":{"type":"expense"}}'
                            class="group flex flex-col items-center gap-2 rounded-xl border border-line p-3 text-center text-xs font-medium text-ink transition-colors hover:border-red-200 hover:bg-red-50/50 lg:flex-row lg:text-left xl:flex-col xl:text-center">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-red-50 text-red-600"><x-icon name="arrow-up-right" class="size-4.5" /></span>
                            Add expense
                        </button>
                        <button type="button" data-dialog-open="transaction-modal" data-dialog-fill='{"title":"Add income","fields":{"type":"income"}}'
                            class="group flex flex-col items-center gap-2 rounded-xl border border-line p-3 text-center text-xs font-medium text-ink transition-colors hover:border-brand-200 hover:bg-brand-50/50 lg:flex-row lg:text-left xl:flex-col xl:text-center">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-brand-50 text-brand-700"><x-icon name="arrow-down-left" class="size-4.5" /></span>
                            Add income
                        </button>
                        <a href="{{ route('reports.index') }}"
                            class="group flex flex-col items-center gap-2 rounded-xl border border-line p-3 text-center text-xs font-medium text-ink transition-colors hover:border-slate-300 hover:bg-slate-50 lg:flex-row lg:text-left xl:flex-col xl:text-center">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600"><x-icon name="bar-chart" class="size-4.5" /></span>
                            View reports
                        </a>
                    </div>
                </x-card>

                {{-- Budget snapshot --}}
                <x-card title="Budgets to watch" class="flex-1">
                    <x-slot:actions>
                        <x-button variant="ghost" size="sm" :href="route('budgets.index')">All budgets</x-button>
                    </x-slot:actions>
                    <ul class="flex flex-col gap-5">
                        @foreach ($topBudgets as $budget)
                            @php($category = $categories[$budget['category']])
                            <li>
                                <div class="flex items-center justify-between gap-3">
                                    <p class="flex items-center gap-2 text-sm font-medium"><x-category-icon :category="$category" size="sm" class="size-7!" /> {{ $category['name'] }}</p>
                                    <x-budget-status :status="$budget['status']" />
                                </div>
                                <x-progress :value="$budget['spent']" :max="$budget['limit']" :status="$budget['status']" :label="$category['name'].' budget used'" class="mt-2.5" />
                                <p class="mt-1.5 flex justify-between text-xs text-muted tabular-nums">
                                    <span>TZS {{ number_format($budget['spent']) }} of {{ number_format($budget['limit']) }}</span>
                                    <span>{{ round($budget['percent']) }}%</span>
                                </p>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            </div>
        </div>
    @endif

    <x-transaction-modal :categories="$categories" />
</x-layouts.app>
