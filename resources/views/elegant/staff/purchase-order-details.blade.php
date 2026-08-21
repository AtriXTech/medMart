{{--
    Intended path: resources/views/staff/purchase-order-details.blade.php

    CHANGE SUMMARY:
    - Every static ID purchase-order-details.js binds to is preserved
      exactly: po-error, po-loading, po-content, po-info, po-items-table,
      receive-btn, cancel-btn, receive-modal, receive-form, receive-items,
      receive-error, receive-submit-btn, close-receive-btn, cancel-receive-btn.
    - The dynamic per-item IDs the JS generates and later reads back
      (receive-quantity-${id}, receive-batch-${id}, receive-expiry-${id})
      are untouched — I didn't rename anything in renderReceiveForm()'s
      output pattern.
    - #receive-btn / #cancel-btn: the JS toggles these with
      style.display = 'inline-flex' / 'none' (not 'block'), so I kept
      that exact value rather than switching to a Tailwind class, same
      "don't mix class-based and inline-style visibility" rule as before.
    - #po-error, #po-loading→#po-content, #receive-modal, #receive-error:
      all plain inline style="display:none" to match what the JS toggles.
    - Not changed, just flagging: cancelBtn still uses a native confirm()
      dialog before cancelling an order. Left as-is, same reasoning as the
      prompt() note on the PO create page — interaction change, not styling.
--}}
<x-layouts.staff title="Purchase Order Details" active="purchase-orders">

    <div id="po-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div id="po-loading">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3 mb-4">
            <div class="skel h-6 w-1/3"></div>
            <div class="skel h-16 w-full"></div>
        </div>
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3">
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
        </div>
    </div>

    <div id="po-content" style="display: none;">

        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-5 md:p-7 mb-5">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Purchase Order Information</h3>
                <div class="flex items-center gap-2.5">
                    <a href="/staff/purchase-orders"
                        class="px-4 py-2 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[13px] text-[#171E26] hover:bg-[#F7FAFD]">Back</a>
                    <button id="receive-btn" type="button" style="display: none; background:#2E9E5B;"
                        class="items-center gap-1.5 px-4 py-2 rounded-xl font-inter font-semibold text-[13px] text-white">
                        <i class="ph-light ph-package"></i> Receive Items
                    </button>
                    <button id="cancel-btn" type="button" style="display: none;"
                        class="items-center gap-1.5 px-4 py-2 rounded-xl font-inter font-semibold text-[13px] text-[#9C3A32] hover:bg-[#FDEDEC] border border-[#F5C9C4]">
                        Cancel Order
                    </button>
                </div>
            </div>
            <div id="po-info"></div>
        </div>

        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <h3 class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Order Items</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Product</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Ordered</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Received</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Cost Price</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody id="po-items-table"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- RECEIVE ITEMS MODAL --}}
    <div id="receive-modal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center bg-[#171E26]/50 px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#EAF1FB]">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Receive Items</h3>
                <button type="button" id="close-receive-btn" class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50 text-xl leading-none">&times;</button>
            </div>

            <div class="px-5 pt-4">
                <div id="receive-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3"></div>
            </div>

            <form id="receive-form" class="px-5 py-5">
                <div id="receive-items"></div>
                <div class="flex items-center justify-end gap-3 mt-5 pt-4 border-t border-[#EAF1FB]">
                    <button type="button" id="cancel-receive-btn"
                        class="px-4 py-2.5 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[13px] text-[#171E26] hover:bg-[#F7FAFD]">Cancel</button>
                    <button type="submit" id="receive-submit-btn"
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13px] shadow-sm shadow-[#2775E4]/20 disabled:opacity-60">Receive Items</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/purchase-order-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>