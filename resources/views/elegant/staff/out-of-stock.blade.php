<x-layouts.staff title="Out of Stock Products" active="out-of-stock">

    {{-- Error banner --}}
    <div id="out-of-stock-error"
         style="display: none;"
         class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- Loading state — only shown on first load, JS toggles via style.display = 'block'/'none' --}}
    <div id="out-of-stock-loading" class="py-20 text-center">
        <i class="ph ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Checking stock levels...</p>
    </div>

    {{-- Main content — JS toggles via style.display = 'block'/'none' --}}
    <div id="out-of-stock-content" style="display: none;">

        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <div>
                <h2 class="font-manrope font-bold text-[18px] text-[#171E26]">Out of Stock Products</h2>
                <p class="font-inter text-[13px] text-[#171E26]/50 mt-1">
                    Products at zero stock. This list updates automatically — once a product is restocked, it disappears on its own.
                </p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <span id="last-checked" class="font-inter text-[12px] text-[#171E26]/40"></span>
                <button type="button" id="refresh-btn"
                        class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-[#DBEBFB] font-inter text-[13px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
                    <i class="ph ph-arrows-clockwise text-base"></i>
                    Refresh
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Product</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Category</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Barcode</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Price</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Reorder Level</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="out-of-stock-table-body">
                        {{-- Populated by out-of-stock.js --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/out-of-stock.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>