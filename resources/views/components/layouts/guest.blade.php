{{-- Split-screen layout for the sign in, register and forgot password pages. --}}
@props(['title'])

<x-layouts.base :title="$title" class="bg-white">
    <div class="grid min-h-dvh lg:grid-cols-[1fr_1fr] xl:grid-cols-[1fr_1.1fr]">
        <div class="flex flex-col px-4 py-6 sm:px-8 lg:px-12">
            <a href="{{ route('home') }}" class="self-start" aria-label="ExpenseFlow home"><x-logo /></a>

            <div class="flex flex-1 items-center justify-center py-12">
                <div class="w-full max-w-sm">
                    {{ $slot }}
                </div>
            </div>

            <p class="text-xs text-muted">© {{ date('Y') }} ExpenseFlow. All rights reserved.</p>
        </div>

        <aside class="relative hidden overflow-hidden bg-brand-950 lg:flex lg:flex-col lg:justify-between lg:p-12 xl:p-16" aria-label="About ExpenseFlow">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_10%,rgb(22_163_74/0.35),transparent_45%)]" aria-hidden="true"></div>

            <div class="relative max-w-md">
                <p class="text-sm font-medium text-brand-300">Personal finance, simplified</p>
                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-white">Know exactly where every shilling goes.</h2>
                <p class="mt-4 text-base leading-7 text-brand-100/80">Track income and expenses, set monthly budgets and get clear insights — all in one calm, focused workspace.</p>
            </div>

            <div class="relative mt-10 max-w-md rounded-2xl bg-white p-5 shadow-2xl shadow-black/30">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-ink">October budget</p>
                    <x-badge variant="warning" icon="alert-triangle">86% used</x-badge>
                </div>
                <p class="mt-3 flex items-baseline gap-1.5 tabular-nums">
                    <span class="text-xl font-semibold text-ink">TZS 1,924,500</span>
                    <span class="text-sm text-muted">of 2,250,000</span>
                </p>
                <x-progress :value="86" :max="100" status="warning" label="October budget used" class="mt-3" />
                <ul class="mt-5 flex flex-col gap-3 text-sm">
                    <li class="flex items-center justify-between"><span class="flex items-center gap-2 text-muted"><span class="size-2 rounded-full bg-[#2a78d6]"></span>Rent &amp; Housing</span><span class="font-medium tabular-nums">TZS 900,000</span></li>
                    <li class="flex items-center justify-between"><span class="flex items-center gap-2 text-muted"><span class="size-2 rounded-full bg-[#eb6834]"></span>Food &amp; Groceries</span><span class="font-medium tabular-nums">TZS 313,500</span></li>
                    <li class="flex items-center justify-between"><span class="flex items-center gap-2 text-muted"><span class="size-2 rounded-full bg-[#eda100]"></span>Utilities &amp; Bills</span><span class="font-medium tabular-nums">TZS 240,000</span></li>
                </ul>
            </div>

            <figure class="relative mt-10 max-w-md">
                <blockquote class="text-base leading-7 text-white">“I finally stopped guessing at the end of the month. ExpenseFlow showed me I was spending twice what I thought on transport.”</blockquote>
                <figcaption class="mt-4 flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-full bg-brand-800 text-sm font-semibold text-brand-100">NJ</span>
                    <span class="text-sm"><span class="block font-semibold text-white">Neema Joseph</span><span class="text-brand-200/70">Product designer, Arusha</span></span>
                </figcaption>
            </figure>
        </aside>
    </div>
</x-layouts.base>
