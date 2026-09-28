{{--
    Intended path: resources/views/customer/support.blade.php
    Route: /customer/support

    NOTES:
    - Matches the two-row card layout from your reference image (FAQ /
      Chat, each with icon + title + description + chevron).
    - Instead of adding two more routes/pages for FAQ content and chat
      details, both rows expand in place (accordion-style) when tapped —
      keeps this self-contained as one normal blade page, same
      inline-toggle pattern already used everywhere else in this app.
      Say the word if you'd rather these be separate pages instead.
    - #faq-panel / #chat-panel content is entirely built by support.js.
--}}
<x-layouts.customer title="Support" active="support">

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[24px] md:text-[26px] text-[#171E26]">Support</h2>
    </div>

    <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm overflow-hidden">

        {{-- FAQ --}}
        <button type="button" id="faq-toggle-btn" class="w-full flex items-start gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition">
            <i class="ph-light ph-list-bullets text-[26px] text-[#171E26] mt-0.5"></i>
            <div class="flex-1">
                <p class="font-manrope font-bold text-[17px] text-[#171E26]">FAQ</p>
                <p class="font-inter text-[14px] text-[#171E26]/45 mt-0.5">Find quick answers to common questions</p>
            </div>
            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 mt-1.5 transition-transform" id="faq-toggle-icon"></i>
        </button>

        <div id="faq-panel" style="display: none;" class="px-5 pb-5"></div>

        <div class="border-t border-[#F3F7FC]"></div>

        {{-- Chat --}}
        <button type="button" id="chat-toggle-btn" class="w-full flex items-start gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition">
            <i class="ph-light ph-chat-circle-text text-[26px] text-[#171E26] mt-0.5"></i>
            <div class="flex-1">
                <p class="font-manrope font-bold text-[17px] text-[#171E26]">Chat</p>
                <p class="font-inter text-[14px] text-[#171E26]/45 mt-0.5">Get help instantly through live messaging</p>
            </div>
            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 mt-1.5 transition-transform" id="chat-toggle-icon"></i>
        </button>

        <div id="chat-panel" style="display: none;" class="px-5 pb-5">
            <div id="chat-loading" class="py-2">
                <div class="rounded-xl bg-[#F1F3F6] h-16 w-full animate-pulse"></div>
            </div>
            <div id="chat-content" style="display: none;"></div>
        </div>

    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/support.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>