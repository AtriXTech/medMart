<x-layouts.superadmin title="Customer Details - Khadijah Garuba" active="customers">

    <div class="space-y-6">

        {{-- BACK NAVIGATION & PAGE TITLE --}}
        <div class="space-y-3">
            <a href="{{ route('customers') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2775E4] hover:underline">
                <i class="ph ph-arrow-left"></i> Back to Customers
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                            Khadijah Garuba
                        </h1>
                        <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 rounded-full">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                        </span>
                    </div>
                    <p class="font-inter text-xs text-[#171E26]/60 mt-0.5">Customer since Aug 21, 2026</p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" class="flex items-center gap-1.5 px-3 py-2 rounded-xl border border-[#DBEBFB] bg-white font-inter text-xs font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] transition">
                        <i class="ph ph-envelope-simple text-sm text-[#2775E4]"></i> Contact
                    </button>
                    <button type="button" class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-rose-50 border border-rose-200/60 font-inter text-xs font-semibold text-rose-600 hover:bg-rose-100/70 transition">
                        <i class="ph ph-user-minus text-sm"></i> Suspend
                    </button>
                </div>
            </div>
        </div>

        {{-- KPI METRICS GRID --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Total Orders</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#171E26] mt-1">18</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Pharmacies Connected</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#2775E4] mt-1">3</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Total Spend</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#08AEBC] mt-1">₦184,500</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Last Activity</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#171E26] mt-1">2 hrs ago</p>
            </div>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- LEFT COLUMN: PROFILE INFO & LINKED PHARMACIES --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- CUSTOMER INFORMATION CARD --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] border-b border-[#F3F7FC] pb-3">Customer Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 font-inter text-xs md:text-sm">
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Full Name</span>
                            <p class="font-medium text-[#171E26] mt-0.5">Khadijah Garuba</p>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Username</span>
                            <p class="font-medium text-[#171E26] mt-0.5">khadijahh</p>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Email Address</span>
                            <p class="font-mono text-[#171E26] mt-0.5">sulaimonabubakre260@gmail.com</p>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Phone Number</span>
                            <p class="font-medium text-[#171E26] mt-0.5">+234 801 234 5678</p>
                        </div>
                    </div>
                </div>

                {{-- LINKED PHARMACIES CARD --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3">
                        <h3 class="font-manrope font-bold text-base text-[#171E26]">Linked Pharmacies</h3>
                        <span class="font-inter text-xs text-[#171E26]/50">3 connected</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse font-inter text-xs md:text-sm">
                            <thead>
                                <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[11px] font-bold text-[#171E26]/50 uppercase tracking-wider">
                                    <th class="py-2.5 px-3">Pharmacy</th>
                                    <th class="py-2.5 px-3">Status</th>
                                    <th class="py-2.5 px-3 text-right">Linked Since</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#F3F7FC]">
                                <tr class="hover:bg-[#F7FAFD]/50 transition">
                                    <td class="py-3 px-3 font-manrope font-bold text-[#171E26]">Triple B Pharmacy</td>
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center gap-1 font-semibold text-[11px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Active</span>
                                    </td>
                                    <td class="py-3 px-3 text-right text-[#171E26]/60">Aug 19, 2026</td>
                                </tr>
                                <tr class="hover:bg-[#F7FAFD]/50 transition">
                                    <td class="py-3 px-3 font-manrope font-bold text-[#171E26]">MedMart Yaba</td>
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center gap-1 font-semibold text-[11px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Active</span>
                                    </td>
                                    <td class="py-3 px-3 text-right text-[#171E26]/60">Aug 25, 2026</td>
                                </tr>
                                <tr class="hover:bg-[#F7FAFD]/50 transition">
                                    <td class="py-3 px-3 font-manrope font-bold text-[#171E26]">GreenLife Pharmacy</td>
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center gap-1 font-semibold text-[11px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Active</span>
                                    </td>
                                    <td class="py-3 px-3 text-right text-[#171E26]/60">Sep 01, 2026</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: ORDER ACTIVITY SUMMARY --}}
            <div class="space-y-6">
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] border-b border-[#F3F7FC] pb-3">Order Activity</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-[#F7FAFD] rounded-xl font-inter text-xs">
                            <div>
                                <p class="font-semibold text-[#171E26]">This month</p>
                                <p class="text-[11px] text-[#171E26]/50">8 orders placed</p>
                            </div>
                            <span class="font-manrope font-bold text-[#2775E4]">₦82,000</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-[#F7FAFD] rounded-xl font-inter text-xs">
                            <div>
                                <p class="font-semibold text-[#171E26]">Last month</p>
                                <p class="text-[11px] text-[#171E26]/50">6 orders placed</p>
                            </div>
                            <span class="font-manrope font-bold text-[#2775E4]">₦61,500</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-[#F7FAFD] rounded-xl font-inter text-xs">
                            <div>
                                <p class="font-semibold text-[#171E26]">Previous month</p>
                                <p class="text-[11px] text-[#171E26]/50">4 orders placed</p>
                            </div>
                            <span class="font-manrope font-bold text-[#2775E4]">₦41,000</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-layouts.superadmin>