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
    </style>

    <main class="flex justify-center px-4 py-8 sm:px-6 md:px-8 md:py-12
                 md:min-h-dvh md:items-center">

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
                <div class="login-orb absolute -top-24 -left-16 h-64 w-64 rounded-full bg-[#08AEBC]/25 blur-3xl"></div>
                <div class="absolute bottom-[-12%] right-[-18%] h-80 w-80 rounded-full bg-[#B1D0FB]/20 blur-3xl"></div>
                <div class="absolute top-1/3 right-8 h-32 w-32 rounded-full border border-white/10"></div>

                <div class="relative z-10 login-enter-1">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="h-10 w-10 rounded-xl bg-white/10 backdrop-blur-sm border border-white/15
                                    flex items-center justify-center">
                            <i class="ph ph-pill text-white text-xl"></i>
                        </div>
                        <span class="font-manrope text-xl font-extrabold tracking-tight text-white">MedMart</span>
                    </div>

                    <p class="font-inter text-[11px] font-semibold uppercase tracking-[0.18em] text-white/50 mb-3">
                        Pharmacy onboarding
                    </p>
                    <h2 class="font-manrope text-[1.85rem] lg:text-[2.15rem] font-extrabold text-white leading-[1.2]">
                        Your pharmacy.<br>
                        Your customers.<br>
                        One platform.
                    </h2>
                    <p class="font-inter text-[15px] text-white/65 mt-4 leading-relaxed max-w-sm">
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
                            relative md:px-10 lg:px-12 md:py-10">

                <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-[#B1D0FB]/35 blur-3xl md:hidden"></div>
                <div class="absolute bottom-0 left-0 h-64 w-64 rounded-full bg-[#DBEBFB]/50 blur-3xl md:hidden"></div>

                <div class="relative z-10 w-full max-w-[460px] mx-auto">

                    {{-- Mobile brand --}}
                    <div class="flex items-center gap-2.5 mb-6 md:hidden login-enter-1">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC]
                                    flex items-center justify-center shadow-md shadow-[#2775E4]/20">
                            <i class="ph ph-pill text-white text-lg"></i>
                        </div>
                        <span class="font-manrope text-lg font-extrabold text-[#171E26]">MedMart</span>
                    </div>

                    <div class="login-enter-2 rounded-2xl md:rounded-none bg-white md:bg-transparent
                                border border-[#EAF1FB] md:border-0
                                shadow-[0_20px_50px_-24px_rgba(23,30,38,0.18)] md:shadow-none
                                p-6 sm:p-8 md:p-0">

                        <div class="alert alert-error" id="register-error" style="display: none;"></div>

                        <p class="font-inter text-[11px] font-semibold uppercase tracking-[0.16em] text-[#2775E4] mb-3">
                            Create account
                        </p>
                        <h1 class="font-manrope text-[1.65rem] sm:text-[1.85rem] font-extrabold text-[#171E26] leading-tight">
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
                                    <div class="field-error" id="pharmacy-name-error"></div>
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
                                    <div class="field-error" id="owner-name-error"></div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="field">
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
                                        <div class="field-error" id="email-error"></div>
                                    </div>

                                    <div class="field">
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
                                        <div class="field-error" id="phone-error"></div>
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
                                        <button type="button" aria-label="Show password"
                                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/35
                                                   hover:text-[#2775E4] transition">
                                            <i class="ph ph-eye text-lg"></i>
                                        </button>
                                    </div>
                                    <div class="field-error" id="password-error"></div>
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
                                        <button type="button" aria-label="Show password"
                                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/35
                                                   hover:text-[#2775E4] transition">
                                            <i class="ph ph-eye text-lg"></i>
                                        </button>
                                    </div>
                                    <div class="field-error" id="password-confirmation-error"></div>
                                </div>
                            </div>

                            <label class="flex items-start gap-2.5 cursor-pointer mt-5">
                                <input type="checkbox" id="terms"
                                    class="mt-0.5 h-4 w-4 rounded border-[#DBEBFB] text-[#2775E4] focus:ring-[#2775E4]">
                                <span class="font-inter text-xs text-[#171E26]/60 leading-relaxed">
                                    By creating an account, you agree to the MedMart
                                    <a href="#" class="font-semibold text-[#2775E4] hover:text-[#08AEBC] transition">Terms of Service</a>
                                    and
                                    <a href="#" class="font-semibold text-[#2775E4] hover:text-[#08AEBC] transition">Privacy Policy</a>.
                                </span>
                            </label>

                            <button type="submit" id="register-submit"
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