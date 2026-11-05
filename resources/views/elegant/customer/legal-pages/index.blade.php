
<x-layouts.customer title="Legal Pages" active="legal-pages">

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[24px] md:text-[26px] text-[#171E26]">
            Legal Pages
        </h2>
        <p class="font-inter text-sm text-[#171E26]/50 mt-1">
            Important policies and information about using MedMart.
        </p>
    </div>

    <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm overflow-hidden">

        {{-- Cookie Policy --}}
        <a href="{{ route('cookiePolicy') }}"
           class="w-full flex items-center gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition-colors duration-200 group">

            <i class="ph-light ph-cookie text-[27px] text-[#2775E4] shrink-0"></i>

            <div class="flex-1 min-w-0">
                <p class="font-manrope font-bold text-[16px] md:text-[17px] text-[#171E26]">
                    Cookie Policy
                </p>
                <p class="font-inter text-[13px] md:text-[14px] text-[#171E26]/50 mt-0.5">
                    Find out how cookies help us improve your browsing experience.
                </p>
            </div>

            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 group-hover:text-[#2775E4] group-hover:translate-x-1 transition-all shrink-0"></i>
        </a>

        <div class="border-t border-[#F3F7FC]"></div>

        {{-- Disclaimer --}}
        <a href="{{ route('disclaimer') }}"
           class="w-full flex items-center gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition-colors duration-200 group">

            <i class="ph-light ph-info text-[27px] text-[#2775E4] shrink-0"></i>

            <div class="flex-1 min-w-0">
                <p class="font-manrope font-bold text-[16px] md:text-[17px] text-[#171E26]">
                    Disclaimer
                </p>
                <p class="font-inter text-[13px] md:text-[14px] text-[#171E26]/50 mt-0.5">
                    Understand the limitations and responsibilities associated with using MedMart.
                </p>
            </div>

            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 group-hover:text-[#2775E4] group-hover:translate-x-1 transition-all shrink-0"></i>
        </a>

        <div class="border-t border-[#F3F7FC]"></div>

        {{-- Privacy Policy --}}
        <a href="{{ route('privacyPolicy') }}"
           class="w-full flex items-center gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition-colors duration-200 group">

            <i class="ph-light ph-shield-check text-[27px] text-[#2775E4] shrink-0"></i>

            <div class="flex-1 min-w-0">
                <p class="font-manrope font-bold text-[16px] md:text-[17px] text-[#171E26]">
                    Privacy Policy
                </p>
                <p class="font-inter text-[13px] md:text-[14px] text-[#171E26]/50 mt-0.5">
                    Learn how we collect, use, and protect your personal information.
                </p>
            </div>

            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 group-hover:text-[#2775E4] group-hover:translate-x-1 transition-all shrink-0"></i>
        </a>

        <div class="border-t border-[#F3F7FC]"></div>

        {{-- Refund and Cancellation Policy --}}
        <a href="{{ route('refundAndCancellationPolicy') }}"
           class="w-full flex items-center gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition-colors duration-200 group">

            <i class="ph-light ph-arrows-counter-clockwise text-[27px] text-[#2775E4] shrink-0"></i>

            <div class="flex-1 min-w-0">
                <p class="font-manrope font-bold text-[16px] md:text-[17px] text-[#171E26]">
                    Refund and Cancellation Policy
                </p>
                <p class="font-inter text-[13px] md:text-[14px] text-[#171E26]/50 mt-0.5">
                    Know what happens when an order is cancelled or an item is unavailable.
                </p>
            </div>

            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 group-hover:text-[#2775E4] group-hover:translate-x-1 transition-all shrink-0"></i>
        </a>

        <div class="border-t border-[#F3F7FC]"></div>

        {{-- Terms and Conditions --}}
        <a href="{{ route('termsAndCondition') }}"
           class="w-full flex items-center gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition-colors duration-200 group">

            <i class="ph-light ph-file-text text-[27px] text-[#2775E4] shrink-0"></i>

            <div class="flex-1 min-w-0">
                <p class="font-manrope font-bold text-[16px] md:text-[17px] text-[#171E26]">
                    Terms and Conditions
                </p>
                <p class="font-inter text-[13px] md:text-[14px] text-[#171E26]/50 mt-0.5">
                    Review the rules and conditions for accessing and using MedMart.
                </p>
            </div>

            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 group-hover:text-[#2775E4] group-hover:translate-x-1 transition-all shrink-0"></i>
        </a>

    </div>

</x-layouts.customer>
