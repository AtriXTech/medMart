<x-layouts.staff title="Product Details" active="products">

    {{-- Error banner --}}
    <div id="product-error"
         style="display: none;"
         class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- Loading state — JS toggles via style.display = 'block'/'none' --}}
    <div id="product-loading" class="py-20 text-center">
        <i class="ph ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Loading product details...</p>
    </div>

    {{-- Main content — JS toggles via style.display = 'block'/'none' --}}
    <div id="product-content" style="display: none;">

        {{-- Product Information --}}
        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5 mb-5">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <p class="font-manrope font-bold text-[16px] text-[#171E26]">Product Information</p>
                <a href="/staff/products"
                   class="flex items-center gap-1.5 rounded-lg border border-[#DBEBFB] px-3.5 py-2 font-inter text-[13px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
                    <i class="ph ph-arrow-left text-base"></i>
                    Back to Products
                </a>
            </div>
            <div id="product-info">
                {{-- Populated by product-details.js --}}
            </div>
        </div>

        {{-- Batches --}}
        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5 mb-5">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <p class="font-manrope font-bold text-[16px] text-[#171E26]">Batches</p>
                <button type="button" id="add-batch-btn"
                        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition">
                    Add Batch
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Batch Number</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Quantity</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Cost Price</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Expiry Date</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="batches-table-body">
                        {{-- Populated by product-details.js --}}
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Stock Movements --}}
        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5">
            <p class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Stock Movements</p>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Type</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Quantity</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Reason</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Staff</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Date</th>
                        </tr>
                    </thead>
                    <tbody id="movements-table-body">
                        {{-- Populated by product-details.js --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= ADD BATCH MODAL ================= --}}
    {{-- Critical overlay positioning set via inline style, not Tailwind classes,
         so it can't silently break due to arbitrary-value class compilation issues.
         JS only ever toggles style.display between 'none' and 'flex', unchanged.
         Note: this modal has no click-outside-to-close listener in the original JS —
         intentionally not adding one here either. --}}
    <div id="batch-modal"
         style="display:none; position:fixed; inset:0; z-index:50; align-items:center; justify-content:center; background-color:rgba(23,30,38,0.4); padding:24px 16px;">
        <div class="bg-white rounded-2xl w-full max-w-[440px] max-h-[90vh] overflow-y-auto p-6">

            <div class="flex items-center justify-between mb-4">
                <h3 class="font-manrope text-lg font-bold text-[#171E26]">Add Batch</h3>
                <button type="button" id="close-batch-modal-btn" aria-label="Close"
                        class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <div id="batch-form-error"
                 style="display: none;"
                 class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
            </div>

            <form id="batch-form" class="space-y-4">

                <div>
                    <label for="batch-number" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Batch Number</label>
                    <input type="text" id="batch-number" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div>
                    <label for="batch-expiry" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Expiry Date</label>
                    <input type="date" id="batch-expiry" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div>
                    <label for="batch-quantity" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Quantity</label>
                    <input type="number" id="batch-quantity" min="1" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div>
                    <label for="batch-cost" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Cost Price</label>
                    <input type="number" id="batch-cost" step="0.01" min="0" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div class="flex justify-end gap-2.5 pt-2">
                    <button type="button" id="cancel-batch-modal-btn"
                            class="px-5 py-2.5 rounded-xl border border-[#DBEBFB] font-inter text-[14px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
                        Cancel
                    </button>
                    <button type="submit" id="batch-submit-btn"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                        Add Batch
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/product-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>