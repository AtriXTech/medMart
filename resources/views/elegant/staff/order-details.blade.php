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
                <div class="flex items-center gap-2.5 self-start sm:self-auto">
                    <button type="button" id="print-order-btn"
                            class="btn btn-secondary inline-flex items-center gap-2 justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold bg-white border border-[#DBEBFB] text-[#171E26]
                                   hover:bg-[#F7FAFD] hover:border-[#2775E4] hover:text-[#2775E4] transition">
                        <i class="ph ph-printer text-base"></i> Print Receipt
                    </button>
                    <a href="/staff/orders"
                       class="btn btn-secondary inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                              font-inter text-[12.5px] font-semibold bg-white border border-[#DBEBFB] text-[#171E26]
                              hover:bg-[#F7FAFD] hover:border-[#2775E4] hover:text-[#2775E4] transition">
                        Back to Orders
                    </a>
                </div>
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

        {{-- Hidden on screen. This is what actually prints — built to look exactly
             like the POS receipt (see order-details.js renderOrderReceipt()) rather
             than printing the raw management cards above. --}}
        <div id="order-receipt-printable" class="hidden">
            <div id="order-receipt-content"></div>
        </div>

        <div class="card order-no-print rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <p class="section-title font-manrope font-bold text-[16px] text-[#171E26] mb-4">Update Status</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="field">
                    <label for="status-select" class="field-label">Order Status</label>
                    <div class="relative">
                        <select id="status-select" class="field-input appearance-none pr-9">
                            <option value="">Select Order Status</option>
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
                        {{-- FIXED: options were "Pickup"/"Delivery" (fulfillment-type
                             values, wrong domain entirely) instead of real delivery
                             status values. Restored to pending/shipped/delivered,
                             which is what this field is actually meant to hold and
                             what the backend's delivery_status column expects. --}}
                        <select id="delivery-status-select" class="field-input appearance-none pr-9">
                            <option value="">Select Delivery Status</option>
                            <option value="pending">Pending</option>
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

    {{-- NEW: small info modal, used only to replace the native alert() that fires
         when an order-status update is rejected (e.g. trying to move status
         backward, which is intentionally blocked server-side). Single message +
         OK button, matching your existing modal-backdrop/btn classes. --}}
    <div id="info-modal"
         class="modal-backdrop fixed inset-0 z-[60] items-center justify-center p-4
                bg-[#171E26]/45 backdrop-blur-[2px]"
         style="display: none;">
        <div class="modal-content w-full max-w-[380px]
                    bg-white border border-[#EAF1FB] rounded-2xl
                    shadow-[0_28px_64px_-28px_rgba(23,30,38,0.35)]
                    p-6 text-center">
            <div class="mx-auto mb-4 h-14 w-14 rounded-full flex items-center justify-center bg-red-50">
                <i class="ph ph-warning text-2xl text-red-600"></i>
            </div>
            <h3 id="info-modal-title" class="font-manrope text-[17px] font-extrabold text-[#171E26] mb-1.5">Unable to Update Status</h3>
            <p id="info-modal-message" class="font-inter text-[13.5px] text-[#171E26]/60 leading-relaxed mb-6"></p>
            <button type="button" id="info-modal-close-btn"
                    class="btn btn-primary w-full justify-center
                           bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white
                           shadow-lg shadow-[#2775E4]/20">OK</button>
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

        {{-- Print scoping: identical technique to the POS receipt. Hide the whole
             page, reveal only #order-receipt-printable, and hide anything inside
             it that's marked order-no-print (the header row/buttons). Anything
             marked order-print-only is hidden on screen and revealed only here. --}}
        <style>
            @media print {
                body * { visibility: hidden; }
                #order-receipt-printable, #order-receipt-printable * { visibility: visible; }
                #order-receipt-printable {
                    display: block !important;
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                }
            }
        </style>

        <script src="{{ asset('assets/minimal/js/staff/receipt-template.js') }}"></script>
        <script src="{{ asset('assets/minimal/js/staff/order-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>