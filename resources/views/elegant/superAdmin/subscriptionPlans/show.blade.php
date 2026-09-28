<x-layouts.superadmin title="Plan Performance Details - Standard" active="subscriptions-plans">

    <div class="space-y-6">

        {{-- BACK NAVIGATION --}}
        <div class="space-y-3">
            <a href="{{ route('subscriptionPlans') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2775E4] hover:underline">
                <i class="ph ph-arrow-left"></i> Back to Plans
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                            Standard Plan
                        </h1>
                        <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            ● Active Plan
                        </span>
                    </div>
                    <p class="font-inter text-xs text-[#171E26]/60 mt-0.5">
                        ₦9,500 / month · 126 Active Subscribed Pharmacies
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-[#DBEBFB] bg-white font-inter text-xs font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] transition">
                        <i class="ph ph-[#2775E4] ph-pencil-simple text-sm"></i> Edit Configuration
                    </button>
                </div>
            </div>
        </div>

        {{-- PLAN PERFORMANCE OVERVIEW KPIS --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Total Active Subscribers</span>
                <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1">126</p>
                <span class="font-inter text-[11px] text-emerald-600 font-semibold mt-1 block">↑ +8 this month</span>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Monthly Recurring Revenue (MRR)</span>
                <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#2775E4] mt-1">₦1,197,000</p>
                <span class="font-inter text-[11px] text-[#171E26]/50 mt-1 block">Based on ₦9,500 billing unit</span>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Successful Renewals</span>
                <p class="font-manrope font-extrabold text-2xl md:text-3xl text-emerald-700 mt-1">118</p>
                <span class="font-inter text-[11px] text-emerald-600 font-semibold mt-1 block">93.6% Renewal Rate</span>
            </div>
        </div>

        {{-- CONTENT LAYOUT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN: RECENT SUBSCRIPTION ACTIVITY TABLE --}}
            <div class="lg:col-span-2 space-y-4 bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm">
                <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3">
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Subscribed Pharmacies</h3>
                    <span class="font-inter text-xs text-[#171E26]/50">Showing latest records</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left font-inter text-xs md:text-sm">
                        <thead>
                            <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[11px] font-bold text-[#171E26]/50 uppercase">
                                <th class="py-3 px-4">Pharmacy</th>
                                <th class="py-3 px-4">Subscription Date</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F3F7FC]">
                            <tr>
                                <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">Triple B Pharmacy</td>
                                <td class="py-3.5 px-4 text-[#171E26]/60">Sep 8, 2026</td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-block bg-emerald-50 text-emerald-700 px-2.5 py-0.5 rounded-full font-semibold text-xs">Active</span>
                                </td>
                                <td class="py-3.5 px-4 text-right font-manrope font-bold text-[#171E26]">₦9,500</td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">MedMart Yaba Branch</td>
                                <td class="py-3.5 px-4 text-[#171E26]/60">Sep 7, 2026</td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-block bg-emerald-50 text-emerald-700 px-2.5 py-0.5 rounded-full font-semibold text-xs">Active</span>
                                </td>
                                <td class="py-3.5 px-4 text-right font-manrope font-bold text-[#171E26]">₦9,500</td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">Kano Med Centre</td>
                                <td class="py-3.5 px-4 text-[#171E26]/60">Sep 5, 2026</td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-block bg-emerald-50 text-emerald-700 px-2.5 py-0.5 rounded-full font-semibold text-xs">Active</span>
                                </td>
                                <td class="py-3.5 px-4 text-right font-manrope font-bold text-[#171E26]">₦9,500</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- RIGHT COLUMN: PLAN SPECIFICATIONS & INCLUDED FEATURES --}}
            <div class="space-y-4">
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] border-b border-[#F3F7FC] pb-3">Plan Details</h3>

                    <div class="space-y-3 font-inter text-xs">
                        <div>
                            <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Description</span>
                            <p class="text-[#171E26]/80 mt-1 leading-relaxed">
                                A complete digital pharmacy management package for operational retail stores.
                            </p>
                        </div>

                        <div class="pt-2 border-t border-[#F3F7FC]">
                            <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold mb-2">Configured Features</span>
                            <ul class="space-y-2 text-[#171E26]">
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Customer ordering</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Pharmacy dashboard</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Point of Sale (POS)</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Sales & order management</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Customer management</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-layouts.superadmin>