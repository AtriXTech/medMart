{{-- resources/views/superadmin/dashboard.blade.php --}}
<x-layouts.superAdmin title="dash" active="dash">

    <div class="space-y-8">

        {{-- ================= DASHBOARD HEADER ================= --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                    Good morning, Admin 👋
                </h2>
                <p class="font-inter text-[14px] text-[#171E26]/60 mt-1">
                    Here's what's happening across MedMart today.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <select id="time-range-filter" 
                            class="appearance-none bg-white border border-[#DBEBFB] rounded-xl px-4 py-2.5 pr-9 font-inter text-[13px] font-semibold text-[#171E26] shadow-sm hover:border-[#2775E4] focus:outline-none focus:ring-2 focus:ring-[#2775E4]/20 transition cursor-pointer">
                        <option value="today">Today</option>
                        <option value="7d">Last 7 Days</option>
                        <option value="30d" selected>Last 30 Days</option>
                        <option value="90d">Last 90 Days</option>
                        <option value="12m">Last 12 Months</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/50 pointer-events-none text-xs"></i>
                </div>

                <button type="button" 
                        onclick="refreshDashboardData()" 
                        class="flex items-center gap-2 bg-white border border-[#DBEBFB] rounded-xl px-4 py-2.5 font-inter text-[13px] font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] active:scale-95 transition">
                    <i class="ph ph-arrows-clockwise text-[15px] text-[#2775E4]"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>

        {{-- ================= PLATFORM OVERVIEW (PRIMARY KPIs) ================= --}}
        <div class="space-y-4">
            <p class="font-inter text-[11px] font-bold uppercase tracking-wider text-[#171E26]/40 px-1">
                Platform Overview
            </p>

            {{-- Primary Row: Key Financial & Growth Drivers --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
                
                {{-- Total Revenue (Featured Accent Card) --}}
                <div class="bg-gradient-to-br from-[#2775E4] to-[#08AEBC] rounded-2xl p-5 text-white shadow-sm relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="flex items-center justify-between">
                        <span class="font-inter text-[12px] font-medium text-white/80">Total Revenue</span>
                        <div class="h-8 w-8 rounded-lg bg-white/15 flex items-center justify-center">
                            <i class="ph-fill ph-currency-circle-dollar text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="font-manrope font-extrabold text-2xl md:text-3xl text-white tracking-tight">
                            ₦12.8M
                        </p>
                        <div class="flex items-center gap-1.5 mt-2 font-inter text-[12px] text-white/90">
                            <span class="inline-flex items-center font-semibold bg-white/20 px-1.5 py-0.5 rounded text-[11px]">
                                <i class="ph ph-trend-up mr-1"></i>+14.2%
                            </span>
                            <span class="text-white/70">vs last month</span>
                        </div>
                    </div>
                </div>

                {{-- Total Orders --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="font-inter text-[12px] font-medium text-[#171E26]/60">Total Orders</span>
                        <div class="h-8 w-8 rounded-lg bg-[#E9F3FE] flex items-center justify-center text-[#2775E4]">
                            <i class="ph-fill ph-shopping-bag text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] tracking-tight">
                            8,923
                        </p>
                        <p class="font-inter text-[12px] text-[#171E26]/50 mt-2">
                            <span class="font-semibold text-[#08AEBC]">+412</span> this month
                        </p>
                    </div>
                </div>

                {{-- Total Pharmacies --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="font-inter text-[12px] font-medium text-[#171E26]/60">Total Pharmacies</span>
                        <div class="h-8 w-8 rounded-lg bg-[#DBEBFB] flex items-center justify-center text-[#2775E4]">
                            <i class="ph-fill ph-storefront text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] tracking-tight">
                            128
                        </p>
                        <p class="font-inter text-[12px] text-[#171E26]/50 mt-2">
                            <span class="font-semibold text-[#2775E4]">+8</span> this month
                        </p>
                    </div>
                </div>

                {{-- Total Customers --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="font-inter text-[12px] font-medium text-[#171E26]/60">Total Customers</span>
                        <div class="h-8 w-8 rounded-lg bg-[#E9F3FE] flex items-center justify-center text-[#08AEBC]">
                            <i class="ph-fill ph-users text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] tracking-tight">
                            2,481
                        </p>
                        <p class="font-inter text-[12px] text-[#171E26]/50 mt-2">
                            <span class="font-semibold text-[#08AEBC]">+184</span> this month
                        </p>
                    </div>
                </div>

            </div>

            {{-- Secondary Row: Operational State --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
                
                {{-- Active Pharmacies --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <i class="ph-fill ph-check-circle text-xl"></i>
                        </div>
                        <div>
                            <span class="font-inter text-[12px] font-medium text-[#171E26]/60">Active Pharmacies</span>
                            <p class="font-manrope font-bold text-xl text-[#171E26] mt-0.5">104 <span class="font-inter font-normal text-xs text-[#171E26]/40">/ 128 total</span></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center font-inter text-[12px] font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                            81.2% Active Rate
                        </span>
                    </div>
                </div>

                {{-- Orders Processed --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center flex-shrink-0">
                            <i class="ph-fill ph-[#2775E4] ph-gear text-xl"></i>
                        </div>
                        <div>
                            <span class="font-inter text-[12px] font-medium text-[#171E26]/60">Orders Processed</span>
                            <p class="font-manrope font-bold text-xl text-[#171E26] mt-0.5">7,842 <span class="font-inter font-normal text-xs text-[#171E26]/40">fulfilled</span></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center font-inter text-[12px] font-semibold text-[#2775E4] bg-[#E9F3FE] px-2.5 py-1 rounded-full">
                            87.8% Completion
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================= PLATFORM PERFORMANCE SECTION ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Revenue Overview (Large Chart Card - Spans 2 Columns) --}}
            <div class="lg:col-span-2 bg-white border border-[#EAF1FB] rounded-2xl p-5 md:p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[#F3F7FC]">
                        <div>
                            <h3 class="font-manrope font-bold text-[17px] text-[#171E26]">Revenue Overview</h3>
                            <p class="font-inter text-[12px] text-[#171E26]/50">Gross Revenue stream trends over time</p>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3 font-inter text-[11px] font-semibold">
                                <span class="flex items-center gap-1.5 text-[#171E26]/70">
                                    <span class="h-2.5 w-2.5 rounded-full bg-[#2775E4]"></span> Online Orders
                                </span>
                                <span class="flex items-center gap-1.5 text-[#171E26]/70">
                                    <span class="h-2.5 w-2.5 rounded-full bg-[#08AEBC]"></span> POS Sales
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Visual Area Chart Visual Placeholder --}}
                    <div class="mt-6 h-[220px] w-full relative flex items-end justify-between gap-2 pt-8 pb-2">
                        {{-- Mock Line / Area chart curves using SVG --}}
                        <svg class="absolute inset-0 w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 500 150">
                            <defs>
                                <linearGradient id="revenueGrad1" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#2775E4" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#2775E4" stop-opacity="0.0"/>
                                </linearGradient>
                                <linearGradient id="revenueGrad2" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#08AEBC" stop-opacity="0.20"/>
                                    <stop offset="100%" stop-color="#08AEBC" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>
                            {{-- Area 1 (Online Orders) --}}
                            <path d="M 0,110 Q 75,40 150,70 T 300,30 T 450,60 L 500,20 L 500,150 L 0,150 Z" fill="url(#revenueGrad1)" />
                            <path d="M 0,110 Q 75,40 150,70 T 300,30 T 450,60 L 500,20" fill="none" stroke="#2775E4" stroke-width="3" />

                            {{-- Area 2 (POS Sales) --}}
                            <path d="M 0,130 Q 75,80 150,100 T 300,70 T 450,90 L 500,50 L 500,150 L 0,150 Z" fill="url(#revenueGrad2)" />
                            <path d="M 0,130 Q 75,80 150,100 T 300,70 T 450,90 L 500,50" fill="none" stroke="#08AEBC" stroke-width="2.5" stroke-dasharray="4,4" />
                        </svg>

                        {{-- X-Axis Labels --}}
                        <div class="absolute bottom-0 inset-x-0 flex justify-between font-inter text-[11px] text-[#171E26]/40 pt-2 border-t border-[#F3F7FC]">
                            <span>Week 1</span>
                            <span>Week 2</span>
                            <span>Week 3</span>
                            <span>Week 4</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-[#F3F7FC] flex items-center justify-between font-inter text-[12px] text-[#171E26]/50">
                    <span>Revenue breakdown over time</span>
                    <span class="font-semibold text-[#2775E4]">Dynamic API Stream Active</span>
                </div>
            </div>

            {{-- Orders by Status (Horizontal Bars) --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 md:p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-manrope font-bold text-[17px] text-[#171E26]">Orders by Status</h3>
                    <p class="font-inter text-[12px] text-[#171E26]/50 mt-0.5">Distribution across backend lifecycle</p>

                    <div class="mt-5 space-y-3 font-inter">
                        
                        {{-- Completed --}}
                        <div>
                            <div class="flex justify-between text-[12px] mb-1">
                                <span class="font-medium text-[#171E26]">Completed</span>
                                <span class="font-semibold text-[#171E26]">324</span>
                            </div>
                            <div class="h-2 w-full bg-[#EAF1FB] rounded-full overflow-hidden">
                                <div class="h-full bg-[#08AEBC] rounded-full" style="width: 70%;"></div>
                            </div>
                        </div>

                        {{-- Processing --}}
                        <div>
                            <div class="flex justify-between text-[12px] mb-1">
                                <span class="font-medium text-[#171E26]">Processing</span>
                                <span class="font-semibold text-[#171E26]">148</span>
                            </div>
                            <div class="h-2 w-full bg-[#EAF1FB] rounded-full overflow-hidden">
                                <div class="h-full bg-[#2775E4] rounded-full" style="width: 45%;"></div>
                            </div>
                        </div>

                        {{-- Paid --}}
                        <div>
                            <div class="flex justify-between text-[12px] mb-1">
                                <span class="font-medium text-[#171E26]">Paid</span>
                                <span class="font-semibold text-[#171E26]">96</span>
                            </div>
                            <div class="h-2 w-full bg-[#EAF1FB] rounded-full overflow-hidden">
                                <div class="h-full bg-[#B1D0FB] rounded-full" style="width: 30%;"></div>
                            </div>
                        </div>

                        {{-- Received --}}
                        <div>
                            <div class="flex justify-between text-[12px] mb-1">
                                <span class="font-medium text-[#171E26]">Received</span>
                                <span class="font-semibold text-[#171E26]">51</span>
                            </div>
                            <div class="h-2 w-full bg-[#EAF1FB] rounded-full overflow-hidden">
                                <div class="h-full bg-[#058A98] rounded-full" style="width: 18%;"></div>
                            </div>
                        </div>

                        {{-- Pending Payment --}}
                        <div>
                            <div class="flex justify-between text-[12px] mb-1">
                                <span class="font-medium text-[#171E26]">Pending Payment</span>
                                <span class="font-semibold text-[#171E26]">28</span>
                            </div>
                            <div class="h-2 w-full bg-[#EAF1FB] rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: 12%;"></div>
                            </div>
                        </div>

                        {{-- Ready for Pickup --}}
                        <div>
                            <div class="flex justify-between text-[12px] mb-1">
                                <span class="font-medium text-[#171E26]">Ready for Pickup</span>
                                <span class="font-semibold text-[#171E26]">19</span>
                            </div>
                            <div class="h-2 w-full bg-[#EAF1FB] rounded-full overflow-hidden">
                                <div class="h-full bg-[#2775E4]/60 rounded-full" style="width: 8%;"></div>
                            </div>
                        </div>

                        {{-- Cancelled --}}
                        <div>
                            <div class="flex justify-between text-[12px] mb-1">
                                <span class="font-medium text-[#171E26]">Cancelled</span>
                                <span class="font-semibold text-[#171E26]">13</span>
                            </div>
                            <div class="h-2 w-full bg-[#EAF1FB] rounded-full overflow-hidden">
                                <div class="h-full bg-red-400 rounded-full" style="width: 5%;"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- ================= PLATFORM GROWTH SECTION ================= --}}
        <div class="space-y-4">
            <p class="font-inter text-[11px] font-bold uppercase tracking-wider text-[#171E26]/40 px-1">
                Platform Growth
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Pharmacy Growth --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 md:p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Pharmacy Growth</h3>
                            <p class="font-inter text-[12px] text-[#171E26]/50">New merchant onboardings</p>
                        </div>
                        <span class="font-manrope font-bold text-lg text-[#2775E4] bg-[#E9F3FE] px-3 py-1 rounded-xl">+8 this mo</span>
                    </div>

                    <div class="mt-6 h-[100px] w-full relative">
                        <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 300 80">
                            <path d="M 0,60 C 50,55 100,30 150,40 C 200,50 250,15 300,10" fill="none" stroke="#2775E4" stroke-width="3"/>
                        </svg>
                    </div>
                </div>

                {{-- Customer Growth --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 md:p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Customer Growth</h3>
                            <p class="font-inter text-[12px] text-[#171E26]/50">New user signups</p>
                        </div>
                        <span class="font-manrope font-bold text-lg text-[#08AEBC] bg-[#E9F3FE] px-3 py-1 rounded-xl">+184 this mo</span>
                    </div>

                    <div class="mt-6 h-[100px] w-full relative">
                        <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 300 80">
                            <path d="M 0,70 C 50,60 100,40 150,30 C 200,20 250,25 300,5" fill="none" stroke="#08AEBC" stroke-width="3"/>
                        </svg>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================= OPERATIONAL & ACTIVITY GRID ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Needs Attention Section (2 Columns on Large Screens) --}}
            <div class="lg:col-span-2 bg-white border border-[#EAF1FB] rounded-2xl p-5 md:p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-[#F3F7FC]">
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></div>
                            <h3 class="font-manrope font-bold text-[17px] text-[#171E26]">Needs Attention</h3>
                        </div>
                        <span class="font-inter text-[12px] font-semibold text-amber-700 bg-amber-50 border border-amber-200/60 px-2.5 py-0.5 rounded-full">
                            Action Required
                        </span>
                    </div>

                    <div class="mt-4 space-y-3 font-inter">
                        
                        {{-- Alert Item 1 --}}
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#F7FAFD] border border-[#EAF1FB] hover:border-[#DBEBFB] transition">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                                    <i class="ph-fill ph-warning-circle text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-[13.5px] font-semibold text-[#171E26]">8 pharmacies have incomplete onboarding</p>
                                    <p class="text-[11px] text-[#171E26]/50">KYC verification or store setup pending</p>
                                </div>
                            </div>
                            <a href="/superadmin/pharmacies?filter=incomplete" class="font-inter text-[13px] font-semibold text-[#2775E4] hover:underline flex items-center gap-1">
                                View <i class="ph ph-arrow-right text-xs"></i>
                            </a>
                        </div>

                        {{-- Alert Item 2 --}}
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#F7FAFD] border border-[#EAF1FB] hover:border-[#DBEBFB] transition">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-lg bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                                    <i class="ph-fill ph-bank text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-[13.5px] font-semibold text-[#171E26]">6 settlements require attention</p>
                                    <p class="text-[11px] text-[#171E26]/50">Payout batch failures or account detail mismatches</p>
                                </div>
                            </div>
                            <a href="/superadmin/settlements?filter=attention" class="font-inter text-[13px] font-semibold text-[#2775E4] hover:underline flex items-center gap-1">
                                View <i class="ph ph-arrow-right text-xs"></i>
                            </a>
                        </div>

                        {{-- Alert Item 3 --}}
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#F7FAFD] border border-[#EAF1FB] hover:border-[#DBEBFB] transition">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-lg bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center flex-shrink-0">
                                    <i class="ph-fill ph-crown text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-[13.5px] font-semibold text-[#171E26]">12 subscriptions require attention</p>
                                    <p class="text-[11px] text-[#171E26]/50">Expiring plans or failed auto-renewals</p>
                                </div>
                            </div>
                            <a href="/superadmin/active-subscriptions?filter=attention" class="font-inter text-[13px] font-semibold text-[#2775E4] hover:underline flex items-center gap-1">
                                View <i class="ph ph-arrow-right text-xs"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Recent Platform Activity (1 Column on Large Screens) --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 md:p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-manrope font-bold text-[17px] text-[#171E26] pb-4 border-b border-[#F3F7FC]">
                        Recent Platform Activity
                    </h3>

                    <div class="mt-4 space-y-4 font-inter">
                        
                        {{-- Event 1 --}}
                        <div class="flex items-start gap-3">
                            <div class="h-8 w-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph-fill ph-storefront text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13px] font-semibold text-[#171E26] truncate">New pharmacy registered</p>
                                <p class="text-[12px] text-[#171E26]/60 truncate">GreenLife Pharmacy</p>
                                <span class="text-[10px] text-[#171E26]/40 mt-0.5 block">2 mins ago</span>
                            </div>
                        </div>

                        {{-- Event 2 --}}
                        <div class="flex items-start gap-3">
                            <div class="h-8 w-8 rounded-full bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph-fill ph-user-plus text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13px] font-semibold text-[#171E26] truncate">New customer joined</p>
                                <p class="text-[12px] text-[#171E26]/60 truncate">Garuba Khadijah</p>
                                <span class="text-[10px] text-[#171E26]/40 mt-0.5 block">8 mins ago</span>
                            </div>
                        </div>

                        {{-- Event 3 --}}
                        <div class="flex items-start gap-3">
                            <div class="h-8 w-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph-fill ph-crown text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13px] font-semibold text-[#171E26] truncate">Subscription activated</p>
                                <p class="text-[12px] text-[#171E26]/60 truncate">Pharmacy XYZ (Pro Tier)</p>
                                <span class="text-[10px] text-[#171E26]/40 mt-0.5 block">18 mins ago</span>
                            </div>
                        </div>

                        {{-- Event 4 --}}
                        <div class="flex items-start gap-3">
                            <div class="h-8 w-8 rounded-full bg-[#DBEBFB] text-[#058A98] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph-fill ph-check-circle text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13px] font-semibold text-[#171E26] truncate">Order completed</p>
                                <p class="text-[12px] text-[#171E26]/60 truncate">Order #ORD-8821</p>
                                <span class="text-[10px] text-[#171E26]/40 mt-0.5 block">24 mins ago</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Script slot for dashboard interactivity --}}
    @slot('scripts')
    <script>
        async function refreshDashboardData() {
            try {
                // Future integration point for fetching backend API dynamic data
                console.log('Fetching live platform metrics from backend API...');
            } catch (error) {
                console.error('Failed to load dashboard metrics:', error);
            }
        }
    </script>
    @endslot

</x-layouts.superAdmin>