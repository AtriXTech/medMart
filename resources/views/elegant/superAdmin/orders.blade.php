<x-layouts.superadmin title="Orders Intelligence" active="orders">

    <div x-data="{ 
        period: '30D',
        searchQuery: '',
        statusFilter: 'all',
        viewState: 'loaded' 
    }" class="space-y-6">

        {{-- PAGE HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                    Orders
                </h1>
                <p class="font-inter text-xs md:text-sm text-[#171E26]/60 mt-0.5">
                    Monitor order activity and processing performance across the MedMart platform.
                </p>
            </div>

            <div class="flex items-center gap-2.5 self-start sm:self-auto">
                <div class="inline-flex items-center bg-white border border-[#DBEBFB] rounded-xl p-1 shadow-sm font-inter text-xs font-semibold">
                    <button @click="period = '7D'" :class="period === '7D' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2.5 py-1.5 rounded-lg transition">7D</button>
                    <button @click="period = '30D'" :class="period === '30D' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2.5 py-1.5 rounded-lg transition">30D</button>
                    <button @click="period = '90D'" :class="period === '90D' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2.5 py-1.5 rounded-lg transition">90D</button>
                    <button @click="period = '12M'" :class="period === '12M' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2.5 py-1.5 rounded-lg transition">12M</button>
                </div>

                <button type="button" class="flex items-center justify-center gap-2 bg-white border border-[#DBEBFB] rounded-xl px-3.5 py-2 font-inter text-xs font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] active:scale-95 transition">
                    <i class="ph ph-arrows-clockwise text-sm text-[#2775E4]"></i>
                    <span class="hidden md:inline">Refresh</span>
                </button>
            </div>
        </div>

        {{-- KPI SUMMARY GRID --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
            {{-- Total Orders --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Total Orders</span>
                    <div class="h-8 w-8 rounded-lg bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center">
                        <i class="ph-fill ph-shopping-cart text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-2xl text-[#171E26] tracking-tight">14,892</p>
                    <span class="inline-flex items-center gap-1 font-inter text-[11px] font-semibold text-emerald-600 mt-0.5">
                        <i class="ph ph-trend-up"></i> +12.4%
                    </span>
                </div>
            </div>

            {{-- Pending Orders --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Pending</span>
                    <div class="h-8 w-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="ph-fill ph-clock text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-2xl text-amber-600 tracking-tight">342</p>
                    <span class="font-inter text-[11px] text-[#171E26]/50 mt-0.5 block">Awaiting pickup/prep</span>
                </div>
            </div>

            {{-- Processed Orders --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Processed</span>
                    <div class="h-8 w-8 rounded-lg bg-[#E9F3FE] text-[#08AEBC] flex items-center justify-center">
                        <i class="ph-fill ph-gear text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-2xl text-[#08AEBC] tracking-tight">13,950</p>
                    <span class="inline-flex items-center gap-1 font-inter text-[11px] font-semibold text-emerald-600 mt-0.5">
                        <i class="ph ph-trend-up"></i> +8.1%
                    </span>
                </div>
            </div>

            {{-- Completed Orders --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Completed</span>
                    <div class="h-8 w-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph-fill ph-check-circle text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-2xl text-emerald-600 tracking-tight">13,410</p>
                    <span class="inline-flex items-center gap-1 font-inter text-[11px] font-semibold text-emerald-600 mt-0.5">
                        <i class="ph ph-trend-up"></i> +9.5%
                    </span>
                </div>
            </div>

            {{-- Cancelled Orders --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Cancelled</span>
                    <div class="h-8 w-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="ph-fill ph-x-circle text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-2xl text-rose-600 tracking-tight">600</p>
                    <span class="inline-flex items-center gap-1 font-inter text-[11px] font-semibold text-rose-600 mt-0.5">
                        <i class="ph ph-trend-down"></i> -1.2%
                    </span>
                </div>
            </div>

            {{-- Completion Rate --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Completion Rate</span>
                    <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="ph-fill ph-chart-pie text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-2xl text-[#171E26] tracking-tight">90.1%</p>
                    <span class="inline-flex items-center gap-1 font-inter text-[11px] font-semibold text-emerald-600 mt-0.5">
                        <i class="ph ph-trend-up"></i> +0.4%
                    </span>
                </div>
            </div>
        </div>

        {{-- ORDER PERFORMANCE CHARTS SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            {{-- Orders Overview Line/Area Chart --}}
            <div class="lg:col-span-2 bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-[#F3F7FC] pb-3.5 mb-4">
                    <div>
                        <h3 class="font-manrope font-bold text-base text-[#171E26]">Orders Overview</h3>
                        <p class="font-inter text-xs text-[#171E26]/50">Order volume trends and processing trajectory over time</p>
                    </div>

                    {{-- Chart Legend --}}
                    <div class="flex items-center gap-3 font-inter text-[11px] font-medium text-[#171E26]/70 flex-wrap">
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-[#2775E4]"></span> Received</span>
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-[#08AEBC]"></span> Processed</span>
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Completed</span>
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span> Cancelled</span>
                    </div>
                </div>

                {{-- Chart Area SVG Representation --}}
                <div class="relative h-56 w-full pt-2">
                    <svg class="w-full h-full overflow-visible" viewBox="0 0 600 180" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="orderReceivedGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#2775E4" stop-opacity="0.2" />
                                <stop offset="100%" stop-color="#2775E4" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        {{-- Grid lines --}}
                        <line x1="0" y1="30" x2="600" y2="30" stroke="#F3F7FC" stroke-width="1" />
                        <line x1="0" y1="80" x2="600" y2="80" stroke="#F3F7FC" stroke-width="1" />
                        <line x1="0" y1="130" x2="600" y2="130" stroke="#F3F7FC" stroke-width="1" />

                        {{-- Area fill --}}
                        <path d="M 0,140 Q 150,90 300,50 T 600,20 L 600,180 L 0,180 Z" fill="url(#orderReceivedGrad)" />

                        {{-- Orders Received Line (Blue) --}}
                        <path d="M 0,140 Q 150,90 300,50 T 600,20" fill="none" stroke="#2775E4" stroke-width="2.5" />

                        {{-- Orders Processed Line (Teal) --}}
                        <path d="M 0,148 Q 150,98 300,58 T 600,28" fill="none" stroke="#08AEBC" stroke-width="2" stroke-dasharray="4 2" />

                        {{-- Orders Completed Line (Green) --}}
                        <path d="M 0,152 Q 150,105 300,65 T 600,35" fill="none" stroke="#10B981" stroke-width="2" />

                        {{-- Orders Cancelled Line (Rose) --}}
                        <path d="M 0,172 Q 150,168 300,165 T 600,160" fill="none" stroke="#F43F5E" stroke-width="1.5" />
                    </svg>
                </div>

                <div class="flex items-center justify-between border-t border-[#F3F7FC] pt-3 mt-2 font-inter text-[11px] text-[#171E26]/50">
                    <span>Week 1</span>
                    <span>Week 2</span>
                    <span>Week 3</span>
                    <span>Week 4</span>
                </div>
            </div>

            {{-- Orders by Status Horizontal Bars --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="border-b border-[#F3F7FC] pb-3 mb-4">
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Orders by Status</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Distribution across active order lifecycle</p>
                </div>

                <div class="space-y-3.5 font-inter text-xs">
                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span>Pending</span>
                            <span class="font-semibold">342 (2.3%)</span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: 12%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span>Paid</span>
                            <span class="font-semibold">1,120 (7.5%)</span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-[#2775E4] h-2 rounded-full" style="width: 25%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span>Processing</span>
                            <span class="font-semibold">2,840 (19.1%)</span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-[#08AEBC] h-2 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span>Ready for Pickup</span>
                            <span class="font-semibold">580 (3.9%)</span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full" style="width: 18%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span>Completed</span>
                            <span class="font-semibold">13,410 (90.1%)</span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: 90%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span>Cancelled</span>
                            <span class="font-semibold">600 (4.0%)</span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-rose-500 h-2 rounded-full" style="width: 15%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- NEEDS ATTENTION (PLATFORM LEVEL ISSUES) --}}
        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <i class="ph ph-warning-circle text-amber-500 text-lg"></i>
                <h2 class="font-manrope font-bold text-base text-[#171E26]">Needs Attention</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- High Cancellation Pharmacies --}}
                <div class="bg-white border border-[#EAF1FB] border-l-4 border-l-rose-500 rounded-2xl p-4 shadow-sm space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-manrope font-bold text-sm text-[#171E26]">High Cancellations</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-50 text-rose-600">2 Pharmacies</span>
                    </div>
                    <p class="font-inter text-xs text-[#171E26]/60">Pharmacies exceeding 8% cancellation rate this month.</p>
                    <div class="pt-2 border-t border-[#F3F7FC] flex justify-between items-center text-xs font-semibold">
                        <span class="text-[#171E26]">GreenLife Pharmacy</span>
                        <span class="text-rose-600">11.2% rate</span>
                    </div>
                </div>

                {{-- Processing Delays --}}
                <div class="bg-white border border-[#EAF1FB] border-l-4 border-l-amber-500 rounded-2xl p-4 shadow-sm space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-manrope font-bold text-sm text-[#171E26]">Processing Delays</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-50 text-amber-600">1 Pharmacy</span>
                    </div>
                    <p class="font-inter text-xs text-[#171E26]/60">Exceeding platform SLA (>45 mins average prep time).</p>
                    <div class="pt-2 border-t border-[#F3F7FC] flex justify-between items-center text-xs font-semibold">
                        <span class="text-[#171E26]">MedMart Yaba</span>
                        <span class="text-amber-600">58 min avg</span>
                    </div>
                </div>

                {{-- Order Backlog --}}
                <div class="bg-white border border-[#EAF1FB] border-l-4 border-l-indigo-500 rounded-2xl p-4 shadow-sm space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-manrope font-bold text-sm text-[#171E26]">Order Backlog</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-indigo-50 text-indigo-600">1 Pharmacy</span>
                    </div>
                    <p class="font-inter text-xs text-[#171E26]/60">Unusually high count of pending orders awaiting review.</p>
                    <div class="pt-2 border-t border-[#F3F7FC] flex justify-between items-center text-xs font-semibold">
                        <span class="text-[#171E26]">HealthPlus VI</span>
                        <span class="text-indigo-600">84 pending</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- PHARMACY ORDER PERFORMANCE TABLE --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl shadow-sm overflow-hidden space-y-4 p-4 md:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#F3F7FC] pb-4">
                <div>
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Pharmacy Order Performance</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Aggregate breakdown of order volume and efficiency per registered pharmacy</p>
                </div>

                {{-- Search & Filters --}}
                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    <div class="relative w-full sm:w-64">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#171E26]/40 text-xs"></i>
                        <input type="text" 
                               x-model="searchQuery" 
                               placeholder="Search pharmacy..." 
                               class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-9 pr-3 py-2 font-inter text-xs text-[#171E26] focus:outline-none focus:border-[#2775E4] transition" />
                    </div>

                    <select x-model="statusFilter" class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 font-inter text-xs font-semibold text-[#171E26] focus:outline-none cursor-pointer">
                        <option value="all">Performance: All</option>
                        <option value="high">High (>90%)</option>
                        <option value="low">Low (&lt;85%)</option>
                    </select>
                </div>
            </div>

            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse font-inter text-xs md:text-sm">
                    <thead>
                        <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[11px] font-bold text-[#171E26]/50 uppercase tracking-wider">
                            <th class="py-3 px-4">Pharmacy</th>
                            <th class="py-3 px-4 text-right">Orders Received</th>
                            <th class="py-3 px-4 text-right">Processed</th>
                            <th class="py-3 px-4 text-right">Completed</th>
                            <th class="py-3 px-4 text-right">Cancelled</th>
                            <th class="py-3 px-4 text-center">Completion Rate</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3F7FC]">
                        {{-- Row 1 --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">Triple B Pharmacy</td>
                            <td class="py-3.5 px-4 text-right font-medium text-[#171E26]">1,240</td>
                            <td class="py-3.5 px-4 text-right font-medium text-[#171E26]">1,180</td>
                            <td class="py-3.5 px-4 text-right font-medium text-[#171E26]">1,130</td>
                            <td class="py-3.5 px-4 text-right font-medium text-rose-600">20</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">91.1%</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('orderDetails', 1) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View Pharmacy <i class="ph ph-arrow-right"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 2 --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">Pharmacy B (GreenLife)</td>
                            <td class="py-3.5 px-4 text-right font-medium text-[#171E26]">980</td>
                            <td class="py-3.5 px-4 text-right font-medium text-[#171E26]">920</td>
                            <td class="py-3.5 px-4 text-right font-medium text-[#171E26]">870</td>
                            <td class="py-3.5 px-4 text-right font-medium text-rose-600">15</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">88.8%</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('orderDetails', 2) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View Pharmacy <i class="ph ph-arrow-right"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 3 --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-manrope font-bold text-[#171E26]">MedMart Yaba Branch</td>
                            <td class="py-3.5 px-4 text-right font-medium text-[#171E26]">2,150</td>
                            <td class="py-3.5 px-4 text-right font-medium text-[#171E26]">2,010</td>
                            <td class="py-3.5 px-4 text-right font-medium text-[#171E26]">1,980</td>
                            <td class="py-3.5 px-4 text-right font-medium text-rose-600">30</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">92.0%</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('orderDetails', 3) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View Pharmacy <i class="ph ph-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div class="block md:hidden space-y-3">
                <div class="bg-[#F7FAFD] border border-[#EAF1FB] rounded-xl p-3.5 space-y-3">
                    <div class="flex items-center justify-between border-b border-[#DBEBFB] pb-2">
                        <span class="font-manrope font-bold text-sm text-[#171E26]">Triple B Pharmacy</span>
                        <span class="font-semibold text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">91.1% Rate</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs font-inter">
                        <div><span class="text-[#171E26]/50 block">Received</span><span class="font-semibold">1,240</span></div>
                        <div><span class="text-[#171E26]/50 block">Processed</span><span class="font-semibold">1,180</span></div>
                        <div><span class="text-[#171E26]/50 block">Completed</span><span class="font-semibold text-emerald-600">1,130</span></div>
                        <div><span class="text-[#171E26]/50 block">Cancelled</span><span class="font-semibold text-rose-600">20</span></div>
                    </div>

                    <div class="pt-2 border-t border-[#DBEBFB]">
                        <a href="{{ route('orderDetails', 1) }}" class="w-full flex items-center justify-center gap-1 bg-white border border-[#DBEBFB] py-2 rounded-lg text-xs font-semibold text-[#2775E4]">
                            View Pharmacy <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Pagination Bar --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 font-inter text-xs border-t border-[#F3F7FC]">
                <p class="text-[#171E26]/60">Showing 1 to 3 of 42 pharmacies</p>
                <div class="flex items-center gap-1">
                    <button class="h-8 px-2 rounded-lg border border-[#DBEBFB] bg-white disabled:opacity-40" disabled><i class="ph ph-caret-left"></i></button>
                    <button class="h-8 w-8 rounded-lg bg-[#2775E4] text-white font-semibold">1</button>
                    <button class="h-8 w-8 rounded-lg border border-[#DBEBFB] bg-white">2</button>
                    <button class="h-8 px-2 rounded-lg border border-[#DBEBFB] bg-white"><i class="ph ph-caret-right"></i></button>
                </div>
            </div>
        </div>

        {{-- ORDER TRENDS & PROCESSING PERFORMANCE --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Daily Order Volume Trend --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-3">
                <div class="border-b border-[#F3F7FC] pb-2.5">
                    <h3 class="font-manrope font-bold text-sm md:text-base text-[#171E26]">Daily Order Volume</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Platform-wide daily load trajectory over recent period</p>
                </div>
                <div class="h-32 flex items-end justify-between gap-1.5 pt-4">
                    <div class="w-full bg-[#E9F3FE] hover:bg-[#2775E4] transition rounded-t h-[45%]" title="Mon: 420 orders"></div>
                    <div class="w-full bg-[#E9F3FE] hover:bg-[#2775E4] transition rounded-t h-[60%]" title="Tue: 510 orders"></div>
                    <div class="w-full bg-[#E9F3FE] hover:bg-[#2775E4] transition rounded-t h-[75%]" title="Wed: 680 orders"></div>
                    <div class="w-full bg-[#E9F3FE] hover:bg-[#2775E4] transition rounded-t h-[90%]" title="Thu: 820 orders"></div>
                    <div class="w-full bg-[#E9F3FE] hover:bg-[#2775E4] transition rounded-t h-[70%]" title="Fri: 610 orders"></div>
                    <div class="w-full bg-[#E9F3FE] hover:bg-[#2775E4] transition rounded-t h-[50%]" title="Sat: 480 orders"></div>
                    <div class="w-full bg-[#2775E4] rounded-t h-[85%]" title="Sun: 740 orders"></div>
                </div>
            </div>

            {{-- Processing Performance SLA Summary --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-3">
                <div class="border-b border-[#F3F7FC] pb-2.5">
                    <h3 class="font-manrope font-bold text-sm md:text-base text-[#171E26]">Processing Performance</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Average times and turnaround fulfillment SLAs</p>
                </div>
                <div class="grid grid-cols-3 gap-2 pt-2">
                    <div class="bg-[#F7FAFD] p-3 rounded-xl text-center">
                        <span class="font-inter text-[11px] text-[#171E26]/50 block">Avg Prep Time</span>
                        <span class="font-manrope font-extrabold text-lg text-[#171E26] mt-0.5 block">24 mins</span>
                    </div>
                    <div class="bg-[#F7FAFD] p-3 rounded-xl text-center">
                        <span class="font-inter text-[11px] text-[#171E26]/50 block">Completion Rate</span>
                        <span class="font-manrope font-extrabold text-lg text-emerald-600 mt-0.5 block">90.1%</span>
                    </div>
                    <div class="bg-[#F7FAFD] p-3 rounded-xl text-center">
                        <span class="font-inter text-[11px] text-[#171E26]/50 block">Cancellation</span>
                        <span class="font-manrope font-extrabold text-lg text-rose-600 mt-0.5 block">4.0%</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.superadmin>