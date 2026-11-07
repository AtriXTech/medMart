<x-layouts.staff title="Legal Pages" active="legal-pages">

    <div class="mx-auto w-full max-w-[1200px] px-2 md:px-6 grid gap-4 md:gap-5 min-w-0">

        <div class="mb-1">
            <h2 class="font-manrope font-bold text-[18px] md:text-[20px] text-[#171E26]">Legal Pages</h2>
            <p class="font-inter text-[13px] text-[#171E26]/45 mt-1">Review the policies and agreements that apply to your pharmacy account.</p>
        </div>

        {{-- Privacy and Data Processing --}}
        <div class="card rounded-2xl bg-white border border-[#EAF1FB] shadow-sm hover:shadow-md hover:border-[#2775E4]/40 transition">
            <a href="{{route('privacyAndDataProcessing')}}" class="flex items-center gap-4 p-5 md:p-6">
                <div class="h-12 w-12 shrink-0 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                    <i class="ph ph-shield-check text-2xl text-[#2775E4]"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="font-manrope text-[16px] md:text-[17px] font-extrabold text-[#171E26]">Privacy and Data Processing</h1>
                    <p class="font-inter text-[13px] md:text-[13.5px] text-[#171E26]/60 mt-1 leading-relaxed">How we collect, store and protect your pharmacy and customer data.</p>
                </div>
                <i class="ph ph-caret-right text-lg text-[#171E26]/35 shrink-0"></i>
            </a>
        </div>

        {{-- Subscription and Billing --}}
        <div class="card rounded-2xl bg-white border border-[#EAF1FB] shadow-sm hover:shadow-md hover:border-[#2775E4]/40 transition">
            <a href="{{route('subscriptionAndBilling')}}" class="flex items-center gap-4 p-5 md:p-6">
                <div class="h-12 w-12 shrink-0 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                    <i class="ph ph-credit-card text-2xl text-[#2775E4]"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="font-manrope text-[16px] md:text-[17px] font-extrabold text-[#171E26]">Subscription and Billing</h1>
                    <p class="font-inter text-[13px] md:text-[13.5px] text-[#171E26]/60 mt-1 leading-relaxed">Plans, payments, renewals and refund terms for your subscription.</p>
                </div>
                <i class="ph ph-caret-right text-lg text-[#171E26]/35 shrink-0"></i>
            </a>
        </div>

        {{-- Terms and Conditions --}}
        <div class="card rounded-2xl bg-white border border-[#EAF1FB] shadow-sm hover:shadow-md hover:border-[#2775E4]/40 transition">
            <a href="{{route('termsAndConditions')}}" class="flex items-center gap-4 p-5 md:p-6">
                <div class="h-12 w-12 shrink-0 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                    <i class="ph ph-file-text text-2xl text-[#2775E4]"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="font-manrope text-[16px] md:text-[17px] font-extrabold text-[#171E26]">Terms and Conditions</h1>
                    <p class="font-inter text-[13px] md:text-[13.5px] text-[#171E26]/60 mt-1 leading-relaxed">The rules and responsibilities that govern your use of MedMart.</p>
                </div>
                <i class="ph ph-caret-right text-lg text-[#171E26]/35 shrink-0"></i>
            </a>
        </div>

    </div>

</x-layouts.staff>