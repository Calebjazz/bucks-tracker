<x-layouts.guest title="Sign in">
    <h1 class="text-2xl font-semibold tracking-tight text-ink">Welcome back</h1>
    <p class="mt-2 text-sm text-muted">Sign in to see where your money is going.</p>

    {{-- Later: action="{{ route('login') }}" and remove data-demo-form so Laravel handles it. --}}
    <form method="POST" action="#" novalidate data-demo-form data-redirect="{{ route('dashboard') }}" class="mt-8 flex flex-col gap-5">
        @csrf

        <x-form.input label="Email address" name="email" type="email" icon="mail" autocomplete="email" placeholder="you@example.com" required autofocus />

        <x-form.input label="Password" name="password" type="password" icon="lock" autocomplete="current-password" placeholder="Enter your password" required>
            <x-slot:aside>
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-brand-700 hover:text-brand-800 hover:underline">Forgot password?</a>
            </x-slot:aside>
        </x-form.input>

        <label class="flex items-center gap-2.5 text-sm text-ink">
            <input type="checkbox" name="remember" class="size-4 rounded border-line accent-brand-600">
            Remember me for 30 days
        </label>

        <x-button type="submit" size="lg" class="w-full" data-loading-text="Signing in…">Sign in</x-button>
    </form>

    <p class="mt-8 text-center text-sm text-muted">
        New to ExpenseFlow?
        <a href="{{ route('register') }}" class="font-semibold text-brand-700 hover:text-brand-800 hover:underline">Create an account</a>
    </p>
</x-layouts.guest>
