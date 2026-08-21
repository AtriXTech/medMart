{{--
    Intended path: resources/views/staff/purchase-order-create.blade.php

    CHANGE SUMMARY:
    - Every ID purchase-order-create.js binds to is preserved exactly:
      po-error, po-content, po-supplier, po-expected-date, po-notes,
      po-product-search, po-product-results, po-items, po-submit-btn,
      po-total.
    - #po-error and #po-product-results use plain inline style="display:none"
      (not Tailwind's `hidden` class), matching what the JS toggles.
    - #po-content has no display toggle here, same as the original — this
      page has no async loading gate, so it's just always visible.
    - Flagging, not changing: addItem() in the JS still uses a native
      browser prompt() to ask for cost price when you click a search
      result. That's a real UX rough edge (feels dated next to the rest
      of this design) but swapping it for an inline field/modal is an
      interaction change, not a styling one — left it exactly as-is per
      your "don't touch logic" instruction. Say the word if you want that
      replaced with something nicer.
--}}
<x-layouts.staff title="Create Purchase Order" active="purchase-orders">

    <div id="po-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">Create Purchase Order</h2>
        <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">Order stock from one of your suppliers</p>
    </div>

    <div id="po-content" class="max-w-3xl">

        {{-- ORDER DETAILS --}}
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-5 md:p-7 mb-5">
            <h3 class="font-manrope font-bold text-[15px] text-[#171E26] mb-4">Order Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="po-supplier" class="field-label">Supplier</label>
                    <select id="po-supplier" required class="field-input">
                        <option value="">Select Supplier</option>
                    </select>
                </div>
                <div>
                    <label for="po-expected-date" class="field-label">Expected Date</label>
                    <input type="date" id="po-expected-date" class="field-input">
                </div>
                <div>
                    <label for="po-notes" class="field-label">Notes</label>
                    <input type="text" id="po-notes" class="field-input">
                </div>
            </div>
        </div>

        {{-- ADD PRODUCTS --}}
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-5 md:p-7 mb-5">
            <h3 class="font-manrope font-bold text-[15px] text-[#171E26] mb-4">Add Products</h3>
            <div class="relative">
                <i class="ph-light ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/35 text-[16px]"></i>
                <input type="text" id="po-product-search" placeholder="Search products..." class="field-input pl-10">

                <div id="po-product-results" style="display: none;"
                    class="absolute left-0 right-0 mt-2 max-h-[300px] overflow-y-auto bg-white border border-[#EAF1FB] rounded-xl shadow-lg z-10"></div>
            </div>
        </div>

        {{-- ORDER ITEMS --}}
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-5 md:p-7 mb-6">
            <h3 class="font-manrope font-bold text-[15px] text-[#171E26] mb-2">Order Items</h3>
            <div id="po-items"></div>
            <div class="flex justify-end pt-4 mt-2 border-t border-[#EAF1FB]">
                <p class="font-manrope font-extrabold text-[18px] text-[#171E26]">Total: <span id="po-total">₦0</span></p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="/staff/purchase-orders"
                class="px-5 py-2.5 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[13px] text-[#171E26] hover:bg-[#F7FAFD]">Cancel</a>
            <button id="po-submit-btn" type="button"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13px] shadow-sm shadow-[#2775E4]/20 disabled:opacity-60">Create Purchase Order</button>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/purchase-order-create.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>