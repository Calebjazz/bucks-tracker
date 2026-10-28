{{-- Account settings: profile, preferences and password — each section is its own form. --}}
@php
    $mock = require resource_path('data/mock.php');
    $user = $mock['user'];

    $currencies = [
        'TZS' => 'Tanzanian shilling (TZS)',
        'KES' => 'Kenyan shilling (KES)',
        'UGX' => 'Ugandan shilling (UGX)',
        'USD' => 'US dollar (USD)',
        'EUR' => 'Euro (EUR)',
    ];
@endphp

<x-layouts.app title="Settings" description="Manage your profile, preferences and security.">
    <div class="flex flex-col gap-6">
        {{-- Profile --}}
        <section class="grid gap-4 lg:grid-cols-3 lg:gap-8" aria-labelledby="profile-heading">
            <div>
                <h2 id="profile-heading" class="text-base font-semibold">Profile</h2>
                <p class="mt-1 text-sm text-muted">Your name and the email you use to sign in.</p>
            </div>
            <form method="POST" action="#" novalidate data-demo-form data-toast="Your profile was updated." class="rounded-xl border border-line bg-white shadow-xs lg:col-span-2">
                @csrf
                <div class="flex flex-col gap-5 p-5 sm:p-6">
                    <div class="flex items-center gap-4">
                        <span class="flex size-14 items-center justify-center rounded-full bg-brand-100 text-lg font-semibold text-brand-800">{{ $user['initials'] }}</span>
                        <div>
                            <x-button variant="secondary" size="sm" onclick="showToast('Photo uploads will be available soon.')">Change photo</x-button>
                            <p class="mt-1.5 text-xs text-muted">JPG or PNG, up to 2 MB.</p>
                        </div>
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.input label="Full name" name="name" :value="$user['name']" autocomplete="name" required />
                        <x-form.input label="Email address" name="email" type="email" :value="$user['email']" autocomplete="email" required />
                    </div>
                </div>
                <div class="flex justify-end border-t border-line bg-slate-50/70 px-5 py-3.5 sm:px-6">
                    <x-button type="submit">Save changes</x-button>
                </div>
            </form>
        </section>

        <hr class="border-line">

        {{-- Preferences --}}
        <section class="grid gap-4 lg:grid-cols-3 lg:gap-8" aria-labelledby="preferences-heading">
            <div>
                <h2 id="preferences-heading" class="text-base font-semibold">Preferences</h2>
                <p class="mt-1 text-sm text-muted">How amounts and dates are shown across ExpenseFlow.</p>
            </div>
            <form method="POST" action="#" novalidate data-demo-form data-toast="Your preferences were saved." class="rounded-xl border border-line bg-white shadow-xs lg:col-span-2">
                @csrf
                <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                    <x-form.select label="Preferred currency" name="currency" hint="Used for all new transactions and reports.">
                        @foreach ($currencies as $code => $name)
                            <option value="{{ $code }}" @selected($code === $user['currency'])>{{ $name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.select label="Week starts on" name="week_start">
                        <option value="monday" selected>Monday</option>
                        <option value="sunday">Sunday</option>
                    </x-form.select>
                </div>
                <div class="flex justify-end border-t border-line bg-slate-50/70 px-5 py-3.5 sm:px-6">
                    <x-button type="submit">Save changes</x-button>
                </div>
            </form>
        </section>

        <hr class="border-line">

        {{-- Password --}}
        <section class="grid gap-4 lg:grid-cols-3 lg:gap-8" aria-labelledby="password-heading">
            <div>
                <h2 id="password-heading" class="text-base font-semibold">Change password</h2>
                <p class="mt-1 text-sm text-muted">Use a long, unique password to keep your account secure.</p>
            </div>
            <form method="POST" action="#" novalidate data-demo-form data-reset-on-success data-toast="Your password was changed." class="rounded-xl border border-line bg-white shadow-xs lg:col-span-2">
                @csrf
                <div class="flex flex-col gap-5 p-5 sm:p-6">
                    <x-form.input label="Current password" name="current_password" type="password" autocomplete="current-password" required />
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.input label="New password" name="password" type="password" autocomplete="new-password" minlength="8" hint="At least 8 characters." required />
                        <x-form.input label="Confirm new password" name="password_confirmation" type="password" autocomplete="new-password" data-match="password" required />
                    </div>
                </div>
                <div class="flex justify-end border-t border-line bg-slate-50/70 px-5 py-3.5 sm:px-6">
                    <x-button type="submit" icon="lock" data-loading-text="Updating…">Update password</x-button>
                </div>
            </form>
        </section>
    </div>
</x-layouts.app>
