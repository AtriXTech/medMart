<x-layouts.superadmin title="Admin Users Management" active="administration-admins">

    <div class="space-y-6">

        {{-- PAGE HEADER & GLOBAL CONTROLS --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                    Admin Users
                </h1>
                <p class="font-inter text-xs md:text-sm text-[#171E26]/60 mt-0.5">
                    Manage administrators who have access to the MedMart platform.
                </p>
            </div>

            {{-- Action Controls --}}
            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <button type="button" onclick="openAddAdminModal()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white px-4 py-2.5 rounded-xl font-inter text-xs font-semibold shadow-sm hover:opacity-95 active:scale-95 transition">
                    <i class="ph ph-plus-circle text-base"></i>
                    <span>Add Admin</span>
                </button>

                <button type="button" onclick="refreshAdminData()" class="inline-flex items-center justify-center gap-1.5 bg-white border border-[#DBEBFB] text-[#171E26] px-3.5 py-2.5 rounded-xl font-inter text-xs font-semibold shadow-sm hover:bg-[#F7FAFD] active:scale-95 transition">
                    <i id="refresh-icon" class="ph ph-arrows-counter-clockwise text-base text-[#08AEBC]"></i>
                    <span class="hidden sm:inline">Refresh</span>
                </button>
            </div>
        </div>

        {{-- KPI SECTION (3 METRIC CARDS) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
            
            {{-- 1. Total Admins --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Total Admins</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1" id="kpi-total-admins">6</p>
                    <span class="font-inter text-[11px] text-[#171E26]/50 mt-1 block">Platform access holders</span>
                </div>
                <div class="h-11 w-11 rounded-2xl bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center shrink-0">
                    <i class="ph-fill ph-users-three text-2xl"></i>
                </div>
            </div>

            {{-- 2. Active Admins --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Active Admins</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-emerald-600 mt-1" id="kpi-active-admins">5</p>
                    <span class="font-inter text-[11px] text-emerald-700 font-semibold mt-1 block">● 83.3% access active</span>
                </div>
                <div class="h-11 w-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="ph-fill ph-user-check text-2xl"></i>
                </div>
            </div>

            {{-- 3. Inactive Admins --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Inactive Admins</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-slate-500 mt-1" id="kpi-inactive-admins">1</p>
                    <span class="font-inter text-[11px] text-slate-500 font-medium mt-1 block">Access revoked or paused</span>
                </div>
                <div class="h-11 w-11 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                    <i class="ph-fill ph-user-minus text-2xl"></i>
                </div>
            </div>

        </div>

        {{-- ADMIN DIRECTORY MAIN CONTAINER --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
            
            {{-- Toolbar & Filters --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <h2 class="font-manrope font-bold text-lg text-[#171E26]">Admin Directory</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 font-inter text-xs">
                    
                    {{-- Search Input --}}
                    <div class="relative w-full">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#171E26]/40 text-base"></i>
                        <input type="text" id="admin-search" onkeyup="filterAdmins()" placeholder="Search name or email..." class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-9 pr-3 py-2 text-[#171E26] placeholder-[#171E26]/40 focus:outline-none focus:border-[#2775E4] transition" />
                    </div>

                    {{-- Status Filter --}}
                    <select id="filter-status" onchange="filterAdmins()" class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 text-[#171E26] focus:outline-none focus:border-[#2775E4] cursor-pointer">
                        <option value="ALL">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    {{-- Role Filter --}}
                    <select id="filter-role" onchange="filterAdmins()" class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 text-[#171E26] focus:outline-none focus:border-[#2775E4] cursor-pointer">
                        <option value="ALL">All Roles</option>
                        <option value="Super Admin">Super Admin</option>
                        <option value="Finance Admin">Finance Admin</option>
                        <option value="Support Admin">Support Admin</option>
                    </select>

                </div>
            </div>

            {{-- DESKTOP ADMIN DIRECTORY TABLE (HIDDEN ON MOBILE) --}}
            <div class="hidden md:block overflow-x-auto rounded-xl border border-[#EAF1FB]">
                <table class="w-full text-left font-inter text-xs">
                    <thead>
                        <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[11px] font-bold text-[#171E26]/60 uppercase tracking-wider">
                            <th class="py-3 px-4">Admin</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Last Active</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3F7FC]" id="desktop-admin-rows">
                        
                        {{-- ADMIN 1: SUPER ADMIN --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition admin-row" data-name="Sulaimon Abubakre" data-email="sulaimon@medmart.com" data-role="Super Admin" data-status="Active">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] text-white font-manrope font-bold text-xs flex items-center justify-center shrink-0">
                                        SA
                                    </div>
                                    <div>
                                        <span class="font-manrope font-bold text-sm text-[#171E26] block">Sulaimon Abubakre</span>
                                        <span class="font-inter text-[11px] text-[#171E26]/50">sulaimon@medmart.com</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#2775E4]">
                                <span class="inline-flex items-center gap-1 bg-[#E9F3FE] text-[#2775E4] px-2.5 py-1 rounded-lg">
                                    <i class="ph-fill ph-shield-check text-sm"></i> Super Admin
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/80 font-medium">2 min ago</td>
                            <td class="py-3.5 px-4 text-right space-x-1">
                                <button type="button" onclick="openAdminDrawer('Sulaimon Abubakre', 'sulaimon@medmart.com', 'Super Admin', 'Active', 'Sep 1, 2026', 'Today · 16:42', '248')" class="inline-flex items-center gap-1 bg-[#E9F3FE] text-[#2775E4] hover:bg-[#2775E4] hover:text-white px-3 py-1.5 rounded-lg font-semibold transition">
                                    <span>View</span> <i class="ph ph-caret-right"></i>
                                </button>
                                <button type="button" onclick="confirmDeactivation('Sulaimon Abubakre')" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Deactivate Admin">
                                    <i class="ph ph-user-[#171E26] ph-user-minus text-base"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- ADMIN 2: FINANCE ADMIN --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition admin-row" data-name="Aisha Bello" data-email="aisha@medmart.com" data-role="Finance Admin" data-status="Active">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-[#08AEBC] text-white font-manrope font-bold text-xs flex items-center justify-center shrink-0">
                                        AB
                                    </div>
                                    <div>
                                        <span class="font-manrope font-bold text-sm text-[#171E26] block">Aisha Bello</span>
                                        <span class="font-inter text-[11px] text-[#171E26]/50">aisha@medmart.com</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#058A98]">
                                <span class="inline-flex items-center gap-1 bg-teal-50 text-[#058A98] px-2.5 py-1 rounded-lg">
                                    <i class="ph-fill ph-bank text-sm"></i> Finance Admin
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/80 font-medium">18 min ago</td>
                            <td class="py-3.5 px-4 text-right space-x-1">
                                <button type="button" onclick="openAdminDrawer('Aisha Bello', 'aisha@medmart.com', 'Finance Admin', 'Active', 'Aug 14, 2026', 'Today · 16:26', '142')" class="inline-flex items-center gap-1 bg-[#E9F3FE] text-[#2775E4] hover:bg-[#2775E4] hover:text-white px-3 py-1.5 rounded-lg font-semibold transition">
                                    <span>View</span> <i class="ph ph-caret-right"></i>
                                </button>
                                <button type="button" onclick="confirmDeactivation('Aisha Bello')" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Deactivate Admin">
                                    <i class="ph ph-user-minus text-base"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- ADMIN 3: SUPPORT ADMIN (INACTIVE) --}}
                        <tr class="hover:bg-[#F7FAFD]/70 transition admin-row" data-name="Ibrahim Musa" data-email="ibrahim@medmart.com" data-role="Support Admin" data-status="Inactive">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-slate-200 text-slate-600 font-manrope font-bold text-xs flex items-center justify-center shrink-0">
                                        IM
                                    </div>
                                    <div>
                                        <span class="font-manrope font-bold text-sm text-[#171E26]/70 block">Ibrahim Musa</span>
                                        <span class="font-inter text-[11px] text-[#171E26]/40">ibrahim@medmart.com</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-600">
                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg">
                                    <i class="ph-fill ph-headset text-sm"></i> Support Admin
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-[11px] text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-0.5 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#171E26]/50 font-medium">Sep 2, 2026</td>
                            <td class="py-3.5 px-4 text-right space-x-1">
                                <button type="button" onclick="openAdminDrawer('Ibrahim Musa', 'ibrahim@medmart.com', 'Support Admin', 'Inactive', 'Jul 10, 2026', 'Sep 2, 2026', '89')" class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 hover:bg-slate-200 px-3 py-1.5 rounded-lg font-semibold transition">
                                    <span>View</span> <i class="ph ph-caret-right"></i>
                                </button>
                                <button type="button" onclick="activateAdmin('Ibrahim Musa')" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-emerald-600 hover:bg-emerald-50 transition" title="Reactivate Admin">
                                    <i class="ph ph-user-plus text-base"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- MOBILE ADMIN DIRECTORY CARDS (SHOWS ON 320–767px) --}}
            <div class="block md:hidden space-y-3" id="mobile-admin-cards">
                
                {{-- MOBILE CARD 1 --}}
                <div class="bg-[#F7FAFD]/60 border border-[#EAF1FB] rounded-xl p-4 space-y-3 mobile-admin-card" data-name="Sulaimon Abubakre" data-role="Super Admin" data-status="Active">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] text-white font-manrope font-bold text-xs flex items-center justify-center shrink-0">
                                SA
                            </div>
                            <div>
                                <h3 class="font-manrope font-extrabold text-sm text-[#171E26]">Sulaimon Abubakre</h3>
                                <span class="font-inter text-xs text-[#171E26]/60">sulaimon@medmart.com</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 font-semibold text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                            ● Active
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-y border-[#F3F7FC] py-2.5 font-inter text-xs">
                        <div>
                            <span class="text-[#171E26]/50 block">Platform Role</span>
                            <span class="font-semibold text-[#2775E4]">Super Admin</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block">Last Active</span>
                            <span class="font-medium text-[#171E26]">2 min ago</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="openAdminDrawer('Sulaimon Abubakre', 'sulaimon@medmart.com', 'Super Admin', 'Active', 'Sep 1, 2026', 'Today · 16:42', '248')" class="w-full bg-[#E9F3FE] text-[#2775E4] py-2 rounded-xl font-inter text-xs font-semibold text-center transition">
                            View Admin
                        </button>
                    </div>
                </div>

                {{-- MOBILE CARD 2 --}}
                <div class="bg-[#F7FAFD]/60 border border-[#EAF1FB] rounded-xl p-4 space-y-3 mobile-admin-card" data-name="Aisha Bello" data-role="Finance Admin" data-status="Active">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="h-10 w-10 rounded-full bg-[#08AEBC] text-white font-manrope font-bold text-xs flex items-center justify-center shrink-0">
                                AB
                            </div>
                            <div>
                                <h3 class="font-manrope font-extrabold text-sm text-[#171E26]">Aisha Bello</h3>
                                <span class="font-inter text-xs text-[#171E26]/60">aisha@medmart.com</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 font-semibold text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                            ● Active
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-y border-[#F3F7FC] py-2.5 font-inter text-xs">
                        <div>
                            <span class="text-[#171E26]/50 block">Platform Role</span>
                            <span class="font-semibold text-[#058A98]">Finance Admin</span>
                        </div>
                        <div>
                            <span class="text-[#171E26]/50 block">Last Active</span>
                            <span class="font-medium text-[#171E26]">18 min ago</span>
                        </div>
                    </div>

                    <button type="button" onclick="openAdminDrawer('Aisha Bello', 'aisha@medmart.com', 'Finance Admin', 'Active', 'Aug 14, 2026', 'Today · 16:26', '142')" class="w-full bg-[#E9F3FE] text-[#2775E4] py-2 rounded-xl font-inter text-xs font-semibold text-center transition">
                        View Admin
                    </button>
                </div>

            </div>

            {{-- EMPTY SEARCH / FILTER STATE --}}
            <div id="empty-state" class="hidden py-12 text-center space-y-3">
                <div class="h-12 w-12 rounded-full bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center mx-auto">
                    <i class="ph ph-user-focus text-2xl"></i>
                </div>
                <h3 class="font-manrope font-bold text-base text-[#171E26]">No administrators found</h3>
                <p class="font-inter text-xs text-[#171E26]/60 max-w-sm mx-auto">No platform administrators match your current search query or filter criteria.</p>
                <button type="button" onclick="resetAdminFilters()" class="inline-flex items-center gap-1.5 bg-[#E9F3FE] text-[#2775E4] px-4 py-2 rounded-xl font-inter text-xs font-semibold hover:bg-[#2775E4] hover:text-white transition">
                    Reset Filters
                </button>
            </div>

            {{-- PAGINATION --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-[#F3F7FC] font-inter text-xs">
                <span class="text-[#171E26]/60">Showing <strong class="text-[#171E26]">1–3</strong> of <strong class="text-[#171E26]">6</strong> platform administrators</span>

                <div class="flex items-center gap-1">
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26]/50 cursor-not-allowed" disabled>Prev</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg bg-[#2775E4] text-white font-semibold">1</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26] hover:bg-[#F7FAFD] transition">2</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-[#DBEBFB] bg-white text-[#171E26] hover:bg-[#F7FAFD] transition">Next</button>
                </div>
            </div>

        </div>

    </div>

    {{-- MODAL 1: ADD NEW ADMIN USER --}}
    <div id="add-admin-modal-backdrop" onclick="closeAddAdminModal()" class="fixed inset-0 bg-[#171E26]/50 backdrop-blur-sm z-40 hidden transition-opacity"></div>

    <div id="add-admin-modal" class="fixed inset-x-4 top-10 md:inset-x-auto md:left-1/2 md:-translate-x-1/2 md:w-full md:max-w-lg bg-white rounded-2xl shadow-2xl z-50 hidden transition-transform overflow-hidden">
        
        {{-- Modal Header --}}
        <div class="bg-[#F7FAFD] border-b border-[#EAF1FB] px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center font-bold">
                    <i class="ph ph-user-plus text-base"></i>
                </div>
                <div>
                    <h3 class="font-manrope font-extrabold text-base text-[#171E26]">Create Admin User</h3>
                    <p class="font-inter text-[11px] text-[#171E26]/60">Grant new internal administrative platform access</p>
                </div>
            </div>
            <button type="button" onclick="closeAddAdminModal()" class="h-8 w-8 rounded-full bg-white border border-[#EAF1FB] flex items-center justify-center text-[#171E26]/60 hover:text-[#171E26] transition">
                <i class="ph ph-x text-base"></i>
            </button>
        </div>

        {{-- Form Body --}}
        <form onsubmit="handleCreateAdmin(event)" class="p-6 space-y-4 font-inter text-xs">
            
            {{-- Full Name --}}
            <div>
                <label for="admin_name" class="block font-semibold text-[#171E26] mb-1">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" id="admin_name" required placeholder="e.g. Sulaimon Abubakre" class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 text-[#171E26] placeholder-[#171E26]/40 focus:outline-none focus:border-[#2775E4] transition" />
            </div>

            {{-- Email Address --}}
            <div>
                <label for="admin_email" class="block font-semibold text-[#171E26] mb-1">Email Address <span class="text-rose-500">*</span></label>
                <input type="email" id="admin_email" required placeholder="admin@medmart.com" class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 text-[#171E26] placeholder-[#171E26]/40 focus:outline-none focus:border-[#2775E4] transition" />
            </div>

            {{-- Role & Status Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="admin_role" class="block font-semibold text-[#171E26] mb-1">Platform Role <span class="text-rose-500">*</span></label>
                    <select id="admin_role" required class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 text-[#171E26] focus:outline-none focus:border-[#2775E4] transition cursor-pointer">
                        <option value="Super Admin">Super Admin</option>
                        <option value="Finance Admin">Finance Admin</option>
                        <option value="Support Admin">Support Admin</option>
                    </select>
                </div>

                <div>
                    <label for="admin_status" class="block font-semibold text-[#171E26] mb-1">Initial Status <span class="text-rose-500">*</span></label>
                    <select id="admin_status" required class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 text-[#171E26] focus:outline-none focus:border-[#2775E4] transition cursor-pointer">
                        <option value="Active">● Active</option>
                        <option value="Inactive">● Inactive</option>
                    </select>
                </div>
            </div>

            {{-- Password Field --}}
            <div>
                <label for="admin_password" class="block font-semibold text-[#171E26] mb-1">Password <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input type="password" id="admin_password" required placeholder="••••••••••••" class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-3.5 pr-10 py-2.5 text-[#171E26] placeholder-[#171E26]/40 focus:outline-none focus:border-[#2775E4] transition" />
                    <button type="button" onclick="togglePasswordVisibility('admin_password', 'toggle-pass-icon-1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/50 hover:text-[#171E26] transition">
                        <i id="toggle-pass-icon-1" class="ph ph-eye text-base"></i>
                    </button>
                </div>
            </div>

            {{-- Confirm Password Field --}}
            <div>
                <label for="admin_password_confirmation" class="block font-semibold text-[#171E26] mb-1">Confirm Password <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input type="password" id="admin_password_confirmation" required placeholder="••••••••••••" class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-3.5 pr-10 py-2.5 text-[#171E26] placeholder-[#171E26]/40 focus:outline-none focus:border-[#2775E4] transition" />
                    <button type="button" onclick="togglePasswordVisibility('admin_password_confirmation', 'toggle-pass-icon-2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/50 hover:text-[#171E26] transition">
                        <i id="toggle-pass-icon-2" class="ph ph-eye text-base"></i>
                    </button>
                </div>
            </div>

            {{-- Modal Actions --}}
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-[#F3F7FC]">
                <button type="button" onclick="closeAddAdminModal()" class="px-4 py-2.5 rounded-xl border border-[#DBEBFB] text-[#171E26] font-semibold hover:bg-[#F7FAFD] transition">
                    Cancel
                </button>
                <button type="submit" class="bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm hover:opacity-95 transition">
                    Create Admin
                </button>
            </div>

        </form>

    </div>

    {{-- MODAL 2: CONFIRM DEACTIVATION --}}
    <div id="deactivate-modal-backdrop" onclick="closeDeactivateModal()" class="fixed inset-0 bg-[#171E26]/50 backdrop-blur-sm z-40 hidden transition-opacity"></div>

    <div id="deactivate-modal" class="fixed inset-x-4 top-24 md:inset-x-auto md:left-1/2 md:-translate-x-1/2 md:w-full md:max-w-md bg-white rounded-2xl shadow-2xl z-50 hidden transition-transform overflow-hidden p-6 space-y-4">
        <div class="h-12 w-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
            <i class="ph ph-warning-circle text-2xl"></i>
        </div>

        <div class="text-center space-y-1">
            <h3 class="font-manrope font-extrabold text-lg text-[#171E26]">Deactivate Administrator?</h3>
            <p class="font-inter text-xs text-[#171E26]/60">
                <strong id="deactivate-admin-target" class="text-[#171E26]">Sulaimon Abubakre</strong> will no longer be able to log in or access the MedMart Super Admin platform.
            </p>
        </div>

        <div class="flex items-center justify-center gap-2 pt-2">
            <button type="button" onclick="closeDeactivateModal()" class="w-full bg-[#F7FAFD] border border-[#DBEBFB] text-[#171E26] py-2.5 rounded-xl font-inter text-xs font-semibold hover:bg-slate-100 transition">
                Cancel
            </button>
            <button type="button" onclick="executeDeactivation()" class="w-full bg-rose-600 text-white py-2.5 rounded-xl font-inter text-xs font-semibold hover:bg-rose-700 transition">
                Deactivate
            </button>
        </div>
    </div>

    {{-- DRAWER: ADMIN QUICK DETAILS --}}
    <div id="admin-drawer-backdrop" onclick="closeAdminDrawer()" class="fixed inset-0 bg-[#171E26]/40 backdrop-blur-sm z-40 hidden transition-opacity"></div>

    <div id="admin-drawer" class="fixed right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">
        <div class="p-5 md:p-6 space-y-6">
            
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-4">
                <div class="flex items-center gap-3">
                    <div id="drawer-avatar" class="h-10 w-10 rounded-full bg-[#2775E4] text-white font-manrope font-bold text-sm flex items-center justify-center shrink-0">
                        SA
                    </div>
                    <div>
                        <h2 class="font-manrope font-extrabold text-lg text-[#171E26]" id="drawer-name">Sulaimon Abubakre</h2>
                        <span class="font-inter text-xs text-[#171E26]/50 block" id="drawer-email">sulaimon@medmart.com</span>
                    </div>
                </div>
                <button type="button" onclick="closeAdminDrawer()" class="h-8 w-8 rounded-full bg-[#F7FAFD] flex items-center justify-center text-[#171E26]/60 hover:text-[#171E26] transition">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>

            {{-- Status & Role Badge Container --}}
            <div class="flex items-center justify-between bg-[#F7FAFD] border border-[#EAF1FB] rounded-xl p-3 font-inter text-xs">
                <div>
                    <span class="text-[#171E26]/50 block text-[10px] uppercase font-bold tracking-wider">Role</span>
                    <span class="font-semibold text-[#2775E4]" id="drawer-role">Super Admin</span>
                </div>
                <div class="text-right">
                    <span class="text-[#171E26]/50 block text-[10px] uppercase font-bold tracking-wider">Status</span>
                    <span class="font-semibold text-emerald-700" id="drawer-status">Active</span>
                </div>
            </div>

            {{-- Metadata Grid --}}
            <div class="space-y-3 font-inter text-xs">
                <span class="font-manrope font-bold text-sm text-[#171E26] block border-b border-[#F3F7FC] pb-2">Admin Information</span>

                <div class="flex items-center justify-between py-1 border-b border-[#F3F7FC]">
                    <span class="text-[#171E26]/60">Date Created</span>
                    <span class="font-semibold text-[#171E26]" id="drawer-created">Sep 1, 2026</span>
                </div>

                <div class="flex items-center justify-between py-1 border-b border-[#F3F7FC]">
                    <span class="text-[#171E26]/60">Last Active</span>
                    <span class="font-semibold text-[#171E26]" id="drawer-active">Today · 16:42</span>
                </div>

                <div class="flex items-center justify-between py-1 border-b border-[#F3F7FC]">
                    <span class="text-[#171E26]/60">Total Login Count</span>
                    <span class="font-semibold text-[#2775E4]" id="drawer-logins">248</span>
                </div>
            </div>

            {{-- Recent Activity Feed --}}
            <div class="space-y-3 font-inter text-xs">
                <span class="font-manrope font-bold text-sm text-[#171E26] block border-b border-[#F3F7FC] pb-2">Recent Platform Activity</span>

                <div class="space-y-2.5">
                    <div class="p-2.5 rounded-xl bg-[#F7FAFD] border border-[#EAF1FB] flex items-start gap-2.5">
                        <i class="ph ph-shield-check text-[#2775E4] text-base mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-[#171E26]">Logged into Super Admin</p>
                            <span class="text-[10px] text-[#171E26]/50">Today · 16:42 · IP 102.89.23.4</span>
                        </div>
                    </div>

                    <div class="p-2.5 rounded-xl bg-[#F7FAFD] border border-[#EAF1FB] flex items-start gap-2.5">
                        <i class="ph ph-gear text-[#08AEBC] text-base mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-[#171E26]">Updated Subscription Plan Rules</p>
                            <span class="text-[10px] text-[#171E26]/50">Yesterday · 11:15</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Full Record Link --}}
            <div class="pt-4 border-t border-[#F3F7FC]">
                <a href="{{ route('adminUserDetails', 'sulaimon-abubakre') }}" class="w-full flex items-center justify-center gap-1.5 bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white py-2.5 rounded-xl font-inter text-xs font-semibold shadow-sm hover:opacity-95 transition">
                    View Full Admin Profile <i class="ph ph-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>

    {{-- VANILLA JS SCRIPT CONTROLLER --}}
    <script>
        function openAddAdminModal() {
            document.getElementById('add-admin-modal-backdrop').classList.remove('hidden');
            document.getElementById('add-admin-modal').classList.remove('hidden');
        }

        function closeAddAdminModal() {
            document.getElementById('add-admin-modal-backdrop').classList.add('hidden');
            document.getElementById('add-admin-modal').classList.add('hidden');
        }

        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('ph-eye-slash');
                icon.classList.add('ph-eye');
            }
        }

        function openAdminDrawer(name, email, role, status, created, active, logins) {
            document.getElementById('drawer-name').innerText = name;
            document.getElementById('drawer-email').innerText = email;
            document.getElementById('drawer-role').innerText = role;
            document.getElementById('drawer-status').innerText = status;
            document.getElementById('drawer-created').innerText = created;
            document.getElementById('drawer-active').innerText = active;
            document.getElementById('drawer-logins').innerText = logins;

            // Simple initials extraction
            const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
            document.getElementById('drawer-avatar').innerText = initials;

            document.getElementById('admin-drawer-backdrop').classList.remove('hidden');
            document.getElementById('admin-drawer').classList.remove('translate-x-full');
        }

        function closeAdminDrawer() {
            document.getElementById('admin-drawer').classList.add('translate-x-full');
            document.getElementById('admin-drawer-backdrop').classList.add('hidden');
        }

        function confirmDeactivation(adminName) {
            document.getElementById('deactivate-admin-target').innerText = adminName;
            document.getElementById('deactivate-modal-backdrop').classList.remove('hidden');
            document.getElementById('deactivate-modal').classList.remove('hidden');
        }

        function closeDeactivateModal() {
            document.getElementById('deactivate-modal-backdrop').classList.add('hidden');
            document.getElementById('deactivate-modal').classList.add('hidden');
        }

        function executeDeactivation() {
            closeDeactivateModal();
            refreshAdminData();
        }

        function filterAdmins() {
            const searchQuery = document.getElementById('admin-search').value.toLowerCase();
            const statusFilter = document.getElementById('filter-status').value;
            const roleFilter = document.getElementById('filter-role').value;

            const rows = document.querySelectorAll('.admin-row');
            const cards = document.querySelectorAll('.mobile-admin-card');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name').toLowerCase();
                const email = row.getAttribute('data-email').toLowerCase();
                const status = row.getAttribute('data-status');
                const role = row.getAttribute('data-role');

                const matchesSearch = name.includes(searchQuery) || email.includes(searchQuery);
                const matchesStatus = (statusFilter === 'ALL' || status === statusFilter);
                const matchesRole = (roleFilter === 'ALL' || role === roleFilter);

                if (matchesSearch && matchesStatus && matchesRole) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            cards.forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                const status = card.getAttribute('data-status');
                const role = card.getAttribute('data-role');

                const matchesSearch = name.includes(searchQuery);
                const matchesStatus = (statusFilter === 'ALL' || status === statusFilter);
                const matchesRole = (roleFilter === 'ALL' || role === roleFilter);

                if (matchesSearch && matchesStatus && matchesRole) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            const emptyState = document.getElementById('empty-state');
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        function resetAdminFilters() {
            document.getElementById('admin-search').value = '';
            document.getElementById('filter-status').value = 'ALL';
            document.getElementById('filter-role').value = 'ALL';
            filterAdmins();
        }

        function refreshAdminData() {
            const icon = document.getElementById('refresh-icon');
            icon.classList.add('animate-spin');
            setTimeout(() => {
                icon.classList.remove('animate-spin');
            }, 750);
        }

        function handleCreateAdmin(e) {
            e.preventDefault();
            closeAddAdminModal();
            refreshAdminData();
        }
    </script>

</x-layouts.superadmin>