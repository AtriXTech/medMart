<x-layouts.customer-guest title="Reset Password">

    <div class="relative min-h-screen flex items-center justify-center overflow-hidden px-4 py-12">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-[#B1D0FB]/40 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-[#DBEBFB]/50 blur-3xl"></div>

        <div class="relative w-full max-w-[420px] bg-white/60 backdrop-blur-xl border border-white/60 shadow-lg rounded-2xl p-6 md:p-8">

            <div class="text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="MedMart logo" class="h-[56px] w-auto mx-auto mb-4">
                <h1 class="font-manrope text-2xl font-extrabold text-[#171E26]">Reset your password</h1>
                <p class="sub font-inter text-[#171E26]/70 mt-1.5">Enter your new password</p>
            </div>

            <div class="alert alert-error mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium"
                 id="reset-error"
                 style="display: none;">
            </div>
            <div class="alert alert-success mb-4 flex items-center gap-2.5 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 font-inter text-sm font-medium"
                 id="reset-success"
                 style="display: none;">
            </div>

            <form id="reset-form" novalidate class="space-y-4">

                {{-- Prefilled from the emailed link and hidden from the user.
                     The error divs stay visible so an invalid or expired link still shows a message. --}}
                <input type="hidden" id="token" name="token" value="{{ request()->query('token') }}" required>
                <input type="hidden" id="email" name="email" value="{{ request()->query('email') }}" required>
                <div class="field-error text-red-500 font-inter text-sm empty:hidden" id="token-error"></div>
                <div class="field-error text-red-500 font-inter text-sm empty:hidden" id="email-error"></div>

                <div class="field">
                    <label for="password" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">New Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" autocomplete="new-password" required
                               placeholder="Enter your new password"
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 pr-11 font-inter text-[14px] text-[#171E26] placeholder:text-[#171E26]/30 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                        <button type="button" aria-label="Show password"
                                class="toggle-password absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/40 hover:text-[#2775E4] transition"
                                data-target="password">
                            <i class="ph ph-eye text-lg"></i>
                        </button>
                    </div>
                    <div class="field-error text-red-500" id="password-error"></div>
                </div>

                <div class="field">
                    <label for="password-confirmation" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="password-confirmation" name="password_confirmation" autocomplete="new-password" required
                               placeholder="Re-enter your new password"
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 pr-11 font-inter text-[14px] text-[#171E26] placeholder:text-[#171E26]/30 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                        <button type="button" aria-label="Show password"
                                class="toggle-password absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/40 hover:text-[#2775E4] transition"
                                data-target="password-confirmation">
                            <i class="ph ph-eye text-lg"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" id="reset-submit" class="btn btn-primary btn-block w-full px-5 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60 mt-2">
                    Reset Password
                </button>
            </form>

            <div class="guest-links flex items-center justify-center gap-2 mt-6 font-inter text-sm">
                <a href="/customer/login" class="font-semibold text-[#2775E4] hover:underline">Back to login</a>
            </div>

        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/reset-password.js') }}"></script>
        <script>
            // Purely presentational, doesn't touch anything reset-password.js depends on.
            document.querySelectorAll('.toggle-password').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const input = document.getElementById(btn.dataset.target);
                    const icon = btn.querySelector('i');
                    const showing = input.type === 'text';
                    input.type = showing ? 'password' : 'text';
                    icon.className = showing ? 'ph ph-eye text-lg' : 'ph ph-eye-slash text-lg';
                    btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
                });
            });
        </script>
    </x-slot:scripts>
</x-layouts.customer-guest>