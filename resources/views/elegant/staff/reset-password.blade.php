<x-layouts.guest title="Reset Password">

    {{-- The guest layout's title/subtitle slots are currently commented out and never
         render, so heading + subtitle live directly in this slot instead. The layout's
         .guest-wrap/.guest-card have no CSS behind them (app.css is disabled there too),
         so this page brings its own full centering + card styling rather than relying
         on the shared shell. --}}

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-[420px] bg-white/60 backdrop-blur-xl border border-white/60 shadow-lg rounded-2xl p-6 md:p-8">

            <div class="text-center mb-6">
                <img src="/image/logo.png" alt="MedMart logo" class="h-[56px] w-auto mx-auto mb-4">
                <h1 class="font-manrope text-2xl font-extrabold text-[#171E26]">Reset Password</h1>
                <p class="font-inter text-[#171E26]/70 mt-1.5">Enter your new password</p>
            </div>

            <div id="reset-error"
                 style="display: none;"
                 class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
            </div>
            <div id="reset-success"
                 style="display: none;"
                 class="mb-4 flex items-center gap-2.5 bg-[#DBEBFB] border border-[#B1D0FB] text-[#2775E4] rounded-xl px-4 py-3 font-inter text-sm font-medium">
            </div>

            <form id="reset-form" novalidate class="space-y-4">

                <div>
                    <label for="token" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Reset Token</label>
                    <input type="text" id="token" name="token" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <div id="token-error" class="font-inter text-xs text-red-500 mt-1.5"></div>
                </div>

                <div>
                    <label for="email" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Email</label>
                    <input type="email" id="email" name="email" autocomplete="username" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <div id="email-error" class="font-inter text-xs text-red-500 mt-1.5"></div>
                </div>

                <div>
                    <label for="password" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">New Password</label>
                    <input type="password" id="password" name="password" autocomplete="new-password" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <div id="password-error" class="font-inter text-xs text-red-500 mt-1.5"></div>
                </div>

                <div>
                    <label for="password-confirmation" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Confirm Password</label>
                    <input type="password" id="password-confirmation" name="password_confirmation" autocomplete="new-password" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <div id="password-confirmation-error" class="font-inter text-xs text-red-500 mt-1.5"></div>
                </div>

                <button type="submit" id="reset-submit"
                        class="w-full px-5 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60 mt-2">
                    Reset Password
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="/staff/login" class="font-inter text-sm font-semibold text-[#2775E4] hover:underline">Back to login</a>
            </div>

        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/reset-password.js') }}"></script>
    </x-slot:scripts>
</x-layouts.guest>