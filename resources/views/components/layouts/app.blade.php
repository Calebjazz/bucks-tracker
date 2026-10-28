{{--
    Layout for every signed-in page: fixed sidebar (desktop), slide-in sidebar (mobile),
    top bar with search, notifications and profile menu, and the page heading.

    Usage:
    <x-layouts.app title="Transactions" description="…">
        <x-slot:actions> <x-button>Add</x-button> </x-slot:actions>
        …page content…
    </x-layouts.app>
--}}
@props(['title', 'description' => null])

@php
    $mock = require resource_path('data/mock.php');
    $user = $mock['user'];
    $notifications = $mock['notifications'];

    $navigation = [
        ['label' => 'Overview', 'route' => 'dashboard', 'icon' => 'layout-dashboard'],
        ['label' => 'Transactions', 'route' => 'transactions.index', 'icon' => 'arrow-left-right'],
        ['label' => 'Income', 'route' => 'income', 'icon' => 'trending-up'],
        ['label' => 'Expenses', 'route' => 'expenses', 'icon' => 'trending-down'],
        ['label' => 'Categories', 'route' => 'categories.index', 'icon' => 'tag'],
        ['label' => 'Budgets', 'route' => 'budgets.index', 'icon' => 'target'],
        ['label' => 'Reports', 'route' => 'reports.index', 'icon' => 'pie-chart'],
    ];
@endphp

<x-layouts.base :title="$title">
    {{-- Mobile overlay behind the sidebar --}}
    <div data-sidebar-overlay hidden class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside data-sidebar aria-label="Main navigation"
        class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-line bg-white transition-transform duration-200 data-open:translate-x-0 lg:w-64 lg:translate-x-0">
        <div class="flex h-16 shrink-0 items-center justify-between px-5">
            <a href="{{ route('dashboard') }}" aria-label="ExpenseFlow overview"><x-logo /></a>
            <button type="button" data-sidebar-close class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-ink lg:hidden" aria-label="Close navigation">
                <x-icon name="x" class="size-5" />
            </button>
        </div>

        <nav class="flex flex-1 flex-col gap-6 overflow-y-auto px-3 py-4">
            <div>
                <p class="px-3 pb-2 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Menu</p>
                <ul class="flex flex-col gap-0.5">
                    @foreach ($navigation as $item)
                        @php($active = request()->routeIs($item['route']))
                        <li>
                            <a href="{{ route($item['route']) }}" @if ($active) aria-current="page" @endif
                                @class([
                                    'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                                    'bg-brand-50 text-brand-700' => $active,
                                    'text-slate-600 hover:bg-slate-50 hover:text-ink' => ! $active,
                                ])>
                                <x-icon :name="$item['icon']" @class(['size-4.5', 'text-brand-600' => $active, 'text-slate-400 group-hover:text-slate-600' => ! $active]) />
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <p class="px-3 pb-2 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Account</p>
                @php($active = request()->routeIs('settings'))
                <a href="{{ route('settings') }}" @if ($active) aria-current="page" @endif
                    @class([
                        'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                        'bg-brand-50 text-brand-700' => $active,
                        'text-slate-600 hover:bg-slate-50 hover:text-ink' => ! $active,
                    ])>
                    <x-icon name="settings" @class(['size-4.5', 'text-brand-600' => $active, 'text-slate-400 group-hover:text-slate-600' => ! $active]) />
                    Settings
                </a>
            </div>
        </nav>

        <div class="border-t border-line p-3">
            <div class="flex items-center gap-3 rounded-lg p-2">
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-800">{{ $user['initials'] }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-ink">{{ $user['name'] }}</p>
                    <p class="truncate text-xs text-muted">{{ $user['email'] }}</p>
                </div>
                <a href="{{ route('login') }}" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-ink" aria-label="Sign out" title="Sign out">
                    <x-icon name="log-out" class="size-4" />
                </a>
            </div>
        </div>
    </aside>

    <div class="flex min-h-dvh flex-col lg:pl-64">
        {{-- Top bar --}}
        <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-3 border-b border-line bg-white px-4 sm:px-6 lg:px-8">
            <button type="button" data-sidebar-open aria-expanded="false" class="-ml-1.5 rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 hover:text-ink lg:hidden" aria-label="Open navigation">
                <x-icon name="menu" class="size-5" />
            </button>
            <a href="{{ route('dashboard') }}" class="sm:hidden" aria-label="ExpenseFlow overview"><x-logo :with-text="false" /></a>

            <form method="GET" action="{{ route('transactions.index') }}" role="search" class="hidden w-full max-w-sm sm:block">
                <label for="global-search" class="sr-only">Search transactions</label>
                <div class="relative">
                    <x-icon name="search" class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-slate-400" />
                    <input id="global-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search transactions…"
                        class="h-9 w-full rounded-lg border border-line bg-slate-50 pr-3 pl-9 text-sm placeholder:text-slate-400 hover:border-slate-300 focus:border-brand-600 focus:bg-white focus:ring-3 focus:ring-brand-600/15 focus:outline-none">
                </div>
            </form>

            <div class="ml-auto flex items-center gap-1.5 sm:gap-3">
                <p class="hidden items-center gap-2 text-sm text-muted xl:flex">
                    <x-icon name="calendar" class="size-4" />
                    <time datetime="{{ now()->toDateString() }}">{{ now()->format('l, j F Y') }}</time>
                </p>

                <x-dropdown width="w-80">
                    <x-slot:trigger class="relative size-9 text-slate-500 hover:bg-slate-100 hover:text-ink" aria-label="Notifications, 2 unread">
                        <x-icon name="bell" class="size-5" />
                        <span class="absolute top-2 right-2 size-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </x-slot:trigger>

                    <div class="flex items-center justify-between px-2.5 pt-1.5 pb-2">
                        <p class="text-sm font-semibold">Notifications</p>
                        <button type="button" role="menuitem" class="rounded text-xs font-medium text-brand-700 hover:underline">Mark all as read</button>
                    </div>
                    @foreach ($notifications as $notification)
                        <a href="{{ route('budgets.index') }}" role="menuitem" class="flex gap-3 rounded-lg px-2.5 py-2.5 hover:bg-slate-50 focus:bg-slate-50 focus:outline-none">
                            <span @class([
                                'flex size-8 shrink-0 items-center justify-center rounded-full',
                                'bg-red-50 text-red-600' => $notification['tone'] === 'danger',
                                'bg-brand-50 text-brand-700' => $notification['tone'] === 'success',
                                'bg-slate-100 text-slate-500' => $notification['tone'] === 'neutral',
                            ])>
                                <x-icon :name="$notification['icon']" class="size-4" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm text-ink">{{ $notification['title'] }}</span>
                                <span class="mt-0.5 block text-xs text-muted">{{ $notification['time'] }}</span>
                            </span>
                            @if ($notification['unread'])
                                <span class="mt-1.5 size-2 shrink-0 rounded-full bg-brand-600"><span class="sr-only">Unread</span></span>
                            @endif
                        </a>
                    @endforeach
                </x-dropdown>

                <span class="hidden h-6 w-px bg-line sm:block"></span>

                <x-dropdown width="w-56">
                    <x-slot:trigger class="gap-2 p-1 hover:bg-slate-100 sm:pr-2" aria-label="Open profile menu">
                        <span class="flex size-8 items-center justify-center rounded-full bg-brand-100 text-xs font-semibold text-brand-800">{{ $user['initials'] }}</span>
                        <span class="hidden text-sm font-medium text-ink md:block">{{ $user['name'] }}</span>
                        <x-icon name="chevron-down" class="hidden size-4 text-slate-400 md:block" />
                    </x-slot:trigger>

                    <div class="border-b border-line px-2.5 pt-1.5 pb-2.5">
                        <p class="text-sm font-semibold">{{ $user['name'] }}</p>
                        <p class="truncate text-xs text-muted">{{ $user['email'] }}</p>
                    </div>
                    <div class="pt-1">
                        <x-dropdown-item icon="user" :href="route('settings')">Your profile</x-dropdown-item>
                        <x-dropdown-item icon="settings" :href="route('settings')">Settings</x-dropdown-item>
                        <x-dropdown-item icon="log-out" :href="route('login')">Sign out</x-dropdown-item>
                    </div>
                </x-dropdown>
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
            <div class="mb-6 flex flex-col gap-4 sm:mb-8 md:flex-row md:items-end md:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-ink">{{ $title }}</h1>
                    @if ($description)
                        <p class="mt-1 text-sm text-muted">{{ $description }}</p>
                    @endif
                </div>
                @isset($actions)
                    <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
                @endisset
            </div>

            {{ $slot }}
        </main>
    </div>

    <x-confirm-dialog />
</x-layouts.base>
