<x-layouts.guest title="Create your account">
    <h1 class="text-2xl font-semibold tracking-tight text-ink">Create your account</h1>
    <p class="mt-2 text-sm text-muted">Start tracking for free. It takes less than two minutes.</p>

    {{-- Later: action="{{ route('register') }}" and remove data-demo-form so Laravel handles it. --}}
    <form method="POST" action="#" novalidate data-demo-form data-redirect="{{ route('dashboard') }}" class="mt-8 flex flex-col gap-5">
        @csrf

        <x-form.input label="Full name" name="name" icon="user" autocomplete="name" placeholder="Amani Mushi" required autofocus />

        <x-form.input label="Email address" name="email" type="email" icon="mail" autocomplete="email" placeholder="you@example.com" required />

        <x-form.input label="Password" name="password" type="password" icon="lock" autocomplete="new-password" minlength="8" hint="Use at least 8 characters." required />

        <x-form.input label="Confirm password" name="password_confirmation" type="password" icon="lock" autocomplete="new-password" data-match="password" required />

        <div class="flex flex-col gap-1.5">
            <label class="flex items-start gap-2.5 text-sm text-muted">
                <input type="checkbox" id="terms" name="terms" required aria-describedby="terms-error"
                    data-required-message="Please accept the Terms of Service and Privacy Policy to continue."
                    class="mt-0.5 size-4 shrink-0 rounded border-line accent-brand-600">
                <span>I agree to the <a href="#" class="font-medium text-ink underline decoration-slate-300 underline-offset-2 hover:decoration-ink">Terms of Service</a> and <a href="#" class="font-medium text-ink underline decoration-slate-300 underline-offset-2 hover:decoration-ink">Privacy Policy</a>.</span>
            </label>
            <p id="terms-error" data-error class="text-xs font-medium text-red-600" hidden></p>
        </div>

        <x-button type="submit" size="lg" class="w-full" data-loading-text="Creating your account…">Create account</x-button>
    </form>

    <p class="mt-8 text-center text-sm text-muted">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-brand-700 hover:text-brand-800 hover:underline">Sign in</a>
    </p>
</x-layouts.guest>
