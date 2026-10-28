{{--
    The HTML shell every page shares: <head>, fonts, Vite assets and the toast region.
    The landing page uses it directly; the app and auth layouts wrap it.
--}}
@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="ExpenseFlow helps you track income, monitor expenses, manage budgets and understand your financial habits.">

    <title>{{ $title ? "{$title} · ExpenseFlow" : 'ExpenseFlow — Take control of your money' }}</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body {{ $attributes->class('min-h-dvh') }}>
    {{ $slot }}

    {{-- Toasts are appended here by resources/js/app.js --}}
    <div id="toast-region" aria-live="polite" class="pointer-events-none fixed inset-x-4 bottom-4 z-60 flex flex-col items-end gap-2 sm:inset-x-auto sm:right-6 sm:bottom-6"></div>

    <template id="toast-template">
        <div role="status" class="pointer-events-auto flex w-full max-w-sm translate-y-2 items-start gap-3 rounded-xl border border-line bg-white p-4 opacity-0 shadow-lg transition duration-200">
            <x-icon name="check-circle" data-toast-icon="success" class="mt-0.5 size-5 text-brand-600" />
            <x-icon name="alert-circle" data-toast-icon="error" class="mt-0.5 size-5 text-red-600" />
            <p data-toast-message class="flex-1 text-sm font-medium text-ink"></p>
            <button type="button" data-toast-close class="-m-1 rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-ink" aria-label="Dismiss notification">
                <x-icon name="x" class="size-4" />
            </button>
        </div>
    </template>

    {{-- A controller can do: return redirect()->route('dashboard')->with('status', 'Saved!'); --}}
    @if (session('status'))
        <div data-flash-toast="{{ session('status') }}" hidden></div>
    @endif
</body>
</html>
