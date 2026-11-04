<x-layouts.customer-guest title="Email Verification">
    <x-slot:subtitle>
        <p class="sub">Verifying your email address</p>
    </x-slot:subtitle>

    <div class="w-full max-w-[440px] mx-auto py-4 sm:py-6">

        {{-- Loading State --}}
        <div id="verification-loading"
             class="flex flex-col items-center text-center px-3 py-7 sm:py-9">

            <div class="relative flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 rounded-[26px] bg-[#E9F3FE] text-[#2775E4] mb-7">

                <i class="ph ph-envelope-simple text-4xl sm:text-5xl"></i>

                <span class="absolute inset-[-5px] rounded-full border-[3px] border-transparent border-t-[#2775E4] border-r-[#08AEBC] animate-spin"></span>
            </div>

            <h2 class="text-xl sm:text-2xl font-extrabold text-[#171E26] tracking-tight mb-3">
                Verifying your email
            </h2>

            <p class="max-w-[340px] text-sm text-gray-500 leading-7">
                Please wait while we confirm your email address.
                This should only take a moment.
            </p>
        </div>

        {{-- Error Message --}}
        <div id="verification-error"
             class="hidden w-full mb-5 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 leading-relaxed break-words"
             role="alert">
        </div>

        {{-- Success Alert --}}
        <div id="verification-success"
             class="hidden w-full mb-5 rounded-xl border border-teal-100 bg-teal-50 px-4 py-3 text-sm text-teal-700 leading-relaxed break-words"
             role="status">
        </div>

        {{-- Verified State --}}
        <div id="verification-success-message"
             class="hidden flex-col items-center text-center px-3 py-7 sm:py-9">

            <div class="flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 rounded-[26px] bg-[#E7F8F5] text-[#08AEBC] mb-7">
                <i class="ph ph-check-circle text-5xl sm:text-6xl"></i>
            </div>

            <h2 class="text-xl sm:text-2xl font-extrabold text-[#171E26] tracking-tight mb-3">
                Email verified!
            </h2>

            <p class="max-w-[340px] text-sm text-gray-500 leading-7">
                Your email address has been successfully verified.
                Your MedMart account is now ready to use.
            </p>

            <a href="/customer/login"
               class="flex items-center justify-center gap-2 w-full min-h-[52px] mt-8 px-5 py-3.5 rounded-xl bg-[#2775E4] hover:bg-[#1D62C2] active:scale-[0.99] text-white text-sm font-bold transition-all duration-200 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-100">
                Continue to Login
                <i class="ph ph-arrow-right text-lg"></i>
            </a>
        </div>

    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/verify-email.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer-guest>
