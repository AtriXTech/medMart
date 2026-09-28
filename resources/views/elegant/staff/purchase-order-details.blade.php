{{--
    Intended path: resources/views/staff/purchase-order-details.blade.php

    CHANGE SUMMARY (vs. previous version):
    - UNCHANGED: every static ID purchase-order-details.js binds to
      (po-error, po-loading, po-content, po-info, po-items-table,
      receive-btn, cancel-btn, receive-modal, receive-form, receive-items,
      receive-error, receive-submit-btn, close-receive-btn,
      cancel-receive-btn), the dynamic per-item receive-* IDs, the
      inline-style display toggling on receive-btn/cancel-btn
      ('inline-flex'/'none'), the receive modal, everything else.
    - NEW: #confirm-modal, replacing the native confirm() dialog that
      used to run on "Cancel Order". Follows the same overlay pattern
      already used by #receive-modal on this page (Tailwind
      fixed/inset-0/z-50 classes + style.display 'flex'/'none' toggle),
      and reuses this page's own existing color tokens: the danger
      (red/#9C3A32-family) styling already used on the Cancel Order
      button, and the neutral border/[#DBEBFB] styling already used on
      Back/Cancel-style buttons elsewhere on this page.
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

    {{-- CONFIRM MODAL (Cancel Order) --}}
    {{-- NEW: replaces native confirm(). Same overlay pattern as #receive-modal
         above (fixed inset-0 z-50, style.display 'none'/'flex'). --}}
    <div id="confirm-modal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center bg-[#171E26]/50 px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-[380px] p-6 text-center">
            <div id="confirm-modal-icon" class="mx-auto mb-4 h-14 w-14 rounded-full flex items-center justify-center bg-[#FDEDEC]">
                <i class="ph-light ph-warning text-2xl text-[#9C3A32]"></i>
            </div>
            <h3 id="confirm-modal-title" class="font-manrope font-bold text-[16px] text-[#171E26] mb-1.5">Cancel purchase order?</h3>
            <p id="confirm-modal-message" class="font-inter text-[13px] text-[#171E26]/60 leading-relaxed mb-6"></p>
            <div class="flex items-center gap-2.5">
                <button type="button" id="confirm-modal-cancel-btn"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[13px] text-[#171E26] hover:bg-[#F7FAFD]">No, go back</button>
                <button type="button" id="confirm-modal-confirm-btn"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-[#F5C9C4] font-inter font-semibold text-[13px] text-[#9C3A32] hover:bg-[#FDEDEC]">Cancel Order</button>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/purchase-order-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>