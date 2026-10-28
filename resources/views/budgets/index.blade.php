{{--
    Monthly budgets. Preview the empty state with /budgets?state=empty.
    Status rules (see resources/data/mock.php): over 100% = exceeded, 85% or more = warning.
--}}
@php
    $mock = require resource_path('data/mock.php');
    $categories = $mock['categories'];
    $summary = $mock['summary'];
    $budgets = collect(request('state') === 'empty' ? [] : $mock['budgets']);
    $alerts = $budgets->whereIn('status', ['exceeded', 'warning'])->sortByDesc('percent');
    $dailyAllowance = (int) floor($summary['budget_remaining'] / max(1, $mock['period']['days_left']));
@endphp

<x-layouts.app title="Budgets" description="Set monthly limits per category and stay ahead of overspending.">
    <x-slot:actions>
        <x-dropdown width="w-48">
            <x-slot:trigger class="h-10 gap-2 border border-line bg-white px-3.5 text-sm font-medium text-ink shadow-xs hover:bg-slate-50">
                <x-icon name="calendar" class="size-4 text-slate-500" />
                {{ $mock['period']['label'] }}
                <x-icon name="chevron-down" class="size-4 text-slate-400" />
            </x-slot:trigger>
            <x-dropdown-item icon="check">October 2026</x-dropdown-item>
            <x-dropdown-item>September 2026</x-dropdown-item>
            <x-dropdown-item>August 2026</x-dropdown-item>
        </x-dropdown>
        <x-button icon="plus" data-dialog-open="budget-modal">Create budget</x-button>
    </x-slot:actions>

    @if ($budgets->isEmpty())
        <x-card>
            <x-empty-state icon="target" title="No budgets yet" description="Budgets help you decide in advance how much to spend on each category. We’ll warn you before you go over.">
                <x-button icon="plus" data-dialog-open="budget-modal">Create your first budget</x-button>
            </x-empty-state>
        </x-card>
    @else
        {{-- Overview --}}
        <x-card>
            <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr] lg:items-center">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <p class="text-sm font-medium text-muted">Spent this month</p>
                        <x-budget-status :status="$summary['budget_status']" />
                    </div>
                    <p class="mt-2 flex flex-wrap items-baseline gap-x-2 tabular-nums">
                        <span class="text-3xl font-semibold tracking-tight">TZS {{ number_format($summary['budget_spent']) }}</span>
                        <span class="text-sm text-muted">of TZS {{ number_format($summary['budget_total']) }} budgeted</span>
                    </p>
                    <x-progress :value="$summary['budget_spent']" :max="$summary['budget_total']" :status="$summary['budget_status']" label="Total budget used" class="mt-4 h-3" />
                    <p class="mt-2 text-sm text-muted">{{ round($summary['budget_percent']) }}% of your monthly budget used with {{ $mock['period']['days_left'] }} days to go.</p>
                </div>

                <dl class="grid grid-cols-3 divide-x divide-line rounded-xl border border-line bg-slate-50/60">
                    <div class="p-4">
                        <dt class="text-xs text-muted">Remaining</dt>
                        <dd class="mt-1 text-base font-semibold text-brand-700 tabular-nums sm:text-lg">{{ number_format($summary['budget_remaining']) }}</dd>
                    </div>
                    <div class="p-4">
                        <dt class="text-xs text-muted">Per day left</dt>
                        <dd class="mt-1 text-base font-semibold tabular-nums sm:text-lg">{{ number_format($dailyAllowance) }}</dd>
                    </div>
                    <div class="p-4">
                        <dt class="text-xs text-muted">Budgets</dt>
                        <dd class="mt-1 text-base font-semibold tabular-nums sm:text-lg">{{ $budgets->count() }}</dd>
                    </div>
                </dl>
            </div>
        </x-card>

        {{-- Alerts --}}
        @if ($alerts->isNotEmpty())
            <div class="mt-6 flex flex-col gap-3" role="list" aria-label="Budget alerts">
                @foreach ($alerts as $alert)
                    @php
                        $category = $categories[$alert['category']];
                    @endphp
                    <div role="listitem" @class([
                        'flex items-start gap-3 rounded-xl border px-4 py-3 text-sm',
                        'border-red-200 bg-red-50 text-red-800' => $alert['status'] === 'exceeded',
                        'border-amber-200 bg-amber-50 text-amber-900' => $alert['status'] === 'warning',
                    ])>
                        <x-icon :name="$alert['status'] === 'exceeded' ? 'alert-circle' : 'alert-triangle'" class="mt-0.5 size-4" />
                        <p class="flex-1">
                            @if ($alert['status'] === 'exceeded')
                                <strong class="font-semibold">{{ $category['name'] }}</strong> is over budget by TZS {{ number_format($alert['spent'] - $alert['limit']) }}. Consider pausing spending here until next month.
                            @else
                                <strong class="font-semibold">{{ $category['name'] }}</strong> has used {{ round($alert['percent']) }}% of its budget — TZS {{ number_format($alert['limit'] - $alert['spent']) }} left.
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Category budgets --}}
        <h2 class="mt-8 mb-4 text-base font-semibold">Category budgets</h2>
        <ul class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($budgets as $budget)
                @php
                    $category = $categories[$budget['category']];
                    $left = $budget['limit'] - $budget['spent'];
                    $editData = json_encode(['title' => 'Edit budget', 'fields' => ['category' => $budget['category'], 'amount' => $budget['limit'], 'period' => 'monthly']]);
                @endphp
                <li data-deletable @class([
                    'flex flex-col rounded-xl border bg-white p-5 shadow-xs',
                    'border-red-200' => $budget['status'] === 'exceeded',
                    'border-line' => $budget['status'] !== 'exceeded',
                ])>
                    <div class="flex items-start gap-3">
                        <x-category-icon :category="$category" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold">{{ $category['name'] }}</p>
                            <p class="text-xs text-muted">Monthly budget</p>
                        </div>
                        <x-dropdown width="w-40">
                            <x-slot:trigger class="-mr-2 size-8 text-slate-400 hover:bg-slate-100 hover:text-ink" aria-label="Actions for {{ $category['name'] }} budget">
                                <x-icon name="more-horizontal" class="size-4" />
                            </x-slot:trigger>
                            <x-dropdown-item icon="pencil" data-dialog-open="budget-modal" :data-dialog-fill="$editData">Edit</x-dropdown-item>
                            <x-dropdown-item icon="trash" danger data-confirm-delete="{{ $category['name'] }} budget">Delete</x-dropdown-item>
                        </x-dropdown>
                    </div>

                    <p class="mt-5 flex items-baseline justify-between gap-2 tabular-nums">
                        <span class="text-lg font-semibold">TZS {{ number_format($budget['spent']) }}</span>
                        <span class="text-sm text-muted">of {{ number_format($budget['limit']) }}</span>
                    </p>
                    <x-progress :value="$budget['spent']" :max="$budget['limit']" :status="$budget['status']" :label="$category['name'].' budget used'" class="mt-2.5" />

                    <div class="mt-3 flex items-center justify-between gap-2 text-xs">
                        <x-budget-status :status="$budget['status']" />
                        <span @class(['font-medium tabular-nums', 'text-red-600' => $left < 0, 'text-muted' => $left >= 0])>
                            {{ $left < 0 ? 'TZS '.number_format(abs($left)).' over' : 'TZS '.number_format($left).' left' }}
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    {{-- Create / edit budget --}}
    <x-modal id="budget-modal" title="Create budget" description="Choose a category and how much you plan to spend.">
        <form method="POST" action="#" novalidate data-demo-form data-toast="Budget saved successfully.">
            @csrf
            <div class="flex flex-col gap-5 px-6 py-5">
                <x-form.select label="Category" name="category" required data-required-message="Choose which category this budget is for.">
                    <option value="">Select a category</option>
                    @foreach (collect($categories)->where('type', 'expense') as $category)
                        <option value="{{ $category['slug'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </x-form.select>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form.input label="Amount" name="amount" type="number" prefix="TZS" min="1000" step="any" inputmode="decimal" placeholder="0" required />
                    <x-form.select label="Period" name="period">
                        <option value="monthly">Monthly</option>
                        <option value="weekly">Weekly</option>
                        <option value="yearly">Yearly</option>
                    </x-form.select>
                </div>

                <label class="flex items-start gap-2.5 rounded-lg border border-line p-3 text-sm">
                    <input type="checkbox" name="notify" checked class="mt-0.5 size-4 shrink-0 accent-brand-600">
                    <span>
                        <span class="block font-medium">Warn me at 85%</span>
                        <span class="text-muted">Get a notification before this budget runs out.</span>
                    </span>
                </label>
            </div>

            <div class="flex flex-col-reverse gap-2 border-t border-line bg-slate-50/70 px-6 py-4 sm:flex-row sm:justify-end">
                <x-button variant="secondary" data-dialog-close>Cancel</x-button>
                <x-button type="submit" icon="check">Save budget</x-button>
            </div>
        </form>
    </x-modal>
</x-layouts.app>
