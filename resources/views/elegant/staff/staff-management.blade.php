<x-layouts.staff title="Staff Management" active="staff-management">

    <div class="grid gap-6 md:gap-8 min-w-0 max-w-full">

        {{-- Staff Members --}}
        <section class="min-w-0">
            <div class="mb-4">
                <h2 class="font-manrope font-bold text-[18px] md:text-[20px] text-[#171E26]">Staff Members</h2>
                <p class="font-inter text-[13px] text-[#171E26]/45 mt-1">Manage pharmacy team accounts and roles.</p>
            </div>

            <div class="alert alert-error mb-4 rounded-xl border border-[#F5C9C4] bg-[#FEF2F2] text-[#9C3A32] font-inter text-[13.5px] font-medium px-4 py-3"
                 id="staff-error" style="display: none;"></div>

            <div id="staff-loading"
                 class="loading-state py-14 text-center rounded-2xl bg-white border border-[#EAF1FB]">
                <div class="inline-flex flex-col items-center gap-3">
                    <div class="h-10 w-10 rounded-full border-2 border-[#DBEBFB] border-t-[#2775E4] animate-spin"></div>
                    <p class="font-inter text-sm text-[#171E26]/50">Loading staff...</p>
                </div>
            </div>

            <div id="staff-content" class="min-w-0" style="display: none;">
                <div class="mb-4">
                    <button type="button"
                            class="btn btn-primary inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold text-white
                                   bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                                   shadow-lg shadow-[#2775E4]/20 hover:shadow-xl hover:shadow-[#2775E4]/25 transition"
                            id="create-staff-btn">
                        Add Staff
                    </button>
                </div>

                <div class="card rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 min-w-0">
                    <div class="overflow-x-auto overscroll-x-contain touch-pan-x -mx-1 px-1">
                        <table class="w-full min-w-[720px] text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[#EAF1FB]">
                                    <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Name</th>
                                    <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Email</th>
                                    <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Role</th>
                                    <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Status</th>
                                    <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="staff-table-body"
                                   class="[&_td]:font-inter [&_td]:text-[13.5px] [&_td]:text-[#171E26]
                                          [&_td]:py-3.5 [&_td]:pr-4 [&_td]:border-b [&_td]:border-[#EAF1FB] [&_td]:align-middle
                                          [&_tr:last-child_td]:border-b-0
                                          [&_tr:hover_td]:bg-[#F7FAFD]"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        {{-- Roles & Permissions --}}
        <section class="min-w-0">
            <div class="mb-4">
                <h2 class="font-manrope font-bold text-[18px] md:text-[20px] text-[#171E26]">Roles & Permissions</h2>
                <p class="font-inter text-[13px] text-[#171E26]/45 mt-1">Define access levels for your staff.</p>
            </div>

            <div class="alert alert-error mb-4 rounded-xl border border-[#F5C9C4] bg-[#FEF2F2] text-[#9C3A32] font-inter text-[13.5px] font-medium px-4 py-3"
                 id="roles-error" style="display: none;"></div>

            <div id="roles-loading"
                 class="loading-state py-14 text-center rounded-2xl bg-white border border-[#EAF1FB]">
                <div class="inline-flex flex-col items-center gap-3">
                    <div class="h-10 w-10 rounded-full border-2 border-[#DBEBFB] border-t-[#2775E4] animate-spin"></div>
                    <p class="font-inter text-sm text-[#171E26]/50">Loading roles...</p>
                </div>
            </div>

            <div id="roles-content" class="min-w-0" style="display: none;">
                <div class="mb-4">
                    <button type="button"
                            class="btn btn-primary inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold text-white
                                   bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                                   shadow-lg shadow-[#2775E4]/20 hover:shadow-xl hover:shadow-[#2775E4]/25 transition"
                            id="create-role-btn">
                        Create Role
                    </button>
                </div>

                <div class="card rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 min-w-0">
                    <div class="overflow-x-auto overscroll-x-contain touch-pan-x -mx-1 px-1">
                        <table class="w-full min-w-[680px] text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[#EAF1FB]">
                                    <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Name</th>
                                    <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Description</th>
                                    <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Permissions</th>
                                    <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="roles-table-body"
                                   class="[&_td]:font-inter [&_td]:text-[13.5px] [&_td]:text-[#171E26]
                                          [&_td]:py-3.5 [&_td]:pr-4 [&_td]:border-b [&_td]:border-[#EAF1FB] [&_td]:align-middle
                                          [&_tr:last-child_td]:border-b-0
                                          [&_tr:hover_td]:bg-[#F7FAFD]"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Staff Modal --}}
    <div id="staff-modal"
         class="modal-backdrop fixed inset-0 z-50 items-center justify-center p-4
                bg-[#171E26]/45 backdrop-blur-[2px]"
         style="display: none;">
        <div class="modal-content w-full max-w-[480px] max-h-[min(90vh,760px)] overflow-y-auto
                    bg-white border border-[#EAF1FB] rounded-2xl
                    shadow-[0_28px_64px_-28px_rgba(23,30,38,0.35)]
                    p-5 sm:p-6">
            <div class="modal-header flex items-center justify-between gap-3 mb-4 pb-3.5 border-b border-[#EAF1FB]">
                <h3 class="modal-title m-0 font-manrope text-[17px] font-extrabold text-[#171E26]" id="staff-form-title">Add Staff</h3>
                <button type="button"
                        class="close-btn h-8 w-8 rounded-lg border-0 bg-transparent text-[#171E26]/45 text-[22px] leading-none
                               hover:bg-[#F7FAFD] hover:text-[#171E26] transition cursor-pointer"
                        id="close-staff-modal-btn"
                        aria-label="Close">&times;</button>
            </div>

            <div class="alert alert-error mb-4 rounded-xl border border-[#F5C9C4] bg-[#FEF2F2] text-[#9C3A32] font-inter text-[13.5px] font-medium px-4 py-3"
                 id="staff-form-error" style="display: none;"></div>

            <form id="staff-form" class="space-y-3.5">
                <input type="hidden" id="staff-id">
                <div class="field">
                    <label for="staff-name" class="field-label">Name</label>
                    <input type="text" id="staff-name" class="field-input" required>
                </div>
                <div class="field">
                    <label for="staff-phone" class="field-label">Phone</label>
                    <input type="text" id="staff-phone" class="field-input">
                </div>
                <div class="field">
                    <label for="staff-email" class="field-label">Email</label>
                    <input type="email" id="staff-email" class="field-input" required>
                </div>
                <div class="field">
                    <label for="staff-password" class="field-label">Password</label>
                    <input type="password" id="staff-password" class="field-input" required>
                </div>
                <div class="field">
                    <label for="staff-role" class="field-label">Role</label>
                    <div class="relative">
                        <select id="staff-role" class="field-input appearance-none pr-9" required>
                            <option value="">Select Role</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/35 pointer-events-none text-sm"></i>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 justify-end pt-4 mt-1 border-t border-[#EAF1FB]">
                    <button type="button"
                            class="btn btn-secondary inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold bg-white border border-[#DBEBFB] text-[#171E26]
                                   hover:bg-[#F7FAFD] hover:border-[#2775E4] hover:text-[#2775E4] transition"
                            id="cancel-staff-modal-btn">Cancel</button>
                    <button type="submit"
                            class="btn btn-primary inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold text-white
                                   bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                                   shadow-lg shadow-[#2775E4]/20 transition disabled:opacity-45 disabled:cursor-not-allowed"
                            id="staff-submit-btn">Add Staff</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Role Modal --}}
    <div id="role-modal"
         class="modal-backdrop fixed inset-0 z-50 items-center justify-center p-4
                bg-[#171E26]/45 backdrop-blur-[2px]"
         style="display: none;">
        <div class="modal-content w-full max-w-[480px] max-h-[min(90vh,760px)] overflow-y-auto
                    bg-white border border-[#EAF1FB] rounded-2xl
                    shadow-[0_28px_64px_-28px_rgba(23,30,38,0.35)]
                    p-5 sm:p-6">
            <div class="modal-header flex items-center justify-between gap-3 mb-4 pb-3.5 border-b border-[#EAF1FB]">
                <h3 class="modal-title m-0 font-manrope text-[17px] font-extrabold text-[#171E26]" id="role-form-title">Create Role</h3>
                <button type="button"
                        class="close-btn h-8 w-8 rounded-lg border-0 bg-transparent text-[#171E26]/45 text-[22px] leading-none
                               hover:bg-[#F7FAFD] hover:text-[#171E26] transition cursor-pointer"
                        id="close-role-modal-btn"
                        aria-label="Close">&times;</button>
            </div>

            <div class="alert alert-error mb-4 rounded-xl border border-[#F5C9C4] bg-[#FEF2F2] text-[#9C3A32] font-inter text-[13.5px] font-medium px-4 py-3"
                 id="role-form-error" style="display: none;"></div>

            <form id="role-form" class="space-y-3.5">
                <input type="hidden" id="role-id">
                <div class="field">
                    <label for="role-name" class="field-label">Role Name</label>
                    <input type="text" id="role-name" class="field-input" required>
                </div>
                <div class="field">
                    <label for="role-description" class="field-label">Description</label>
                    <textarea id="role-description" class="field-input" rows="3"></textarea>
                </div>
                <div class="field">
                    <label class="field-label">Permissions</label>
                    <div id="role-permissions"
                         class="rounded-xl border border-[#DBEBFB] bg-[#F7FAFD] px-3.5 py-3
                                [&_label]:font-inter [&_label]:text-[13px] [&_label]:text-[#171E26]
                                [&_.permission-checkbox]:accent-[#2775E4]"
                         style="max-height: 300px; overflow-y: auto;"></div>
                </div>
                <div class="flex flex-wrap gap-2 justify-end pt-4 mt-1 border-t border-[#EAF1FB]">
                    <button type="button"
                            class="btn btn-secondary inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold bg-white border border-[#DBEBFB] text-[#171E26]
                                   hover:bg-[#F7FAFD] hover:border-[#2775E4] hover:text-[#2775E4] transition"
                            id="cancel-role-modal-btn">Cancel</button>
                    <button type="submit"
                            class="btn btn-primary inline-flex items-center justify-center rounded-[0.65rem] px-4 py-2.5
                                   font-inter text-[12.5px] font-semibold text-white
                                   bg-gradient-to-r from-[#2775E4] to-[#08AEBC]
                                   shadow-lg shadow-[#2775E4]/20 transition disabled:opacity-45 disabled:cursor-not-allowed"
                            id="role-submit-btn">Create Role</button>
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
            .btn-danger {
                @apply bg-white border border-[#F5C9C4] text-red-700 hover:bg-red-50;
            }

            .empty-state {
                @apply text-center py-10 px-4 font-inter text-sm text-[#171E26]/45;
            }

            #staff-table-body td .btn,
            #roles-table-body td .btn {
                @apply mr-1 my-0.5;
            }
        </style>
        <script src="{{ asset('assets/minimal/js/staff/staff-management.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>