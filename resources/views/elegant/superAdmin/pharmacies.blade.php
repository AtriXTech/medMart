{{-- resources/views/superAdmin/pharmacies/index.blade.php --}}
<x-layouts.superAdmin title="Pharmacies" active="pharmacies">

    <div x-data="{ state: 'loaded', filterDrawerOpen: false }" class="space-y-6">

        {{-- ================= PAGE HEADER & CONTROLS ================= --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                    Pharmacies
                </h1>
                <p class="font-inter text-xs md:text-sm text-[#171E26]/60 mt-0.5">
                    Manage and monitor pharmacies registered on MedMart.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <button type="button" 
                        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-white border border-[#DBEBFB] rounded-xl px-4 py-2.5 font-inter text-xs font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] active:scale-95 transition">
                    <i class="ph ph-arrows-clockwise text-sm text-[#2775E4]"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>

        {{-- ================= SUMMARY KPI CARDS ================= --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            {{-- Total Pharmacies --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Total Pharmacies</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1 tracking-tight">128</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-storefront text-xl"></i>
                </div>
            </div>

            {{-- Active Pharmacies --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Active Pharmacies</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-emerald-600 mt-1 tracking-tight">104</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                </div>
            </div>

            {{-- Inactive Pharmacies --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Inactive Pharmacies</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26]/50 mt-1 tracking-tight">24</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-minus-circle text-xl"></i>
                </div>
            </div>

            {{-- New This Month --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">New This Month</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#08AEBC] mt-1 tracking-tight">8</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#08AEBC] flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-trend-up text-xl"></i>
                </div>
            </div>
        </div>

        {{-- ================= SEARCH & FILTER CONTROL BAR ================= --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl p-3.5 md:p-4 shadow-sm space-y-3 md:space-y-0 md:flex md:items-center md:justify-between md:gap-4">
            
            {{-- Search Bar --}}
            <div class="relative w-full md:max-w-md">
                <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/40 text-base"></i>
                <input type="text" 
                       placeholder="Search by pharmacy name, owner, or email..." 
                       class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-10 pr-4 py-2.5 font-inter text-xs md:text-sm text-[#171E26] placeholder-[#171E26]/40 focus:outline-none focus:bg-white focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/20 transition" />
            </div>

            {{-- Filters (Desktop) --}}
            <div class="hidden md:flex items-center gap-3">
                <div class="relative">
                    <select class="appearance-none bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-3.5 pr-8 py-2.5 font-inter text-xs font-semibold text-[#171E26] hover:border-[#2775E4] focus:outline-none transition cursor-pointer">
                        <option value="">Status: All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="pending">Pending</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/50 pointer-events-none text-xs"></i>
                </div>

                <div class="relative">
                    <select class="appearance-none bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-3.5 pr-8 py-2.5 font-inter text-xs font-semibold text-[#171E26] hover:border-[#2775E4] focus:outline-none transition cursor-pointer">
                        <option value="">Plan: All</option>
                        <option value="basic">Basic Tier</option>
                        <option value="pro">Pro Tier</option>
                        <option value="enterprise">Enterprise</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/50 pointer-events-none text-xs"></i>
                </div>
            </div>

            {{-- Mobile Filter Controls --}}
            <div class="flex md:hidden items-center justify-between pt-1">
                <button @click="filterDrawerOpen = !filterDrawerOpen" 
                        type="button" 
                        class="w-full flex items-center justify-center gap-2 bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl py-2 font-inter text-xs font-semibold text-[#171E26]">
                    <i class="ph ph-sliders text-sm text-[#2775E4]"></i>
                    <span>Filters & Options</span>
                </button>
            </div>

            <div x-show="filterDrawerOpen" 
                 x-collapse 
                 class="md:hidden pt-2 space-y-2 border-t border-[#F3F7FC]">
                <select class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 font-inter text-xs font-semibold text-[#171E26]">
                    <option value="">Status: All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending</option>
                </select>
                <select class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 font-inter text-xs font-semibold text-[#171E26]">
                    <option value="">Plan: All</option>
                    <option value="basic">Basic Tier</option>
                    <option value="pro">Pro Tier</option>
                    <option value="enterprise">Enterprise</option>
                </select>
            </div>
        </div>

        {{-- ================= PHARMACY PERFORMANCE TABLE (LOADED DEFAULT) ================= --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-4 py-3.5 md:px-5 md:py-4 border-b border-[#F3F7FC] flex items-center justify-between">
                <h3 class="font-manrope font-bold text-sm md:text-base text-[#171E26]">Pharmacy Performance</h3>
                <span class="font-inter text-xs text-[#171E26]/50">128 registered</span>
            </div>

            {{-- Desktop / Tablet Table View --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] font-inter text-[11px] font-bold text-[#171E26]/50 uppercase tracking-wider">
                            <th class="py-3 px-5">Pharmacy</th>
                            <th class="py-3 px-5">Status</th>
                            <th class="py-3 px-5 text-right">Total Orders</th>
                            <th class="py-3 px-5 text-right">Processed</th>
                            <th class="py-3 px-5 text-right">Revenue</th>
                            <th class="py-3 px-5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3F7FC] font-inter text-xs md:text-sm">
                        
                        {{-- Row 1 --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-[#E9F3FE] text-[#2775E4] font-manrope font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                        BBB
                                    </div>
                                    <div>
                                        <p class="font-manrope font-bold text-[#171E26]">Triple B Pharmacy</p>
                                        <p class="text-[11px] text-[#171E26]/50">Lagos · Pro Plan</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#171E26]">1,240</td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#171E26]">1,180</td>
                            <td class="py-3.5 px-5 text-right font-manrope font-bold text-[#171E26]">₦2,400,000</td>
                            <td class="py-3.5 px-5 text-center">
                                <a href="{{ route('pharmacyDetails') }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-[#2775E4] hover:bg-[#E9F3FE] transition" title="View Details">
                                    <i class="ph ph-arrow-right text-base font-bold"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 2 --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-[#DBEBFB] text-[#08AEBC] font-manrope font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                        MMY
                                    </div>
                                    <div>
                                        <p class="font-manrope font-bold text-[#171E26]">MedMart Yaba</p>
                                        <p class="text-[11px] text-[#171E26]/50">Yaba, Lagos · Enterprise</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#171E26]">820</td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#171E26]">790</td>
                            <td class="py-3.5 px-5 text-right font-manrope font-bold text-[#171E26]">₦1,900,000</td>
                            <td class="py-3.5 px-5 text-center">
                                <a href="/superAdmin/pharmacies/2" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-[#2775E4] hover:bg-[#E9F3FE] transition" title="View Details">
                                    <i class="ph ph-arrow-right text-base font-bold"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 3 --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-emerald-100 text-emerald-700 font-manrope font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                        GLP
                                    </div>
                                    <div>
                                        <p class="font-manrope font-bold text-[#171E26]">GreenLife Pharmacy</p>
                                        <p class="text-[11px] text-[#171E26]/50">Abuja · Pro Plan</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#171E26]">642</td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#171E26]">601</td>
                            <td class="py-3.5 px-5 text-right font-manrope font-bold text-[#171E26]">₦1,300,000</td>
                            <td class="py-3.5 px-5 text-center">
                                <a href="/superAdmin/pharmacies/3" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-[#2775E4] hover:bg-[#E9F3FE] transition" title="View Details">
                                    <i class="ph ph-arrow-right text-base font-bold"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 4 --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-slate-100 text-slate-600 font-manrope font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                        XYZ
                                    </div>
                                    <div>
                                        <p class="font-manrope font-bold text-[#171E26]">XYZ Pharmacy</p>
                                        <p class="text-[11px] text-[#171E26]/50">Port Harcourt · Basic Plan</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-slate-600 bg-slate-100 border border-slate-200/60 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#171E26]">210</td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#171E26]">187</td>
                            <td class="py-3.5 px-5 text-right font-manrope font-bold text-[#171E26]">₦420,000</td>
                            <td class="py-3.5 px-5 text-center">
                                <a href="/superAdmin/pharmacies/4" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-[#2775E4] hover:bg-[#E9F3FE] transition" title="View Details">
                                    <i class="ph ph-arrow-right text-base font-bold"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 5 --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-amber-100 text-amber-700 font-manrope font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                        HFP
                                    </div>
                                    <div>
                                        <p class="font-manrope font-bold text-[#171E26]">HealthFirst Pharmacy</p>
                                        <p class="text-[11px] text-[#171E26]/50">Ibadan · Verification Pending</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-amber-700 bg-amber-50 border border-amber-200/60 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Pending
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#171E26]">0</td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#171E26]">0</td>
                            <td class="py-3.5 px-5 text-right font-manrope font-bold text-[#171E26]">₦0</td>
                            <td class="py-3.5 px-5 text-center">
                                <a href="/superAdmin/pharmacies/5" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-[#2775E4] hover:bg-[#E9F3FE] transition" title="View Details">
                                    <i class="ph ph-arrow-right text-base font-bold"></i>
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- Mobile Stacked Cards View --}}
            <div class="block sm:hidden divide-y divide-[#F3F7FC]">
                
                {{-- Mobile Item 1 --}}
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 rounded-lg bg-[#E9F3FE] text-[#2775E4] font-manrope font-bold text-xs flex items-center justify-center">BBB</div>
                            <div>
                                <p class="font-manrope font-bold text-sm text-[#171E26]">Triple B Pharmacy</p>
                                <p class="text-[10px] text-[#171E26]/50">Lagos · Pro Plan</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 font-semibold text-[11px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                            Active
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 bg-[#F7FAFD] p-2.5 rounded-xl font-inter text-xs">
                        <div>
                            <span class="text-[10px] text-[#171E26]/50 block">Orders</span>
                            <span class="font-semibold text-[#171E26]">1,240</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#171E26]/50 block">Processed</span>
                            <span class="font-semibold text-[#171E26]">1,180</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#171E26]/50 block">Revenue</span>
                            <span class="font-manrope font-bold text-[#2775E4]">₦2.4M</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="/superAdmin/pharmacies/1" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4]">
                            View Details <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Mobile Item 2 --}}
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 rounded-lg bg-[#DBEBFB] text-[#08AEBC] font-manrope font-bold text-xs flex items-center justify-center">MMY</div>
                            <div>
                                <p class="font-manrope font-bold text-sm text-[#171E26]">MedMart Yaba</p>
                                <p class="text-[10px] text-[#171E26]/50">Yaba · Enterprise</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 font-semibold text-[11px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                            Active
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 bg-[#F7FAFD] p-2.5 rounded-xl font-inter text-xs">
                        <div>
                            <span class="text-[10px] text-[#171E26]/50 block">Orders</span>
                            <span class="font-semibold text-[#171E26]">820</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#171E26]/50 block">Processed</span>
                            <span class="font-semibold text-[#171E26]">790</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#171E26]/50 block">Revenue</span>
                            <span class="font-manrope font-bold text-[#2775E4]">₦1.9M</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="/superAdmin/pharmacies/2" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2775E4]">
                            View Details <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>

            {{-- Pagination Bar --}}
            <div class="px-4 py-3.5 bg-[#F7FAFD] border-t border-[#EAF1FB] flex flex-col sm:flex-row items-center justify-between gap-3 font-inter text-xs">
                <p class="text-[#171E26]/60 text-center sm:text-left">
                    Showing <span class="font-semibold text-[#171E26]">1</span> to <span class="font-semibold text-[#171E26]">5</span> of <span class="font-semibold text-[#171E26]">128</span> pharmacies
                </p>

                <div class="flex items-center gap-1">
                    <button class="h-8 px-2.5 rounded-lg border border-[#DBEBFB] bg-white flex items-center justify-center text-[#171E26]/50 disabled:opacity-40" disabled>
                        <i class="ph ph-caret-left"></i>
                    </button>
                    <button class="h-8 w-8 rounded-lg bg-[#2775E4] text-white font-semibold flex items-center justify-center">1</button>
                    <button class="h-8 w-8 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26] font-medium flex items-center justify-center">2</button>
                    <button class="h-8 w-8 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26] font-medium flex items-center justify-center">3</button>
                    <button class="h-8 px-2.5 rounded-lg border border-[#DBEBFB] bg-white flex items-center justify-center text-[#171E26]">
                        <i class="ph ph-caret-right"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

</x-layouts.superAdmin>