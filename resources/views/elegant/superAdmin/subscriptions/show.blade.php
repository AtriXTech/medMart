<x-layouts.superadmin title="Subscription Details - Pharmacy A" active="subscriptions-all">

    <div class="space-y-6">

        {{-- BACK NAVIGATION & TITLE --}}
        <div class="space-y-3">
            <a href="{{ route('subscriptions') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2775E4] hover:underline">
                <i class="ph ph-arrow-left"></i> Back to Subscriptions
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                            Pharmacy A
                        </h1>
                        <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            ● Active Subscription
                        </span>
                    </div>
                    <p class="font-inter text-xs text-[#171E26]/60 mt-0.5">
                        Standard Tier · ₦9,500 / month · Ref: SUB-93821
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-[#DBEBFB] bg-white font-inter text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                        Cancel Subscription
                    </button>
                    <button type="button" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#2775E4] text-white font-inter text-xs font-semibold shadow-sm hover:bg-[#2775E4]/90 transition">
                        Change Plan Tier
                    </button>
                </div>
            </div>
        </div>

        {{-- SUBSCRIPTION SUMMARY KPI CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Subscription Started</span>
                <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1">Sep 8, 2026</p>
                <span class="font-inter text-[11px] text-[#171E26]/50 mt-1 block">Active for 1 billing cycle</span>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Next Billing Date</span>
                <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#2775E4] mt-1">Oct 8, 2026</p>
                <span class="font-inter text-[11px] text-emerald-600 font-semibold mt-1 block">Auto-renewal enabled</span>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Total Lifetime Paid</span>
                <p class="font-manrope font-extrabold text-2xl md:text-3xl text-emerald-700 mt-1">₦19,000</p>
                <span class="font-inter text-[11px] text-[#171E26]/50 mt-1 block">2 payments processed</span>
            </div>
        </div>

        {{-- CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN: DETAILED SUBSCRIPTION INFORMATION --}}
            <div class="lg:col-span-1 bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="font-manrope font-bold text-base text-[#171E26] border-b border-[#F3F7FC] pb-3">Subscription Details</h3>

                <div class="space-y-3 font-inter text-xs">
                    <div>
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Subscribed Pharmacy</span>
                        <p class="font-manrope font-bold text-sm text-[#171E26] mt-0.5">Pharmacy A</p>
                        <span class="text-[#171E26]/60">pharmacyA@medmart.ng</span>
                    </div>

                    <div class="pt-2 border-t border-[#F3F7FC]">
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Plan Name</span>
                        <p class="font-semibold text-[#2775E4] mt-0.5">Standard Tier</p>
                    </div>

                    <div class="pt-2 border-t border-[#F3F7FC]">
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Billing Interval</span>
                        <p class="font-semibold text-[#171E26] mt-0.5">Monthly (₦9,500 / month)</p>
                    </div>

                    <div class="pt-2 border-t border-[#F3F7FC]">
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Status</span>
                        <p class="font-semibold text-emerald-700 mt-0.5">Active</p>
                    </div>

                    <div class="pt-2 border-t border-[#F3F7FC]">
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Start Date</span>
                        <p class="font-semibold text-[#171E26] mt-0.5">September 8, 2026</p>
                    </div>

                    <div class="pt-2 border-t border-[#F3F7FC]">
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Next Billing Date</span>
                        <p class="font-semibold text-[#171E26] mt-0.5">October 8, 2026</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: PAYMENT HISTORY TABLE --}}
            <div class="lg:col-span-2 bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3">
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Payment History</h3>
                    <span class="font-inter text-xs text-[#171E26]/50">Platform Subscription Receipts</span>
                </div>

                {{-- Desktop History Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left font-inter text-xs">
                        <thead>
                            <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[10px] font-bold text-[#171E26]/50 uppercase">
                                <th class="py-2.5 px-3">Reference</th>
                                <th class="py-2.5 px-3">Amount</th>
                                <th class="py-2.5 px-3">Status</th>
                                <th class="py-2.5 px-3 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F3F7FC]">
                            <tr>
                                <td class="py-3 px-3 font-manrope font-bold text-[#171E26]">SUB-93821</td>
                                <td class="py-3 px-3 font-manrope font-bold text-[#171E26]">₦9,500</td>
                                <td class="py-3 px-3">
                                    <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-semibold text-[10px]">Successful</span>
                                </td>
                                <td class="py-3 px-3 text-right text-[#171E26]/70">Sep 8, 2026</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-3 font-manrope font-bold text-[#171E26]">SUB-92018</td>
                                <td class="py-3 px-3 font-manrope font-bold text-[#171E26]">₦9,500</td>
                                <td class="py-3 px-3">
                                    <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-semibold text-[10px]">Successful</span>
                                </td>
                                <td class="py-3 px-3 text-right text-[#171E26]/70">Aug 8, 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Mobile History Cards --}}
                <div class="block sm:hidden space-y-2 font-inter text-xs">
                    <div class="p-3 bg-[#F7FAFD] rounded-xl border border-[#EAF1FB] flex items-center justify-between">
                        <div>
                            <span class="font-manrope font-bold text-[#171E26] block">SUB-93821</span>
                            <span class="text-[10px] text-[#171E26]/50">Sep 8, 2026</span>
                        </div>
                        <div class="text-right">
                            <span class="font-manrope font-bold text-[#171E26] block">₦9,500</span>
                            <span class="text-[10px] text-emerald-600 font-semibold">Successful</span>
                        </div>
                    </div>

                    <div class="p-3 bg-[#F7FAFD] rounded-xl border border-[#EAF1FB] flex items-center justify-between">
                        <div>
                            <span class="font-manrope font-bold text-[#171E26] block">SUB-92018</span>
                            <span class="text-[10px] text-[#171E26]/50">Aug 8, 2026</span>
                        </div>
                        <div class="text-right">
                            <span class="font-manrope font-bold text-[#171E26] block">₦9,500</span>
                            <span class="text-[10px] text-emerald-600 font-semibold">Successful</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-layouts.superadmin>