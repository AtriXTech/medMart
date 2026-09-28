<x-layouts.staff title="Sales" active="sales">

    {{-- Error banner --}}
    <div id="sales-error"
         style="display: none;"
         class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- Loading state — JS toggles via style.display = 'block'/'none' --}}
    <div id="sales-loading" class="py-20 text-center">
        <i class="ph ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Loading sales...</p>
    </div>

    {{-- Main content — JS toggles via style.display = 'block'/'none' --}}
    <div id="sales-content" style="display: none;">

        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 mb-5">
            <div class="max-w-[260px]">
                <label for="date-filter" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Filter by Date</label>
                <input type="date" id="date-filter"
                       class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5">

            {{-- Horizontal scroll on small screens so the table never breaks the layout --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Receipt #</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Customer</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Total</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Items</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Date</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sales-table-body">
                        {{-- Populated by sales.js --}}
                    </tbody>
                </table>
            </div>

            <div id="pagination-container" class="mt-5 flex items-center justify-center"></div>
        </div>
    </div>

    {{-- ================= SALE DETAILS MODAL ================= --}}
    {{-- Critical overlay positioning set via inline style, not Tailwind classes,
         so it can't silently break due to arbitrary-value class compilation issues.
         JS only ever toggles style.display between 'none' and 'flex', unchanged. --}}
    <div id="sale-modal"
         style="display:none; position:fixed; inset:0; z-index:50; align-items:center; justify-content:center; background-color:rgba(23,30,38,0.4); padding:0 16px;">
        <div class="bg-white rounded-2xl w-full max-w-[600px] max-h-[90vh] overflow-y-auto p-6">
            <div class="flex items-center justify-between mb-5 sale-no-print">
                <h3 class="font-manrope text-lg font-bold text-[#171E26]">Sale Details</h3>
                <div class="flex items-center gap-2.5">
                    <button type="button" id="print-sale-btn"
                            class="rounded-lg border border-[#DBEBFB] px-3.5 py-2 font-inter text-[13px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition flex items-center gap-1.5">
                        <i class="ph ph-printer text-base"></i> Print Receipt
                    </button>
                    <button type="button" id="close-sale-modal-btn" aria-label="Close"
                            class="h-9 w-9 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>
            </div>

            <div id="sale-details">
                {{-- Populated by sales.js — the on-screen management view --}}
            </div>

            {{-- Hidden on screen. This is what actually prints, formatted with the
                 same MedMartReceipt template used by POS and Order Details. --}}
            <div id="sale-receipt-printable" class="hidden">
                <div id="sale-receipt-content"></div>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        {{-- Print scoping: identical technique used on the POS and Order Details
             receipts. Hide the whole page, reveal only the printable block. --}}
        <style>
            @media print {
                body * { visibility: hidden; }
                #sale-receipt-printable, #sale-receipt-printable * { visibility: visible; }
                #sale-receipt-printable {
                    display: block !important;
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                }
            }
        </style>

        <script src="{{ asset('assets/minimal/js/staff/receipt-template.js') }}"></script>
        <script src="{{ asset('assets/minimal/js/staff/sales.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>