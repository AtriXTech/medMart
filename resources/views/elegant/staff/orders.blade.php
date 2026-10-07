<x-layouts.staff title="Orders" active="orders">

    <div id="orders-error"
         style="display: none;"
         class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    <div id="orders-loading" class="py-20 text-center">
        <i class="ph ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Loading orders...</p>
    </div>

    <div id="orders-content" style="display: none;">

        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 mb-5">
            <div class="max-w-[260px]">
                <label for="status-filter" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Filter by Status</label>
                <div class="relative">
                    <select id="status-filter"
                            class="w-full appearance-none rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 pr-9 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition bg-white">
                        <option value="">All Statuses</option>
                        <option value="pending_payment">Pending Payment</option>
                        <option value="paid">Paid</option>
                        <option value="received">Received</option>
                        <option value="processing">Processing</option>
                        <option value="ready_for_pickup">Ready for Pickup / Dispatch</option>
                        <option value="completed">Completed (Delivered / Picked Up)</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/40 pointer-events-none text-sm"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Order #</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Customer</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Total</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Status</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Delivery</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Items</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Date</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body">
                    </tbody>
                </table>
            </div>

            <div id="pagination-container" class="mt-5 flex items-center justify-center"></div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/orders.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>
