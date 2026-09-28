<x-layouts.superadmin title="Admin Profile - Sulaimon Abubakre" active="administration-admins">

    <div class="space-y-6">

        {{-- BACK NAVIGATION & HEADER --}}
        <div class="space-y-3">
            <a href="{{ route('adminUsers') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2775E4] hover:underline">
                <i class="ph ph-arrow-left"></i> Back to Admin Users
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] text-white font-manrope font-extrabold text-base flex items-center justify-center shrink-0 shadow-sm">
                        SA
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                                Sulaimon Abubakre
                            </h1>
                            <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                ● Active
                            </span>
                        </div>
                        <p class="font-inter text-xs text-[#171E26]/60 mt-0.5">
                            Super Admin · sulaimon@medmart.com
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-[#DBEBFB] bg-white font-inter text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                        Deactivate Admin
                    </button>
                    <button type="button" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#2775E4] text-white font-inter text-xs font-semibold shadow-sm hover:bg-[#2775E4]/90 transition">
                        Edit Access Permissions
                    </button>
                </div>
            </div>
        </div>

        {{-- KPI SNAPSHOT CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Date Created</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#171E26] mt-1">Sep 1, 2026</p>
                <span class="font-inter text-[11px] text-[#171E26]/50 mt-1 block">Platform Administrator</span>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Last Login Activity</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-[#2775E4] mt-1">Today · 16:42</p>
                <span class="font-inter text-[11px] text-emerald-600 font-semibold mt-1 block">Current session active</span>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm">
                <span class="font-inter text-xs text-[#171E26]/60 block">Total Login Count</span>
                <p class="font-manrope font-extrabold text-xl md:text-2xl text-emerald-700 mt-1">248 Logins</p>
                <span class="font-inter text-[11px] text-[#171E26]/50 mt-1 block">0 failed attempts</span>
            </div>
        </div>

        {{-- CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN: DETAILED ADMIN PROFILE --}}
            <div class="lg:col-span-1 bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="font-manrope font-bold text-base text-[#171E26] border-b border-[#F3F7FC] pb-3">Admin Profile</h3>

                <div class="space-y-3 font-inter text-xs">
                    <div>
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Full Name</span>
                        <p class="font-manrope font-bold text-sm text-[#171E26] mt-0.5">Sulaimon Abubakre</p>
                    </div>

                    <div class="pt-2 border-t border-[#F3F7FC]">
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Email Address</span>
                        <p class="font-semibold text-[#2775E4] mt-0.5">sulaimon@medmart.com</p>
                    </div>

                    <div class="pt-2 border-t border-[#F3F7FC]">
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Platform Role</span>
                        <p class="font-semibold text-[#171E26] mt-0.5">Super Admin</p>
                    </div>

                    <div class="pt-2 border-t border-[#F3F7FC]">
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Account Status</span>
                        <p class="font-semibold text-emerald-700 mt-0.5">Active</p>
                    </div>

                    <div class="pt-2 border-t border-[#F3F7FC]">
                        <span class="text-[#171E26]/50 block uppercase tracking-wider text-[10px] font-semibold">Two-Factor Authentication</span>
                        <p class="font-semibold text-emerald-700 mt-0.5">● Enabled (Authenticator App)</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: ACTIVITY LOG TABLE --}}
            <div class="lg:col-span-2 bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3">
                    <h3 class="font-manrope font-bold text-base text-[#171E26]">Platform Activity Log</h3>
                    <span class="font-inter text-xs text-[#171E26]/50">Audit Trail</span>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left font-inter text-xs">
                        <thead>
                            <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[10px] font-bold text-[#171E26]/50 uppercase">
                                <th class="py-2.5 px-3">Event Action</th>
                                <th class="py-2.5 px-3">IP Address</th>
                                <th class="py-2.5 px-3 text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F3F7FC]">
                            <tr>
                                <td class="py-3 px-3 font-semibold text-[#171E26]">Logged into Super Admin Platform</td>
                                <td class="py-3 px-3 text-[#171E26]/70">102.89.23.4</td>
                                <td class="py-3 px-3 text-right text-[#171E26]/70">Today · 16:42</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-3 font-semibold text-[#171E26]">Updated Subscription Pricing Tier Settings</td>
                                <td class="py-3 px-3 text-[#171E26]/70">102.89.23.4</td>
                                <td class="py-3 px-3 text-right text-[#171E26]/70">Sep 7, 2026 · 11:15</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-3 font-semibold text-[#171E26]">Approved Pharmacy B Settlement Batch</td>
                                <td class="py-3 px-3 text-[#171E26]/70">102.89.23.4</td>
                                <td class="py-3 px-3 text-right text-[#171E26]/70">Sep 5, 2026 · 09:30</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

</x-layouts.superadmin>