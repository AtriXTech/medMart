<x-layouts.customer-guest title="Reset Password">
    <x-slot:subtitle>
        <p class="sub mt-1 text-sm text-gray-500">Enter your new password</p>
    </x-slot:subtitle>

    <div class="alert alert-error mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" id="reset-error" style="display: none;"></div>
    <div class="alert alert-success mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700" id="reset-success" style="display: none;"></div>

    <form id="reset-form" class="space-y-5" novalidate>
        <div class="field">
            <label for="token" class="mb-1.5 block text-sm font-medium text-gray-700">Reset Token</label>
            <input type="text" id="token" name="token" required
                     value="{{ request()->query('token') }}"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm placeholder-gray-400 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
            <div class="field-error mt-1.5 text-xs text-red-600" id="token-error"></div>
        </div>

        <div class="field">
            <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" required autocomplete="email"
            value="{{ request()->query('email') }}"

                class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm placeholder-gray-400 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
            <div class="field-error mt-1.5 text-xs text-red-600" id="email-error"></div>
        </div>

        <div class="field">
            <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm placeholder-gray-400 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
            <div class="field-error mt-1.5 text-xs text-red-600" id="password-error"></div>
        </div>

        <div class="field">
            <label for="password-confirmation" class="mb-1.5 block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" id="password-confirmation" name="password_confirmation" required autocomplete="new-password"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm placeholder-gray-400 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
        </div>

        <button type="submit" id="reset-submit"
            class="btn btn-primary btn-block w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
            Reset Password
        </button>
    </form>

    <div class="guest-links mt-6 text-center text-sm">
        <a href="/customer/login" class="font-medium text-indigo-600 transition hover:text-indigo-500 hover:underline">&larr; Back to login</a>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/reset-password.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer-guest>