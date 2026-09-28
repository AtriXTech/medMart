<x-layouts.staff title="Customer Details" active="customers">

    {{-- Error banner --}}
    <div id="customer-error"
         style="display: none;"
         class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- Loading state — JS toggles via style.display = 'block'/'none' --}}
    <div id="customer-loading" class="py-20 text-center">
        <i class="ph ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Loading customer details...</p>
    </div>

    {{-- Main content — JS toggles via style.display = 'block'/'none' --}}
    <div id="customer-content" style="display: none;">

        {{-- Customer Information --}}
        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5 mb-5">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <p class="font-manrope font-bold text-[16px] text-[#171E26]">Customer Information</p>
                <a href="/staff/customers"
                   class="flex items-center gap-1.5 rounded-lg border border-[#DBEBFB] px-3.5 py-2 font-inter text-[13px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
                    <i class="ph ph-arrow-left text-base"></i>
                    Back to Customers
                </a>
            </div>
            <div id="customer-info">
                {{-- Populated by customer-details.js, including the suspend/unsuspend button --}}
            </div>
        </div>

        {{-- Order History --}}
        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5">
            <p class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Order History</p>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[520px] border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Order ID</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Status</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Total</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Date</th>
                            <th class="text-left py-3 px-3 font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body">
                        {{-- Populated by customer-details.js --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/customer-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>