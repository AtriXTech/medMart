<x-layouts.superadmin title="Settlement Record - ST-92831" active="finance-settlements">

    <div class="space-y-6">

        {{-- BACK NAVIGATION --}}
        <div class="space-y-3">
            <a href="{{ route('settlements') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2775E4] hover:underline">
                <i class="ph ph-arrow-left"></i> Back to Settlements
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                            ST-92831
                        </h1>
                        <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Settlement Successful
                        </span>
                    </div>
                    <p class="font-inter text-xs text-[#171E26]/60 mt-0.5">
                        Processed for Triple B Pharmacy · Disbursed on Sep 8, 2026 at 14:32:10 WAT
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" class="flex items-center gap-1.5 px-3 py-2 rounded-xl border border-[#DBEBFB] bg-white font-inter text-xs font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] transition">
                        <i class="ph ph-printer text-sm text-[#2775E4]"></i> Print Statement
                    </button>
                </div>
            </div>
        </div>

        {{-- SETTLEMENT SUMMARY OVERVIEW --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm bg-gradient-to-r from-white via-white to-[#E9F3FE]/40">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 font-inter text-xs">
                <div>
                    <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Net Disbursed Amount</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-emerald-700 mt-1">₦320,000.00</p>
                </div>
                <div>
                    <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Beneficiary Pharmacy</span>
                    <p class="font-manrope font-bold text-base text-[#2775E4] mt-1">Triple B Pharmacy</p>
                </div>
                <div>
                    <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Settlement Status</span>
                    <p class="font-manrope font-bold text-base text-emerald-600 mt-1">Completed / Settled</p>
                </div>
                <div>
                    <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Processed Date</span>
                    <p class="font-manrope font-bold text-base text-[#171E26] mt-1">Sep 08, 2026</p>
                </div>
            </div>
        </div>

        {{-- MAIN DETAIL CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN: SETTLEMENT INFORMATION & AUDIT --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Settlement Information Card --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] border-b border-[#F3F7FC] pb-3">Settlement Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 font-inter text-xs md:text-sm">
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Settlement Reference</span>
                            <p class="font-mono font-bold text-[#171E26] mt-0.5">ST-92831</p>
                        </div>

                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Gateway Reference</span>
                            <p class="font-mono text-[#171E26] mt-0.5">PSTK_ST_9812038192</p>
                        </div>

                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Destination Account</span>
                            <p class="font-medium text-[#171E26] mt-0.5">Guaranty Trust Bank · 0123456789</p>
                        </div>

                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Account Name</span>
                            <p class="font-manrope font-semibold text-[#171E26] mt-0.5">Triple B Pharmacy Ltd</p>
                        </div>

                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Batch Initialization</span>
                            <p class="font-medium text-[#171E26] mt-0.5">Sep 08, 2026 · 02:00:00 WAT</p>
                        </div>

                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Bank Credit Time</span>
                            <p class="font-medium text-[#171E26] mt-0.5">Sep 08, 2026 · 14:32:10 WAT</p>
                        </div>
                    </div>
                </div>

                {{-- ASSOCIATED CUSTOMER TRANSACTIONS IN BATCH --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-3">
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Included Customer Orders (2)</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Customer payments cleared in this settlement disbursement</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left font-inter text-xs">
                            <thead>
                                <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[10px] font-bold text-[#171E26]/50 uppercase">
                                    <th class="py-2.5 px-3">Tx Ref</th>
                                    <th class="py-2.5 px-3">Date</th>
                                    <th class="py-2.5 px-3 text-right">Gross Amount</th>
                                    <th class="py-2.5 px-3 text-right">Platform Fee</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#F3F7FC]">
                                <tr>
                                    <td class="py-2.5 px-3 font-mono font-bold text-[#2775E4]">MM-92831</td>
                                    <td class="py-2.5 px-3 text-[#171E26]/70">Sep 7, 2026</td>
                                    <td class="py-2.5 px-3 text-right font-manrope font-bold text-[#171E26]">₦250,000</td>
                                    <td class="py-2.5 px-3 text-right font-manrope font-bold text-rose-600">₦10,000</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-mono font-bold text-[#2775E4]">MM-92812</td>
                                    <td class="py-2.5 px-3 text-[#171E26]/70">Sep 7, 2026</td>
                                    <td class="py-2.5 px-3 text-right font-manrope font-bold text-[#171E26]">₦100,000</td>
                                    <td class="py-2.5 px-3 text-right font-manrope font-bold text-rose-600">₦5,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: FINANCIAL BREAKDOWN --}}
            <div class="space-y-6">

                {{-- Financial Breakdown (Backend Exact Representation) --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] border-b border-[#F3F7FC] pb-3">Financial Breakdown</h3>

                    <div class="space-y-3 font-inter text-xs">
                        <div class="flex items-center justify-between py-1">
                            <span class="text-[#171E26]/70">Customer Revenue Collected</span>
                            <span class="font-manrope font-bold text-[#171E26]">₦350,000.00</span>
                        </div>

                        <div class="flex items-center justify-between py-1 text-rose-600">
                            <span>MedMart Platform Fee</span>
                            <span class="font-manrope font-bold">-₦15,000.00</span>
                        </div>

                        <div class="flex items-center justify-between py-1 text-rose-600">
                            <span>Gateway Processing Fees</span>
                            <span class="font-manrope font-bold">-₦15,000.00</span>
                        </div>

                        <div class="pt-3 border-t border-[#F3F7FC] flex items-center justify-between font-semibold">
                            <span class="text-[#171E26]">Net Disbursed Amount</span>
                            <span class="font-manrope font-extrabold text-emerald-700 text-sm">₦320,000.00</span>
                        </div>
                    </div>
                </div>

                {{-- Platform Payout Note --}}
                <div class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-2xl p-4 shadow-sm space-y-2">
                    <div class="flex items-center gap-1.5 font-manrope font-bold text-xs text-[#171E26]">
                        <i class="ph ph-shield-check text-[#08AEBC] text-sm"></i>
                        <span>Disbursement Verified</span>
                    </div>

                    <p class="font-inter text-[11px] text-[#171E26]/60 leading-relaxed">
                        Funds were automatically reconciled and transferred via payment gateway settlement API.
                    </p>
                </div>

            </div>

        </div>

    </div>

</x-layouts.superadmin>