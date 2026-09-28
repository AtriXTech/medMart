{{-- resources/views/superAdmin/pharmacies/show.blade.php --}}
<x-layouts.superAdmin title="Triple B Pharmacy - Details" active="pharmacies">

    <div class="space-y-6">

        {{-- ================= BREADCRUMB & HEADER ================= --}}
        <div class="space-y-3">
            <a href="{{ route('pharmacies') }}" class="inline-flex items-center gap-1.5 font-inter text-xs font-semibold text-[#2775E4] hover:underline">
                <i class="ph ph-arrow-left"></i> Back to Pharmacies
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3.5">
                    <div class="h-12 w-12 rounded-2xl bg-[#E9F3FE] text-[#2775E4] font-manrope font-extrabold text-base flex items-center justify-center flex-shrink-0">
                        BBB
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h1 class="font-manrope font-extrabold text-xl md:text-2xl text-[#171E26]">Triple B Pharmacy</h1>
                            <span class="inline-flex items-center gap-1 font-inter text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">
                                Active
                            </span>
                        </div>
                        <p class="font-inter text-xs text-[#171E26]/50 mt-0.5">Registered on Oct 12, 2024 · ID: PH-88392</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= BUSINESS METRICS SUMMARY ================= --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-3.5 shadow-sm">
                <span class="font-inter text-[11px] text-[#171E26]/50 block">Total Orders</span>
                <p class="font-manrope font-extrabold text-lg md:text-xl text-[#171E26] mt-0.5">1,240</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-3.5 shadow-sm">
                <span class="font-inter text-[11px] text-[#171E26]/50 block">Orders Processed</span>
                <p class="font-manrope font-extrabold text-lg md:text-xl text-[#171E26] mt-0.5">1,180</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-3.5 shadow-sm">
                <span class="font-inter text-[11px] text-[#171E26]/50 block">Orders Completed</span>
                <p class="font-manrope font-extrabold text-lg md:text-xl text-[#08AEBC] mt-0.5">1,142</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-3.5 shadow-sm">
                <span class="font-inter text-[11px] text-[#171E26]/50 block">Revenue Generated</span>
                <p class="font-manrope font-extrabold text-lg md:text-xl text-[#2775E4] mt-0.5">₦2.4M</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-3.5 shadow-sm">
                <span class="font-inter text-[11px] text-[#171E26]/50 block">Linked Customers</span>
                <p class="font-manrope font-extrabold text-lg md:text-xl text-[#171E26] mt-0.5">482</p>
            </div>
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-3.5 shadow-sm">
                <span class="font-inter text-[11px] text-[#171E26]/50 block">Active Staff</span>
                <p class="font-manrope font-extrabold text-lg md:text-xl text-[#171E26] mt-0.5">6</p>
            </div>
        </div>

        {{-- ================= DETAILED BUSINESS PANELS ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Column 1 & 2: Performance Charts & Info --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Historical Revenue/Order Chart --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#F3F7FC]">
                        <div>
                            <h3 class="font-manrope font-bold text-base text-[#171E26]">Revenue & Order Growth</h3>
                            <p class="font-inter text-xs text-[#171E26]/50">Historical trend from backend analytics</p>
                        </div>
                        <span class="font-inter text-xs font-semibold text-[#2775E4] bg-[#E9F3FE] px-2.5 py-1 rounded-lg">Last 30 Days</span>
                    </div>

                    <div class="h-44 w-full relative flex items-end pt-6">
                        <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 400 100">
                            <path d="M 0,80 Q 100,20 200,50 T 400,10" fill="none" stroke="#2775E4" stroke-width="3"/>
                        </svg>
                    </div>
                </div>

                {{-- Pharmacy Information --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm space-y-4">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] pb-3 border-b border-[#F3F7FC]">Pharmacy Credentials</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 font-inter text-xs">
                        <div>
                            <span class="text-[#171E26]/50 block">Pharmacy Name</span>
                            <span class="font-semibold text-[#171E26] text-sm">Triple B Pharmacy</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block">Owner / Lead Admin</span>
                            <span class="font-semibold text-[#171E26] text-sm">Dr. Babatunde Bello</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block">Email Address</span>
                            <span class="font-semibold text-[#171E26]">contact@triplebpharm.com</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block">Phone Number</span>
                            <span class="font-semibold text-[#171E26]">+234 803 123 4567</span>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-[#171E26]/50 block">Physical Address</span>
                            <span class="font-semibold text-[#171E26]">14 Commercial Avenue, Sabo, Yaba, Lagos State</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Column 3: Platform Subscriptions & Settlements --}}
            <div class="space-y-6">
                
                {{-- Subscription Summary --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm space-y-3 font-inter">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] pb-2 border-b border-[#F3F7FC]">Subscription</h3>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-[#171E26]/60">Current Tier</span>
                        <span class="font-manrope font-bold text-xs text-[#2775E4] bg-[#E9F3FE] px-2 py-0.5 rounded">Pro Plan</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-[#171E26]/60">Status</span>
                        <span class="font-semibold text-xs text-emerald-600">Active</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-[#171E26]/60">Start Date</span>
                        <span class="font-medium text-xs text-[#171E26]">Jan 01, 2026</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-[#171E26]/60">Next Renewal</span>
                        <span class="font-medium text-xs text-[#171E26]">Jan 01, 2027</span>
                    </div>
                </div>

                {{-- Settlement Summary --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm space-y-3 font-inter">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] pb-2 border-b border-[#F3F7FC]">Settlement Configuration</h3>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-[#171E26]/60">Bank Account</span>
                        <span class="font-semibold text-xs text-emerald-600">Configured</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-[#171E26]/60">Total Settled</span>
                        <span class="font-manrope font-bold text-xs text-[#171E26]">₦2,150,000</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-[#171E26]/60">Pending Payout</span>
                        <span class="font-manrope font-bold text-xs text-[#08AEBC]">₦250,000</span>
                    </div>
                </div>

                {{-- Staff Operational Overview --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm space-y-3 font-inter">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] pb-2 border-b border-[#F3F7FC]">Staff Summary</h3>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-[#171E26]/60">Registered Accounts</span>
                        <span class="font-semibold text-xs text-[#171E26]">6 Staff Members</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-[#171E26]/60">Active Access</span>
                        <span class="font-semibold text-xs text-emerald-600">6 Active</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-layouts.superAdmin>