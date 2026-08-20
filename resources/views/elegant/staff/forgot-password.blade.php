<x-layouts.guest title="Forgot Password">
    
    <main class="flex items-center justify-center px-4 py-16 md:py-24 min-h-[calc(100vh-96px)] relative overflow-hidden">
        <!-- Subtle decorative shapes, same language as login/signup -->
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-[#B1D0FB]/40 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-[#DBEBFB]/50 blur-3xl"></div>

        <div class="relative z-10 w-full max-w-md bg-white/60 backdrop-blur-xl border border-white/60 shadow-lg rounded-2xl p-6 md:p-10">
            <div class="alert alert-error" id="forgot-error" style="display: none;"></div>
    <div class="alert alert-success" id="forgot-success" style="display: none;"></div>
    
            <div class="h-12 w-12 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                <i class="ph ph-lock-key text-[#2775E4] text-2xl"></i>
            </div>

            <h1 class="font-manrope text-3xl font-extrabold text-[#171E26] mt-5">Forgot your password?</h1>
            <p class="font-inter text-[#171E26]/70 mt-2">
                Enter the email address linked to your MedMart account and we'll send you a link to reset your password.
            </p>

            <form class="mt-7 space-y-5" id="forgot-form" novalidate>

                <div>
                    <label for="email" class="block font-inter text-sm font-medium text-[#171E26] mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" autocomplete="email" required
                        placeholder="you@example.com"
                        class="w-full rounded-xl border border-[#DBEBFB] px-4 py-3 font-inter text-[#171E26] placeholder:text-[#171E26]/30 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                     <div class="field-error" id="email-error"></div>
                    </div>

                <button type="submit" id="forgot-submit" class="w-full px-7 py-3.5 rounded-full bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold tracking-wide shadow-lg shadow-[#2775E4]/20 hover:scale-[1.02] transition">
                    Send Reset Link
                </button>

            </form>
            <a href="/staff/login" class="flex items-center justify-center gap-1.5 font-inter text-sm font-semibold text-[#2775E4] hover:underline mt-7">
                <i class="ph ph-arrow-left text-base"></i>
                Back to log in
            </a>

        </div>

    </main>

 <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/forgot-password.js') }}"></script>
    </x-slot:scripts>
</x-layouts.guest>