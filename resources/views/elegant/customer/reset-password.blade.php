<x-layouts.customer-guest title="Reset Password">
    <x-slot:subtitle>
        <p class="text-sm text-gray-500 mt-2">
            Create a new password to regain access to your account.
        </p>
    </x-slot:subtitle>

    <div class="space-y-4">

        <div
            class="hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            id="reset-error"
            role="alert"
        ></div>

        <div
            class="hidden rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
            id="reset-success"
            role="status"
        ></div>

        <form id="reset-form" novalidate class="space-y-5">

            <div>
                <label for="token" class="mb-2 block text-sm font-semibold text-gray-700">
                    Reset Token
                </label>
                <input
                    type="text"
                    id="token"
                    type="hidden" 
                    name="token"
                    required
                    autocomplete="off"
                    placeholder="Enter your reset token"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#2775E4] focus:bg-white focus:ring-4 focus:ring-blue-100"
                >
                <div class="mt-1.5 text-xs text-red-600" id="token-error"></div>
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                    Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                   type="hidden"
                    autocomplete="email"
                    placeholder="you@example.com"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#2775E4] focus:bg-white focus:ring-4 focus:ring-blue-100"
                >
                <div class="mt-1.5 text-xs text-red-600" id="email-error"></div>
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">
                    New Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Enter your new password"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#2775E4] focus:bg-white focus:ring-4 focus:ring-blue-100"
                >
                <div class="mt-1.5 text-xs text-red-600" id="password-error"></div>
            </div>

            <div>
                <label for="password-confirmation" class="mb-2 block text-sm font-semibold text-gray-700">
                    Confirm New Password
                </label>
                <input
                    type="password"
                    id="password-confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Re-enter your new password"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#2775E4] focus:bg-white focus:ring-4 focus:ring-blue-100"
                >
            </div>

            <button
                type="submit"
                id="reset-submit"
                class="flex w-full items-center justify-center rounded-xl bg-[#2775E4] px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:cursor-not-allowed disabled:opacity-60"
            >
                Reset Password
            </button>
        </form>

        <div class="border-t border-gray-100 pt-5 text-center">
            <a
                href="/customer/login"
                class="inline-flex items-center gap-2 text-sm font-semibold text-[#2775E4] transition hover:text-blue-700"
            >
                <span aria-hidden="true">&larr;</span>
                Back to login
            </a>
        </div>

    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/reset-password.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer-guest>
