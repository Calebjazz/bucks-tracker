{{-- Public marketing page for ExpenseFlow. --}}
@php
    $mock = require resource_path('data/mock.php');
    $summary = $mock['summary'];
    $categories = $mock['categories'];
    $recent = array_slice($mock['transactions'], 0, 4);

    $navLinks = ['Features' => '#features', 'How it works' => '#how-it-works', 'Benefits' => '#benefits'];

    $features = [
        ['icon' => 'arrow-left-right', 'title' => 'Income & expense tracking', 'text' => 'Log salary, side income and every purchase in seconds. Each entry lands in the right place automatically.', 'points' => ['Add transactions in under 10 seconds', 'Search and filter your full history']],
        ['icon' => 'target', 'title' => 'Smart budget management', 'text' => 'Give every category a monthly limit and see progress at a glance — before overspending happens.', 'points' => ['Warnings at 85% of a budget', 'Daily spending allowance']],
        ['icon' => 'pie-chart', 'title' => 'Spending insights & reports', 'text' => 'Clear charts show trends month over month, so you can see where your money really goes.', 'points' => ['Six-month income vs. expense trends', 'Export reports to CSV']],
        ['icon' => 'tag', 'title' => 'Expense categories', 'text' => 'Use ready-made categories or create your own, each with an icon you can recognise instantly.', 'points' => ['Separate income and expense groups', 'Custom names and icons']],
    ];

    $steps = [
        ['icon' => 'user-plus', 'title' => 'Create your account', 'text' => 'Sign up with your email in under a minute. No credit card, no bank connection required.'],
        ['icon' => 'pen-line', 'title' => 'Record income and expenses', 'text' => 'Add what comes in and what goes out. Pick a category and you’re done.'],
        ['icon' => 'bar-chart', 'title' => 'Review your insights', 'text' => 'Watch your dashboard fill with trends, budgets and savings you can act on.'],
    ];

    $benefits = [
        ['icon' => 'eye', 'title' => 'See the full picture', 'text' => 'Balance, income, spending and budgets on a single screen — no spreadsheets to maintain.'],
        ['icon' => 'piggy-bank', 'title' => 'Save more each month', 'text' => 'Small leaks become obvious. Users typically cut 10–15% of discretionary spending in their first three months.'],
        ['icon' => 'shield-check', 'title' => 'Private by design', 'text' => 'Your data stays yours. We never sell it and never need access to your bank account.'],
        ['icon' => 'smartphone', 'title' => 'Works on every device', 'text' => 'A fast, responsive web app that feels at home on your phone, tablet or laptop.'],
    ];
@endphp

<x-layouts.base class="bg-white">
    {{-- Navbar --}}
    <header class="sticky top-0 z-40 border-b border-line/70 bg-white/95">
        <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-6 px-4 sm:px-6 lg:px-8" aria-label="Main">
            <a href="{{ route('home') }}" aria-label="ExpenseFlow home"><x-logo /></a>

            <ul class="hidden items-center gap-8 md:flex">
                @foreach ($navLinks as $label => $href)
                    <li><a href="{{ $href }}" class="text-sm font-medium text-muted transition-colors hover:text-ink">{{ $label }}</a></li>
                @endforeach
            </ul>

            <div class="hidden items-center gap-2 md:flex">
                <x-button variant="ghost" :href="route('login')">Sign in</x-button>
                <x-button :href="route('register')">Get started</x-button>
            </div>

            <button type="button" data-toggle="mobile-nav" aria-controls="mobile-nav" aria-expanded="false" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 md:hidden" aria-label="Toggle menu">
                <x-icon name="menu" class="size-5" />
            </button>
        </nav>

        <div id="mobile-nav" hidden class="border-t border-line px-4 pt-2 pb-4 md:hidden">
            <ul class="flex flex-col">
                @foreach ($navLinks as $label => $href)
                    <li><a href="{{ $href }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-ink hover:bg-slate-50">{{ $label }}</a></li>
                @endforeach
            </ul>
            <div class="mt-3 grid grid-cols-2 gap-2">
                <x-button variant="secondary" :href="route('login')">Sign in</x-button>
                <x-button :href="route('register')">Get started</x-button>
            </div>
        </div>
    </header>

    <main>
        {{-- Hero --}}
        <section class="relative overflow-hidden">
            <div class="absolute inset-x-0 top-0 -z-10 h-[620px] bg-linear-to-b from-brand-50/70 to-white" aria-hidden="true"></div>

            <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 pt-14 pb-20 sm:px-6 lg:grid-cols-[1fr_1.15fr] lg:gap-16 lg:px-8 lg:pt-20 lg:pb-28">
                <div class="max-w-xl">
                    <p class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white px-3 py-1 text-xs font-medium text-brand-800 shadow-xs">
                        <span class="size-1.5 rounded-full bg-brand-600"></span>
                        New: budget alerts before you overspend
                    </p>
                    <h1 class="mt-6 text-4xl font-semibold tracking-tight text-ink sm:text-5xl lg:text-[56px] lg:leading-[1.05]">
                        Take control of <span class="text-brand-600">your money.</span>
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-muted">
                        ExpenseFlow helps you track income, monitor expenses and manage monthly budgets — so you always know where your money goes and how to keep more of it.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <x-button size="lg" :href="route('register')">Start tracking for free <x-icon name="arrow-right" class="size-4" /></x-button>
                        <x-button size="lg" variant="secondary" href="#features">Explore features</x-button>
                    </div>
                    <ul class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-muted">
                        @foreach (['Free to start', 'No credit card needed', 'Set up in 2 minutes'] as $point)
                            <li class="flex items-center gap-2"><x-icon name="check" class="size-4 text-brand-600" />{{ $point }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- Hero visual: a live preview of the dashboard --}}
                <div class="relative">
                    <div class="overflow-hidden rounded-2xl border border-line bg-canvas shadow-2xl shadow-slate-900/10">
                        <div class="flex items-center gap-2 border-b border-line bg-white px-4 py-3" aria-hidden="true">
                            <span class="size-2.5 rounded-full bg-slate-200"></span>
                            <span class="size-2.5 rounded-full bg-slate-200"></span>
                            <span class="size-2.5 rounded-full bg-slate-200"></span>
                            <span class="ml-3 rounded-md bg-slate-100 px-3 py-1 text-[11px] text-slate-500">app.expenseflow.co.tz/dashboard</span>
                        </div>
                        <div class="flex flex-col gap-4 p-4 sm:p-5">
                            <div class="grid grid-cols-3 gap-3">
                                @foreach ([['Balance', $summary['balance'], 'text-ink'], ['Income', $summary['income'], 'text-brand-700'], ['Expenses', $summary['expenses'], 'text-red-600']] as [$label, $amount, $color])
                                    <div class="rounded-xl border border-line bg-white p-3 sm:p-4">
                                        <p class="text-[11px] font-medium text-muted sm:text-xs">{{ $label }}</p>
                                        <p class="mt-1 text-sm font-semibold tabular-nums sm:text-base {{ $color }}">{{ number_format($amount / 1_000_000, 2) }}M</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="rounded-xl border border-line bg-white p-4">
                                <p class="mb-3 text-sm font-semibold">Income vs. expenses</p>
                                <x-charts.bar :data="$mock['months']" height="h-36 sm:h-44" />
                            </div>
                        </div>
                    </div>

                    <div class="absolute -bottom-16 -left-4 hidden w-64 rounded-xl border border-line bg-white p-4 shadow-xl shadow-slate-900/10 sm:block lg:-left-10">
                        <div class="flex items-center gap-3">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><x-icon name="alert-triangle" class="size-4" /></span>
                            <div>
                                <p class="text-sm font-semibold">Rent &amp; Housing</p>
                                <p class="text-xs text-muted">95% of budget used</p>
                            </div>
                        </div>
                        <x-progress :value="95" :max="100" status="warning" label="Rent and housing budget used" class="mt-3" />
                    </div>
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section id="features" class="scroll-mt-16 border-t border-line bg-canvas py-20 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold text-brand-700">Features</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Everything you need to manage money, nothing you don’t.</h2>
                    <p class="mt-4 text-lg text-muted">Four focused tools that work together to give you a calm, complete view of your finances.</p>
                </div>

                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($features as $feature)
                        <article class="flex flex-col rounded-2xl border border-line bg-white p-6 shadow-xs transition hover:-translate-y-0.5 hover:shadow-md">
                            <span class="flex size-11 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-600/10">
                                <x-icon :name="$feature['icon']" class="size-5" />
                            </span>
                            <h3 class="mt-5 text-base font-semibold">{{ $feature['title'] }}</h3>
                            <p class="mt-2 flex-1 text-sm leading-6 text-muted">{{ $feature['text'] }}</p>
                            <ul class="mt-5 flex flex-col gap-2 border-t border-line pt-5">
                                @foreach ($feature['points'] as $point)
                                    <li class="flex items-start gap-2 text-sm text-ink"><x-icon name="check" class="mt-0.5 size-4 text-brand-600" />{{ $point }}</li>
                                @endforeach
                            </ul>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- How it works --}}
        <section id="how-it-works" class="scroll-mt-16 py-20 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-sm font-semibold text-brand-700">How it works</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Clear finances in three steps</h2>
                </div>

                <div class="relative mt-14">
                    <div class="absolute top-6 right-[16%] left-[16%] hidden border-t border-dashed border-slate-300 md:block" aria-hidden="true"></div>
                <ol class="relative grid gap-10 md:grid-cols-3 md:gap-8">
                    @foreach ($steps as $step)
                        <li class="relative flex flex-col items-center text-center">
                            <span class="relative flex size-12 items-center justify-center rounded-full border border-line bg-white text-brand-700 shadow-sm">
                                <x-icon :name="$step['icon']" class="size-5" />
                                <span class="absolute -top-1 -right-1 flex size-5 items-center justify-center rounded-full bg-brand-600 text-[11px] font-semibold text-white">{{ $loop->iteration }}</span>
                            </span>
                            <h3 class="mt-5 text-base font-semibold">{{ $step['title'] }}</h3>
                            <p class="mt-2 max-w-xs text-sm leading-6 text-muted">{{ $step['text'] }}</p>
                        </li>
                    @endforeach
                </ol>
                </div>
            </div>
        </section>

        {{-- Dashboard preview --}}
        <section class="bg-ink py-20 sm:py-24" aria-labelledby="preview-heading">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-sm font-semibold text-brand-400">Dashboard preview</p>
                    <h2 id="preview-heading" class="mt-2 text-3xl font-semibold tracking-tight text-white sm:text-4xl">Your whole month, on one screen</h2>
                    <p class="mt-4 text-lg text-slate-400">See your summary, latest transactions and spending breakdown the moment you sign in.</p>
                </div>

                <div class="mt-12 rounded-2xl bg-white/5 p-2 ring-1 ring-white/10 sm:p-3">
                    <div class="grid gap-4 rounded-xl bg-canvas p-4 sm:p-6 lg:grid-cols-5">
                        <div class="grid grid-cols-2 gap-4 lg:col-span-5 lg:grid-cols-4">
                            <x-stat-card label="Total balance" icon="wallet" :amount="$summary['balance']" :change="$summary['balance_change']" />
                            <x-stat-card label="Income" icon="arrow-down-left" :amount="$summary['income']" :change="$summary['income_change']" />
                            <x-stat-card label="Expenses" icon="arrow-up-right" :amount="$summary['expenses']" :change="$summary['expenses_change']" :good-when-up="false" />
                            <x-stat-card label="Budget left" icon="target" :amount="$summary['budget_remaining']">
                                <x-progress :value="$summary['budget_spent']" :max="$summary['budget_total']" :status="$summary['budget_status']" label="Monthly budget used" class="mt-3" />
                            </x-stat-card>
                        </div>

                        <x-card title="Recent transactions" :padded="false" class="lg:col-span-3">
                            <ul class="divide-y divide-line">
                                @foreach ($recent as $transaction)
                                    @php($isIncome = $transaction['type'] === 'income')
                                    <li class="flex items-center gap-3 px-5 py-3.5">
                                        <x-category-icon :category="$categories[$transaction['category']]" size="sm" />
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-medium">{{ $transaction['description'] }}</p>
                                            <p class="text-xs text-muted">{{ $categories[$transaction['category']]['name'] }} · {{ $transaction['date']->format('M j') }}</p>
                                        </div>
                                        <x-money :amount="$transaction['amount']" signed :type="$transaction['type']" @class(['text-sm font-semibold', 'text-brand-700' => $isIncome, 'text-red-600' => ! $isIncome]) />
                                    </li>
                                @endforeach
                            </ul>
                        </x-card>

                        <x-card title="Spending by category" class="lg:col-span-2">
                            <x-charts.donut :segments="$mock['donut']" />
                        </x-card>
                    </div>
                </div>
            </div>
        </section>

        {{-- Benefits --}}
        <section id="benefits" class="scroll-mt-16 py-20 sm:py-24">
            <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[1fr_1.4fr] lg:gap-16 lg:px-8">
                <div>
                    <p class="text-sm font-semibold text-brand-700">Benefits</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Spend with confidence, save without stress.</h2>
                    <p class="mt-4 text-lg text-muted">ExpenseFlow turns scattered receipts and mobile money messages into a clear plan you can actually follow.</p>
                    <dl class="mt-10 grid grid-cols-2 gap-6 border-t border-line pt-8">
                        <div>
                            <dt class="text-sm text-muted">Average monthly savings</dt>
                            <dd class="mt-1 text-2xl font-semibold tracking-tight">+18%</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-muted">Time to log an expense</dt>
                            <dd class="mt-1 text-2xl font-semibold tracking-tight">&lt; 10 sec</dd>
                        </div>
                    </dl>
                </div>

                <div class="grid gap-x-8 gap-y-10 sm:grid-cols-2">
                    @foreach ($benefits as $benefit)
                        <div>
                            <span class="flex size-10 items-center justify-center rounded-lg bg-slate-100 text-ink">
                                <x-icon :name="$benefit['icon']" class="size-5" />
                            </span>
                            <h3 class="mt-4 text-base font-semibold">{{ $benefit['title'] }}</h3>
                            <p class="mt-2 text-sm leading-6 text-muted">{{ $benefit['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Final CTA --}}
        <section class="px-4 pb-20 sm:px-6 sm:pb-24 lg:px-8">
            <div class="relative mx-auto max-w-7xl overflow-hidden rounded-3xl bg-brand-700 px-6 py-16 text-center sm:px-12 sm:py-20">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgb(255_255_255/0.14),transparent_60%)]" aria-hidden="true"></div>
                <h2 class="relative text-3xl font-semibold tracking-tight text-white sm:text-4xl">Your finances, clearer than ever.</h2>
                <p class="relative mx-auto mt-4 max-w-xl text-lg text-brand-100">Join thousands of people who finally know where their money goes. Free to start, ready in minutes.</p>
                <div class="relative mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-lg bg-white px-6 text-base font-semibold text-brand-800 shadow-sm transition-colors hover:bg-brand-50 focus-visible:outline-white">
                        Get started <x-icon name="arrow-right" class="size-4" />
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex h-12 items-center justify-center rounded-lg px-6 text-base font-semibold text-white ring-1 ring-white/30 transition-colors hover:bg-white/10 focus-visible:outline-white">
                        Sign in
                    </a>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="border-t border-line bg-canvas">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-[1.5fr_1fr_1fr_1fr] lg:px-8">
            <div class="max-w-xs">
                <x-logo />
                <p class="mt-4 text-sm leading-6 text-muted">A simple, private personal finance tracker for income, expenses and budgets.</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold">Product</h3>
                <ul class="mt-4 flex flex-col gap-3 text-sm text-muted">
                    <li><a href="#features" class="hover:text-ink">Features</a></li>
                    <li><a href="#how-it-works" class="hover:text-ink">How it works</a></li>
                    <li><a href="#benefits" class="hover:text-ink">Benefits</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold">Account</h3>
                <ul class="mt-4 flex flex-col gap-3 text-sm text-muted">
                    <li><a href="{{ route('register') }}" class="hover:text-ink">Create account</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-ink">Sign in</a></li>
                    <li><a href="{{ route('password.request') }}" class="hover:text-ink">Reset password</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold">Legal</h3>
                <ul class="mt-4 flex flex-col gap-3 text-sm text-muted">
                    <li><a href="#" class="hover:text-ink">Privacy policy</a></li>
                    <li><a href="#" class="hover:text-ink">Terms of service</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-line">
            <p class="mx-auto max-w-7xl px-4 py-6 text-sm text-muted sm:px-6 lg:px-8">© {{ date('Y') }} ExpenseFlow. Built with Laravel &amp; Tailwind CSS.</p>
        </div>
    </footer>
</x-layouts.base>
