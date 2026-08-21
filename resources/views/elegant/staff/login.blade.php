<x-layouts.guest title="Staff Login">

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
            .login-panel, .login-orb {
                animation: none;
            }
        }

        /* Mobile responsiveness only */
        @media (max-width: 639px) {
            html,
            body {
                overflow-x: hidden;
            }

            .login-input {
                min-height: 52px;
            }
        }

        @media (max-width: 374px) {
            .login-form-card {
                padding: 1.25rem;
            }
        }

        @media (max-height: 700px) and (max-width: 767px) {
            .login-main {
                padding-top: 1.25rem;
                padding-bottom: 1.25rem;
            }
        }
    </style>

    {{-- Outer shell: mobile-first, centered card on md+ --}}
    <main class="login-main min-h-dvh w-full flex justify-center
                 px-4 py-6
                 sm:px-6 sm:py-8
                 md:px-8 md:py-12 md:items-center
                 overflow-x-hidden">

        <div class="w-full max-w-[1040px] flex flex-col md:flex-row
                    md:rounded-[1.75rem] md:overflow-hidden
                    md:border md:border-[#DBEBFB]
                    md:shadow-[0_28px_64px_-28px_rgba(23,30,38,0.22)]
                    md:bg-white">

            {{-- Left: brand plane (desktop) --}}
            <aside class="login-panel relative hidden md:flex md:w-[46%] lg:w-[48%] flex-col justify-center
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

                        <span class="font-manrope text-xl font-extrabold tracking-tight text-white">
                            MedMart
                        </span>
                    </div>

                    <p class="font-inter text-[11px] font-semibold uppercase tracking-[0.18em] text-white/50 mb-3">
                        Staff portal
                    </p>

                    <h2 class="font-manrope text-[1.85rem] lg:text-[2.15rem] font-extrabold text-white leading-[1.2]">
                        Run your pharmacy<br>
                        with quiet confidence.
                    </h2>

                    <p class="font-inter text-[15px] text-white/65 mt-4 leading-relaxed max-w-sm">
                        Orders, prescriptions, inventory, and customers — one calm workspace for your team.
                    </p>

                    <ul class="mt-8 space-y-3.5">

                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 h-8 w-8 shrink-0 rounded-lg bg-white/10 border border-white/10
                                         flex items-center justify-center">
                                <i class="ph ph-shield-check text-[#B1D0FB] text-base"></i>
                            </span>

                            <div>
                                <p class="font-inter text-sm font-semibold text-white">
                                    Secure staff access
                                </p>

                                <p class="font-inter text-[13px] text-white/50 mt-0.5">
                                    Role-based tools for your team
                                </p>
                            </div>
                        </li>

                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 h-8 w-8 shrink-0 rounded-lg bg-white/10 border border-white/10
                                         flex items-center justify-center">
                                <i class="ph ph-lightning text-[#B1D0FB] text-base"></i>
                            </span>

                            <div>
                                <p class="font-inter text-sm font-semibold text-white">
                                    Built for daily ops
                                </p>

                                <p class="font-inter text-[13px] text-white/50 mt-0.5">
                                    POS, orders, and fulfilment
                                </p>
                            </div>
                        </li>

                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 h-8 w-8 shrink-0 rounded-lg bg-white/10 border border-white/10
                                         flex items-center justify-center">
                                <i class="ph ph-heartbeat text-[#B1D0FB] text-base"></i>
                            </span>

                            <div>
                                <p class="font-inter text-sm font-semibold text-white">
                                    Care stays central
                                </p>

                                <p class="font-inter text-[13px] text-white/50 mt-0.5">
                                    Prescriptions, clearly organised
                                </p>
                            </div>
                        </li>

                    </ul>
                </div>
            </aside>

            {{-- Right: form --}}
            <section class="w-full md:w-[54%] lg:w-[52%] flex flex-col justify-center
                            px-0
                            relative overflow-hidden
                            sm:px-0
                            md:px-10 lg:px-12
                            py-0 md:py-12">

                <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-[#B1D0FB]/35 blur-3xl md:hidden"></div>

                <div class="absolute bottom-0 left-0 h-64 w-64 rounded-full bg-[#DBEBFB]/50 blur-3xl md:hidden"></div>

                <div class="relative z-10 w-full max-w-[420px] mx-auto">

                    {{-- Mobile brand --}}
                    <div class="flex items-center gap-2.5 mb-6 md:hidden login-enter-1">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC]
                                    flex items-center justify-center shadow-md shadow-[#2775E4]/20">
                            <i class="ph ph-pill text-white text-lg"></i>
                        </div>

                        <span class="font-manrope text-lg font-extrabold text-[#171E26]">
                            MedMart
                        </span>
                    </div>

                    <div class="login-enter-2 login-form-card
                                rounded-2xl md:rounded-none
                                bg-white md:bg-transparent
                                border border-[#EAF1FB] md:border-0
                                shadow-[0_20px_50px_-24px_rgba(23,30,38,0.18)] md:shadow-none
                                p-6 sm:p-8 md:p-0">

                        <div class="alert alert-error" id="login-error" style="display: none;"></div>

                        <p class="font-inter text-[11px] font-semibold uppercase tracking-[0.16em] text-[#2775E4] mb-3">
                            Staff sign in
                        </p>

                        <h1 class="font-manrope text-[1.75rem] sm:text-3xl font-extrabold text-[#171E26] leading-tight">
                            Welcome back
                        </h1>

                        <p class="font-inter text-[15px] text-[#171E26]/55 mt-2 leading-relaxed">
                            Sign in to continue to your MedMart workspace.
                        </p>

                        <form class="mt-7 space-y-5" id="login-form" novalidate>

                            <div class="field">
                                <label for="email"
                                       class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">
                                    Email address
                                </label>

                                <div class="relative">
                                    <i class="ph ph-envelope-simple absolute left-3.5 top-1/2 -translate-y-1/2
                                              text-[#171E26]/30 text-lg pointer-events-none"></i>

                                    <input type="email"
                                           id="email"
                                           name="email"
                                           autocomplete="email"
                                           required
                                           placeholder="you@pharmacy.com"
                                           class="login-input w-full rounded-xl border border-[#DBEBFB] bg-[#F8FBFF]
                                                  pl-11 pr-4 py-3.5
                                                  font-inter text-[15px] text-[#171E26]
                                                  placeholder:text-[#171E26]/28
                                                  focus:outline-none focus:bg-white transition">
                                </div>

                                <div class="field-error" id="email-error"></div>
                            </div>

                            <div class="field">
                                <label for="password"
                                       class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">
                                    Password
                                </label>

                                <div class="relative">
                                    <i class="ph ph-lock-simple absolute left-3.5 top-1/2 -translate-y-1/2
                                              text-[#171E26]/30 text-lg pointer-events-none"></i>

                                    <input type="password"
                                           id="password"
                                           name="password"
                                           autocomplete="current-password"
                                           required
                                           placeholder="Enter your password"
                                           class="login-input w-full rounded-xl border border-[#DBEBFB] bg-[#F8FBFF]
                                                  pl-11 pr-11 py-3.5
                                                  font-inter text-[15px] text-[#171E26]
                                                  placeholder:text-[#171E26]/28
                                                  focus:outline-none focus:bg-white transition">

                                    <button type="button"
                                            aria-label="Show password"
                                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/35
                                                   hover:text-[#2775E4] transition">
                                        <i class="ph ph-eye text-lg"></i>
                                    </button>
                                </div>

                                <div class="field-error" id="password-error"></div>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-0.5">

                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox"
                                           name="remember"
                                           class="h-4 w-4 rounded border-[#DBEBFB] text-[#2775E4] focus:ring-[#2775E4]">

                                    <span class="font-inter text-sm text-[#171E26]/60">
                                        Remember me
                                    </span>
                                </label>

                                <a href="/staff/forgot-password"
                                   class="font-inter text-sm font-semibold text-[#2775E4] hover:text-[#08AEBC] transition">
                                    Forgot password?
                                </a>

                            </div>

                            <button type="submit"
                                    id="login-submit"
                                    class="login-submit w-full mt-1 px-7 py-3.5 rounded-full
                                           bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                                           text-white font-inter font-semibold tracking-wide
                                           shadow-lg shadow-[#2775E4]/25
                                           transition disabled:opacity-60 disabled:cursor-not-allowed">
                                Sign In
                            </button>

                        </form>

                        <div class="mt-7 pt-6 border-t border-[#EAF1FB]">
                            <p class="font-inter text-sm text-center text-[#171E26]/55">
                                Don’t have an account?

                                <a href="/register"
                                   class="font-semibold text-[#2775E4] hover:text-[#08AEBC] transition ml-1">
                                    Create an account
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
        <script src="{{ asset('assets/minimal/js/staff/login.js') }}"></script>
    </x-slot:scripts>

</x-layouts.guest>