<x-layouts.superAdmin title="Customers" active="customers">

    <div x-data="{ 
        state: 'loaded', 
        growthPeriod: '30D', 
        activityPeriod: '30D',
        filterDrawerOpen: false
    }" class="space-y-6">

        {{-- PAGE HEADER --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                    Customers
                </h1>
                <p class="font-inter text-xs md:text-sm text-[#171E26]/60 mt-0.5">
                    Monitor customers across the MedMart platform.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#E9F3FE] text-[#2775E4] font-inter text-xs font-medium">
                    <i class="ph ph-clock text-sm"></i> Updated 5 mins ago
                </span>
                <button type="button" 
                        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-white border border-[#DBEBFB] rounded-xl px-4 py-2.5 font-inter text-xs font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] active:scale-95 transition">
                    <i class="ph ph-arrows-clockwise text-sm text-[#2775E4]"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>

        {{-- KPI SUMMARY CARDS --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Total Customers</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1 tracking-tight">2,481</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-users text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Active Customers</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-emerald-600 mt-1 tracking-tight">2,156</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">New This Month</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#08AEBC] mt-1 tracking-tight">184</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#08AEBC] flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-user-plus text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Orders / Customer</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1 tracking-tight">3.6</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-shopping-bag text-xl"></i>
                </div>
            </div>
        </div>

        {{-- CHARTS SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3 mb-4">
                    <div>
                        <h3 class="font-manrope font-bold text-base text-[#171E26]">Customer Growth</h3>
                        <p class="font-inter text-xs text-[#171E26]/50">Total registered platform customers over time</p>
                    </div>
                    <div class="flex items-center gap-1 bg-[#F7FAFD] border border-[#DBEBFB] rounded-lg p-1 font-inter text-[11px] font-semibold">
                        <button @click="growthPeriod = '7D'" :class="growthPeriod === '7D' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2 py-0.5 rounded transition">7D</button>
                        <button @click="growthPeriod = '30D'" :class="growthPeriod === '30D' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2 py-0.5 rounded transition">30D</button>
                        <button @click="growthPeriod = '90D'" :class="growthPeriod === '90D' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2 py-0.5 rounded transition">90D</button>
                        <button @click="growthPeriod = '12M'" :class="growthPeriod === '12M' ? 'bg-[#2775E4] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2 py-0.5 rounded transition">12M</button>
                    </div>
                </div>

                <div class="relative h-48 w-full flex items-end pt-4">
                    <svg class="w-full h-full overflow-visible" viewBox="0 0 500 150" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="growthGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#2775E4" stop-opacity="0.25" />
                                <stop offset="100%" stop-color="#2775E4" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        <path d="M 0,130 Q 125,100 250,60 T 500,20 L 500,150 L 0,150 Z" fill="url(#growthGradient)" />
                        <path d="M 0,130 Q 125,100 250,60 T 500,20" fill="none" stroke="#2775E4" stroke-width="3" stroke-linecap="round" />
                        <circle cx="500" cy="20" r="5" fill="#2775E4" stroke="#FFFFFF" stroke-width="2" />
                    </svg>
                </div>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3 mb-4">
                    <div>
                        <h3 class="font-manrope font-bold text-base text-[#171E26]">Customer Activity</h3>
                        <p class="font-inter text-xs text-[#171E26]/50">Active engagement vs new signups</p>
                    </div>
                    <div class="flex items-center gap-1 bg-[#F7FAFD] border border-[#DBEBFB] rounded-lg p-1 font-inter text-[11px] font-semibold">
                        <button @click="activityPeriod = '7D'" :class="activityPeriod === '7D' ? 'bg-[#08AEBC] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2 py-0.5 rounded transition">7D</button>
                        <button @click="activityPeriod = '30D'" :class="activityPeriod === '30D' ? 'bg-[#08AEBC] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2 py-0.5 rounded transition">30D</button>
                        <button @click="activityPeriod = '90D'" :class="activityPeriod === '90D' ? 'bg-[#08AEBC] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2 py-0.5 rounded transition">90D</button>
                        <button @click="activityPeriod = '12M'" :class="activityPeriod === '12M' ? 'bg-[#08AEBC] text-white' : 'text-[#171E26]/60 hover:text-[#171E26]'" class="px-2 py-0.5 rounded transition">12M</button>
                    </div>
                </div>

                <div class="relative h-48 w-full flex items-end justify-between gap-3 pt-4 px-2">
                    <div class="flex-1 flex flex-col items-center gap-1 h-full justify-end">
                        <div class="w-full max-w-[28px] bg-[#E9F3FE] rounded-t-md h-[40%]"></div>
                        <span class="font-inter text-[10px] text-[#171E26]/50">W1</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1 h-full justify-end">
                        <div class="w-full max-w-[28px] bg-[#2775E4] rounded-t-md h-[65%]"></div>
                        <span class="font-inter text-[10px] text-[#171E26]/50">W2</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1 h-full justify-end">
                        <div class="w-full max-w-[28px] bg-[#08AEBC] rounded-t-md h-[85%]"></div>
                        <span class="font-inter text-[10px] text-[#171E26]/50">W3</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1 h-full justify-end">
                        <div class="w-full max-w-[28px] bg-[#2775E4] rounded-t-md h-[70%]"></div>
                        <span class="font-inter text-[10px] text-[#171E26]/50">W4</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- SEARCH & FILTER BAR --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl p-3.5 md:p-4 shadow-sm space-y-3 md:space-y-0 md:flex md:items-center md:justify-between md:gap-4">
            <div class="relative w-full md:max-w-md">
                <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/40 text-base"></i>
                <input type="text" 
                       placeholder="Search customers by name, email, or phone..." 
                       class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-10 pr-4 py-2.5 font-inter text-xs md:text-sm text-[#171E26] placeholder-[#171E26]/40 focus:outline-none focus:bg-white focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/20 transition" />
            </div>

            <div class="hidden md:flex items-center gap-3">
                <div class="relative">
                    <select class="appearance-none bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-3.5 pr-8 py-2.5 font-inter text-xs font-semibold text-[#171E26] hover:border-[#2775E4] focus:outline-none transition cursor-pointer">
                        <option value="">Status: All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/50 pointer-events-none text-xs"></i>
                </div>

                <div class="relative">
                    <select class="appearance-none bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-3.5 pr-8 py-2.5 font-inter text-xs font-semibold text-[#171E26] hover:border-[#2775E4] focus:outline-none transition cursor-pointer">
                        <option value="">Pharmacy: All</option>
                        <option value="triple_b">Triple B Pharmacy</option>
                        <option value="medmart_yaba">MedMart Yaba</option>
                        <option value="greenlife">GreenLife Pharmacy</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/50 pointer-events-none text-xs"></i>
                </div>
            </div>

            <div class="flex md:hidden items-center justify-between pt-1">
                <button @click="filterDrawerOpen = !filterDrawerOpen" 
                        type="button" 
                        class="w-full flex items-center justify-center gap-2 bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl py-2 font-inter text-xs font-semibold text-[#171E26]">
                    <i class="ph ph-sliders text-sm text-[#2775E4]"></i>
                    <span>Filter Options</span>
                </button>
            </div>

            <div x-show="filterDrawerOpen" x-collapse class="md:hidden pt-2 space-y-2 border-t border-[#F3F7FC]">
                <select class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 font-inter text-xs font-semibold text-[#171E26]">
                    <option value="">Status: All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
                <select class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 font-inter text-xs font-semibold text-[#171E26]">
                    <option value="">Pharmacy: All</option>
                    <option value="triple_b">Triple B Pharmacy</option>
                    <option value="medmart_yaba">MedMart Yaba</option>
                </select>
            </div>
        </div>

        {{-- CUSTOMERS TABLE CONTAINER --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-4 py-3.5 md:px-5 md:py-4 border-b border-[#F3F7FC] flex items-center justify-between">
                <h3 class="font-manrope font-bold text-sm md:text-base text-[#171E26]">Platform Customers</h3>
                <span class="font-inter text-xs text-[#171E26]/50">Showing 1 - 4 of 2,481</span>
            </div>

            {{-- LOADED STATE: TABLE --}}
            <div>
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] font-inter text-[11px] font-bold text-[#171E26]/50 uppercase tracking-wider">
                                <th class="py-3 px-5">Customer</th>
                                <th class="py-3 px-5">Email</th>
                                <th class="py-3 px-5 text-center">Pharmacies</th>
                                <th class="py-3 px-5 text-right">Orders</th>
                                <th class="py-3 px-5 text-center">Status</th>
                                <th class="py-3 px-5 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F3F7FC] font-inter text-xs md:text-sm">
                            
                            {{-- Row 1 --}}
                            <tr class="hover:bg-[#F7FAFD]/70 transition">
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-[#E9F3FE] text-[#2775E4] font-manrope font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                            KG
                                        </div>
                                        <div>
                                            <a href="{{ route('customerDetails') }}" class="font-manrope font-bold text-[#171E26] hover:text-[#2775E4] transition">Khadijah Garuba</a>
                                            <p class="text-[11px] text-[#171E26]/50">Joined Aug 21, 2026</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 font-mono text-xs text-[#171E26]/80">khad...@gmail.com</td>
                                <td class="py-3.5 px-5 text-center font-semibold text-[#171E26]">3</td>
                                <td class="py-3.5 px-5 text-right font-semibold text-[#171E26]">18</td>
                                <td class="py-3.5 px-5 text-center">
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 rounded-full">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <a href="{{ route('customerDetails', 1) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-[#2775E4] hover:bg-[#E9F3FE] transition" title="View Details">
                                        <i class="ph ph-arrow-right text-base font-bold"></i>
                                    </a>
                                </td>
                            </tr>

                            {{-- Row 2 --}}
                            <tr class="hover:bg-[#F7FAFD]/70 transition">
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-[#DBEBFB] text-[#08AEBC] font-manrope font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                            YY
                                        </div>
                                        <div>
                                            <a href="{{ route('customerDetails', 2) }}" class="font-manrope font-bold text-[#171E26] hover:text-[#2775E4] transition">Yusuf Yusuf</a>
                                            <p class="text-[11px] text-[#171E26]/50">Joined Aug 15, 2026</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 font-mono text-xs text-[#171E26]/80">yusuf...@gmail.com</td>
                                <td class="py-3.5 px-5 text-center font-semibold text-[#171E26]">1</td>
                                <td class="py-3.5 px-5 text-right font-semibold text-[#171E26]">7</td>
                                <td class="py-3.5 px-5 text-center">
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 rounded-full">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <a href="{{ route('customerDetails', 2) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-[#2775E4] hover:bg-[#E9F3FE] transition" title="View Details">
                                        <i class="ph ph-arrow-right text-base font-bold"></i>
                                    </a>
                                </td>
                            </tr>

                            {{-- Row 3 --}}
                            <tr class="hover:bg-[#F7FAFD]/70 transition">
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-indigo-100 text-indigo-700 font-manrope font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                            AB
                                        </div>
                                        <div>
                                            <a href="{{ route('customerDetails', 3) }}" class="font-manrope font-bold text-[#171E26] hover:text-[#2775E4] transition">Ahmed Bello</a>
                                            <p class="text-[11px] text-[#171E26]/50">Joined Jul 28, 2026</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 font-mono text-xs text-[#171E26]/80">ahmed...@gmail.com</td>
                                <td class="py-3.5 px-5 text-center font-semibold text-[#171E26]">2</td>
                                <td class="py-3.5 px-5 text-right font-semibold text-[#171E26]">11</td>
                                <td class="py-3.5 px-5 text-center">
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 rounded-full">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <a href="{{ route('customerDetails', 3) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-[#2775E4] hover:bg-[#E9F3FE] transition" title="View Details">
                                        <i class="ph ph-arrow-right text-base font-bold"></i>
                                    </a>
                                </td>
                            </tr>

                            {{-- Row 4 --}}
                            <tr class="hover:bg-[#F7FAFD]/70 transition">
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-slate-100 text-slate-600 font-manrope font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                            JD
                                        </div>
                                        <div>
                                            <a href="{{ route('customerDetails', 4) }}" class="font-manrope font-bold text-[#171E26] hover:text-[#2775E4] transition">Jane Doe</a>
                                            <p class="text-[11px] text-[#171E26]/50">Joined Jun 10, 2026</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 font-mono text-xs text-[#171E26]/80">jane...@gmail.com</td>
                                <td class="py-3.5 px-5 text-center font-semibold text-[#171E26]">1</td>
                                <td class="py-3.5 px-5 text-right font-semibold text-[#171E26]">2</td>
                                <td class="py-3.5 px-5 text-center">
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-slate-600 bg-slate-100 border border-slate-200/60 px-2.5 py-0.5 rounded-full">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Inactive
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <a href="{{ route('customerDetails', 4) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-[#2775E4] hover:bg-[#E9F3FE] transition" title="View Details">
                                        <i class="ph ph-arrow-right text-base font-bold"></i>
                                    </a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                {{-- Mobile View Cards --}}
                <div class="block sm:hidden divide-y divide-[#F3F7FC]">
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="h-8 w-8 rounded-full bg-[#E9F3FE] text-[#2775E4] font-manrope font-bold text-xs flex items-center justify-center">KG</div>
                                <div>
                                    <a href="{{ route('customerDetails', 1) }}" class="font-manrope font-bold text-sm text-[#171E26]">Khadijah Garuba</a>
                                    <p class="text-[10px] text-[#171E26]/50">khad...@gmail.com</p>
                                </div>
                            </div>
                            <span class="font-semibold text-[11px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Active</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 bg-[#F7FAFD] p-2.5 rounded-xl font-inter text-xs">
                            <div><span class="text-[10px] text-[#171E26]/50 block">Pharmacies</span><span class="font-semibold text-[#171E26]">3 Connected</span></div>
                            <div><span class="text-[10px] text-[#171E26]/50 block">Orders</span><span class="font-semibold text-[#171E26]">18 Placed</span></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PAGINATION --}}
            <div class="px-4 py-3.5 bg-[#F7FAFD] border-t border-[#EAF1FB] flex flex-col sm:flex-row items-center justify-between gap-3 font-inter text-xs">
                <p class="text-[#171E26]/60 text-center sm:text-left">
                    Showing <span class="font-semibold text-[#171E26]">1</span> to <span class="font-semibold text-[#171E26]">4</span> of <span class="font-semibold text-[#171E26]">2,481</span> customers
                </p>

                <div class="flex items-center gap-1">
                    <button class="h-8 px-2.5 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26]/50 disabled:opacity-40" disabled><i class="ph ph-caret-left"></i></button>
                    <button class="h-8 w-8 rounded-lg bg-[#2775E4] text-white font-semibold flex items-center justify-center">1</button>
                    <button class="h-8 w-8 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26] font-medium flex items-center justify-center">2</button>
                    <button class="h-8 w-8 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26] font-medium flex items-center justify-center">3</button>
                    <button class="h-8 px-2.5 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26] flex items-center justify-center"><i class="ph ph-caret-right"></i></button>
                </div>
            </div>

        </div>

    </div>

</x-layouts.superAdmin>