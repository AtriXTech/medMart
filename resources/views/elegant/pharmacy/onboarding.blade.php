<x-layouts.staff title="Choose Your Plan" active="subscription">

    {{-- Error banner --}}
    <div id="onboarding-error"
         style="display: none;"
         class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- Loading state — JS toggles via style.display = 'block'/'none' --}}
    <div id="onboarding-loading" class="py-20 text-center">
        <i class="ph ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Loading plans...</p>
    </div>

    {{-- Main content — JS toggles via style.display = 'block'/'none' --}}
    <div id="onboarding-content" style="display: none;">
        <div class="max-w-[640px] mx-auto">

            <div class="text-center mb-8">
                <h1 class="font-manrope text-2xl md:text-3xl font-extrabold text-[#171E26]">Welcome to MedMart!</h1>
                <p class="font-inter text-[#171E26]/60 mt-2">Choose a subscription plan to get started</p>
            </div>

            {{-- Note: subscription-message is reused for both error AND success states
                 by swapping its full className — kept exactly as-is. --}}
            <div id="subscription-message"
                 style="display: none;"
                 class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
            </div>

            <div id="plans-container">
                {{-- Populated by onboarding.js --}}
            </div>

            <div id="selected-plan-info"
                 style="display: none;"
                 class="my-5 px-4 py-3.5 bg-[#DBEBFB] border border-[#B1D0FB] rounded-xl font-inter text-[14px] text-[#171E26]">
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center mt-6">
                <button type="button" id="skip-for-now-btn"
                        class="px-6 py-3 rounded-xl border border-[#DBEBFB] font-inter text-[14px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
                    Skip for Now
                </button>
                <button type="button" id="confirm-subscription-btn" disabled
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-40 disabled:cursor-not-allowed">
                    Confirm Subscription
                </button>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/pharmacy/onboarding.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>