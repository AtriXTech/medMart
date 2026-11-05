<x-layouts.customer title="legal-pages" active="legal-pages">

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[24px] md:text-[26px] text-[#171E26]">Legal-pages</h2>
    </div>

    <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm overflow-hidden">

        {{-- cookie-policy--}}
        <button type="button"  class="w-full flex items-start gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition">
           <a href="{{route('cookie-policy')}}">
              <i class="ph-light ph-list-bullets text-[26px] text-[#171E26] mt-0.5"></i>
            <div class="flex-1">
                <p class="font-manrope font-bold text-[17px] text-[#171E26]">cookie-policy</p>
                <p class="font-inter text-[14px] text-[#171E26]/45 mt-0.5">cookie-policy</p>
            </div>
            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 mt-1.5 transition-transform" ></i>
           </a>
        </button>
        <div class="border-t border-[#F3F7FC]"></div>

        {{-- disclaimer --}}
        <button type="button"  class="w-full flex items-start gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition">
           <a href="{{route('disclaimer')}}">
              <i class="ph-light ph-list-bullets text-[26px] text-[#171E26] mt-0.5"></i>
            <div class="flex-1">
                <p class="font-manrope font-bold text-[17px] text-[#171E26]">disclaimer</p>
                <p class="font-inter text-[14px] text-[#171E26]/45 mt-0.5">disclaimer</p>
            </div>
            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 mt-1.5 transition-transform" ></i>
           </a>
        </button>
        <div class="border-t border-[#F3F7FC]"></div>

        {{-- privacy-policy --}}
        <button type="button"  class="w-full flex items-start gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition">
          <a href="{{route('privacy-policy')}}">
               <i class="ph-light ph-list-bullets text-[26px] text-[#171E26] mt-0.5"></i>
            <div class="flex-1">
                <p class="font-manrope font-bold text-[17px] text-[#171E26]">privacy-policy</p>
                <p class="font-inter text-[14px] text-[#171E26]/45 mt-0.5">privacy-policy</p>
            </div>
            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 mt-1.5 transition-transform" ></i>
        </button>
           </a>
        <div class="border-t border-[#F3F7FC]"></div>

        {{-- refund-and-cancellation-policy --}}
        <button type="button"  class="w-full flex items-start gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition">
           <a href="{{route('refund-and-cancellation-policy')}}">
              <i class="ph-light ph-list-bullets text-[26px] text-[#171E26] mt-0.5"></i>
            <div class="flex-1">
                <p class="font-manrope font-bold text-[17px] text-[#171E26]">refund and cancellation policy</p>
                <p class="font-inter text-[14px] text-[#171E26]/45 mt-0.5">refund-and-cancellation-policy</p>
            </div>
            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 mt-1.5 transition-transform" ></i>
           </a>
        </button>
        <div class="border-t border-[#F3F7FC]"></div>


        {{-- terms-and-condition --}}
        <button type="button"  class="w-full flex items-start gap-4 px-5 py-5 text-left hover:bg-[#F7FAFD] transition">
           <a href="{{route('terms-and-condition')}}">
             <i class="ph-light ph-list-bullets text-[26px] text-[#171E26] mt-0.5"></i>
            <div class="flex-1">
                <p class="font-manrope font-bold text-[17px] text-[#171E26]">terms and condition</p>
                <p class="font-inter text-[14px] text-[#171E26]/45 mt-0.5">terms-and-condition</p>
            </div>
            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30 mt-1.5 transition-transform" ></i>
           </a>
        </button>
        <div class="border-t border-[#F3F7FC]"></div>
        
    </div>
</x-layouts.customer>