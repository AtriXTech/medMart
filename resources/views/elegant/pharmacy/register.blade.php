
<x-layouts.guest >
    {{-- <x-slot:subtitle>
        <p class="sub">Create your pharmacy account</p>
    </x-slot:subtitle> --}}

    {{-- <div class="alert alert-error" id="register-error" style="display: none;"></div>

    <!-- Minimal nav — no glassmorphism, intentionally distraction-free -->
    <nav class="flex items-center justify-between px-4 md:px-10 py-4">
        <a href="index.html">
            <img src="{{'images/logo.png'}}" alt="MedMart logo" class="h-[60px] w-[85px] md:h-[70px] md:w-[100px]">
        </a>
        <p class="font-inter text-sm md:text-base text-[#171E26]/70">
            Already have an account?
            <a href="/staff/login" class="font-semibold bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:opacity-80 transition">Log in</a>
        </p>
    </nav> --}}
<div class="alert alert-error" id="register-error" style="display: none;"></div>
    <main class="flex flex-col md:flex-row min-h-[calc(100vh-96px)]">

        <!-- Left: branding panel (desktop only) -->
        <div class="hidden md:flex md:w-1/2 relative overflow-hidden items-center justify-center p-12 bg-gradient-to-br from-[#E9F3FE] via-[#DBEBFB] to-[#B1D0FB]">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-[#B1D0FB]/60 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-[#DBEBFB]/70 blur-3xl"></div>

            <div class="relative z-10 max-w-sm">
                <h2 class="font-manrope text-4xl font-extrabold text-[#171E26] leading-tight">
                    Your pharmacy.<br>Your customers.<br>One platform.
                </h2>

                <div class="mt-10 space-y-0">
                    <div class="relative flex items-center gap-4 pb-8">
                        <div class="absolute left-6 top-12 bottom-0 w-0.5 bg-white/60"></div>
                        <div class="relative z-10 h-12 w-12 rounded-full bg-white shadow-md flex items-center justify-center flex-shrink-0">
                            <i class="ph ph-storefront text-[#2775E4] text-xl"></i>
                        </div>
                        <p class="font-inter font-semibold text-[#171E26]">Pharmacy Owner</p>
                    </div>
                    <div class="relative flex items-center gap-4 pb-8">
                        <div class="absolute left-6 top-12 bottom-0 w-0.5 bg-white/60"></div>
                        <div class="relative z-10 h-12 w-12 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] shadow-md flex items-center justify-center flex-shrink-0">
                            <i class="ph ph-arrows-left-right text-white text-xl"></i>
                        </div>
                        <p class="font-inter font-semibold text-[#171E26]">MedMart</p>
                    </div>
                    <div class="relative flex items-center gap-4">
                        <div class="relative z-10 h-12 w-12 rounded-full bg-white shadow-md flex items-center justify-center flex-shrink-0">
                            <i class="ph ph-users text-[#2775E4] text-xl"></i>
                        </div>
                        <p class="font-inter font-semibold text-[#171E26]">Customers</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: form panel -->
        <div class="w-full md:w-1/2 flex items-center justify-center px-4 md:px-10 py-10">
            <div class="w-full max-w-md bg-white/60 backdrop-blur-xl border border-white/60 shadow-lg rounded-2xl p-6 md:p-10">

                <h1 class="font-manrope text-3xl font-extrabold text-[#171E26]">Create your MedMart account</h1>
                <p class="font-inter text-[#171E26]/70 mt-2">Get started with MedMart and experience a simpler way to manage your pharmacy or place orders online.</p>

                <form id="register-form" novalidate>
                    <!-- Pharmacy Owner fields (shown by default — swap for Customer fields below as needed) -->
                    <div class="space-y-5 mt-6">

                        <div class="field">
                            <label for="pharmacy-name" class="block font-inter text-sm font-medium text-[#171E26] mb-1.5">Full Name</label>
                            <input type="text" id="pharmacy-name" name="pharmacy_name" autocomplete="name" placeholder="Your full name" required
                                class="w-full rounded-xl border border-[#DBEBFB] px-4 py-3 font-inter focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                                 <div class="field-error" id="pharmacy-name-error"></div>
                        </div>

                        <div class="field">
                            <label for="owner-name" class="block font-inter text-sm font-medium text-[#171E26] mb-1.5">Pharmacy Name</label>
                            <input type="text" id="owner-name" name="owner_name" placeholder="e.g. GreenLife Pharmacy"required
                                class="w-full rounded-xl border border-[#DBEBFB] px-4 py-3 font-inter focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                                <div class="field-error" id="owner-name-error"></div>
                            </div>

                        <div class="field">
                            <label for="email" class="block font-inter text-sm font-medium text-[#171E26] mb-1.5">Email Address</label>
                            <input type="email" id="email" name="email" autocomplete="email" placeholder="you@example.com" required
                                class="w-full rounded-xl border border-[#DBEBFB] px-4 py-3 font-inter focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                            <div class="field-error" id="email-error"></div>
                            </div>

                        <div class="field">
                            <label for="phone" class="block font-inter text-sm font-medium text-[#171E26] mb-1.5">Phone Number</label>
                            <input type="tel" id="phone" name="phone" autocomplete="tel" placeholder="080X XXX XXXX" required
                                class="w-full rounded-xl border border-[#DBEBFB] px-4 py-3 font-inter focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                                <div class="field-error" id="phone-error"></div>
                            </div>
                        <div>
                            <label for="password" class="block font-inter text-sm font-medium text-[#171E26] mb-1.5">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" autocomplete="new-password" placeholder="Create a password" required
                                    class="w-full rounded-xl border border-[#DBEBFB] px-4 py-3 pr-11 font-inter focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                                <button type="button" aria-label="Show password" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/40 hover:text-[#2775E4] transition">
                                    <i class="ph ph-eye text-lg"></i>
                                </button>
                            </div>
                              <div class="field-error" id="password-error"></div>
                            <p class="font-inter text-xs text-[#171E26]/50 mt-1.5">Use at least 8 characters.</p>
                        </div>

                        <div class="field">
                            <label for="password-confirmation" class="block font-inter text-sm font-medium text-[#171E26] mb-1.5">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="password-confirmation" name="password-confirmation" autocomplete="new-password" placeholder="Re-enter your password" required
                                    class="w-full rounded-xl border border-[#DBEBFB] px-4 py-3 pr-11 font-inter focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                                <button type="button" aria-label="Show password" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/40 hover:text-[#2775E4] transition">
                                    <i class="ph ph-eye text-lg"></i>
                                </button>
                            </div>
                            <div class="field-error" id="password-confirmation-error"></div>
                        </div>
                    </div>
                    <!-- Terms -->
                    <label class="flex items-start gap-2.5 cursor-pointer mt-6">
                        <input type="checkbox" id="terms" class="mt-0.5 h-4 w-4 rounded border-[#DBEBFB] text-[#2775E4] focus:ring-[#2775E4]">
                        <span class="font-inter text-xs text-[#171E26]/70">
                            By creating an account, you agree to the MedMart
                            <a href="#" class="font-semibold text-[#2775E4] hover:underline">Terms of Service</a>
                            and
                            <a href="#" class="font-semibold text-[#2775E4] hover:underline">Privacy Policy</a>.
                        </span>
                    </label>
                    <!-- Example error state: <p class="font-inter text-xs text-red-500 mt-1.5">Please accept the Terms of Service and Privacy Policy to continue.</p> -->

                    <button type="submit" id="register-submit" class="w-full px-7 py-3.5 rounded-full bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold tracking-wide shadow-lg shadow-[#2775E4]/20 hover:scale-[1.02] transition mt-6">
                        Create Pharmacy Account
                    </button>

                </form>
                <p class="font-inter text-sm text-center text-[#171E26]/70 mt-7">
                    Already have an account?
                    <a href="/staff/login" class="font-semibold text-[#2775E4] hover:underline">Log in →</a>
                </p>

            </div>
        </div>

    </main>

     <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/pharmacy/register.js') }}"></script>
    </x-slot:scripts>
</x-layouts.guest>