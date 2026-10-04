<x-layouts.customer-guest title="Create Account">

    {{-- Same situation as login/forgot-password: subtitle slot is dead,
         .guest-wrap/.guest-card have no CSS. Self-contained card here too. --}}

    <div class="min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden">

        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-[#B1D0FB]/40 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-[#DBEBFB]/50 blur-3xl"></div>

        <div class="relative z-10 w-full max-w-[440px] bg-white/60 backdrop-blur-xl border border-white/60 shadow-lg rounded-2xl p-6 md:p-8">

            <div class="text-center mb-6">
                <img src="{{asset('images/logo.png')}}" alt="MedMart logo" class="h-[56px] w-auto mx-auto mb-4">
                <h1 class="font-manrope text-2xl font-extrabold text-[#171E26]">Create Account</h1>
                <p class="font-inter text-[#171E26]/70 mt-1.5">Join your pharmacy's platform</p>
            </div>

            {{-- Note: register-error is reused for both error AND success states
                 by swapping its full className in register.js — kept exactly
                 as-is, but register.js needed updating so the swap uses full
                 Tailwind class strings instead of the bare "alert alert-error"
                 / "alert alert-success", otherwise the className assignment
                 wipes out all styling on submit. See register.js. --}}
            <div class="alert alert-error mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium"
                 id="register-error"
                 style="display: none;">
            </div>

            <form id="register-form" novalidate class="space-y-4">

                <div class="field">
                    <label for="name" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Full Name</label>
                    <input type="text" id="name" name="name" required autocomplete="name"
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <div class="field-error text-red-500" id="name-error"></div>
                </div>

                <div class="field">
                    <label for="username" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Username</label>
                    <input type="text" id="username" name="username" required autocomplete="username"
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <div class="field-error text-red-500" id="username-error"></div>
                </div>

                <div class="field">
                    <label for="email" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Email</label>
                    <input type="email" id="email" name="email" required autocomplete="email"
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <div class="field-error text-red-500" id="email-error"></div>
                </div>

                <div class="field">
                    <label for="password" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 pr-11 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                        <button type="button" aria-label="Show password" class="toggle-password absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/40 hover:text-[#2775E4] transition" data-target="password">
                            <i class="ph ph-eye text-lg"></i>
                        </button>
                    </div>
                    <div class="field-error text-red-500" id="password-error"></div>
                </div>

                <div class="field">
                    <label for="password-confirmation" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="password-confirmation" name="password_confirmation" required autocomplete="new-password"
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 pr-11 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                        <button type="button" aria-label="Show password" class="toggle-password absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/40 hover:text-[#2775E4] transition" data-target="password-confirmation">
                            <i class="ph ph-eye text-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="field">
                    <label for="pharmacy-code" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Pharmacy Code</label>
                    <input type="text" id="pharmacy-code" name="pharmacy_code" required
                           placeholder="e.g. MED-1234"
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] uppercase tracking-wider text-[#171E26] placeholder:text-[#171E26]/30 placeholder:normal-case focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <div class="field-error text-red-500" id="pharmacy-code-error"></div>
                </div>

                <div class="mt-5">
                                <label class="flex items-start gap-2.5 cursor-pointer">
                                    <input type="checkbox" id="terms"
                                        class="mt-0.5 h-4 w-4 rounded border-[#DBEBFB] text-[#2775E4] focus:ring-[#2775E4]">
                                    <span class="font-inter text-xs text-[#171E26]/60 leading-relaxed">
                                        By creating an account, you agree to the MedMart
                                        <a href="#" class="font-semibold text-[#2775E4] hover:text-[#08AEBC] transition">Terms of Service</a>
                                        and
                                        <a href="#" class="font-semibold text-[#2775E4] hover:text-[#08AEBC] transition">Privacy Policy</a>.
                                    </span>
                                </label>
                                {{-- NEW: safety-net message shown if the form is ever submitted
                                     without the box checked (the button is disabled by default
                                     below, but this covers edge cases like a browser submitting
                                     on Enter despite a disabled submit button). --}}
                                <p class="field-error text-red-500 mt-1.5" id="terms-error"></p>
                            </div>
                <button type="submit" id="register-submit" disabled class="btn btn-primary btn-block w-full px-5 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60 mt-2">
                    Create Account
                </button>
            </form>

            <div class="guest-links text-center mt-6">
                <a href="/customer/login" class="font-inter text-sm font-semibold text-[#2775E4] hover:underline">Already have an account? Login</a>
            </div>

        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/register.js') }}"></script>
        <script>
            // Purely presentational, doesn't touch anything register.js depends on.
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