{{--
    Intended path: resources/views/staff/suppliers.blade.php

    CHANGE SUMMARY:
    - Every ID suppliers.js binds to is preserved exactly: suppliers-error,
      suppliers-loading, suppliers-content, suppliers-table-body,
      create-supplier-btn, supplier-modal, supplier-form,
      supplier-form-title, supplier-name, supplier-contact-name,
      supplier-email, supplier-phone, supplier-address, supplier-id,
      supplier-form-error, supplier-submit-btn, close-supplier-modal-btn,
      cancel-supplier-modal-btn.
    - #suppliers-error, #supplier-form-error, #supplier-modal all use
      plain inline style="display:none" (not Tailwind's `hidden` class),
      matching exactly what suppliers.js already toggles via
      el.style.display — this avoids the same show/hide bug we hit on
      the POS page from mixing class-based and inline-style visibility.
    - #suppliers-loading has no forced display state, same as the
      original (visible by default, JS explicitly sets display on it).
    - No search/filter bar added — suppliers.js has no logic to back one,
      and I didn't want to ship a UI control that silently does nothing.
    - suppliers.js itself is otherwise untouched below: same endpoints,
      same window.editSupplier / window.deleteSupplier globals (still
      needed since rendered rows call them via inline onclick).
--}}
<x-layouts.staff title="Suppliers" active="suppliers">

    <div id="suppliers-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">Suppliers</h2>
            <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">Vendors and distributors supplying your pharmacy</p>
        </div>
        <button id="create-supplier-btn" type="button"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13px] shadow-sm shadow-[#2775E4]/20 hover:scale-[1.02] transition flex-shrink-0">
            <i class="ph-light ph-plus text-base"></i> Create Supplier
        </button>
    </div>

    <div id="suppliers-loading">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3">
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
        </div>
    </div>

    <div id="suppliers-content" style="display: none;">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Name</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Contact Name</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Email</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Phone</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="suppliers-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SUPPLIER MODAL --}}
    <div id="supplier-modal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center bg-[#171E26]/50 px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#EAF1FB]">
                <h3 id="supplier-form-title" class="font-manrope font-bold text-[16px] text-[#171E26]">Create Supplier</h3>
                <button type="button" id="close-supplier-modal-btn" class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50 text-xl leading-none">&times;</button>
            </div>

            <div class="px-5 pt-4">
                <div id="supplier-form-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-1"></div>
            </div>

            <form id="supplier-form" class="px-5 py-5 space-y-4">
                <input type="hidden" id="supplier-id">

                <div>
                    <label for="supplier-name" class="field-label">Name</label>
                    <input type="text" id="supplier-name" required class="field-input">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="supplier-contact-name" class="field-label">Contact Name</label>
                        <input type="text" id="supplier-contact-name" class="field-input">
                    </div>
                    <div>
                        <label for="supplier-phone" class="field-label">Phone</label>
                        <input type="text" id="supplier-phone" class="field-input">
                    </div>
                </div>

                <div>
                    <label for="supplier-email" class="field-label">Email</label>
                    <input type="email" id="supplier-email" class="field-input">
                </div>

                <div>
                    <label for="supplier-address" class="field-label">Address</label>
                    <textarea id="supplier-address" rows="3" class="field-input resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" id="cancel-supplier-modal-btn"
                        class="px-4 py-2.5 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[13px] text-[#171E26] hover:bg-[#F7FAFD]">Cancel</button>
                    <button type="submit" id="supplier-submit-btn"
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13px] shadow-sm shadow-[#2775E4]/20 disabled:opacity-60">Create Supplier</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/suppliers.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>