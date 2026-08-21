{{--
    Intended path: resources/views/staff/dashboard.blade.php

    CHANGE SUMMARY:
    - Kept the exact loading/content/error pattern: #dashboard-error,
      #dashboard-loading, #dashboard-content are still the three states
      dashboard.js toggles. Nothing about that contract changed.
    - #stat-grid and #status-table-body no longer exist — dashboard.js
      was rewritten to match this new markup (new element IDs below).
      See dashboard.js change summary for the full mapping.
    - Added: KPI grid with visual hierarchy (primary vs operational),
      Orders by Status bar chart, Today's Performance bar chart,
      Needs Attention cards, Recent Orders list.
    - Chart.js is loaded here (page-specific), not in the shared layout,
      since only this page needs it.
--}}
<x-layouts.staff title="Dashboard" active="dashboard">

    <div id="dashboard-error" class="hidden rounded-2xl bg-white border border-[#F5C9C4] p-8 text-center mb-6">
        <i class="ph-light ph-warning-circle text-3xl text-[#9C3A32] mb-2"></i>
        <p class="font-manrope font-bold text-[16px] text-[#171E26]" id="dashboard-error-text">Unable to load dashboard.</p>
        <button onclick="location.reload()" class="mt-4 px-5 py-2.5 rounded-xl bg-[#2775E4] text-white font-inter font-semibold text-[13px]">Try Again</button>
    </div>

    <div id="dashboard-loading">
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

        {{-- PRIMARY + OPERATIONAL METRICS --}}
        <div id="stat-grid-primary" class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4"></div>
        <div id="stat-grid-operational" class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4 mb-8"></div>

        {{-- ANALYTICS: ORDERS BY STATUS + TODAY'S PERFORMANCE --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

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

        {{-- NEEDS ATTENTION --}}
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 mb-4">
            <h3 class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Needs Attention</h3>
            <div id="attentionWrap" class="grid grid-cols-1 sm:grid-cols-2 gap-3"></div>
        </div>

        {{-- RECENT ORDERS --}}
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 mb-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Recent Orders</h3>
                <a href="/staff/orders" class="font-inter text-[13px] font-semibold text-[#2775E4] hover:underline flex items-center gap-1">View all orders <i class="ph-light ph-arrow-right"></i></a>
            </div>
            <div id="ordersTableWrap" class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
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
            <div id="ordersCardsWrap" class="md:hidden space-y-3"></div>
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