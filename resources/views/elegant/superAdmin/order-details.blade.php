<x-layouts.superAdmin title="Pharmacy Order Performance - Triple B Pharmacy" active="orders">

    <div class="space-y-6">

        {{-- BACK NAVIGATION & TITLE --}}
        <div class="space-y-3">
            <a href="{{ route('orders') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2775E4] hover:underline">
                <i class="ph ph-arrow-left"></i> Back to Orders Intelligence
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                            Triple B Pharmacy
                        </h1>
                        <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                        </span>
                    </div>
                    <p class="font-inter text-xs text-[#171E26]/60 mt-0.5">
                        Registered since Aug 12, 2025 · Order Intelligence & Performance Analytics
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-[#DBEBFB] bg-white font-inter text-xs font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] transition">
                        <i class="ph ph-arrows-clockwise text-sm text-[#2775E4]"></i> Refresh Data
                    </button>
                </div>
            </div>
        </div>

        {{-- SUMMARY KPIs FOR THIS PHARMACY --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4">
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Total Orders</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#171E26] mt-1">1,240</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Processed</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#08AEBC] mt-1">1,180</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Completed</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-emerald-600 mt-1">1,130</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Cancelled</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-rose-600 mt-1">20</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm col-span-2 lg:col-span-1">
                <span class="font-inter text-xs text-[#171E26]/60 block">Completion Rate</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#2775E4] mt-1">91.1%</p>
            </div>
        </div>

        {{-- ANALYTICS GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- ORDER TREND OVER TIME --}}
            <div class="lg:col-span-2 bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
                <div class="border-b border-[#F3F7FC] pb-3">
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Order Trend Over Time</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Historical volume processed by Triple B Pharmacy</p>
                </div>

                <div class="relative h-52 w-full pt-2">
                    <svg class="w-full h-full overflow-visible" viewBox="0 0 500 150" preserveAspectRatio="none">
                        <path d="M 0,120 Q 125,80 250,40 T 500,15 L 500,150 L 0,150 Z" fill="#E9F3FE" opacity="0.6" />
                        <path d="M 0,120 Q 125,80 250,40 T 500,15" fill="none" stroke="#2775E4" stroke-width="2.5" />
                    </svg>
                </div>
            </div>

            {{-- ORDER STATUS DISTRIBUTION --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
                <div class="border-b border-[#F3F7FC] pb-3">
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Status Breakdown</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Order volume distribution across states</p>
                </div>

                <div class="space-y-3 font-inter text-xs">
                    <div>
                        <div class="flex justify-between text-[#171E26] mb-1"><span>Completed</span><span class="font-bold">1,130</span></div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2"><div class="bg-emerald-500 h-2 rounded-full" style="width: 91%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[#171E26] mb-1"><span>In Processing</span><span class="font-bold">50</span></div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2"><div class="bg-[#08AEBC] h-2 rounded-full" style="width: 15%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[#171E26] mb-1"><span>Pending Prep</span><span class="font-bold">40</span></div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2"><div class="bg-amber-500 h-2 rounded-full" style="width: 10%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[#171E26] mb-1"><span>Cancelled</span><span class="font-bold">20</span></div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2"><div class="bg-rose-500 h-2 rounded-full" style="width: 5%"></div></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- PERFORMANCE SUMMARY CARD --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
            <h3 class="font-manrope font-bold text-base text-[#171E26] border-b border-[#F3F7FC] pb-3">Operational Intelligence Summary</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 font-inter text-xs">
                <div class="p-3.5 bg-[#F7FAFD] rounded-xl space-y-1">
                    <span class="text-[#171E26]/50 block font-medium">Average Processing Duration</span>
                    <p class="font-manrope font-bold text-sm text-[#171E26]">18 minutes</p>
                    <p class="text-[11px] text-emerald-600 font-medium">Within 30-min platform SLA</p>
                </div>

                <div class="p-3.5 bg-[#F7FAFD] rounded-xl space-y-1">
                    <span class="text-[#171E26]/50 block font-medium">Peak Order Hour</span>
                    <p class="font-manrope font-bold text-sm text-[#171E26]">2:00 PM – 4:00 PM</p>
                    <p class="text-[11px] text-[#171E26]/50">34% of total daily volume</p>
                </div>

                <div class="p-3.5 bg-[#F7FAFD] rounded-xl space-y-1">
                    <span class="text-[#171E26]/50 block font-medium">Platform Health Rating</span>
                    <p class="font-manrope font-bold text-sm text-emerald-600">Optimal (Grade A)</p>
                    <p class="text-[11px] text-[#171E26]/50">Low cancellation & steady SLA compliance</p>
                </div>
            </div>
        </div>

    </div>

</x-layouts.superAdmin>