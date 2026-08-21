<x-layouts.staff title="Order Details" active="orders">

    <div class="alert alert-error mb-4 rounded-xl border border-[#F5C9C4] bg-[#FEF2F2] text-[#9C3A32]
                font-inter text-[13.5px] font-medium px-4 py-3"
         id="order-error" style="display: none;"></div>

    <div id="order-loading" class="loading-state py-16 text-center">
        <div class="inline-flex flex-col items-center gap-3">
            <div class="h-10 w-10 rounded-full border-2 border-[#DBEBFB] border-t-[#2775E4] animate-spin"></div>
            <p class="font-inter text-sm text-[#171E26]/50">Loading order details...</p>
        </div>
    </div>

    <div id="order-content" style="display: none;">

        <div class="card mb-4 md:mb-5 rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5">
                <p class="section-title m-0 font-manrope font-bold text-[16px] text-[#171E26]">Order Information</p>
                <a href="/staff/orders"
                   class="btn btn-secondary self-start sm:self-auto inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                          font-inter text-[12.5px] font-semibold bg-white border border-[#DBEBFB] text-[#171E26]
                          hover:bg-[#F7FAFD] hover:border-[#2775E4] hover:text-[#2775E4] transition">
                    Back to Orders
                </a>
            </div>
            <div id="order-info"
                 class="[&>div]:!gap-3
                        [&>div>div]:bg-[#F7FAFD] [&>div>div]:border [&>div>div]:border-[#EAF1FB]
                        [&>div>div]:rounded-xl [&>div>div]:px-4 [&>div>div]:py-3.5
                        [&>div>div]:font-inter [&>div>div]:text-[13.5px] [&>div>div]:text-[#171E26] [&>div>div]:leading-snug
                        [&_strong]:block [&_strong]:text-[11px] [&_strong]:font-semibold [&_strong]:tracking-wide
                        [&_strong]:uppercase [&_strong]:text-[#171E26]/40 [&_strong]:mb-1.5"></div>
        </div>

        <div class="card mb-4 md:mb-5 rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <p class="section-title font-manrope font-bold text-[16px] text-[#171E26] mb-4">Order Items</p>
            <div class="overflow-x-auto -mx-1 px-1">
                <table class="w-full min-w-[560px] text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Product</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Quantity</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Unit Price</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3">Total</th>
                        </tr>
                    </thead>
                    <tbody id="order-items-table"
                           class="[&_td]:font-inter [&_td]:text-[13.5px] [&_td]:text-[#171E26]
                                  [&_td]:py-3.5 [&_td]:pr-4 [&_td]:border-b [&_td]:border-[#EAF1FB] [&_td]:align-middle
                                  [&_tr:last-child_td]:border-b-0
                                  [&_tr:hover_td]:bg-[#F7FAFD]"></tbody>
                </table>
            </div>
        </div>

        <div class="card rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <p class="section-title font-manrope font-bold text-[16px] text-[#171E26] mb-4">Update Status</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="field">
                    <label for="status-select" class="field-label">Order Status</label>
                    <div class="relative">
                        <select id="status-select" class="field-input appearance-none pr-9">
                            <option value="processing">Processing</option>
                            <option value="ready_for_pickup">Ready for Pickup</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/35 pointer-events-none text-sm"></i>
                    </div>
                </div>

                <div class="field">
                    <label for="status-reason" class="field-label">Reason (for cancellation)</label>
                    <input type="text" id="status-reason" class="field-input" placeholder="Optional reason">
                </div>

                <div class="flex items-end">
                    <button type="button"
                            class="btn btn-primary w-full sm:w-auto inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold text-white
                                   bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                                   shadow-lg shadow-[#2775E4]/20 hover:shadow-xl hover:shadow-[#2775E4]/25 transition"
                            id="update-status-btn">
                        Update Status
                    </button>
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-[#EAF1FB] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="field">
                    <label for="delivery-status-select" class="field-label">Delivery Status</label>
                    <div class="relative">
                        <select id="delivery-status-select" class="field-input appearance-none pr-9">
                            <option value="">Select Delivery Status</option>
                            <option value="pending">Pending</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/35 pointer-events-none text-sm"></i>
                    </div>
                </div>

                <div class="flex items-end sm:col-span-1 lg:col-span-2">
                    <button type="button"
                            class="btn btn-secondary w-full sm:w-auto inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold bg-white border border-[#DBEBFB] text-[#171E26]
                                   hover:bg-[#F7FAFD] hover:border-[#2775E4] hover:text-[#2775E4] transition"
                            id="update-delivery-btn">
                        Update Delivery Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <style type="text/tailwindcss">
            .badge {
                @apply inline-flex items-center rounded-full px-2.5 py-0.5 font-inter text-[11px] font-semibold tracking-wide capitalize whitespace-nowrap;
            }
            .badge-success { @apply bg-emerald-50 text-emerald-700; }
            .badge-danger  { @apply bg-red-50 text-red-700; }
            .badge-warning { @apply bg-amber-50 text-amber-700; }
            .badge-muted   { @apply bg-[#EAF1FB] text-[#171E26]/70; }

            .btn {
                @apply inline-flex items-center justify-center rounded-[0.65rem] px-3.5 py-2
                       font-inter text-[12.5px] font-semibold cursor-pointer transition
                       disabled:opacity-45 disabled:cursor-not-allowed;
            }
            .btn-secondary {
                @apply bg-white border border-[#DBEBFB] text-[#171E26]
                       hover:bg-[#F7FAFD] hover:border-[#2775E4] hover:text-[#2775E4];
            }
            .btn-primary {
                @apply text-white bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                       shadow-lg shadow-[#2775E4]/20;
            }

            .empty-state {
                @apply text-center py-10 px-4 font-inter text-sm text-[#171E26]/45;
            }
        </style>
        <script src="{{ asset('assets/minimal/js/staff/order-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>