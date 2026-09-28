<x-layouts.customer-guest title="Forgot Password">

    {{-- Same situation as the login page: the customer-guest layout's subtitle
         slot is commented out and never renders, and .guest-wrap/.guest-card
         have no CSS behind them. This page brings its own full centering +
         card styling directly in the slot. --}}

    <div class="min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden">

        {{-- Subtle decorative shapes, same language as login/signup --}}
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-[#B1D0FB]/40 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-[#DBEBFB]/50 blur-3xl"></div>

        <div class="relative z-10 w-full max-w-[420px] bg-white/60 backdrop-blur-xl border border-white/60 shadow-lg rounded-2xl p-6 md:p-8">

            <div class="h-12 w-12 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                <i class="ph ph-lock-key text-[#2775E4] text-2xl"></i>
            </div>

            <h1 class="font-manrope text-2xl font-extrabold text-[#171E26] mt-5">Forgot your password?</h1>
            <p class="font-inter text-[#171E26]/70 mt-1.5">
                Enter your email to receive a reset link
            </p>

            <div class="alert alert-error mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium mt-6"
                 id="forgot-error"
                 style="display: none;">
            </div>
            <div class="alert alert-success flex items-center gap-2.5 bg-[#DBEBFB] border border-[#B1D0FB] text-[#2775E4] rounded-xl px-4 py-3 font-inter text-sm font-medium mb-4"
                 id="forgot-success"
                 style="display: none;">
            </div>

            <form id="forgot-form" novalidate class="space-y-4 mt-6">

                <div class="field">
                    <label for="email" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Email</label>
                    <input type="email" id="email" name="email" required
                           placeholder="you@example.com"
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] placeholder:text-[#171E26]/30 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <div class="field-error" id="email-error"></div>
                </div>

                <button type="submit" id="forgot-submit" class="btn btn-primary btn-block w-full px-5 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                    Send Reset Link
                </button>
            </form>

            <div class="guest-links flex items-center justify-center gap-1.5 mt-6">
                <i class="ph ph-arrow-left text-base text-[#2775E4]"></i>
                <a href="/customer/login" class="font-inter text-sm font-semibold text-[#2775E4] hover:underline">Back to login</a>
            </div>

        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/forgot-password.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer-guest>