<x-layouts.guest title="Reset your password">
    <div id="reset-request">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-muted hover:text-ink">
            <x-icon name="arrow-left" class="size-4" /> Back to sign in
        </a>

        <h1 class="mt-6 text-2xl font-semibold tracking-tight text-ink">Forgot your password?</h1>
        <p class="mt-2 text-sm text-muted">No problem. Enter the email you signed up with and we’ll send you a link to choose a new one.</p>

        {{-- Later: action="{{ route('password.email') }}" and remove data-demo-form. --}}
        <form method="POST" action="#" novalidate data-demo-form data-hide="#reset-request" data-reveal="#reset-sent" class="mt-8 flex flex-col gap-5">
            @csrf

            <x-form.input label="Email address" name="email" type="email" icon="mail" autocomplete="email" placeholder="you@example.com" required autofocus />

            <x-button type="submit" size="lg" class="w-full" data-loading-text="Sending link…">Send reset link</x-button>
        </form>
    </div>

    {{-- Confirmation state, shown after a successful submit --}}
    <div id="reset-sent" hidden class="text-center">
        <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-brand-50 text-brand-600 ring-8 ring-brand-50/50">
            <x-icon name="mail" class="size-6" />
        </span>
        <h1 class="mt-6 text-2xl font-semibold tracking-tight text-ink">Check your inbox</h1>
        <p class="mt-2 text-sm text-muted">
            We sent a password reset link to <strong data-echo="email" class="font-semibold text-ink"></strong>. The link expires in 60 minutes.
        </p>

        <x-button :href="route('login')" size="lg" class="mt-8 w-full">Back to sign in</x-button>

        <p class="mt-6 text-sm text-muted">
            Didn’t get the email? Check your spam folder or
            <button type="button" onclick="showToast('A new reset link is on its way.')" class="font-semibold text-brand-700 hover:text-brand-800 hover:underline">resend it</button>.
        </p>
    </div>
</x-layouts.guest>
