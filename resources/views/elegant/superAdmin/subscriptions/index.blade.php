<x-layouts.superadmin title="Subscriptions Monitoring" active="subscriptions-all">

    <div class="space-y-6">

        {{-- PAGE HEADER & GLOBAL CONTROLS --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                    Subscriptions
                </h1>
                <p class="font-inter text-xs md:text-sm text-[#171E26]/60 mt-0.5">
                    Monitor pharmacy subscriptions across the MedMart platform.
                </p>
            </div>

            {{-- Date Range & Refresh Toolbar --}}
            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <button type="button" id="date-range-menu-btn" onclick="toggleDateMenu()" class="w-full sm:w-auto inline-flex items-center justify-between gap-2 bg-white border border-[#DBEBFB] text-[#171E26] px-3.5 py-2 rounded-xl font-inter text-xs font-semibold shadow-sm hover:bg-[#F7FAFD] transition">
                        <i class="ph ph-calendar-blank text-[#2775E4] text-base"></i>
                        <span id="selected-date-label">Last 30 Days</span>
                        <i class="ph ph-caret-down text-[#171E26]/50 text-xs"></i>
                    </button>
                    
                    {{-- Dropdown Menu --}}
                    <div id="date-range-dropdown" class="hidden absolute right-0 mt-1 w-48 bg-white border border-[#DBEBFB] rounded-xl shadow-lg z-30 py-1 font-inter text-xs">
                        <button type="button" onclick="selectDateRange('Last 7 Days')" class="w-full text-left px-3.5 py-2 text-[#171E26] hover:bg-[#E9F3FE] hover:text-[#2775E4] transition">Last 7 Days</button>
                        <button type="button" onclick="selectDateRange('Last 30 Days')" class="w-full text-left px-3.5 py-2 text-[#171E26] hover:bg-[#E9F3FE] hover:text-[#2775E4] font-semibold transition">Last 30 Days</button>
                        <button type="button" onclick="selectDateRange('This Quarter')" class="w-full text-left px-3.5 py-2 text-[#171E26] hover:bg-[#E9F3FE] hover:text-[#2775E4] transition">This Quarter</button>
                        <button type="button" onclick="selectDateRange('Year to Date')" class="w-full text-left px-3.5 py-2 text-[#171E26] hover:bg-[#E9F3FE] hover:text-[#2775E4] transition">Year to Date</button>
                    </div>
                </div>

                <button type="button" onclick="refreshData()" class="inline-flex items-center justify-center gap-1.5 bg-white border border-[#DBEBFB] text-[#171E26] px-3.5 py-2 rounded-xl font-inter text-xs font-semibold shadow-sm hover:bg-[#F7FAFD] active:scale-95 transition">
                    <i id="refresh-icon" class="ph ph-arrows-counter-clockwise text-base text-[#08AEBC]"></i>
                    <span class="hidden sm:inline">Refresh</span>
                </button>
            </div>
        </div>

        {{-- KPI SECTION (4 COMPACT METRIC CARDS) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            
            {{-- 1. Total Subscriptions --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Total Subscriptions</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1">184</p>
                    <span class="font-inter text-[11px] text-emerald-600 font-semibold mt-1 inline-block">↑ 12% vs last month</span>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center shrink-0">
                    <i class="ph-fill ph-arrows-merge text-xl"></i>
                </div>
            </div>

            {{-- 2. Active Subscriptions --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Active Subscriptions</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#2775E4] mt-1">171</p>
                    <span class="font-inter text-[11px] text-[#171E26]/50 mt-1 inline-block">92.9% active rate</span>
                </div>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                </div>
            </div>

            {{-- 3. Expiring Soon --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Expiring Soon</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-amber-600 mt-1">8</p>
                    <span class="font-inter text-[11px] text-amber-600 font-semibold mt-1 inline-block">Next 7 days</span>
                </div>
                <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="ph-fill ph-clock text-xl"></i>
                </div>
            </div>

            {{-- 4. Cancelled --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Cancelled</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-rose-600 mt-1">13</p>
                    <span class="font-inter text-[11px] text-[#171E26]/50 mt-1 inline-block">Historical churn</span>
                </div>
                <div class="h-10 w-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <i class="ph-fill ph-x-circle text-xl"></i>
                </div>
            </div>

        </div>

        {{-- SUBSCRIPTION OVERVIEW (ANALYTICS GRID) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            
            {{-- CARD 1: ACTIVE SUBSCRIPTION STATUS DISTRIBUTION --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm flex flex-col justify-between space-y-4">
                <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3">
                    <h2 class="font-manrope font-bold text-base text-[#171E26]">Status Breakdown</h2>
                    <span class="font-inter text-[11px] font-semibold text-[#2775E4] bg-[#E9F3FE] px-2.5 py-0.5 rounded-md">Live Platform Data</span>
                </div>

                {{-- Segmented Visual Representation Bar --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-xs font-inter">
                        <span class="text-[#171E26]/60 font-medium">Distribution ratio</span>
                        <span class="font-manrope font-bold text-[#171E26]">184 Total</span>
                    </div>

                    {{-- Multi-colored Segmented Bar --}}
                    <div class="h-3 w-full bg-slate-100 rounded-full flex overflow-hidden p-0.5 gap-0.5">
                        <div class="bg-[#2775E4] h-full rounded-l-full" style="width: 88%;"></div>
                        <div class="bg-amber-500 h-full" style="width: 5%;"></div>
                        <div class="bg-rose-500 h-full rounded-r-full" style="width: 7%;"></div>
                    </div>
                </div>

                {{-- Legend & Detailed Counts --}}
                <div class="space-y-2 pt-2 border-t border-[#F3F7FC] font-inter text-xs">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#2775E4]"></span>
                            <span class="text-[#171E26]/80">Active</span>
                        </div>
                        <span class="font-manrope font-bold text-[#171E26]">171</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                            <span class="text-[#171E26]/80">Expiring Soon</span>
                        </div>
                        <span class="font-manrope font-bold text-[#171E26]">8</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                            <span class="text-[#171E26]/80">Cancelled</span>
                        </div>
                        <span class="font-manrope font-bold text-[#171E26]">13</span>
                    </div>
                </div>
            </div>

            {{-- CARD 2: SUBSCRIPTION TREND GRAPH --}}
            <div class="lg:col-span-2 bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm flex flex-col justify-between space-y-4">
                <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3">
                    <div>
                        <h2 class="font-manrope font-bold text-base text-[#171E26]">Subscription Trend</h2>
                        <p class="font-inter text-xs text-[#171E26]/50">Activity and renewals timeline over current cycle</p>
                    </div>
                    <div class="flex items-center gap-3 font-inter text-xs">
                        <span class="flex items-center gap-1.5 text-[#171E26]/70"><span class="h-2 w-2 rounded-full bg-[#2775E4]"></span> Active</span>
                        <span class="flex items-center gap-1.5 text-[#171E26]/70"><span class="h-2 w-2 rounded-full bg-[#08AEBC]"></span> Renewals</span>
                    </div>
                </div>

                {{-- SVG Sparkline Vector Representation --}}
                <div class="relative h-36 w-full pt-2">
                    <svg class="w-full h-full overflow-visible" viewBox="0 0 500 120" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="trendGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#2775E4" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="#2775E4" stop-opacity="0.0"/>
                            </linearGradient>
                        </defs>

                        {{-- Background Grid Lines --}}
                        <line x1="0" y1="30" x2="500" y2="30" stroke="#F3F7FC" stroke-dasharray="4" />
                        <line x1="0" y1="70" x2="500" y2="70" stroke="#F3F7FC" stroke-dasharray="4" />
                        <line x1="0" y1="110" x2="500" y2="110" stroke="#F3F7FC" stroke-dasharray="4" />

                        {{-- Area Fill --}}
                        <path d="M 0,90 Q 75,30 150,60 T 300,40 T 420,20 L 500,35 L 500,120 L 0,120 Z" fill="url(#trendGradient)" />

                        {{-- Primary Trend Line --}}
                        <path d="M 0,90 Q 75,30 150,60 T 300,40 T 420,20 L 500,35" fill="none" stroke="#2775E4" stroke-width="3" stroke-linecap="round" />
                        
                        {{-- Secondary Renewal Line --}}
                        <path d="M 0,100 Q 75,50 150,80 T 300,60 T 420,40 L 500,50" fill="none" stroke="#08AEBC" stroke-width="2" stroke-dasharray="3,3" stroke-linecap="round" />

                        {{-- Active Nodes --}}
                        <circle cx="150" cy="60" r="4" fill="#2775E4" stroke="#FFFFFF" stroke-width="2" />
                        <circle cx="300" cy="40" r="4" fill="#2775E4" stroke="#FFFFFF" stroke-width="2" />
                        <circle cx="420" cy="20" r="4" fill="#2775E4" stroke="#FFFFFF" stroke-width="2" />
                    </svg>
                </div>

                {{-- Timeline Labels --}}
                <div class="flex items-center justify-between font-inter text-[11px] text-[#171E26]/50 border-t border-[#F3F7FC] pt-2">
                    <span>Sep 1, 2026</span>
                    <span>Sep 3, 2026</span>
                    <span>Sep 5, 2026</span>
                    <span>Sep 7, 2026</span>
                    <span class="font-semibold text-[#2775E4]">Sep 8, 2026 (Today)</span>
                </div>
            </div>

        </div>

        {{-- ALL SUBSCRIPTIONS MAIN TABLE SECTION --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
            
            {{-- Section Title & Filter Toolbar --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <h2 class="font-manrope font-bold text-lg text-[#171E26]">All Subscriptions</h2>

                {{-- Toolbar Controls --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:items-center gap-2 font-inter text-xs">
                    
                    {{-- Search Input --}}
                    <div class="relative w-full lg:w-64">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#171E26]/40 text-base"></i>
                        <input type="text" id="table-search" onkeyup="filterSubscriptions()" placeholder="Search pharmacy or email..." class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-9 pr-3 py-2 text-[#171E26] placeholder-[#171E26]/40 focus:outline-none focus:border-[#2775E4] transition" />
                    </div>

                    {{-- Plan Filter --}}
                    <select id="filter-plan" onchange="filterSubscriptions()" class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 text-[#171E26] focus:outline-none focus:border-[#2775E4] cursor-pointer">
                        <option value="ALL">All Plans</option>
                        <option value="Standard">Standard Tier</option>
                        <option value="Premium">Premium Tier</option>
                        <option value="Basic">Basic Tier</option>
                    </select>

                    {{-- Status Filter --}}
                    <select id="filter-status" onchange="filterSubscriptions()" class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 text-[#171E26] focus:outline-none focus:border-[#2775E4] cursor-pointer">
                        <option value="ALL">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Expiring">Expiring Soon</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Past Due">Past Due</option>
                    </select>

                    {{-- Billing Interval Filter --}}
                    <select id="filter-interval" onchange="filterSubscriptions()" class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 text-[#171E26] focus:outline-none focus:border-[#2775E4] cursor-pointer">
                        <option value="ALL">All Intervals</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Annual">Annual</option>
                    </select>

                </div>
            </div>

            {{-- DESKTOP DATA TABLE (HIDDEN ON MOBILE) --}}
            <div class="hidden md:block overflow-x-auto rounded-xl border border-[#EAF1FB]">
                <table class="w-full text-left font-inter text-xs">
                    <thead>
                        <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[11px] font-bold text-[#171E26]/60 uppercase tracking-wider">
                            <th class="py-3 px-4">Pharmacy</th>
                            <th class="py-3 px-4">Plan</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Amount</th>
                            <th class="py-3 px-4">Next Billing</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3F7FC]" id="desktop-subscription-rows">
                        
                        {{-- ROW 1: STANDARD ACTIVE --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition subscription-row" data-pharmacy="Pharmacy A" data-plan="Standard" data-status="Active" data-interval="Monthly">
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">
                                Pharmacy A
                                <span class="block font-inter text-[11px] font-normal text-[#171E26]/50">pharmacyA@medmart.ng</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#2775E4]">Standard</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 font-semibold text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">₦9,500 <span class="font-inter text-[11px] font-normal text-[#171E26]/50">/ mo</span></td>
                            <td class="py-3.5 px-4 text-[#171E26]/80 font-medium">Oct 8, 2026</td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openSubscriptionDrawer('Pharmacy A', 'Standard', '₦9,500 / month', 'Active', 'Sep 8, 2026', 'Oct 8, 2026', '₦19,000')" class="inline-flex items-center gap-1 bg-[#E9F3FE] text-[#2775E4] hover:bg-[#2775E4] hover:text-white px-3 py-1.5 rounded-lg font-semibold transition">
                                    <span>View</span> <i class="ph ph-caret-right"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- ROW 2: PREMIUM ACTIVE --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition subscription-row" data-pharmacy="Pharmacy B" data-plan="Premium" data-status="Active" data-interval="Monthly">
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">
                                Pharmacy B
                                <span class="block font-inter text-[11px] font-normal text-[#171E26]/50">pharmacyB@medmart.ng</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#08AEBC]">Premium</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 font-semibold text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">₦15,000 <span class="font-inter text-[11px] font-normal text-[#171E26]/50">/ mo</span></td>
                            <td class="py-3.5 px-4 text-[#171E26]/80 font-medium">Oct 5, 2026</td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openSubscriptionDrawer('Pharmacy B', 'Premium', '₦15,000 / month', 'Active', 'Aug 5, 2026', 'Oct 5, 2026', '₦30,000')" class="inline-flex items-center gap-1 bg-[#E9F3FE] text-[#2775E4] hover:bg-[#2775E4] hover:text-white px-3 py-1.5 rounded-lg font-semibold transition">
                                    <span>View</span> <i class="ph ph-caret-right"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- ROW 3: STANDARD EXPIRING SOON --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition subscription-row" data-pharmacy="Pharmacy C" data-plan="Standard" data-status="Expiring" data-interval="Monthly">
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">
                                Pharmacy C
                                <span class="block font-inter text-[11px] font-normal text-[#171E26]/50">pharmacyC@medmart.ng</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#2775E4]">Standard</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 font-semibold text-[11px] text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Expiring in 4 days
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">₦9,500 <span class="font-inter text-[11px] font-normal text-[#171E26]/50">/ mo</span></td>
                            <td class="py-3.5 px-4 text-amber-700 font-semibold">Sep 12, 2026</td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openSubscriptionDrawer('Pharmacy C', 'Standard', '₦9,500 / month', 'Expiring', 'Aug 12, 2026', 'Sep 12, 2026', '₦9,500')" class="inline-flex items-center gap-1 bg-[#E9F3FE] text-[#2775E4] hover:bg-[#2775E4] hover:text-white px-3 py-1.5 rounded-lg font-semibold transition">
                                    <span>View</span> <i class="ph ph-caret-right"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- ROW 4: CANCELLED --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition subscription-row" data-pharmacy="Kano Health Hub" data-plan="Basic" data-status="Cancelled" data-interval="Monthly">
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]/70">
                                Kano Health Hub
                                <span class="block font-inter text-[11px] font-normal text-[#171E26]/40">contact@kanohealth.ng</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#171E26]/60">Basic</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 font-semibold text-[11px] text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Cancelled
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]/60">₦5,000 <span class="font-inter text-[11px] font-normal text-[#171E26]/40">/ mo</span></td>
                            <td class="py-3.5 px-4 text-[#171E26]/40 font-medium">—</td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openSubscriptionDrawer('Kano Health Hub', 'Basic', '₦5,000 / month', 'Cancelled', 'Jan 10, 2026', 'N/A', '₦35,000')" class="inline-flex items-center gap-1 bg-slate-100 text-[#171E26]/70 hover:bg-slate-200 px-3 py-1.5 rounded-lg font-semibold transition">
                                    <span>View</span> <i class="ph ph-caret-right"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- MOBILE SUBSCRIPTION CARD LIST (SHOWS ON 320–767px) --}}
            <div class="block md:hidden space-y-3" id="mobile-subscription-cards">
                
                {{-- MOBILE CARD 1 --}}
                <div class="bg-[#F7FAFD]/60 border border-[#EAF1FB] rounded-xl p-4 space-y-3 mobile-card" data-pharmacy="Pharmacy A" data-plan="Standard" data-status="Active">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-manrope font-extrabold text-base text-[#171E26]">Pharmacy A</h3>
                            <span class="font-inter text-xs font-semibold text-[#2775E4]">Standard Tier</span>
                        </div>
                        <span class="inline-flex items-center gap-1 font-semibold text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                            ● Active
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-y border-[#F3F7FC] py-2.5 font-inter text-xs">
                        <div>
                            <span class="text-[#171E26]/50 block">Pricing</span>
                            <span class="font-manrope font-bold text-[#171E26]">₦9,500 / mo</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block">Next Billing</span>
                            <span class="font-semibold text-[#171E26]">Oct 8, 2026</span>
                        </div>
                    </div>

                    <button type="button" onclick="openSubscriptionDrawer('Pharmacy A', 'Standard', '₦9,500 / month', 'Active', 'Sep 8, 2026', 'Oct 8, 2026', '₦19,000')" class="w-full bg-[#E9F3FE] text-[#2775E4] py-2 rounded-xl font-inter text-xs font-semibold text-center transition">
                        View Subscription
                    </button>
                </div>

                {{-- MOBILE CARD 2 --}}
                <div class="bg-[#F7FAFD]/60 border border-[#EAF1FB] rounded-xl p-4 space-y-3 mobile-card" data-pharmacy="Pharmacy B" data-plan="Premium" data-status="Active">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-manrope font-extrabold text-base text-[#171E26]">Pharmacy B</h3>
                            <span class="font-inter text-xs font-semibold text-[#08AEBC]">Premium Tier</span>
                        </div>
                        <span class="inline-flex items-center gap-1 font-semibold text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                            ● Active
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-y border-[#F3F7FC] py-2.5 font-inter text-xs">
                        <div>
                            <span class="text-[#171E26]/50 block">Pricing</span>
                            <span class="font-manrope font-bold text-[#171E26]">₦15,000 / mo</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block">Next Billing</span>
                            <span class="font-semibold text-[#171E26]">Oct 5, 2026</span>
                        </div>
                    </div>

                    <button type="button" onclick="openSubscriptionDrawer('Pharmacy B', 'Premium', '₦15,000 / month', 'Active', 'Aug 5, 2026', 'Oct 5, 2026', '₦30,000')" class="w-full bg-[#E9F3FE] text-[#2775E4] py-2 rounded-xl font-inter text-xs font-semibold text-center transition">
                        View Subscription
                    </button>
                </div>

                {{-- MOBILE CARD 3 --}}
                <div class="bg-[#F7FAFD]/60 border border-[#EAF1FB] rounded-xl p-4 space-y-3 mobile-card" data-pharmacy="Pharmacy C" data-plan="Standard" data-status="Expiring">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-manrope font-extrabold text-base text-[#171E26]">Pharmacy C</h3>
                            <span class="font-inter text-xs font-semibold text-[#2775E4]">Standard Tier</span>
                        </div>
                        <span class="inline-flex items-center gap-1 font-semibold text-[10px] text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                            ● Expiring
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-y border-[#F3F7FC] py-2.5 font-inter text-xs">
                        <div>
                            <span class="text-[#171E26]/50 block">Pricing</span>
                            <span class="font-manrope font-bold text-[#171E26]">₦9,500 / mo</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block">Next Billing</span>
                            <span class="font-semibold text-amber-700">Sep 12, 2026</span>
                        </div>
                    </div>

                    <button type="button" onclick="openSubscriptionDrawer('Pharmacy C', 'Standard', '₦9,500 / month', 'Expiring', 'Aug 12, 2026', 'Sep 12, 2026', '₦9,500')" class="w-full bg-[#E9F3FE] text-[#2775E4] py-2 rounded-xl font-inter text-xs font-semibold text-center transition">
                        View Subscription
                    </button>
                </div>

            </div>

            {{-- EMPTY SEARCH STATE (HIDDEN BY DEFAULT) --}}
            <div id="empty-state" class="hidden py-12 text-center space-y-3">
                <div class="h-12 w-12 rounded-full bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center mx-auto">
                    <i class="ph ph-magnifying-glass text-2xl"></i>
                </div>
                <h3 class="font-manrope font-bold text-base text-[#171E26]">No subscriptions found</h3>
                <p class="font-inter text-xs text-[#171E26]/60 max-w-sm mx-auto">No pharmacy subscriptions match your active filter or search query.</p>
                <button type="button" onclick="resetFilters()" class="inline-flex items-center gap-1.5 bg-[#E9F3FE] text-[#2775E4] px-4 py-2 rounded-xl font-inter text-xs font-semibold hover:bg-[#2775E4] hover:text-white transition">
                    Reset Filters
                </button>
            </div>

            {{-- PAGINATION CONTROLS --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-[#F3F7FC] font-inter text-xs">
                <span class="text-[#171E26]/60">Showing <strong class="text-[#171E26]">1–4</strong> of <strong class="text-[#171E26]">184</strong> subscriptions</span>

                <div class="flex items-center gap-1">
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26]/50 cursor-not-allowed" disabled>Prev</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg bg-[#2775E4] text-white font-semibold">1</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26] hover:bg-[#F7FAFD] transition">2</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26] hover:bg-[#F7FAFD] transition">3</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26] hover:bg-[#F7FAFD] transition">Next</button>
                </div>
            </div>

        </div>

    </div>

    {{-- DETAILED SUBSCRIPTION QUICK-VIEW DRAWER (VANILLA JS OPERATED) --}}
    <div id="drawer-backdrop" onclick="closeSubscriptionDrawer()" class="fixed inset-0 bg-[#171E26]/40 backdrop-blur-sm z-40 hidden transition-opacity"></div>

    <div id="sub-drawer" class="fixed right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">
        <div class="p-5 md:p-6 space-y-6">
            
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-4">
                <div>
                    <span class="font-inter text-xs text-[#171E26]/50">Subscription Overview</span>
                    <h2 class="font-manrope font-extrabold text-xl text-[#171E26]" id="drawer-pharmacy">Pharmacy A</h2>
                </div>
                <button type="button" onclick="closeSubscriptionDrawer()" class="h-8 w-8 rounded-full bg-[#F7FAFD] flex items-center justify-center text-[#171E26]/60 hover:text-[#171E26] transition">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>

            {{-- Quick Summary Cards --}}
            <div class="grid grid-cols-3 gap-2 bg-[#E9F3FE]/60 border border-[#DBEBFB] rounded-2xl p-4 text-center font-inter">
                <div>
                    <span class="text-[11px] text-[#171E26]/60 block">Started</span>
                    <p class="font-manrope font-extrabold text-xs text-[#171E26]" id="drawer-started">Sep 8, 2026</p>
                </div>
                <div class="border-x border-[#DBEBFB]">
                    <span class="text-[11px] text-[#171E26]/60 block">Next Billing</span>
                    <p class="font-manrope font-extrabold text-xs text-[#2775E4]" id="drawer-next">Oct 8, 2026</p>
                </div>
                <div>
                    <span class="text-[11px] text-[#171E26]/60 block">Total Paid</span>
                    <p class="font-manrope font-extrabold text-xs text-emerald-700" id="drawer-paid">₦19,000</p>
                </div>
            </div>

            {{-- Detailed Information List --}}
            <div class="space-y-3 font-inter text-xs">
                <span class="font-manrope font-bold text-sm text-[#171E26] block border-b border-[#F3F7FC] pb-2">Subscription Information</span>
                
                <div class="flex items-center justify-between py-1 border-b border-[#F3F7FC]">
                    <span class="text-[#171E26]/60">Plan Tier</span>
                    <span class="font-semibold text-[#2775E4]" id="drawer-plan">Standard</span>
                </div>

                <div class="flex items-center justify-between py-1 border-b border-[#F3F7FC]">
                    <span class="text-[#171E26]/60">Pricing</span>
                    <span class="font-manrope font-bold text-[#171E26]" id="drawer-price">₦9,500 / month</span>
                </div>

                <div class="flex items-center justify-between py-1 border-b border-[#F3F7FC]">
                    <span class="text-[#171E26]/60">Status</span>
                    <span class="font-semibold text-emerald-700" id="drawer-status">Active</span>
                </div>

                <div class="flex items-center justify-between py-1 border-b border-[#F3F7FC]">
                    <span class="text-[#171E26]/60">Billing Interval</span>
                    <span class="font-semibold text-[#171E26]">Monthly</span>
                </div>
            </div>

            {{-- Payment History Snapshot --}}
            <div class="space-y-3 font-inter text-xs">
                <span class="font-manrope font-bold text-sm text-[#171E26] block border-b border-[#F3F7FC] pb-2">Recent Payment History</span>
                
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-[#F7FAFD] border border-[#EAF1FB]">
                        <div>
                            <span class="font-manrope font-bold text-[#171E26] block">SUB-93821</span>
                            <span class="text-[10px] text-[#171E26]/50">Sep 8, 2026 · Card Payment</span>
                        </div>
                        <div class="text-right">
                            <span class="font-manrope font-bold text-[#171E26] block">₦9,500</span>
                            <span class="text-[10px] font-semibold text-emerald-600">Successful</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-[#F7FAFD] border border-[#EAF1FB]">
                        <div>
                            <span class="font-manrope font-bold text-[#171E26] block">SUB-92018</span>
                            <span class="text-[10px] text-[#171E26]/50">Aug 8, 2026 · Bank Transfer</span>
                        </div>
                        <div class="text-right">
                            <span class="font-manrope font-bold text-[#171E26] block">₦9,500</span>
                            <span class="text-[10px] font-semibold text-emerald-600">Successful</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Drawer Footer Actions --}}
            <div class="pt-4 border-t border-[#F3F7FC]">
                <a href="{{ route('subscriptionShow', 'pharmacy-a') }}" class="w-full flex items-center justify-center gap-1.5 bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white py-2.5 rounded-xl font-inter text-xs font-semibold shadow-sm hover:opacity-95 transition">
                    View Full Subscription Record <i class="ph ph-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>

    {{-- VANILLA JS CONTROLLER --}}
    <script>
        function toggleDateMenu() {
            document.getElementById('date-range-dropdown').classList.toggle('hidden');
        }

        function selectDateRange(label) {
            document.getElementById('selected-date-label').innerText = label;
            document.getElementById('date-range-dropdown').classList.add('hidden');
        }

        function openSubscriptionDrawer(pharmacy, plan, price, status, started, next, paid) {
            document.getElementById('drawer-pharmacy').innerText = pharmacy;
            document.getElementById('drawer-plan').innerText = plan;
            document.getElementById('drawer-price').innerText = price;
            document.getElementById('drawer-status').innerText = status;
            document.getElementById('drawer-started').innerText = started;
            document.getElementById('drawer-next').innerText = next;
            document.getElementById('drawer-paid').innerText = paid;

            document.getElementById('drawer-backdrop').classList.remove('hidden');
            document.getElementById('sub-drawer').classList.remove('translate-x-full');
        }

        function closeSubscriptionDrawer() {
            document.getElementById('sub-drawer').classList.add('translate-x-full');
            document.getElementById('drawer-backdrop').classList.add('hidden');
        }

        function filterSubscriptions() {
            const searchQuery = document.getElementById('table-search').value.toLowerCase();
            const planFilter = document.getElementById('filter-plan').value;
            const statusFilter = document.getElementById('filter-status').value;

            const rows = document.querySelectorAll('.subscription-row');
            const cards = document.querySelectorAll('.mobile-card');
            let visibleCount = 0;

            rows.forEach(row => {
                const pharmacy = row.getAttribute('data-pharmacy').toLowerCase();
                const plan = row.getAttribute('data-plan');
                const status = row.getAttribute('data-status');

                const matchesSearch = pharmacy.includes(searchQuery);
                const matchesPlan = (planFilter === 'ALL' || plan === planFilter);
                const matchesStatus = (statusFilter === 'ALL' || status === statusFilter);

                if (matchesSearch && matchesPlan && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            cards.forEach(card => {
                const pharmacy = card.getAttribute('data-pharmacy').toLowerCase();
                const plan = card.getAttribute('data-plan');
                const status = card.getAttribute('data-status');

                const matchesSearch = pharmacy.includes(searchQuery);
                const matchesPlan = (planFilter === 'ALL' || plan === planFilter);
                const matchesStatus = (statusFilter === 'ALL' || status === statusFilter);

                if (matchesSearch && matchesPlan && matchesStatus) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            const emptyState = document.getElementById('empty-state');
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        function resetFilters() {
            document.getElementById('table-search').value = '';
            document.getElementById('filter-plan').value = 'ALL';
            document.getElementById('filter-status').value = 'ALL';
            document.getElementById('filter-interval').value = 'ALL';
            filterSubscriptions();
        }

        function refreshData() {
            const icon = document.getElementById('refresh-icon');
            icon.classList.add('animate-spin');
            setTimeout(() => {
                icon.classList.remove('animate-spin');
            }, 750);
        }
    </script>

</x-layouts.superadmin>