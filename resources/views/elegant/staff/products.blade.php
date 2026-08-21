<x-layouts.staff title="Products" active="products">

    {{-- Error banner --}}
    <div id="products-error"
         style="display: none;"
         class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- Loading state — JS toggles via style.display = 'block'/'none' --}}
    <div id="products-loading" class="py-20 text-center">
        <i class="ph ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Loading products...</p>
    </div>

    {{-- Main content — JS toggles via style.display = 'block'/'none' --}}
    <div id="products-content" style="display: none;">

        {{-- Filters --}}
        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 mb-5">
            <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:items-end">

                <div class="flex-1 min-w-[180px]">
                    <label for="product-search" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Search</label>
                    <input type="text" id="product-search" placeholder="Search products..."
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] placeholder:text-[#171E26]/35 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div class="w-full sm:w-auto">
                    <label for="category-filter" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Category</label>
                    <div class="relative">
                        <select id="category-filter"
                                class="w-full sm:w-[180px] appearance-none rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 pr-9 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition bg-white">
                            <option value="">All Categories</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/40 pointer-events-none text-sm"></i>
                    </div>
                </div>

                <div class="w-full sm:w-auto">
                    <label for="availability-filter" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Availability</label>
                    <div class="relative">
                        <select id="availability-filter"
                                class="w-full sm:w-[150px] appearance-none rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 pr-9 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition bg-white">
                            <option value="">All</option>
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/40 pointer-events-none text-sm"></i>
                    </div>
                </div>

                <div class="w-full sm:w-auto">
                    <button type="button" id="create-product-btn"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition">
                        Create Product
                    </button>
                </div>

            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5">

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Name</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Category</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Barcode</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Price</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Stock</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Status</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="products-table-body">
                        {{-- Populated by products.js --}}
                    </tbody>
                </table>
            </div>

            <div id="pagination-container" class="mt-5 flex items-center justify-center"></div>
        </div>
    </div>

    {{-- ================= PRODUCT MODAL (Create / Edit) ================= --}}
    {{-- Critical overlay positioning set via inline style, not Tailwind classes,
         so it can't silently break due to arbitrary-value class compilation issues.
         JS only ever toggles style.display between 'none' and 'flex', unchanged. --}}
    <div id="product-modal"
         style="display:none; position:fixed; inset:0; z-index:50; align-items:center; justify-content:center; background-color:rgba(23,30,38,0.4); padding:24px 16px;">
        <div class="bg-white rounded-2xl w-full max-w-[600px] max-h-[90vh] overflow-y-auto p-6">

            <div class="flex items-center justify-between mb-4">
                <h3 id="product-form-title" class="font-manrope text-lg font-bold text-[#171E26]">Create Product</h3>
                <button type="button" id="close-product-modal-btn" aria-label="Close"
                        class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <div id="product-form-error"
                 style="display: none;"
                 class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
            </div>

            <form id="product-form" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="hidden" id="product-id">

                <div class="sm:col-span-2">
                    <label for="product-name" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Name</label>
                    <input type="text" id="product-name" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div class="sm:col-span-2">
                    <label for="product-generic-name" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Generic Name</label>
                    <input type="text" id="product-generic-name"
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div>
                    <label for="product-category" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Category</label>
                    <div class="relative">
                        <select id="product-category"
                                class="w-full appearance-none rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 pr-9 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition bg-white">
                            <option value="">Select Category</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/40 pointer-events-none text-sm"></i>
                    </div>
                </div>

                <div>
                    <label for="product-price" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Price</label>
                    <input type="number" id="product-price" step="0.01" min="0" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div>
                    <label for="product-reorder-level" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Reorder Level</label>
                    <input type="number" id="product-reorder-level" min="0" value="0"
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div>
                    <label for="product-barcode" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Barcode</label>
                    <input type="text" id="product-barcode"
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div class="sm:col-span-2">
                    <label for="product-description" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Description</label>
                    <textarea id="product-description" rows="3"
                              class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition resize-none"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" id="product-requires-prescription"
                               class="h-4 w-4 rounded border-[#DBEBFB] text-[#2775E4] focus:ring-[#2775E4]">
                        <span class="font-inter text-[14px] text-[#171E26]">Requires Prescription</span>
                    </label>
                </div>

                <div class="sm:col-span-2">
                    <label for="product-image" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Product Image</label>
                    <input type="file" id="product-image" accept="image/*"
                           class="block w-full font-inter text-[13px] text-[#171E26]/60
                                  file:mr-4 file:px-4 file:py-2 file:rounded-lg file:border-0
                                  file:bg-[#DBEBFB] file:text-[#2775E4] file:font-semibold file:text-[13px]
                                  hover:file:bg-[#B1D0FB] file:cursor-pointer cursor-pointer">
                    <img id="product-image-preview"
                         style="display: none;"
                         class="w-[120px] h-[120px] object-cover rounded-xl border border-[#EAF1FB] mt-3">
                </div>

                <div class="sm:col-span-2 flex justify-end gap-2.5 pt-2">
                    <button type="button" id="cancel-product-modal-btn"
                            class="px-5 py-2.5 rounded-xl border border-[#DBEBFB] font-inter text-[14px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
                        Cancel
                    </button>
                    <button type="submit" id="product-submit-btn"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                        Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/products.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>