<x-layouts.staff title="Point of Sale" active="pos">

    {{-- Error banner — JS toggles this via style.display, unchanged behavior --}}
    <div id="pos-error"
         style="display: none;"
         class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- ================= ADD-TO-CART TOAST ================= --}}
    {{-- Brief, auto-dismissing confirmation shown by showToast() in pos.js.
         Positioned top-center so it never collides with the fixed mobile cart. --}}
    <div id="pos-toast"
         class="fixed top-4 left-1/2 -translate-x-1/2 z-[60] flex items-center gap-2 bg-[#171E26] text-white px-4 py-2.5 rounded-full shadow-lg font-inter text-[13px] font-medium opacity-0 -translate-y-3 pointer-events-none transition-all duration-300">
        <i class="ph-fill ph-check-circle text-[#08AEBC] text-base"></i>
        <span id="pos-toast-message">Added to cart</span>
    </div>

    {{-- Mobile-first: stacked by default, side-by-side from lg breakpoint up --}}
    <div id="pos-content" class="flex flex-col lg:flex-row gap-5 lg:gap-6">

        {{-- ================= PRODUCTS ================= --}}
        {{-- pb-[52vh] on mobile reserves room so the fixed bottom cart never
             covers the last row of products; lg: resets it since the cart
             goes back to being a normal sticky sidebar there. --}}
        <div class="flex-1 min-w-0 pb-[52vh] lg:pb-0">

            <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 mb-5">
                <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#171E26]/35 text-lg"></i>
                    <input type="text" id="pos-product-search"
                           placeholder="Search products by name or barcode..."
                           class="w-full rounded-xl border border-[#DBEBFB] pl-11 pr-4 py-3 font-inter text-[15px] text-[#171E26] placeholder:text-[#171E26]/35 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>
            </div>

            <div id="pos-product-grid"
                 class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                {{-- Populated by pos.js --}}
            </div>
        </div>

        {{-- ================= CART / CHECKOUT ================= --}}
        {{-- Mobile (below lg): fixed to the bottom of the viewport so the cart
             is always visible as items are added, with its own scroll area
             capped at 50vh so it never eats the whole screen. From lg: up,
             this reverts to the original static sticky sidebar — nothing
             about the desktop layout changes. --}}
        <div class="w-full lg:w-[380px] flex-shrink-0">
            <div class="fixed bottom-0 left-0 right-0 z-40 bg-white rounded-t-2xl border-t border-[#EAF1FB] shadow-[0_-6px_24px_rgba(23,30,38,0.12)] p-4 max-h-[50vh] overflow-y-auto
                        lg:static lg:z-auto lg:rounded-2xl lg:border lg:shadow-none lg:p-5 lg:sticky lg:top-24 lg:max-h-none lg:overflow-visible">

                <p class="font-manrope font-bold text-[16px] text-[#171E26] mb-3">Current Sale</p>

                <div id="pos-cart-items" class="max-h-[320px] overflow-y-auto sidebar-scroll -mx-1 px-1">
                    {{-- Populated by pos.js --}}
                </div>

                <div class="mt-5 space-y-4">

                    <div>
                        <label for="pos-customer-name" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Customer Name</label>
                        <input type="text" id="pos-customer-name" placeholder="Walk-in Customer"
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] placeholder:text-[#171E26]/35 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    </div>

                    <div>
                        <label for="pos-payment-method" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Payment Method</label>
                        <div class="relative">
                            <select id="pos-payment-method"
                                    class="w-full appearance-none rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 pr-10 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition bg-white">
                                <option value="cash">Cash</option>
                                <option value="pos">POS</option>
                                <option value="transfer">Transfer</option>
                            </select>
                            <i class="ph ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/40 pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label for="pos-discount" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Discount Amount</label>
                        <input type="number" id="pos-discount" min="0" step="0.01" value="0"
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    </div>

                    <div class="border-t border-[#EAF1FB] pt-4 space-y-2">
                        <div class="flex justify-between font-inter text-[14px] text-[#171E26]/70">
                            <span>Subtotal</span>
                            <strong id="pos-subtotal" class="font-semibold text-[#171E26]">₦0</strong>
                        </div>
                        <div class="flex justify-between font-inter text-[14px] text-[#171E26]/70">
                            <span>Discount</span>
                            <strong id="pos-discount-display" class="font-semibold text-[#171E26]">₦0</strong>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-[#EAF1FB]">
                            <span class="font-inter text-[15px] font-semibold text-[#171E26]">Total</span>
                            <strong id="pos-total" class="font-manrope text-[20px] font-extrabold text-[#2775E4]">₦0</strong>
                        </div>
                    </div>

                    <div class="flex gap-2.5 pt-1">
                        <button type="button" id="pos-clear-cart-btn"
                                class="px-4 py-3 rounded-xl border border-[#DBEBFB] font-inter text-[14px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
                            Clear
                        </button>
                        <button type="button" id="pos-checkout-btn"
                                class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                            Complete Sale
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ================= RECEIPT MODAL ================= --}}
    {{-- Note the id="receipt-printable" wrapper around the actual receipt card —
         the print stylesheet below targets that specifically, so printing only
         outputs the receipt itself, not the dark backdrop or the rest of the app. --}}
    <div id="receipt-modal"
         style="display:none; position:fixed; inset:0; z-index:50; align-items:center; justify-content:center; background-color:rgba(23,30,38,0.4); padding:0 16px;">
        <div id="receipt-printable" class="bg-white rounded-2xl w-full max-w-[500px] max-h-[90vh] overflow-y-auto p-6">
            <div class="flex items-center justify-between mb-5 receipt-no-print">
                <h3 class="font-manrope text-lg font-bold text-[#171E26]">Receipt</h3>
                <button type="button" id="close-receipt-btn" aria-label="Close"
                        class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <div id="receipt-content">
                {{-- Populated by pos.js --}}
            </div>

            <div class="mt-6 flex items-center justify-center gap-3 receipt-no-print">
                <button type="button" id="print-receipt-btn"
                        class="px-5 py-3 rounded-xl border border-[#DBEBFB] font-inter text-[14px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition flex items-center gap-2">
                    <i class="ph ph-printer text-lg"></i> Print Receipt
                </button>
                <button type="button" id="new-sale-btn"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition">
                    New Sale
                </button>
            </div>
        </div>
    </div>

    {{-- ================= CUSTOM MODAL (replaces alert/confirm) ================= --}}
    {{-- Generic reusable modal. When opened in "sticky" mode, clicking the
         backdrop does nothing — only the action button closes it.
         FIXED: title/message font sizes changed from text-[17px]/text-[14px]
         to text-[16px]/text-[13px] to exactly match the logout confirm modal
         on /customer/extra (and the other confirm/alert modals across the
         app) — colors themselves are set inline by pos.js's MODAL_STYLES. --}}
    <div id="app-modal"
         style="display:none; position:fixed; inset:0; z-index:70; align-items:center; justify-content:center; background-color:rgba(23,30,38,0.45); padding:0 16px;">
        <div class="bg-white rounded-2xl w-full max-w-[380px] p-6 text-center">
            <div id="app-modal-icon" class="mx-auto mb-4 h-14 w-14 rounded-full flex items-center justify-center">
                <i class="ph ph-warning text-2xl"></i>
            </div>
            <h3 id="app-modal-title" class="font-manrope text-[16px] font-bold text-[#171E26] mb-1.5">Notice</h3>
            <p id="app-modal-message" class="font-inter text-[13px] text-[#171E26]/60 leading-relaxed mb-6"></p>
            <button type="button" id="app-modal-btn"
                    class="w-full px-5 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition">
                Got it
            </button>
        </div>
    </div>

    <style>
        /* Print only the receipt card when the browser print dialog is triggered */
        @media print {
            body * { visibility: hidden; }
            #receipt-printable, #receipt-printable * { visibility: visible; }
            #receipt-printable {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                max-height: none;
                box-shadow: none;
                border: none;
            }
            .receipt-no-print { display: none !important; }
        }
    </style>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/receipt-template.js') }}"></script>
        <script src="{{ asset('assets/minimal/js/staff/pos.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>