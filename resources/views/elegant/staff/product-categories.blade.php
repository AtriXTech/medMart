<x-layouts.staff title="Product Categories" active="product-categories">

    {{-- Error banner --}}
    <div id="categories-error"
         style="display: none;"
         class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- Loading state — JS toggles via style.display = 'block'/'none' --}}
    <div id="categories-loading" class="py-20 text-center">
        <i class="ph ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Loading categories...</p>
    </div>

    {{-- Main content — JS toggles via style.display = 'block'/'none' --}}
    <div id="categories-content" style="display: none;">

        <div class="mb-5 flex justify-end">
            <button type="button" id="create-category-btn"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition">
                Create Category
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[420px] border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Name</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Products</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="categories-table-body">
                        {{-- Populated by product-categories.js --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= CATEGORY MODAL (Create / Edit) ================= --}}
    {{-- Critical overlay positioning set via inline style, not Tailwind classes,
         so it can't silently break due to arbitrary-value class compilation issues.
         JS only ever toggles style.display between 'none' and 'flex', unchanged.
         This modal DOES have click-outside-to-close in the original JS — kept as-is. --}}
    <div id="category-modal"
         style="display:none; position:fixed; inset:0; z-index:50; align-items:center; justify-content:center; background-color:rgba(23,30,38,0.4); padding:24px 16px;">
        <div class="bg-white rounded-2xl w-full max-w-[420px] max-h-[90vh] overflow-y-auto p-6">

            <div class="flex items-center justify-between mb-4">
                <h3 id="category-form-title" class="font-manrope text-lg font-bold text-[#171E26]">Create Category</h3>
                <button type="button" id="close-modal-btn" aria-label="Close"
                        class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <div id="category-form-error"
                 style="display: none;"
                 class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
            </div>

            <form id="category-form" class="space-y-4">
                <input type="hidden" id="category-id">

                <div>
                    <label for="category-name" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Name</label>
                    <input type="text" id="category-name" required
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                </div>

                <div class="flex justify-end gap-2.5 pt-2">
                    <button type="button" id="cancel-modal-btn"
                            class="px-5 py-2.5 rounded-xl border border-[#DBEBFB] font-inter text-[14px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
                        Cancel
                    </button>
                    <button type="submit" id="category-submit-btn"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/product-categories.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>