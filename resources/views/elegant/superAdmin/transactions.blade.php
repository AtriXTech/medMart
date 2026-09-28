<x-layouts.superadmin title="Financial Transactions Intelligence" active="finance-transactions">

    <div x-data="{ 
        period: '30D',
        typeFilter: 'all',
        statusFilter: 'all',
        searchQuery: ''
    }" class="space-y-6">

        {{-- PAGE HEADER & TOOLBAR --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                    Transactions
                </h1>
                <p class="font-inter text-xs md:text-sm text-[#171E26]/60 mt-0.5">
                    Monitor financial transactions across the MedMart platform.
                </p>
            </div>

            {{-- HEADER CONTROLS --}}
            <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
                {{-- Date Range Selector --}}
                <div class="inline-flex items-center bg-white border border-[#DBEBFB] rounded-xl p-1 shadow-sm font-inter text-xs font-semibold">
                    <button @click="period = '7D'" :class="period === '7D' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2.5 py-1.5 rounded-lg transition">7D</button>
                    <button @click="period = '30D'" :class="period === '30D' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2.5 py-1.5 rounded-lg transition">30D</button>
                    <button @click="period = '90D'" :class="period === '90D' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2.5 py-1.5 rounded-lg transition">90D</button>
                    <button @click="period = '12M'" :class="period === '12M' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2.5 py-1.5 rounded-lg transition">12M</button>
                </div>

                {{-- Type Dropdown --}}
                <select x-model="typeFilter" class="bg-white border border-[#DBEBFB] rounded-xl px-3 py-2 font-inter text-xs font-semibold text-[#171E26] shadow-sm focus:outline-none cursor-pointer">
                    <option value="all">Type: All</option>
                    <option value="order">Customer Order Payment</option>
                    <option value="subscription">Subscription Payment</option>
                    <option value="settlement">Settlement</option>
                    <option value="refund">Refund</option>
                </select>

                {{-- Status Dropdown --}}
                <select x-model="statusFilter" class="bg-white border border-[#DBEBFB] rounded-xl px-3 py-2 font-inter text-xs font-semibold text-[#171E26] shadow-sm focus:outline-none cursor-pointer">
                    <option value="all">Status: All</option>
                    <option value="successful">Successful</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                </select>

                {{-- Refresh Button --}}
                <button type="button" class="flex items-center justify-center gap-1.5 bg-white border border-[#DBEBFB] rounded-xl px-3 py-2 font-inter text-xs font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] active:scale-95 transition">
                    <i class="ph ph-arrows-clockwise text-sm text-[#2775E4]"></i>
                    <span class="hidden md:inline">Refresh</span>
                </button>
            </div>
        </div>

        {{-- KPI SECTION (6 CARDS) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
            
            {{-- 1. Total Transaction Value --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Total Value</span>
                    <div class="h-8 w-8 rounded-lg bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center">
                        <i class="ph-fill ph-[#2775E4] ph-currency-ngn text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#171E26] tracking-tight">₦142.8M</p>
                    <span class="inline-flex items-center gap-1 font-inter text-[11px] font-semibold text-emerald-600 mt-0.5">
                        <i class="ph ph-trend-up"></i> +14.2%
                    </span>
                </div>
            </div>

            {{-- 2. Successful Transactions --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Successful</span>
                    <div class="h-8 w-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph-fill ph-check-circle text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-xl md:text-2xl text-emerald-600 tracking-tight">18,420</p>
                    <span class="font-inter text-[11px] text-[#171E26]/50 mt-0.5 block">95.6% volume</span>
                </div>
            </div>

            {{-- 3. Pending Transactions --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Pending</span>
                    <div class="h-8 w-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="ph-fill ph-clock text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-xl md:text-2xl text-amber-600 tracking-tight">412</p>
                    <span class="font-inter text-[11px] text-[#171E26]/50 mt-0.5 block">₦3.1M processing</span>
                </div>
            </div>

            {{-- 4. Failed Transactions --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Failed</span>
                    <div class="h-8 w-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="ph-fill ph-x-circle text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-xl md:text-2xl text-rose-600 tracking-tight">280</p>
                    <span class="inline-flex items-center gap-1 font-inter text-[11px] font-semibold text-emerald-600 mt-0.5">
                        <i class="ph ph-trend-down"></i> -0.8%
                    </span>
                </div>
            </div>

            {{-- 5. Refunded Transactions --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Refunded</span>
                    <div class="h-8 w-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="ph-fill ph-arrow-u-down-left text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-xl md:text-2xl text-purple-700 tracking-tight">145</p>
                    <span class="font-inter text-[11px] text-[#171E26]/50 mt-0.5 block">₦1.25M total</span>
                </div>
            </div>

            {{-- 6. Total Transaction Count --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="font-inter text-xs font-medium text-[#171E26]/60">Total Count</span>
                    <div class="h-8 w-8 rounded-lg bg-[#E9F3FE] text-[#08AEBC] flex items-center justify-center">
                        <i class="ph-fill ph-receipt text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#171E26] tracking-tight">19,257</p>
                    <span class="inline-flex items-center gap-1 font-inter text-[11px] font-semibold text-emerald-600 mt-0.5">
                        <i class="ph ph-trend-up"></i> +8.5%
                    </span>
                </div>
            </div>

        </div>

        {{-- FINANCIAL OVERVIEW CHARTS SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            {{-- Transaction Overview Line/Area Chart --}}
            <div class="lg:col-span-2 bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-[#F3F7FC] pb-3.5 mb-4">
                    <div>
                        <h3 class="font-manrope font-bold text-base text-[#171E26]">Transaction Overview</h3>
                        <p class="font-inter text-xs text-[#171E26]/50">Gross platform transaction value processed over time</p>
                    </div>

                    {{-- Chart Legend --}}
                    <div class="flex items-center gap-3 font-inter text-[11px] font-medium text-[#171E26]/70 flex-wrap">
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Successful</span>
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Pending</span>
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span> Failed</span>
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span> Refunded</span>
                    </div>
                </div>

                {{-- Vector Area Chart Representation --}}
                <div class="relative h-56 w-full pt-2">
                    <svg class="w-full h-full overflow-visible" viewBox="0 0 600 180" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="financialGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#2775E4" stop-opacity="0.18" />
                                <stop offset="100%" stop-color="#2775E4" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        {{-- Grid lines --}}
                        <line x1="0" y1="30" x2="600" y2="30" stroke="#F3F7FC" stroke-width="1" />
                        <line x1="0" y1="80" x2="600" y2="80" stroke="#F3F7FC" stroke-width="1" />
                        <line x1="0" y1="130" x2="600" y2="130" stroke="#F3F7FC" stroke-width="1" />

                        {{-- Main Area fill --}}
                        <path d="M 0,130 Q 150,80 300,40 T 600,15 L 600,180 L 0,180 Z" fill="url(#financialGrad)" />

                        {{-- Successful (Green) --}}
                        <path d="M 0,130 Q 150,80 300,40 T 600,15" fill="none" stroke="#10B981" stroke-width="2.5" />

                        {{-- Pending (Amber) --}}
                        <path d="M 0,160 Q 150,145 300,130 T 600,120" fill="none" stroke="#F59E0B" stroke-width="1.5" stroke-dasharray="3 2" />

                        {{-- Failed (Rose) --}}
                        <path d="M 0,172 Q 150,170 300,168 T 600,165" fill="none" stroke="#F43F5E" stroke-width="1.5" />
                    </svg>
                </div>

                <div class="flex items-center justify-between border-t border-[#F3F7FC] pt-3 mt-2 font-inter text-[11px] text-[#171E26]/50">
                    <span>Aug 10</span>
                    <span>Aug 17</span>
                    <span>Aug 24</span>
                    <span>Sep 01</span>
                    <span>Sep 07, 2026</span>
                </div>
            </div>

            {{-- Transaction Status Distribution --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="border-b border-[#F3F7FC] pb-3 mb-4">
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Transaction Status</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Percentage breakdown by status category</p>
                </div>

                <div class="space-y-4 font-inter text-xs">
                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Successful</span>
                            <span class="font-semibold">18,420 (95.6%)</span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: 95.6%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-amber-500"></span> Pending</span>
                            <span class="font-semibold">412 (2.1%)</span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: 15%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-rose-500"></span> Failed</span>
                            <span class="font-semibold">280 (1.5%)</span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-rose-500 h-2 rounded-full" style="width: 10%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium text-[#171E26] mb-1">
                            <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-purple-500"></span> Refunded</span>
                            <span class="font-semibold">145 (0.8%)</span>
                        </div>
                        <div class="w-full bg-[#F7FAFD] rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: 6%"></div>
                        </div>
                    </div>
                </div>

                {{-- Important Concept Reminder Banner --}}
                <div class="mt-4 p-3 bg-[#E9F3FE]/60 border border-[#DBEBFB] rounded-xl text-[11px] font-inter text-[#171E26]/70 flex items-start gap-2">
                    <i class="ph ph-info text-[#2775E4] text-sm shrink-0 mt-0.5"></i>
                    <span><strong>Platform Note:</strong> Successful transactions represent collected revenue. Pharmacy settlements are processed on separate scheduled payouts.</span>
                </div>
            </div>

        </div>

        {{-- ALL TRANSACTIONS DATA TABLE SECTION --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl shadow-sm overflow-hidden space-y-4 p-4 md:p-5">
            
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 border-b border-[#F3F7FC] pb-4">
                <div>
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">All Transactions</h3>
                    <p class="font-inter text-xs text-[#171E26]/50">Granular real-time record of all payment and settlement transactions</p>
                </div>

                {{-- SEARCH & FILTER TOOLBAR --}}
                <div class="flex items-center gap-2 flex-wrap lg:flex-nowrap">
                    <div class="relative w-full sm:w-64">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#171E26]/40 text-xs"></i>
                        <input type="text" 
                               x-model="searchQuery" 
                               placeholder="Search ref or gateway ID..." 
                               class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-9 pr-3 py-2 font-inter text-xs text-[#171E26] focus:outline-none focus:border-[#2775E4] transition" />
                    </div>

                    <select class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 font-inter text-xs font-semibold text-[#171E26] focus:outline-none cursor-pointer">
                        <option value="">Payment Method: All</option>
                        <option value="card">Card (Paystack)</option>
                        <option value="transfer">Bank Transfer</option>
                        <option value="wallet">MedMart Wallet</option>
                    </select>

                    <button type="button" class="p-2 bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl text-[#171E26]/70 hover:text-[#2775E4] transition">
                        <i class="ph ph-sliders-horizontal text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- DESKTOP TABLE VIEW --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse font-inter text-xs md:text-sm">
                    <thead>
                        <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[11px] font-bold text-[#171E26]/50 uppercase tracking-wider">
                            <th class="py-3 px-4">Reference</th>
                            <th class="py-3 px-4">Pharmacy</th>
                            <th class="py-3 px-4">Transaction Type</th>
                            <th class="py-3 px-4 text-right">Amount</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4">Date & Time</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3F7FC]">
                        
                        {{-- Row 1: Successful Order --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#2775E4]">MM-92831</td>
                            <td class="py-3.5 px-4 font-manrope font-semibold text-[#171E26]">Triple B Pharmacy</td>
                            <td class="py-3.5 px-4 text-[#171E26]/80 font-medium">Customer Order Payment</td>
                            <td class="py-3.5 px-4 text-right font-manrope font-extrabold text-[#171E26]">₦25,000</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/50">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Successful
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/60 text-xs">Sep 7, 2026 · 14:32</td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('transactionDetails', 'MM-92831') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View <i class="ph ph-arrow-right"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 2: Subscription Payment --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#2775E4]">MM-92832</td>
                            <td class="py-3.5 px-4 font-manrope font-semibold text-[#171E26]">MedMart Yaba</td>
                            <td class="py-3.5 px-4 text-[#171E26]/80 font-medium">Subscription Payment</td>
                            <td class="py-3.5 px-4 text-right font-manrope font-extrabold text-[#171E26]">₦9,500</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/50">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Successful
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/60 text-xs">Sep 7, 2026 · 13:15</td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('transactionDetails', 'MM-92832') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View <i class="ph ph-arrow-right"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 3: Pending Order --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#2775E4]">MM-92833</td>
                            <td class="py-3.5 px-4 font-manrope font-semibold text-[#171E26]">GreenLife Pharmacy</td>
                            <td class="py-3.5 px-4 text-[#171E26]/80 font-medium">Customer Order Payment</td>
                            <td class="py-3.5 px-4 text-right font-manrope font-extrabold text-[#171E26]">₦14,200</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200/50">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Pending
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/60 text-xs">Sep 7, 2026 · 12:40</td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('transactionDetails', 'MM-92833') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View <i class="ph ph-arrow-right"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 4: Failed Transaction --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#2775E4]">MM-92834</td>
                            <td class="py-3.5 px-4 font-manrope font-semibold text-[#171E26]">HealthPlus VI</td>
                            <td class="py-3.5 px-4 text-[#171E26]/80 font-medium">Customer Order Payment</td>
                            <td class="py-3.5 px-4 text-right font-manrope font-extrabold text-[#171E26]">₦38,000</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-rose-700 bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200/50">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Failed
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/60 text-xs">Sep 7, 2026 · 11:05</td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('transactionDetails', 'MM-92834') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View <i class="ph ph-arrow-right"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 5: Refund --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#2775E4]">MM-92835</td>
                            <td class="py-3.5 px-4 font-manrope font-semibold text-[#171E26]">Triple B Pharmacy</td>
                            <td class="py-3.5 px-4 text-[#171E26]/80 font-medium">Refund</td>
                            <td class="py-3.5 px-4 text-right font-manrope font-extrabold text-purple-700">-₦8,500</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold text-xs text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200/50">
                                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span> Refunded
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/60 text-xs">Sep 6, 2026 · 16:50</td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('transactionDetails', 'MM-92835') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4] hover:underline">
                                    View <i class="ph ph-arrow-right"></i>
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- MOBILE CARDS VIEW --}}
            <div class="block md:hidden space-y-3">
                {{-- Card 1 --}}
                <div class="bg-[#F7FAFD] border border-[#EAF1FB] rounded-xl p-3.5 space-y-3">
                    <div class="flex items-center justify-between border-b border-[#DBEBFB] pb-2">
                        <div>
                            <span class="font-mono font-bold text-xs text-[#2775E4] block">MM-92831</span>
                            <span class="font-manrope font-bold text-xs text-[#171E26]">Triple B Pharmacy</span>
                        </div>
                        <span class="font-semibold text-xs text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/50">Successful</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs font-inter">
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px]">Type</span>
                            <span class="font-medium text-[#171E26]">Customer Order</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px]">Amount</span>
                            <span class="font-manrope font-extrabold text-[#171E26]">₦25,000</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-[#171E26]/50 block text-[11px]">Date & Time</span>
                            <span class="text-[#171E26]/70">Sep 7, 2026 · 14:32</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-[#DBEBFB]">
                        <a href="{{ route('transactionDetails', 'MM-92831') }}" class="w-full flex items-center justify-center gap-1 bg-white border border-[#DBEBFB] py-2 rounded-lg text-xs font-semibold text-[#2775E4] shadow-sm">
                            View Transaction <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- PAGINATION BAR --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 font-inter text-xs border-t border-[#F3F7FC]">
                <p class="text-[#171E26]/60">Showing 1 to 5 of 19,257 transactions</p>
                <div class="flex items-center gap-1">
                    <button class="h-8 px-2 rounded-lg border border-[#DBEBFB] bg-white disabled:opacity-40" disabled><i class="ph ph-caret-left"></i></button>
                    <button class="h-8 w-8 rounded-lg bg-[#2775E4] text-white font-semibold">1</button>
                    <button class="h-8 w-8 rounded-lg border border-[#DBEBFB] bg-white">2</button>
                    <button class="h-8 w-8 rounded-lg border border-[#DBEBFB] bg-white">3</button>
                    <button class="h-8 px-2 rounded-lg border border-[#DBEBFB] bg-white"><i class="ph ph-caret-right"></i></button>
                </div>
            </div>

        </div>

    </div>

</x-layouts.superadmin>