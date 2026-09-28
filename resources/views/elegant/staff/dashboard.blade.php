<x-layouts.staff title="Dashboard" active="dashboard">

    <div id="dashboard-error" class="hidden rounded-2xl bg-white border border-[#F5C9C4] p-8 text-center mb-6">
        <i class="ph-light ph-warning-circle text-3xl text-[#9C3A32] mb-2"></i>
        <p class="font-manrope font-bold text-[16px] text-[#171E26]" id="dashboard-error-text">Unable to load dashboard.</p>
        <button onclick="location.reload()" class="mt-4 px-5 py-2.5 rounded-xl bg-[#2775E4] text-white font-inter font-semibold text-[13px]">Try Again</button>
    </div>

    <div id="dashboard-loading">
        <div class="skel h-[100px] rounded-2xl mb-4"></div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4">
            <div class="skel h-[104px] rounded-2xl"></div>
            <div class="skel h-[104px] rounded-2xl"></div>
            <div class="skel h-[104px] rounded-2xl"></div>
            <div class="skel h-[104px] rounded-2xl"></div>
        </div>
        <div class="skel h-[260px] rounded-2xl mb-4"></div>
        <div class="skel h-[200px] rounded-2xl"></div>
    </div>

    <div id="dashboard-content" class="hidden">

        {{-- WELCOME --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] p-5 md:p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <i id="welcome-icon" class="ph-fill ph-sun text-white text-xl"></i>
                        <p class="font-manrope font-extrabold text-[20px] md:text-[22px] text-white" id="welcome-greeting">Good morning</p>
                    </div>
                    <p class="font-inter text-[13px] text-white/80 mt-1" id="welcome-pharmacy">Your Pharmacy</p>
                    <p class="font-inter text-[12px] text-white/60 mt-0.5" id="welcome-date">Today</p>
                </div>
                <div class="flex gap-2.5 flex-shrink-0">
                    <a href="/staff/pos" class="px-4 py-2.5 rounded-xl bg-white text-[#2775E4] font-inter text-[13px] font-semibold hover:opacity-90 transition">Open POS</a>
                    <a href="/staff/orders" class="px-4 py-2.5 rounded-xl bg-white/15 border border-white/30 text-white font-inter text-[13px] font-semibold hover:bg-white/20 transition">View Orders</a>
                </div>
            </div>
        </div>

        {{-- BUSINESS PERFORMANCE --}}
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-2.5">Business Performance</p>
        <div id="stat-grid-primary" class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4"></div>
        <div id="stat-grid-secondary" class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-8"></div>

        {{-- ANALYTICS --}}
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-2.5">Analytics</p>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">

            <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Orders by Status</h3>
                <p class="font-inter text-[12px] text-[#171E26]/45 mt-0.5 mb-4">All-time order distribution</p>
                <div id="statusChartWrap" class="min-h-[240px]"></div>
            </div>

            <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Today's Performance</h3>
                <p class="font-inter text-[12px] text-[#171E26]/45 mt-0.5 mb-4">Customer vs POS revenue today</p>
                <div id="performanceChartWrap" class="min-h-[240px]"></div>
            </div>

        </div>

        {{-- BUSINESS HEALTH --}}
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-2.5">Business Health</p>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">

            <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Inventory Health</h3>
                <div id="inventoryHealthWrap" class="grid grid-cols-2 gap-3"></div>
            </div>

            <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Needs Attention</h3>
                <div id="attentionWrap" class="grid grid-cols-1 gap-3"></div>
            </div>

        </div>

        {{-- RECENT ACTIVITY --}}
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-2.5">Recent Activity</p>
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 mb-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Recent Orders</h3>
                <a href="/staff/orders" class="font-inter text-[13px] font-semibold text-[#2775E4] hover:underline flex items-center gap-1">View all orders <i class="ph-light ph-arrow-right"></i></a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] text-left">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3">Order</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3">Customer</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3">Amount</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3">Status</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3">Date</th>
                            <th class="pb-3"></th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody"></tbody>
                </table>
            </div>
        </div>

    </div>

    <x-slot:scripts>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
        <style>
            .skel{ position:relative; overflow:hidden; background:#EAF1FB; }
            .skel::after{ content:''; position:absolute; inset:0; background:linear-gradient(90deg, transparent, rgba(255,255,255,0.7), transparent); transform:translateX(-100%); animation:shimmer 1.4s infinite; }
            @keyframes shimmer{ 100%{ transform:translateX(100%); } }
            @media (prefers-reduced-motion: reduce){ .skel::after{ animation:none; } }
        </style>
        <script src="{{ asset('assets/minimal/js/staff/dashboard.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>