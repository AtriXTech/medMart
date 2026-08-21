<x-layouts.staff title="Pharmacy Codes" active="pharmacy-codes">

    <div class="mb-4">
        <h2 class="font-manrope font-bold text-[18px] md:text-[20px] text-[#171E26]">Pharmacy Codes</h2>
        <p class="font-inter text-[13px] text-[#171E26]/45 mt-1">Generate and manage codes customers use to join your pharmacy.</p>
    </div>

    <div class="alert alert-error mb-4 rounded-xl border border-[#F5C9C4] bg-[#FEF2F2] text-[#9C3A32]
                font-inter text-[13.5px] font-medium px-4 py-3"
         id="pharmacy-codes-error" style="display: none;"></div>

    <div id="pharmacy-codes-loading" class="loading-state py-16 text-center">
        <div class="inline-flex flex-col items-center gap-3">
            <div class="h-10 w-10 rounded-full border-2 border-[#DBEBFB] border-t-[#2775E4] animate-spin"></div>
            <p class="font-inter text-sm text-[#171E26]/50">Loading pharmacy codes...</p>
        </div>
    </div>

    <div id="pharmacy-codes-content" class="min-w-0" style="display: none;">
        <div class="mb-4 md:mb-5">
            <button type="button"
                    class="btn btn-primary inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                           font-inter text-[12.5px] font-semibold text-white
                           bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                           shadow-lg shadow-[#2775E4]/20 hover:shadow-xl hover:shadow-[#2775E4]/25 transition"
                    id="create-code-btn">
                Generate Code
            </button>
        </div>

        <div class="card rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 min-w-0">
            <div class="overflow-x-auto overscroll-x-contain touch-pan-x -mx-1 px-1">
                <table class="w-full min-w-[720px] text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Code</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Uses</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Expires</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Status</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Created</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pharmacy-codes-table-body"
                           class="[&_td]:font-inter [&_td]:text-[13.5px] [&_td]:text-[#171E26]
                                  [&_td]:py-3.5 [&_td]:pr-4 [&_td]:border-b [&_td]:border-[#EAF1FB] [&_td]:align-middle
                                  [&_tr:last-child_td]:border-b-0
                                  [&_tr:hover_td]:bg-[#F7FAFD]"></tbody>
                </table>
            </div>

            <div id="pagination-container"
                 class="mt-5 flex flex-wrap items-center justify-center gap-2
                        [&_span]:font-inter [&_span]:text-[13px] [&_span]:text-[#171E26]/55"
                 style="margin-top: 16px; display: flex; align-items: center; justify-content: center;"></div>
        </div>
    </div>

    {{-- Modal — JS toggles style.display to "flex" / "none" --}}
    <div id="code-modal"
         class="modal-backdrop fixed inset-0 z-50 items-center justify-center p-4
                bg-[#171E26]/45 backdrop-blur-[2px]"
         style="display: none;">
        <div class="modal-content w-full max-w-[480px] max-h-[min(90vh,760px)] overflow-y-auto
                    bg-white border border-[#EAF1FB] rounded-2xl
                    shadow-[0_28px_64px_-28px_rgba(23,30,38,0.35)]
                    p-5 sm:p-6">
            <div class="modal-header flex items-center justify-between gap-3 mb-4 pb-3.5 border-b border-[#EAF1FB]">
                <h3 class="modal-title m-0 font-manrope text-[17px] font-extrabold text-[#171E26]" id="code-form-title">
                    Generate Pharmacy Code
                </h3>
                <button type="button"
                        class="close-btn h-8 w-8 rounded-lg border-0 bg-transparent text-[#171E26]/45 text-[22px] leading-none
                               hover:bg-[#F7FAFD] hover:text-[#171E26] transition cursor-pointer"
                        id="close-code-modal-btn"
                        aria-label="Close">&times;</button>
            </div>

            <div class="alert alert-error mb-4 rounded-xl border border-[#F5C9C4] bg-[#FEF2F2] text-[#9C3A32]
                        font-inter text-[13.5px] font-medium px-4 py-3"
                 id="code-form-error" style="display: none;"></div>

            <form id="code-form" class="space-y-3.5">
                <div class="field">
                    <label for="code" class="field-label">Custom Code (Optional)</label>
                    <input type="text" id="code" class="field-input" placeholder="Leave blank for auto-generated code">
                </div>
                <div class="field">
                    <label for="code-expires-at" class="field-label">Expires At (Optional)</label>
                    <input type="datetime-local" id="code-expires-at" class="field-input">
                </div>
                <div class="field">
                    <label for="code-max-uses" class="field-label">Max Uses (Optional)</label>
                    <input type="number" id="code-max-uses" class="field-input" min="1" placeholder="Leave blank for unlimited uses">
                </div>
                <div class="flex flex-wrap gap-2 justify-end pt-4 mt-1 border-t border-[#EAF1FB]">
                    <button type="button"
                            class="btn btn-secondary inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold bg-white border border-[#DBEBFB] text-[#171E26]
                                   hover:bg-[#F7FAFD] hover:border-[#2775E4] hover:text-[#2775E4] transition"
                            id="cancel-code-modal-btn">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-primary inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold text-white
                                   bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                                   shadow-lg shadow-[#2775E4]/20 transition disabled:opacity-45 disabled:cursor-not-allowed"
                            id="code-submit-btn">
                        Generate Code
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <style type="text/tailwindcss">
            .badge {
                @apply inline-flex items-center rounded-full px-2.5 py-0.5 font-inter text-[11px] font-semibold tracking-wide capitalize whitespace-nowrap;
            }
            .badge-success { @apply bg-emerald-50 text-emerald-700; }
            .badge-danger  { @apply bg-red-50 text-red-700; }
            .badge-warning { @apply bg-amber-50 text-amber-700; }
            .badge-muted   { @apply bg-[#EAF1FB] text-[#171E26]/70; }

            .btn {
                @apply inline-flex items-center justify-center gap-1 rounded-[0.65rem] px-3.5 py-2
                       font-inter text-[12.5px] font-semibold cursor-pointer transition
                       disabled:opacity-45 disabled:cursor-not-allowed;
            }
            .btn-secondary {
                @apply bg-white border border-[#DBEBFB] text-[#171E26]
                       hover:bg-[#F7FAFD] hover:border-[#2775E4] hover:text-[#2775E4];
            }
            .btn-primary {
                @apply text-white bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                       shadow-lg shadow-[#2775E4]/20;
            }

            .empty-state {
                @apply text-center py-10 px-4 font-inter text-sm text-[#171E26]/45;
            }

            #pagination-container .btn {
                @apply min-w-[5.5rem];
            }
        </style>
        <script src="{{ asset('assets/minimal/js/staff/pharmacy-codes.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>