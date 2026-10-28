<?php

use Illuminate\Support\Carbon;

/**
 * Mock data for the ExpenseFlow UI (frontend-only phase).
 *
 * Views load it with: @php($mock = require resource_path('data/mock.php'))
 *
 * When you build the backend, each key below becomes a database query in a controller:
 *   categories   -> Category::all()
 *   transactions -> auth()->user()->transactions()->latest('date')->get()
 *   budgets      -> auth()->user()->budgets()->with('category')->get()
 *
 * @return array{
 *     user: array{name: string, email: string, initials: string, currency: string},
 *     period: array{label: string, range: string, days_left: int},
 *     categories: array<string, array{slug: string, name: string, type: string, icon: string, color: string}>,
 *     transactions: list<array{id: int, date: Carbon, description: string, category: string, type: string, amount: int, notes: ?string}>,
 *     months: list<array{label: string, full: string, income: int, expense: int}>,
 *     weeks: list<array{label: string, full: string, value: int}>,
 *     spending: array<string, int>,
 *     donut: list<array{label: string, value: int, color: string}>,
 *     budgets: list<array{category: string, limit: int, spent: int, percent: float, status: string}>,
 *     summary: array<string, int|float|string>,
 *     notifications: list<array{icon: string, tone: string, title: string, time: string, unread: bool}>,
 * }
 */
$categories = collect([
    ['slug' => 'salary', 'name' => 'Salary', 'type' => 'income', 'icon' => 'briefcase', 'color' => '#16a34a'],
    ['slug' => 'freelance', 'name' => 'Freelance', 'type' => 'income', 'icon' => 'laptop', 'color' => '#0d9488'],
    ['slug' => 'investments', 'name' => 'Investments', 'type' => 'income', 'icon' => 'trending-up', 'color' => '#2a78d6'],
    ['slug' => 'gifts', 'name' => 'Gifts', 'type' => 'income', 'icon' => 'gift', 'color' => '#db2777'],
    ['slug' => 'housing', 'name' => 'Rent & Housing', 'type' => 'expense', 'icon' => 'home', 'color' => '#2a78d6'],
    ['slug' => 'food', 'name' => 'Food & Groceries', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#eb6834'],
    ['slug' => 'transport', 'name' => 'Transport', 'type' => 'expense', 'icon' => 'car', 'color' => '#1baf7a'],
    ['slug' => 'utilities', 'name' => 'Utilities & Bills', 'type' => 'expense', 'icon' => 'zap', 'color' => '#eda100'],
    ['slug' => 'shopping', 'name' => 'Shopping', 'type' => 'expense', 'icon' => 'shopping-cart', 'color' => '#e87ba4'],
    ['slug' => 'entertainment', 'name' => 'Entertainment', 'type' => 'expense', 'icon' => 'tv', 'color' => '#6250d6'],
    ['slug' => 'health', 'name' => 'Health', 'type' => 'expense', 'icon' => 'heart', 'color' => '#e34948'],
    ['slug' => 'education', 'name' => 'Education', 'type' => 'expense', 'icon' => 'graduation-cap', 'color' => '#0891b2'],
])->keyBy('slug');

$transaction = fn (int $id, string $date, string $description, string $category, int $amount, ?string $notes = null): array => [
    'id' => $id,
    'date' => Carbon::parse($date),
    'description' => $description,
    'category' => $category,
    'type' => $categories[$category]['type'],
    'amount' => $amount,
    'notes' => $notes,
];

$transactions = collect([
    $transaction(1, '2026-10-27', 'Cinema tickets — Mlimani City', 'entertainment', 30000),
    $transaction(2, '2026-10-26', 'Weekly groceries — Kariakoo Market', 'food', 135000, 'Rice, vegetables, cooking oil and fruit for the week.'),
    $transaction(3, '2026-10-25', 'Monthly salary — Kilimanjaro Tech Ltd', 'salary', 3800000),
    $transaction(4, '2026-10-24', 'Ride-hailing trips', 'transport', 46500),
    $transaction(5, '2026-10-22', 'Team lunch', 'food', 42000),
    $transaction(6, '2026-10-20', 'Mobile data bundle', 'utilities', 30000),
    $transaction(7, '2026-10-19', 'Running sneakers', 'shopping', 165000),
    $transaction(8, '2026-10-18', 'Website project — Mama Ntilie Café', 'freelance', 850000, 'Final 50% payment for the redesign.'),
    $transaction(9, '2026-10-16', 'Pharmacy — vitamins & first aid', 'health', 27500),
    $transaction(10, '2026-10-15', 'Dinner with family', 'food', 38000),
    $transaction(11, '2026-10-14', 'Streaming subscription', 'entertainment', 22000),
    $transaction(12, '2026-10-12', 'Groceries top-up', 'food', 98500),
    $transaction(13, '2026-10-11', 'Online course — Laravel fundamentals', 'education', 60000),
    $transaction(14, '2026-10-10', 'Unit trust dividend', 'investments', 145000),
    $transaction(15, '2026-10-08', 'Petrol refill', 'transport', 120000),
    $transaction(16, '2026-10-06', 'Birthday gift from Neema', 'gifts', 100000),
    $transaction(17, '2026-10-05', 'Water bill', 'utilities', 35000),
    $transaction(18, '2026-10-03', 'Electricity tokens (LUKU)', 'utilities', 80000),
    $transaction(19, '2026-10-02', 'Home fibre internet', 'utilities', 95000),
    $transaction(20, '2026-10-01', 'October rent — Mikocheni apartment', 'housing', 900000),
    $transaction(21, '2026-09-27', 'Dinner at Samaki Samaki', 'food', 55000),
    $transaction(22, '2026-09-25', 'Monthly salary — Kilimanjaro Tech Ltd', 'salary', 3800000),
    $transaction(23, '2026-09-20', 'Logo design — Pwani Tours', 'freelance', 600000),
    $transaction(24, '2026-09-14', 'Weekly groceries — Kariakoo Market', 'food', 142000),
    $transaction(25, '2026-09-09', 'Petrol refill', 'transport', 110000),
    $transaction(26, '2026-09-04', 'Electricity tokens (LUKU)', 'utilities', 75000),
    $transaction(27, '2026-09-01', 'September rent — Mikocheni apartment', 'housing', 900000),
]);

$october = $transactions->filter(fn (array $item): bool => $item['date']->month === 10);
$income = $october->where('type', 'income')->sum('amount');
$expenses = $october->where('type', 'expense')->sum('amount');

$spending = $october->where('type', 'expense')
    ->groupBy('category')
    ->map(fn ($items): int => $items->sum('amount'))
    ->sortDesc();

$budgetLimits = [
    'housing' => 950000,
    'food' => 300000,
    'transport' => 200000,
    'utilities' => 300000,
    'shopping' => 200000,
    'entertainment' => 100000,
    'health' => 100000,
    'education' => 100000,
];

$budgetStatus = fn (float $percent): string => match (true) {
    $percent > 100 => 'exceeded',
    $percent >= 85 => 'warning',
    default => 'ok',
};

$budgets = collect($budgetLimits)->map(function (int $limit, string $slug) use ($spending, $budgetStatus): array {
    $spent = $spending[$slug] ?? 0;
    $percent = round($spent / $limit * 100, 1);

    return ['category' => $slug, 'limit' => $limit, 'spent' => $spent, 'percent' => $percent, 'status' => $budgetStatus($percent)];
})->values();

$budgetTotal = $budgets->sum('limit');
$budgetSpent = $budgets->sum('spent');

/** The donut shows the four biggest fixed categories, everything else folds into "Other". */
$donutSlugs = ['housing', 'food', 'transport', 'utilities'];
$donut = collect($donutSlugs)
    ->map(fn (string $slug): array => ['label' => $categories[$slug]['name'], 'value' => $spending[$slug] ?? 0, 'color' => $categories[$slug]['color']])
    ->push(['label' => 'Other', 'value' => $spending->except($donutSlugs)->sum(), 'color' => '#e87ba4'])
    ->all();

$previousIncome = 4400000;
$previousExpenses = 2265000;

return [
    'user' => [
        'name' => 'Amani Mushi',
        'email' => 'amani.mushi@example.com',
        'initials' => 'AM',
        'currency' => 'TZS',
    ],

    'period' => [
        'label' => 'October 2026',
        'range' => 'Oct 1 – Oct 28, 2026',
        'days_left' => 3,
    ],

    'categories' => $categories->all(),

    'transactions' => $transactions->all(),

    'months' => [
        ['label' => 'May', 'full' => 'May 2026', 'income' => 4200000, 'expense' => 2310000],
        ['label' => 'Jun', 'full' => 'June 2026', 'income' => 4450000, 'expense' => 2580000],
        ['label' => 'Jul', 'full' => 'July 2026', 'income' => 4100000, 'expense' => 2140000],
        ['label' => 'Aug', 'full' => 'August 2026', 'income' => 4650000, 'expense' => 2920000],
        ['label' => 'Sep', 'full' => 'September 2026', 'income' => $previousIncome, 'expense' => $previousExpenses],
        ['label' => 'Oct', 'full' => 'October 2026', 'income' => $income, 'expense' => $expenses],
    ],

    'weeks' => [
        ['label' => 'Sep 1', 'full' => 'Week of Sep 1', 'value' => 1085000],
        ['label' => 'Sep 8', 'full' => 'Week of Sep 8', 'value' => 386000],
        ['label' => 'Sep 15', 'full' => 'Week of Sep 15', 'value' => 298500],
        ['label' => 'Sep 22', 'full' => 'Week of Sep 22', 'value' => 495500],
        ['label' => 'Sep 29', 'full' => 'Week of Sep 29', 'value' => 1110000],
        ['label' => 'Oct 6', 'full' => 'Week of Oct 6', 'value' => 314000],
        ['label' => 'Oct 13', 'full' => 'Week of Oct 13', 'value' => 313000],
        ['label' => 'Oct 20', 'full' => 'Week of Oct 20', 'value' => 418500],
    ],

    'spending' => $spending->all(),

    'donut' => $donut,

    'budgets' => $budgets->all(),

    'summary' => [
        'balance' => 9870500,
        'balance_change' => 3.2,
        'income' => $income,
        'income_change' => round(($income - $previousIncome) / $previousIncome * 100, 1),
        'expenses' => $expenses,
        'expenses_change' => round(($expenses - $previousExpenses) / $previousExpenses * 100, 1),
        'net' => $income - $expenses,
        'savings_rate' => round(($income - $expenses) / $income * 100, 1),
        'budget_total' => $budgetTotal,
        'budget_spent' => $budgetSpent,
        'budget_remaining' => $budgetTotal - $budgetSpent,
        'budget_percent' => round($budgetSpent / $budgetTotal * 100, 1),
        'budget_status' => $budgetStatus($budgetSpent / $budgetTotal * 100),
    ],

    'notifications' => [
        ['icon' => 'alert-triangle', 'tone' => 'danger', 'title' => 'Food & Groceries is over budget by TZS 13,500', 'time' => '2 hours ago', 'unread' => true],
        ['icon' => 'arrow-down-left', 'tone' => 'success', 'title' => 'Salary of TZS 3,800,000 received', 'time' => '3 days ago', 'unread' => true],
        ['icon' => 'file-text', 'tone' => 'neutral', 'title' => 'Your September report is ready', 'time' => '4 weeks ago', 'unread' => false],
    ],
];
