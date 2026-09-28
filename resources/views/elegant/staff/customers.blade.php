{{--
    Intended path: resources/views/staff/customers.blade.php

    CHANGE SUMMARY (vs. previous version):
    - UNCHANGED: every ID customers.js binds to (customers-error,
      customers-loading, customers-content, customers-table-body,
      customer-search, pagination-container), the Create customer link,
      the search field, the table structure.
    - NEW: #confirm-modal, replacing the native confirm() dialog that
      used to run on Suspend (Unsuspend was intentionally left as-is —
      only Suspend was asked for). Follows the same Tailwind-class
      overlay pattern already used on Purchase Order Details / Product
      Categories (fixed inset-0 z-50, style.display 'flex'/'none'), and
      reuses this page's own existing danger colors already on the
      Suspend button (#9C3A32 text / #FDEDEC background).
--}}
<x-layouts.staff title="Customers" active="customers">

    <div id="customers-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">Customers</h2>
        <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">Everyone linked to your pharmacy</p>
    </div>

    <div id="customers-loading">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3">
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
        </div>
    </div>

    <div id="customers-content" style="display: none;">

        <div class='md:flex justify-between item-center'>
            <div class="mb-4 max-w-sm ">
            <label for="customer-search" class="field-label">Search Customers</label>
            <div class="relative">
                <i class="ph-light ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/35 text-[16px]"></i>
                <input type="text" id="customer-search" placeholder="Search by name or username..." class="field-input pl-10">
            </div>
        </div>
        <div class="w-full sm:w-auto mb-4">
                    <a href='{{ route('createCustomer') }}'><button type="button" 
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition">
                        Create customer
                    </button></a>
                </div>
        </div>
        

        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Name</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Phone Number</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Username</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Link ID</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Status</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Linked At</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customers-table-body"></tbody>
                </table>
            </div>
            <div id="pagination-container" class="flex items-center justify-center gap-2 mt-5 pt-4 border-t border-[#EAF1FB]"></div>
        </div>
    </div>

    {{-- CONFIRM MODAL (Suspend customer) --}}
    {{-- NEW: replaces native confirm(). Same overlay pattern used on
         Purchase Order Details / Product Categories confirm modals. --}}
    <div id="confirm-modal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center bg-[#171E26]/50 px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-[380px] p-6 text-center">
            <div id="confirm-modal-icon" class="mx-auto mb-4 h-14 w-14 rounded-full flex items-center justify-center bg-[#FDEDEC]">
                <i class="ph-light ph-warning text-2xl text-[#9C3A32]"></i>
            </div>
            <h3 id="confirm-modal-title" class="font-manrope font-bold text-[16px] text-[#171E26] mb-1.5">Suspend customer?</h3>
            <p id="confirm-modal-message" class="font-inter text-[13px] text-[#171E26]/60 leading-relaxed mb-6"></p>
            <div class="flex items-center gap-2.5">
                <button type="button" id="confirm-modal-cancel-btn"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[13px] text-[#171E26] hover:bg-[#F7FAFD]">Cancel</button>
                <button type="button" id="confirm-modal-confirm-btn"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-[#F5C9C4] font-inter font-semibold text-[13px] text-[#9C3A32] hover:bg-[#FDEDEC]">Suspend</button>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/customers.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>