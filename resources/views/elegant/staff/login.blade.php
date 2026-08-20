<x-layouts.guest title="Staff Login">
    <div class="alert alert-error" id="login-error" style="display: none;"></div>


    {{-- <!-- Minimal nav — no glassmorphism, intentionally distraction-free -->
    <nav class="flex items-center justify-between px-4 md:px-10 py-4">
        <a href="index.html">
            <img src="./image/logo.png" alt="MedMart logo" class="h-[60px] w-[85px] md:h-[70px] md:w-[100px]">
        </a>
        <p class="font-inter text-sm md:text-base text-[#171E26]/70">
            Don't have an account?
            <a href="signup.html" class="font-semibold bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:opacity-80 transition">Sign up</a>
        </p>
    </nav> --}}

    <main class="flex flex-col md:flex-row min-h-[calc(100vh-96px)]">

        <!-- Left: branding panel (desktop only) -->
        <div class="hidden md:flex md:w-1/2 relative overflow-hidden items-center justify-center p-12 bg-gradient-to-br from-[#E9F3FE] via-[#DBEBFB] to-[#B1D0FB]">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-[#B1D0FB]/60 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-[#DBEBFB]/70 blur-3xl"></div>

            <div class="relative z-10 max-w-sm">
                <h2 class="font-manrope text-4xl font-extrabold text-[#171E26] leading-tight">
                    Your pharmacy.<br>Your customers.<br>One platform.
                </h2>

                <!-- Minimal flow: Pharmacy -> MedMart -> Customers -->
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

                <h1 class="font-manrope text-3xl font-extrabold text-[#171E26]">Welcome back</h1>
                <p class="font-inter text-[#171E26]/70 mt-2">Log in to continue to your MedMart account.</p>

                <form class="mt-6 space-y-5"  id="login-form" novalidate>

                    <div class="field">
                        <label for="email" class="block font-inter text-sm font-medium text-[#171E26] mb-1.5">Email Address</label>
                        <input type="email" id="email" name="email" autocomplete="email" required
                            placeholder="you@example.com"
                            class="w-full rounded-xl border border-[#DBEBFB] px-4 py-3 font-inter text-[#171E26] placeholder:text-[#171E26]/30 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                         <div class="field-error" id="email-error"></div>
                    </div>

                    <div class="field">
                        <label for="password" class="block font-inter text-sm font-medium text-[#171E26] mb-1.5">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" autocomplete="current-password" required
                                placeholder="Enter your password"
                                class="w-full rounded-xl border border-[#DBEBFB] px-4 py-3 pr-11 font-inter text-[#171E26] placeholder:text-[#171E26]/30 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                            <button type="button" aria-label="Show password" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/40 hover:text-[#2775E4] transition">
                                <i class="ph ph-eye text-lg"></i>
                            </button>
                        </div>
                         <div class="field-error" id="password-error"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-[#DBEBFB] text-[#2775E4] focus:ring-[#2775E4]">
                            <span class="font-inter text-sm text-[#171E26]/70">Remember me</span>
                        </label>
                        <a href="/staff/forgot-password" class="font-inter text-sm font-semibold text-[#2775E4] hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" id="login-submit" class="w-full px-7 py-3.5 rounded-full bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold tracking-wide shadow-lg shadow-[#2775E4]/20 hover:scale-[1.02] transition">
                        Log In
                    </button>

                </form>
                <p class="font-inter text-sm text-center text-[#171E26]/70 mt-7">
                    Don't have an account?
                    <a href="/register" class="font-semibold text-[#2775E4] hover:underline">Create an account →</a>
                </p>

            </div>
        </div>

    </main>

 <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/login.js') }}"></script>
    </x-slot:scripts>
</x-layouts.guest>
