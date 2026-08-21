{{--
    Intended path: resources/views/staff/purchase-orders.blade.php

    CHANGE SUMMARY:
    - Every ID purchase-orders.js binds to is preserved exactly:
      purchase-orders-error, purchase-orders-loading, purchase-orders-content,
      purchase-orders-table-body, status-filter, pagination-container,
      create-po-btn.
    - <option> values for status-filter unchanged: "", ordered,
      partially_received, received, cancelled.
    - #purchase-orders-error / #purchase-orders-loading / #purchase-orders-content
      use plain inline style="display:none" (not Tailwind's `hidden` class),
      matching what purchase-orders.js actually toggles.
    - purchase-orders.js itself: same endpoint (GET /staff/purchase-orders
      with page/per_page/status params), same pagination logic (data.meta.last_page),
      same window.viewPurchaseOrder redirect, same create/filter button behavior.
--}}
<x-layouts.staff title="Purchase Orders" active="purchase-orders">

    <div id="purchase-orders-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">Purchase Orders</h2>
            <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">Orders placed with your suppliers</p>
        </div>
        <button id="create-po-btn" type="button"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13px] shadow-sm shadow-[#2775E4]/20 hover:scale-[1.02] transition flex-shrink-0">
            <i class="ph-light ph-plus text-base"></i> Create Purchase Order
        </button>
    </div>

    <div id="purchase-orders-loading">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3">
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
        </div>
    </div>

    <div id="purchase-orders-content" style="display: none;">

        <div class="mb-4 max-w-[240px]">
            <label for="status-filter" class="field-label">Filter by Status</label>
            <select id="status-filter" class="field-input">
                <option value="">All Statuses</option>
                <option value="ordered">Ordered</option>
                <option value="partially_received">Partially Received</option>
                <option value="received">Received</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">PO ID</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Supplier</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Status</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Expected Date</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Created</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="purchase-orders-table-body"></tbody>
                </table>
            </div>
            <div id="pagination-container" class="flex items-center justify-center gap-2 mt-5 pt-4 border-t border-[#EAF1FB]"></div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/purchase-orders.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>