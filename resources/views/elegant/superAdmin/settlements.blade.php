<x-layouts.superadmin title="Settlement Activity & Monitoring" active="finance-settlements">

    <div class="space-y-6">

        {{-- PAGE HEADER & GLOBAL CONTROLS --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                    Settlements
                </h1>
                <p class="font-inter text-xs md:text-sm text-[#171E26]/60 mt-0.5">
                    Monitor settlement activity across pharmacies on MedMart.
                </p>
            </div>

            {{-- HEADER CONTROLS --}}
            <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
                {{-- Date Range Selector --}}
                <div class="inline-flex items-center bg-white border border-[#DBEBFB] rounded-xl p-1 shadow-sm font-inter text-xs font-semibold">
                    <button type="button" id="btn-period-7d" onclick="selectPeriod('7D')" class="px-2.5 py-1.5 rounded-lg text-[#171E26]/60 hover:text-[#171E26] transition">7D</button>
                    <button type="button" id="btn-period-30d" onclick="selectPeriod('30D')" class="px-2.5 py-1.5 rounded-lg bg-[#2775E4] text-white transition">30D</button>
                    <button type="button" id="btn-period-90d" onclick="selectPeriod('90D')" class="px-2.5 py-1.5 rounded-lg text-[#171E26]/60 hover:text-[#171E26] transition">90D</button>
                    <button type="button" id="btn-period-12m" onclick="selectPeriod('12M')" class="px-2.5 py-1.5 rounded-lg text-[#171E26]/60 hover:text-[#171E26] transition">12M</button>
                </div>

                {{-- Refresh Button --}}
                <button type="button" onclick="refreshPageData()" class="flex items-center justify-center gap-1.5 bg-white border border-[#DBEBFB] rounded-xl px-3 py-2 font-inter text-xs font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] active:scale-95 transition">
                    <i class="ph ph-arrows-clockwise text-sm text-[#2775E4]"></i>
                    <span class="hidden md:inline">Refresh</span>
                </button>
            </div>
        </div>

        {{-- PRIMARY KPI GRID (4 CARDS) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            
            {{-- 1. Total Settled --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Total Settled</span>
                    <div class="h-8 w-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph-fill ph-check-circle text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#171E26] tracking-tight">₦18,450,000</p>
                    <span class="inline-flex items-center gap-1 font-inter text-[11px] font-semibold text-emerald-600 mt-0.5">
                        <i class="ph ph-trend-up"></i> +12.4% vs last period
                    </span>
                </div>
            </div>

            {{-- 2. Pending Settlement --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Pending Settlement</span>
                    <div class="h-8 w-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="ph-fill ph-clock text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-xl md:text-2xl text-amber-600 tracking-tight">₦1,240,000</p>
                    <span class="font-inter text-[11px] font-medium text-[#171E26]/50 mt-0.5 block">14 settlements queued</span>
                </div>
            </div>

            {{-- 3. Today's Settlement --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Today's Settlement</span>
                    <div class="h-8 w-8 rounded-lg bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center">
                        <i class="ph-fill ph-calendar-check text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#171E26] tracking-tight">₦850,000</p>
                    <span class="font-inter text-[11px] font-medium text-[#171E26]/50 mt-0.5 block">6 settlements processed today</span>
                </div>
            </div>

            {{-- 4. Last Settlement --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Last Settlement</span>
                    <div class="h-8 w-8 rounded-lg bg-[#E9F3FE] text-[#08AEBC] flex items-center justify-center">
                        <i class="ph-fill ph-receipt text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#171E26] tracking-tight">₦620,000</p>
                    <span class="font-inter text-[11px] font-semibold text-[#171E26]/60 mt-0.5 block">Sep 7, 2026 · 18:40</span>
                </div>
            </div>

        </div>

        {{-- SETTLEMENT OVERVIEW ANALYTICS SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            {{-- Settlement Overview Historical Line/Area Chart --}}
            <div class="lg:col-span-2 bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-[#F3F7FC] pb-3 mb-3">
                    <div>
                        <h3 class="font-manrope font-bold text-base text-[#171E26]">Settlement Overview</h3>
                        <p class="font-inter text-xs text-[#171E26]/50">Total volume settled to pharmacy bank accounts over time</p>
                    </div>
                    <div class="flex items-center gap-2 font-inter text-xs">
                        <span class="inline-flex items-center gap-1.5 font-medium text-[#171E26]/70">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#2775E4]"></span> Settled Payouts
                        </span>
                    </div>
                </div>

                {{-- Chart Area SVG Representation --}}
                <div class="relative h-52 w-full pt-2">
                    <svg class="w-full h-full overflow-visible" viewBox="0 0 600 160" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="settlementGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#2775E4" stop-opacity="0.2" />
                                <stop offset="100%" stop-color="#2775E4" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        {{-- Grid lines --}}
                        <line x1="0" y1="30" x2="600" y2="30" stroke="#F3F7FC" stroke-width="1" />
                        <line x1="0" y1="80" x2="600" y2="80" stroke="#F3F7FC" stroke-width="1" />
                        <line x1="0" y1="130" x2="600" y2="130" stroke="#F3F7FC" stroke-width="1" />

                        {{-- Main Gradient Fill --}}
                        <path d="M 0,110 C 100,30 200,120 300,50 C 400,90 500,20 600,40 L 600,160 L 0,160 Z" fill="url(#settlementGrad)" />

                        {{-- Line --}}
                        <path d="M 0,110 C 100,30 200,120 300,50 C 400,90 500,20 600,40" fill="none" stroke="#2775E4" stroke-width="3" />
                        
                        {{-- Nodes --}}
                        <circle cx="0" cy="110" r="4" fill="#2775E4" />
                        <circle cx="150" cy="65" r="4" fill="#2775E4" />
                        <circle cx="300" cy="50" r="4" fill="#2775E4" />
                        <circle cx="450" cy="55" r="4" fill="#2775E4" />
                        <circle cx="600" cy="40" r="5" fill="#08AEBC" stroke="#FFFFFF" stroke-width="2" />
                    </svg>
                </div>

                <div class="flex items-center justify-between border-t border-[#F3F7FC] pt-3 mt-2 font-inter text-[11px] text-[#171E26]/50">
                    <span>Sep 1, 2026</span>
                    <span>Sep 3, 2026</span>
                    <span>Sep 5, 2026</span>
                    <span>Sep 7, 2026</span>
                    <span>Sep 8, 2026</span>
                </div>
            </div>

            {{-- Settlement Status Distribution --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="border-b border-[#F3F7FC] pb-3 mb-3">
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Settlement Status</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Distribution of platform payout statuses</p>
                </div>

                <div class="space-y-4 font-inter text-xs">
                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Settled</span>
                            <span class="font-bold">82% <span class="font-normal text-[#171E26]/50">(₦18.45M)</span></span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: 82%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Pending</span>
                            <span class="font-bold">14% <span class="font-normal text-[#171E26]/50">(₦1.24M)</span></span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: 14%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span> Failed</span>
                            <span class="font-bold">4% <span class="font-normal text-[#171E26]/50">(₦95.0K)</span></span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-rose-500 h-2 rounded-full" style="width: 4%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-[#E9F3FE]/60 border border-[#DBEBFB] rounded-xl text-center">
                    <span class="font-inter text-xs text-[#171E26]/60">Total Platform Volume Processed:</span>
                    <p class="font-manrope font-extrabold text-base text-[#2775E4]">₦19,690,000</p>
                </div>
            </div>

        </div>

        {{-- CONCEPTUAL SETTLEMENT FLOW PIPELINE --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-3">
            <div>
                <h3 class="font-manrope font-bold text-base text-[#171E26]">Settlement Flow</h3>
                <p class="font-inter text-xs text-[#171E26]/50">Lifecycle separation of collected customer payments versus disbursed pharmacy payouts</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 relative">
                
                {{-- Stage 1: Revenue Generated --}}
                <div class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl p-4 flex flex-col justify-between relative">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-inter text-xs font-bold uppercase tracking-wider text-[#171E26]/60">1. Revenue Generated</span>
                        <i class="ph ph-hand-coins text-lg text-[#2775E4]"></i>
                    </div>
                    <p class="font-manrope font-extrabold text-2xl text-[#171E26]">₦19,690,000</p>
                    <p class="font-inter text-[11px] text-[#171E26]/50 mt-1">Total customer transactions processed</p>
                </div>

                {{-- Stage 2: Pending Settlement --}}
                <div class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl p-4 flex flex-col justify-between relative">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-inter text-xs font-bold uppercase tracking-wider text-amber-700">2. Pending Settlement</span>
                        <i class="ph ph-hourglass-high text-lg text-amber-600"></i>
                    </div>
                    <p class="font-manrope font-extrabold text-2xl text-amber-600">₦1,240,000</p>
                    <p class="font-inter text-[11px] text-[#171E26]/50 mt-1">Queued for clearing & disbursement</p>
                </div>

                {{-- Stage 3: Settled / Credited --}}
                <div class="bg-gradient-to-br from-[#E9F3FE] to-white border border-[#B1D0FB] rounded-xl p-4 flex flex-col justify-between relative">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-inter text-xs font-bold uppercase tracking-wider text-[#2775E4]">3. Settled / Credited</span>
                        <i class="ph ph-bank text-lg text-[#08AEBC]"></i>
                    </div>
                    <p class="font-manrope font-extrabold text-2xl text-emerald-700">₦18,450,000</p>
                    <p class="font-inter text-[11px] text-[#171E26]/60 mt-1">Disbursed directly to pharmacy bank accounts</p>
                </div>

            </div>
        </div>

        {{-- SETTLEMENT HISTORY TABLE & FILTER TOOLBAR --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl shadow-sm overflow-hidden space-y-4 p-4 md:p-5">
            
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 border-b border-[#F3F7FC] pb-4">
                <div>
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Settlement History</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Complete register of all pharmacy payout batches and transactions</p>
                </div>

                {{-- TOOLBAR CONTROLS --}}
                <div class="flex items-center gap-2 flex-wrap lg:flex-nowrap font-inter text-xs">
                    {{-- Search Input --}}
                    <div class="relative w-full sm:w-64">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#171E26]/40"></i>
                        <input type="text" 
                               id="table-search" 
                               onkeyup="filterTable()" 
                               placeholder="Search reference or pharmacy..." 
                               class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-9 pr-3 py-2 text-[#171E26] focus:outline-none focus:border-[#2775E4] transition" />
                    </div>

                    {{-- Status Filter --}}
                    <select id="status-filter" onchange="filterTable()" class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 font-semibold text-[#171E26] focus:outline-none cursor-pointer">
                        <option value="all">Status: All</option>
                        <option value="settled">Settled</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>

                    <button type="button" onclick="refreshPageData()" class="p-2 bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl text-[#171E26]/70 hover:text-[#2775E4] transition" title="Refresh list">
                        <i class="ph ph-arrows-clockwise text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- DESKTOP TABLE VIEW --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse font-inter text-xs md:text-sm" id="settlements-table">
                    <thead>
                        <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[11px] font-bold text-[#171E26]/50 uppercase tracking-wider">
                            <th class="py-3 px-4">Reference</th>
                            <th class="py-3 px-4">Pharmacy</th>
                            <th class="py-3 px-4 text-right">Amount</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4">Settlement Date</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3F7FC]">
                        
                        {{-- Row 1: Settled --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#2775E4]">ST-92831</td>
                            <td class="py-3.5 px-4 font-manrope font-semibold text-[#171E26]">Triple B Pharmacy</td>
                            <td class="py-3.5 px-4 text-right font-manrope font-extrabold text-[#171E26]">₦320,000</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/50">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Settled
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/60 text-xs">Sep 8, 2026 · 14:32</td>
                            <td class="py-3.5 px-4 text-center">
                                <a href='{{ route('settlementDetails') }}'>
                                    <button type="button" onclick="openDrawer('ST-92831')" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View <i class="ph ph-arrow-right"></i>
                                </button>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 2: Pending --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#2775E4]">ST-92830</td>
                            <td class="py-3.5 px-4 font-manrope font-semibold text-[#171E26]">MedMart Yaba Branch</td>
                            <td class="py-3.5 px-4 text-right font-manrope font-extrabold text-[#171E26]">₦185,000</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200/50">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Pending
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/60 text-xs">Sep 8, 2026 · 11:10</td>
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" onclick="openDrawer('ST-92830')" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View <i class="ph ph-arrow-right"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Row 3: Failed --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#2775E4]">ST-92829</td>
                            <td class="py-3.5 px-4 font-manrope font-semibold text-[#171E26]">GreenLife Pharmacy</td>
                            <td class="py-3.5 px-4 text-right font-manrope font-extrabold text-[#171E26]">₦95,000</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-rose-700 bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200/50">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Failed
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/60 text-xs">Sep 7, 2026 · 16:45</td>
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" onclick="openDrawer('ST-92829')" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View <i class="ph ph-arrow-right"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- MOBILE SETTLEMENT CARDS VIEW --}}
            <div class="block md:hidden space-y-3" id="mobile-settlements-list">
                
                {{-- Card 1: Settled --}}
                <div class="bg-[#F7FAFD] border border-[#EAF1FB] rounded-xl p-3.5 space-y-3">
                    <div class="flex items-center justify-between border-b border-[#DBEBFB] pb-2">
                        <div>
                            <span class="font-mono font-bold text-xs text-[#2775E4] block">ST-92831</span>
                            <span class="font-manrope font-bold text-xs text-[#171E26]">Triple B Pharmacy</span>
                        </div>
                        <span class="font-semibold text-xs text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/50">Settled</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs font-inter">
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px]">Amount</span>
                            <span class="font-manrope font-extrabold text-[#171E26]">₦320,000</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px]">Date</span>
                            <span class="text-[#171E26]/70">Sep 8, 2026</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-[#DBEBFB]">
                        <button type="button" onclick="openDrawer('ST-92831')" class="w-full flex items-center justify-center gap-1 bg-white border border-[#DBEBFB] py-2 rounded-lg text-xs font-semibold text-[#2775E4] shadow-sm">
                            View Settlement <i class="ph ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- Card 2: Pending --}}
                <div class="bg-[#F7FAFD] border border-[#EAF1FB] rounded-xl p-3.5 space-y-3">
                    <div class="flex items-center justify-between border-b border-[#DBEBFB] pb-2">
                        <div>
                            <span class="font-mono font-bold text-xs text-[#2775E4] block">ST-92830</span>
                            <span class="font-manrope font-bold text-xs text-[#171E26]">MedMart Yaba Branch</span>
                        </div>
                        <span class="font-semibold text-xs text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200/50">Pending</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs font-inter">
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px]">Amount</span>
                            <span class="font-manrope font-extrabold text-[#171E26]">₦185,000</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px]">Date</span>
                            <span class="text-[#171E26]/70">Sep 8, 2026</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-[#DBEBFB]">
                        <button type="button" onclick="openDrawer('ST-92830')" class="w-full flex items-center justify-center gap-1 bg-white border border-[#DBEBFB] py-2 rounded-lg text-xs font-semibold text-[#2775E4] shadow-sm">
                            View Settlement <i class="ph ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- Card 3: Failed --}}
                <div class="bg-[#F7FAFD] border border-[#EAF1FB] rounded-xl p-3.5 space-y-3">
                    <div class="flex items-center justify-between border-b border-[#DBEBFB] pb-2">
                        <div>
                            <span class="font-mono font-bold text-xs text-[#2775E4] block">ST-92829</span>
                            <span class="font-manrope font-bold text-xs text-[#171E26]">GreenLife Pharmacy</span>
                        </div>
                        <span class="font-semibold text-xs text-rose-700 bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200/50">Failed</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs font-inter">
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px]">Amount</span>
                            <span class="font-manrope font-extrabold text-[#171E26]">₦95,000</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px]">Date</span>
                            <span class="text-[#171E26]/70">Sep 7, 2026</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-[#DBEBFB]">
                        <button type="button" onclick="openDrawer('ST-92829')" class="w-full flex items-center justify-center gap-1 bg-white border border-[#DBEBFB] py-2 rounded-lg text-xs font-semibold text-[#2775E4] shadow-sm">
                            View Settlement <i class="ph ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

            </div>

            {{-- PAGINATION BAR --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 font-inter text-xs border-t border-[#F3F7FC]">
                <p class="text-[#171E26]/60">Showing 1 to 3 of 152 settlements</p>
                <div class="flex items-center gap-1">
                    <button type="button" class="h-8 px-2 rounded-lg border border-[#DBEBFB] bg-white disabled:opacity-40" disabled><i class="ph ph-caret-left"></i></button>
                    <button type="button" class="h-8 w-8 rounded-lg bg-[#2775E4] text-white font-semibold">1</button>
                    <button type="button" class="h-8 w-8 rounded-lg border border-[#DBEBFB] bg-white">2</button>
                    <button type="button" class="h-8 w-8 rounded-lg border border-[#DBEBFB] bg-white">3</button>
                    <button type="button" class="h-8 px-2 rounded-lg border border-[#DBEBFB] bg-white"><i class="ph ph-caret-right"></i></button>
                </div>
            </div>

        </div>

    </div>

    {{-- SETTLEMENT DETAIL RESPONSIVE SLIDE-OVER DRAWER (VANILLA JS OPERATED) --}}
    <div id="drawer-backdrop" onclick="closeDrawer()" class="fixed inset-0 bg-[#171E26]/40 backdrop-blur-sm z-40 hidden transition-opacity"></div>

    <div id="settlement-drawer" class="fixed right-0 top-0 bottom-0 w-full max-w-lg bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">
        <div class="p-5 md:p-6 space-y-6">
            
            {{-- Drawer Header --}}
            <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-4">
                <div>
                    <span class="font-inter text-xs text-[#171E26]/50">Settlement Details</span>
                    <h2 class="font-manrope font-extrabold text-xl text-[#171E26]" id="drawer-ref">ST-92831</h2>
                </div>
                <button type="button" onclick="closeDrawer()" class="h-8 w-8 rounded-full bg-[#F7FAFD] flex items-center justify-center text-[#171E26]/60 hover:text-[#171E26] transition">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>

            {{-- Dynamic Status & Amount Banner --}}
            <div id="drawer-banner" class="bg-[#E9F3FE] border border-[#DBEBFB] rounded-2xl p-4 md:p-5 space-y-2">
                <div class="flex items-center justify-between">
                    <span id="drawer-status-badge" class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-white px-2.5 py-0.5 rounded-full shadow-sm">
                        ● Settled
                    </span>
                    <span class="font-inter text-xs text-[#171E26]/60" id="drawer-date">Sep 8, 2026 · 14:32</span>
                </div>
                <div>
                    <span class="font-inter text-xs text-[#171E26]/60 block">Disbursed Amount</span>
                    <p class="font-manrope font-extrabold text-3xl text-[#171E26]" id="drawer-amount">₦320,000</p>
                </div>
            </div>

            {{-- Failed Reason State (Hidden by default unless failed) --}}
            <div id="drawer-failure-box" class="hidden bg-rose-50 border border-rose-200 rounded-xl p-4 space-y-1.5">
                <div class="flex items-center gap-1.5 text-rose-700 font-manrope font-bold text-xs">
                    <i class="ph ph-warning-circle text-base"></i>
                    <span>Settlement Failed</span>
                </div>
                <p class="font-inter text-xs text-rose-900/80" id="drawer-failure-reason">
                    Unable to process settlement to destination account. Bank routing code invalid.
                </p>
            </div>

            {{-- Detailed Information List --}}
            <div class="space-y-4">
                <h3 class="font-manrope font-bold text-sm text-[#171E26] border-b border-[#F3F7FC] pb-2">Settlement Information</h3>

                <div class="grid grid-cols-2 gap-4 font-inter text-xs">
                    <div>
                        <span class="text-[#171E26]/50 block text-[11px] font-semibold uppercase tracking-wider">Pharmacy</span>
                        <span class="font-manrope font-bold text-[#2775E4] text-sm" id="drawer-pharmacy">Triple B Pharmacy</span>
                    </div>

                    <div>
                        <span class="text-[#171E26]/50 block text-[11px] font-semibold uppercase tracking-wider">Destination</span>
                        <span class="font-medium text-[#171E26]" id="drawer-destination">GTBank · ****4092</span>
                    </div>

                    <div>
                        <span class="text-[#171E26]/50 block text-[11px] font-semibold uppercase tracking-wider">Gateway Ref</span>
                        <span class="font-mono text-[#171E26]" id="drawer-gateway">PSTK_ST_98120381</span>
                    </div>

                    <div>
                        <span class="text-[#171E26]/50 block text-[11px] font-semibold uppercase tracking-wider">Processed Date</span>
                        <span class="text-[#171E26]/80" id="drawer-processed">Sep 8, 2026 · 14:32</span>
                    </div>
                </div>
            </div>

            {{-- Financial Breakdown Section --}}
            <div class="space-y-3 pt-2">
                <h3 class="font-manrope font-bold text-sm text-[#171E26] border-b border-[#F3F7FC] pb-2">Financial Breakdown</h3>

                <div class="space-y-2.5 font-inter text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-[#171E26]/70">Customer Gross Revenue</span>
                        <span class="font-manrope font-bold text-[#171E26]" id="drawer-gross">₦350,000</span>
                    </div>

                    <div class="flex items-center justify-between text-rose-600">
                        <span>MedMart Platform Fee</span>
                        <span class="font-manrope font-bold" id="drawer-fee">-₦15,000</span>
                    </div>

                    <div class="flex items-center justify-between text-rose-600">
                        <span>Gateway Processing Fee</span>
                        <span class="font-manrope font-bold" id="drawer-other-fee">-₦15,000</span>
                    </div>

                    <div class="pt-3 border-t border-[#F3F7FC] flex items-center justify-between font-semibold">
                        <span class="text-[#171E26]">Net Settlement Disbursed</span>
                        <span class="font-manrope font-extrabold text-[#2775E4] text-sm" id="drawer-net">₦320,000</span>
                    </div>
                </div>
            </div>

            {{-- Drawer Actions --}}
            <div class="pt-4 border-t border-[#F3F7FC] flex items-center gap-2">
                <a href="{{ route('settlementDetails', 'ST-92831') }}" id="drawer-full-link" class="w-full flex items-center justify-center gap-1.5 bg-[#2775E4] hover:bg-[#2775E4]/90 text-white py-2.5 rounded-xl font-inter text-xs font-semibold shadow-sm transition">
                    View Full Statement <i class="ph ph-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>

    {{-- VANILLA JAVASCRIPT CONTROLLERS --}}
    <script>
        // Mock dataset for pure Vanilla JS drawer rendering
        const settlementData = {
            'ST-92831': {
                ref: 'ST-92831',
                pharmacy: 'Triple B Pharmacy',
                amount: '₦320,000',
                status: 'Settled',
                statusClass: 'text-emerald-700 bg-emerald-50 border border-emerald-200',
                date: 'Sep 8, 2026 · 14:32',
                destination: 'GTBank · ****4092',
                gateway: 'PSTK_ST_98120381',
                gross: '₦350,000',
                fee: '-₦15,000',
                otherFee: '-₦15,000',
                net: '₦320,000',
                isFailed: false
            },
            'ST-92830': {
                ref: 'ST-92830',
                pharmacy: 'MedMart Yaba Branch',
                amount: '₦185,000',
                status: 'Pending',
                statusClass: 'text-amber-700 bg-amber-50 border border-amber-200',
                date: 'Sep 8, 2026 · 11:10',
                destination: 'Access Bank · ****1029',
                gateway: 'PSTK_ST_98120112',
                gross: '₦200,000',
                fee: '-₦10,000',
                otherFee: '-₦5,000',
                net: '₦185,000',
                isFailed: false
            },
            'ST-92829': {
                ref: 'ST-92829',
                pharmacy: 'GreenLife Pharmacy',
                amount: '₦95,000',
                status: 'Failed',
                statusClass: 'text-rose-700 bg-rose-50 border border-rose-200',
                date: 'Sep 7, 2026 · 16:45',
                destination: 'Zenith Bank · ****8812',
                gateway: 'PSTK_ST_98119044',
                gross: '₦100,000',
                fee: '-₦3,000',
                otherFee: '-₦2,000',
                net: '₦95,000',
                isFailed: true,
                failureReason: 'Unable to process settlement to destination account. Bank routing code rejected by central clearing gateway.'
            }
        };

        function openDrawer(ref) {
            const data = settlementData[ref];
            if (!data) return;

            document.getElementById('drawer-ref').innerText = data.ref;
            document.getElementById('drawer-amount').innerText = data.amount;
            document.getElementById('drawer-date').innerText = data.date;
            document.getElementById('drawer-pharmacy').innerText = data.pharmacy;
            document.getElementById('drawer-destination').innerText = data.destination;
            document.getElementById('drawer-gateway').innerText = data.gateway;
            document.getElementById('drawer-processed').innerText = data.date;
            document.getElementById('drawer-gross').innerText = data.gross;
            document.getElementById('drawer-fee').innerText = data.fee;
            document.getElementById('drawer-other-fee').innerText = data.otherFee;
            document.getElementById('drawer-net').innerText = data.net;

            // Status Badge
            const badge = document.getElementById('drawer-status-badge');
            badge.innerText = `● ${data.status}`;
            badge.className = `inline-flex items-center gap-1 font-semibold text-xs px-2.5 py-0.5 rounded-full ${data.statusClass}`;

            // Failure State Visibility
            const failBox = document.getElementById('drawer-failure-box');
            if (data.isFailed) {
                failBox.classList.remove('hidden');
                document.getElementById('drawer-failure-reason').innerText = data.failureReason;
            } else {
                failBox.classList.add('hidden');
            }

            // Reveal Drawer
            document.getElementById('drawer-backdrop').classList.remove('hidden');
            document.getElementById('settlement-drawer').classList.remove('translate-x-full');
        }

        function closeDrawer() {
            document.getElementById('settlement-drawer').classList.add('translate-x-full');
            document.getElementById('drawer-backdrop').classList.add('hidden');
        }

        function selectPeriod(period) {
            const periods = ['7D', '30D', '90D', '12M'];
            periods.forEach(p => {
                const btn = document.getElementById(`btn-period-${p.toLowerCase()}`);
                if (p === period) {
                    btn.className = 'px-2.5 py-1.5 rounded-lg bg-[#2775E4] text-white transition';
                } else {
                    btn.className = 'px-2.5 py-1.5 rounded-lg text-[#171E26]/60 hover:text-[#171E26] transition';
                }
            });
        }

        function filterTable() {
            const query = document.getElementById('table-search').value.toLowerCase();
            const status = document.getElementById('status-filter').value.toLowerCase();
            const rows = document.querySelectorAll('#settlements-table tbody tr');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const matchesSearch = text.includes(query);
                const matchesStatus = status === 'all' || text.includes(status);

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function refreshPageData() {
            // Visual feedback indicator for data refresh
            const btn = event.currentTarget;
            btn.classList.add('animate-spin');
            setTimeout(() => {
                btn.classList.remove('animate-spin');
            }, 600);
        }
    </script>

</x-layouts.superadmin>