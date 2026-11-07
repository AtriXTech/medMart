<x-layouts.guest>

    <style>
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes soft-float {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-8px); }
        }
        .login-enter-1 { animation: fade-up 0.55s ease-out both; }
        .login-enter-2 { animation: fade-up 0.55s ease-out 0.08s both; }
        .login-enter-3 { animation: fade-up 0.55s ease-out 0.16s both; }
        .login-panel   { animation: fade-in 0.7s ease-out both; }
        .login-orb     { animation: soft-float 9s ease-in-out infinite; }
        .login-input:focus {
            border-color: #2775E4;
            box-shadow: 0 0 0 3px rgba(39, 117, 228, 0.12);
        }
        .login-submit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(39, 117, 228, 0.28);
        }
        .login-submit:active:not(:disabled) {
            transform: translateY(0);
        }
        @media (prefers-reduced-motion: reduce) {
            .login-enter-1, .login-enter-2, .login-enter-3,
            .login-panel, .login-orb { animation: none; }
        }

        /* Mobile responsiveness only */
        @media (max-width: 639px) {
            html,
            body {
                overflow-x: hidden;
            }
        }

        @media (max-width: 374px) {
            .register-card {
                padding: 1.25rem;
            }
        }

        @media (max-height: 700px) and (max-width: 767px) {
            .register-main {
                padding-top: 1.25rem;
                padding-bottom: 1.25rem;
            }
        }
    </style>

    <main class="register-main min-h-dvh w-full flex justify-center overflow-x-hidden
                 px-4 py-6 sm:px-6 sm:py-8 md:px-8 md:py-12
                 md:items-center">

        <div class="w-full max-w-[1080px] flex flex-col md:flex-row
                    md:rounded-[1.75rem] md:overflow-hidden
                    md:border md:border-[#DBEBFB]
                    md:shadow-[0_28px_64px_-28px_rgba(23,30,38,0.22)]
                    md:bg-white">

            {{-- Left: brand plane (desktop) --}}
            <aside class="login-panel relative hidden md:flex md:w-[42%] lg:w-[44%] flex-col justify-center
                          overflow-hidden
                          bg-gradient-to-br from-[#0B1F3A] via-[#123A6B] to-[#2775E4]
                          px-10 lg:px-12 py-12">

                <div class="pointer-events-none absolute inset-0 opacity-[0.07]"
                     style="background-image: linear-gradient(rgba(255,255,255,.35) 1px, transparent 1px),
                                            linear-gradient(90deg, rgba(255,255,255,.35) 1px, transparent 1px);
                            background-size: 48px 48px;"></div>

                {{-- Orbs: radial gradients instead of blur (avoids the iPhone/WebKit black box) --}}
                <div class="login-orb absolute -top-28 -left-20 h-80 w-80 bg-[radial-gradient(circle,rgba(8,174,188,0.25)_0%,transparent_70%)]"></div>
                <div class="absolute bottom-[-18%] right-[-24%] h-96 w-96 bg-[radial-gradient(circle,rgba(177,208,251,0.2)_0%,transparent_70%)]"></div>
                <div class="absolute top-1/3 right-8 h-32 w-32 rounded-full border border-white/10"></div>

                <div class="relative z-10 login-enter-1">

                    {{-- Logo + tagline (same as the login page) --}}
                    <div class="md:max-w-xs mb-8">
                        <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-[70px] w-[100px] md:h-[80px] md:w-[110px] -ml-2">
                        <p class="font-inter text-white/60 text-[10px] mt-2">
                            The digital platform for modern pharmacies.
                        </p>
                    </div>

                    <p class="font-inter text-[11px] font-semibold uppercase tracking-[0.18em] text-white/50 mb-3">
                        Pharmacy onboarding
                    </p>
                    <h2 class="font-manrope text-[1.6rem] lg:text-[2.15rem] font-extrabold text-white leading-[1.2]">
                        Your pharmacy.<br>
                        Your customers.<br>
                        One platform.
                    </h2>
                    <p class="font-inter text-[14px] lg:text-[15px] text-white/65 mt-4 leading-relaxed max-w-sm">
                        Create your MedMart account and start managing orders, stock, and customers with calm clarity.
                    </p>

                    <ul class="mt-8 space-y-3.5">
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 h-8 w-8 shrink-0 rounded-lg bg-white/10 border border-white/10
                                         flex items-center justify-center">
                                <i class="ph ph-storefront text-[#B1D0FB] text-base"></i>
                            </span>
                            <div>
                                <p class="font-inter text-sm font-semibold text-white">Pharmacy owner</p>
                                <p class="font-inter text-[13px] text-white/50 mt-0.5">Set up your storefront in minutes</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 h-8 w-8 shrink-0 rounded-lg bg-white/10 border border-white/10
                                         flex items-center justify-center">
                                <i class="ph ph-arrows-left-right text-[#B1D0FB] text-base"></i>
                            </span>
                            <div>
                                <p class="font-inter text-sm font-semibold text-white">Powered by MedMart</p>
                                <p class="font-inter text-[13px] text-white/50 mt-0.5">One workspace for ops and sales</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 h-8 w-8 shrink-0 rounded-lg bg-white/10 border border-white/10
                                         flex items-center justify-center">
                                <i class="ph ph-users text-[#B1D0FB] text-base"></i>
                            </span>
                            <div>
                                <p class="font-inter text-sm font-semibold text-white">Reach customers</p>
                                <p class="font-inter text-[13px] text-white/50 mt-0.5">Orders and care in one flow</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </aside>

            {{-- Right: form --}}
            <section class="w-full md:w-[58%] lg:w-[56%] flex flex-col justify-center
                            relative overflow-hidden md:px-8 lg:px-12 md:py-10">

                {{-- Mobile orbs: radial gradients instead of blur --}}
                <div class="absolute -top-24 -right-24 h-80 w-80 bg-[radial-gradient(circle,rgba(177,208,251,0.35)_0%,transparent_70%)] md:hidden"></div>
                <div class="absolute bottom-0 left-0 h-72 w-72 bg-[radial-gradient(circle,rgba(219,235,251,0.5)_0%,transparent_70%)] md:hidden"></div>

                <div class="relative z-10 w-full max-w-[460px] mx-auto">

                    {{-- Mobile brand (same logo + tagline as the login page) --}}
                    <div class="flex items-center justify-center mb-6 md:hidden login-enter-1">
                        <div class="flex flex-col items-center text-center">
                            <img src="{{ asset('images/logo.png') }}" alt="logo"
                                 width="160" height="56" decoding="async"
                                 class="h-14 w-auto bg-transparent">
                            <p class="font-inter text-[#2775E4] text-sm mt-2">
                                The digital platform for modern pharmacies.
                            </p>
                        </div>
                    </div>

                    <div class="register-card login-enter-2 rounded-2xl md:rounded-none bg-white md:bg-transparent
                                border border-[#EAF1FB] md:border-0
                                shadow-[0_20px_50px_-24px_rgba(23,30,38,0.18)] md:shadow-none
                                p-6 sm:p-8 md:p-0">

                        <div class="alert alert-error" id="register-error" style="display: none;"></div>

                        <p class="font-inter text-[11px] font-semibold uppercase tracking-[0.16em] text-[#2775E4] mb-3">
                            Create account
                        </p>
                        <h1 class="font-manrope text-[1.5rem] sm:text-[1.85rem] font-extrabold text-[#171E26] leading-tight">
                            Create your MedMart account
                        </h1>
                        <p class="font-inter text-[14px] sm:text-[15px] text-[#171E26]/55 mt-2 leading-relaxed">
                            Get started with MedMart and manage your pharmacy with a simpler workflow.
                        </p>

                        <form id="register-form" class="mt-6" novalidate>
                            <div class="space-y-4">

                                <div class="field">
                                    <label for="pharmacy-name" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">
                                        Full Name
                                    </label>
                                    <div class="relative">
                                        <i class="ph ph-user absolute left-3.5 top-1/2 -translate-y-1/2
                                                  text-[#171E26]/30 text-lg pointer-events-none"></i>
                                        <input type="text" id="pharmacy-name" name="pharmacy_name" autocomplete="name"
                                            placeholder="Your full name" required
                                            class="login-input w-full rounded-xl border border-[#DBEBFB] bg-[#F8FBFF]
                                                   pl-11 pr-4 py-3 font-inter text-[15px] text-[#171E26]
                                                   placeholder:text-[#171E26]/28
                                                   focus:outline-none focus:bg-white transition">
                                    </div>
                                    <div class="field-error text-red-500" id="pharmacy-name-error"></div>
                                </div>

                                <div class="field">
                                    <label for="owner-name" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">
                                        Pharmacy Name
                                    </label>
                                    <div class="relative">
                                        <i class="ph ph-storefront absolute left-3.5 top-1/2 -translate-y-1/2
                                                  text-[#171E26]/30 text-lg pointer-events-none"></i>
                                        <input type="text" id="owner-name" name="owner_name"
                                            placeholder="e.g. GreenLife Pharmacy" required
                                            class="login-input w-full rounded-xl border border-[#DBEBFB] bg-[#F8FBFF]
                                                   pl-11 pr-4 py-3 font-inter text-[15px] text-[#171E26]
                                                   placeholder:text-[#171E26]/28
                                                   focus:outline-none focus:bg-white transition">
                                    </div>
                                    <div class="field-error text-red-500" id="owner-name-error"></div>
                                </div>

                                {{-- CHANGED: two columns only from lg up (was sm), so email and phone
                                     aren't squeezed on tablets and small laptops. --}}
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div class="field min-w-0">
                                        <label for="email" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">
                                            Email Address
                                        </label>
                                        <div class="relative">
                                            <i class="ph ph-envelope-simple absolute left-3.5 top-1/2 -translate-y-1/2
                                                      text-[#171E26]/30 text-lg pointer-events-none"></i>
                                            <input type="email" id="email" name="email" autocomplete="email"
                                                placeholder="you@example.com" required
                                                class="login-input w-full rounded-xl border border-[#DBEBFB] bg-[#F8FBFF]
                                                       pl-11 pr-4 py-3 font-inter text-[15px] text-[#171E26]
                                                       placeholder:text-[#171E26]/28
                                                       focus:outline-none focus:bg-white transition">
                                        </div>
                                        <div class="field-error text-red-500" id="email-error"></div>
                                    </div>

                                    <div class="field min-w-0">
                                        <label for="phone" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">
                                            Phone Number
                                        </label>
                                        <div class="relative">
                                            <i class="ph ph-phone absolute left-3.5 top-1/2 -translate-y-1/2
                                                      text-[#171E26]/30 text-lg pointer-events-none"></i>
                                            <input type="tel" id="phone" name="phone" autocomplete="tel"
                                                placeholder="080X XXX XXXX" required
                                                class="login-input w-full rounded-xl border border-[#DBEBFB] bg-[#F8FBFF]
                                                       pl-11 pr-4 py-3 font-inter text-[15px] text-[#171E26]
                                                       placeholder:text-[#171E26]/28
                                                       focus:outline-none focus:bg-white transition">
                                        </div>
                                        <div class="field-error text-red-500" id="phone-error"></div>
                                    </div>
                                </div>

                                <div class="field">
                                    <label for="password" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">
                                        Password
                                    </label>
                                    <div class="relative">
                                        <i class="ph ph-lock-simple absolute left-3.5 top-1/2 -translate-y-1/2
                                                  text-[#171E26]/30 text-lg pointer-events-none"></i>
                                        <input type="password" id="password" name="password" autocomplete="new-password"
                                            placeholder="Create a password" required
                                            class="login-input w-full rounded-xl border border-[#DBEBFB] bg-[#F8FBFF]
                                                   pl-11 pr-11 py-3 font-inter text-[15px] text-[#171E26]
                                                   placeholder:text-[#171E26]/28
                                                   focus:outline-none focus:bg-white transition">
                                        <button type="button" id="toggle-password-btn" aria-label="Show password"
                                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/35
                                                   hover:text-[#2775E4] transition">
                                            <i class="ph ph-eye text-lg" id="toggle-password-icon"></i>
                                        </button>
                                    </div>
                                    <div class="field-error text-red-500" id="password-error"></div>
                                    <p class="font-inter text-xs text-[#171E26]/45 mt-1.5">Use at least 8 characters.</p>
                                </div>

                                <div class="field">
                                    <label for="password-confirmation" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">
                                        Confirm Password
                                    </label>
                                    <div class="relative">
                                        <i class="ph ph-lock-key absolute left-3.5 top-1/2 -translate-y-1/2
                                                  text-[#171E26]/30 text-lg pointer-events-none"></i>
                                        <input type="password" id="password-confirmation" name="password-confirmation"
                                            autocomplete="new-password" placeholder="Re-enter your password" required
                                            class="login-input w-full rounded-xl border border-[#DBEBFB] bg-[#F8FBFF]
                                                   pl-11 pr-11 py-3 font-inter text-[15px] text-[#171E26]
                                                   placeholder:text-[#171E26]/28
                                                   focus:outline-none focus:bg-white transition">
                                        <button type="button" id="toggle-password-confirmation-btn" aria-label="Show password"
                                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/35
                                                   hover:text-[#2775E4] transition">
                                            <i class="ph ph-eye text-lg" id="toggle-password-confirmation-icon"></i>
                                        </button>
                                    </div>
                                    <div class="field-error text-red-500" id="password-confirmation-error"></div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <label class="flex items-start gap-2.5 cursor-pointer">
                                    <input type="checkbox" id="terms"
                                        class="mt-0.5 h-4 w-4 shrink-0 rounded border-[#DBEBFB] text-[#2775E4] focus:ring-[#2775E4]">
                                    <span class="font-inter text-xs text-[#171E26]/60 leading-relaxed">
                                        By creating an account, you agree to the MedMart
                                        <a href="{{route('termsAndConditions')}}" class="font-semibold text-[#2775E4] hover:text-[#08AEBC] transition">Terms of Service</a>
                                        and
                                        <a href="{{route('privacyAndDataProcessing')}}" class="font-semibold text-[#2775E4] hover:text-[#08AEBC] transition">Privacy Policy</a>.
                                    </span>
                                </label>
                                {{-- NEW: safety-net message shown if the form is ever submitted
                                     without the box checked (the button is disabled by default
                                     below, but this covers edge cases like a browser submitting
                                     on Enter despite a disabled submit button). --}}
                                <p class="field-error text-red-500 mt-1.5" id="terms-error"></p>
                            </div>

                            {{-- CHANGED: disabled by default. terms.js (see below) enables
                                 this the moment the checkbox is ticked, and disables it again
                                 if unticked. --}}
                            <button type="submit" id="register-submit" disabled
                                class="login-submit w-full mt-5 px-7 py-3.5 rounded-full
                                       bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                                       text-white font-inter font-semibold tracking-wide
                                       shadow-lg shadow-[#2775E4]/25
                                       transition disabled:opacity-60 disabled:cursor-not-allowed">
                                Create Pharmacy Account
                            </button>
                        </form>

                        <div class="mt-7 pt-6 border-t border-[#EAF1FB]">
                            <p class="font-inter text-sm text-center text-[#171E26]/55">
                                Already have an account?
                                <a href="/staff/login"
                                   class="font-semibold text-[#2775E4] hover:text-[#08AEBC] transition ml-1">
                                    Log in
                                </a>
                            </p>
                        </div>
                    </div>

                    <p class="login-enter-3 mt-6 text-center font-inter text-xs text-[#171E26]/35 md:hidden">
                        © {{ date('Y') }} MedMart
                    </p>
                </div>
            </section>
        </div>
    </main>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/pharmacy/register.js') }}"></script>
    </x-slot:scripts>
</x-layouts.guest>