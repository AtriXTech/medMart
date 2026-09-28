<x-layouts.superAdmin title="Settings" active="settings">

    <div x-data="{ 
        state: 'loaded', 
        activeTab: 'general',
        maintenanceMode: false,
        require2fa: true,
        emailAlerts: true,
        smsAlerts: false,
        saving: false,
        savedSuccess: false,
        saveSettings() {
            this.saving = true;
            setTimeout(() => {
                this.saving = false;
                this.savedSuccess = true;
                setTimeout(() => this.savedSuccess = false, 3000);
            }, 600);
        }
    }" class="space-y-6">

        {{-- PAGE HEADER --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                    Platform Settings
                </h1>
                <p class="font-inter text-xs md:text-sm text-[#171E26]/60 mt-0.5">
                    Manage global system configurations, security policies, and integrations.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <span x-show="savedSuccess" x-transition class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/60 font-inter text-xs font-semibold">
                    <i class="ph ph-check-circle text-sm text-emerald-600"></i> Settings Saved
                </span>
                <span x-show="!savedSuccess" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#E9F3FE] text-[#2775E4] font-inter text-xs font-medium">
                    <i class="ph ph-clock text-sm"></i> Last modified 2 hrs ago
                </span>
                <button @click="saveSettings()" 
                        type="button" 
                        :disabled="saving"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-[#2775E4] border border-[#2775E4] rounded-xl px-5 py-2.5 font-inter text-xs font-semibold text-white shadow-sm hover:bg-[#1f63c8] active:scale-95 transition disabled:opacity-60">
                    <i class="ph ph-[#saving ? 'arrows-clockwise animate-spin' : 'floppy-disk'] text-base"></i>
                    <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                </button>
            </div>
        </div>

        {{-- KPI SUMMARY CARDS --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">System Status</span>
                    <p class="font-manrope font-extrabold text-lg md:text-xl text-emerald-600 mt-1 tracking-tight flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span> Operational
                    </p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-cpu text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Security Health</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#2775E4] mt-1 tracking-tight">96%</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-shield-check text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Active Integrations</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#08AEBC] mt-1 tracking-tight">8 / 10</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#08AEBC] flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-plugs-connected text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Platform Commission</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1 tracking-tight">5.0%</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-percent text-xl"></i>
                </div>
            </div>
        </div>

        {{-- SETTINGS MAIN NAVIGATION & PANEL --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- LEFT TAB NAVIGATION --}}
            <div class="lg:col-span-3 bg-white border border-[#EAF1FB] rounded-2xl p-2 md:p-3 shadow-sm space-y-1">
                <button @click="activeTab = 'general'" 
                        :class="activeTab === 'general' ? 'bg-[#E9F3FE] text-[#2775E4] font-bold' : 'text-[#171E26]/70 hover:bg-[#F7FAFD] hover:text-[#171E26] font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-inter text-xs md:text-sm text-left transition">
                    <i class="ph ph-[#activeTab === 'general' ? 'gear-fill' : 'gear'] text-lg"></i>
                    <span>General Settings</span>
                </button>

                <button @click="activeTab = 'security'" 
                        :class="activeTab === 'security' ? 'bg-[#E9F3FE] text-[#2775E4] font-bold' : 'text-[#171E26]/70 hover:bg-[#F7FAFD] hover:text-[#171E26] font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-inter text-xs md:text-sm text-left transition">
                    <i class="ph ph-[#activeTab === 'security' ? 'shield-check-fill' : 'shield-check'] text-lg"></i>
                    <span>Security & Access</span>
                </button>

                <button @click="activeTab = 'notifications'" 
                        :class="activeTab === 'notifications' ? 'bg-[#E9F3FE] text-[#2775E4] font-bold' : 'text-[#171E26]/70 hover:bg-[#F7FAFD] hover:text-[#171E26] font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-inter text-xs md:text-sm text-left transition">
                    <i class="ph ph-[#activeTab === 'notifications' ? 'bell-ringing-fill' : 'bell-ringing'] text-lg"></i>
                    <span>Notifications & Alerts</span>
                </button>

                <button @click="activeTab = 'commissions'" 
                        :class="activeTab === 'commissions' ? 'bg-[#E9F3FE] text-[#2775E4] font-bold' : 'text-[#171E26]/70 hover:bg-[#F7FAFD] hover:text-[#171E26] font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-inter text-xs md:text-sm text-left transition">
                    <i class="ph ph-[#activeTab === 'commissions' ? 'currency-circle-dollar-fill' : 'currency-circle-dollar'] text-lg"></i>
                    <span>Fees & Commissions</span>
                </button>

                <button @click="activeTab = 'integrations'" 
                        :class="activeTab === 'integrations' ? 'bg-[#E9F3FE] text-[#2775E4] font-bold' : 'text-[#171E26]/70 hover:bg-[#F7FAFD] hover:text-[#171E26] font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-inter text-xs md:text-sm text-left transition">
                    <i class="ph ph-[#activeTab === 'integrations' ? 'plugs-fill' : 'plugs'] text-lg"></i>
                    <span>API & Gateways</span>
                </button>
            </div>

            {{-- RIGHT PANEL CONTENT --}}
            <div class="lg:col-span-9 bg-white border border-[#EAF1FB] rounded-2xl p-5 md:p-6 shadow-sm">

                {{-- TAB 1: GENERAL SETTINGS --}}
                <div x-show="activeTab === 'general'" class="space-y-6">
                    <div class="border-b border-[#F3F7FC] pb-4">
                        <h3 class="font-manrope font-bold text-base md:text-lg text-[#171E26]">General Platform Settings</h3>
                        <p class="font-inter text-xs text-[#171E26]/50">Configure core identity and global platform parameters.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                        <div class="space-y-1.5">
                            <label class="font-inter text-xs font-semibold text-[#171E26]">Platform Name</label>
                            <input type="text" value="MedMart Marketplace" 
                                   class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-4 py-2.5 font-inter text-xs md:text-sm text-[#171E26] focus:outline-none focus:bg-white focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/20 transition" />
                        </div>

                        <div class="space-y-1.5">
                            <label class="font-inter text-xs font-semibold text-[#171E26]">Support Contact Email</label>
                            <input type="email" value="support@medmart.com" 
                                   class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-4 py-2.5 font-inter text-xs md:text-sm text-[#171E26] focus:outline-none focus:bg-white focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/20 transition" />
                        </div>

                        <div class="space-y-1.5">
                            <label class="font-inter text-xs font-semibold text-[#171E26]">Default Currency</label>
                            <select class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 font-inter text-xs md:text-sm text-[#171E26] focus:outline-none focus:bg-white focus:border-[#2775E4]">
                                <option value="NGN" selected>NGN (₦) - Nigerian Naira</option>
                                <option value="USD">USD ($) - US Dollar</option>
                                <option value="GHS">GHS (₵) - Ghanaian Cedi</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="font-inter text-xs font-semibold text-[#171E26]">Timezone</label>
                            <select class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 font-inter text-xs md:text-sm text-[#171E26] focus:outline-none focus:bg-white focus:border-[#2775E4]">
                                <option value="WAT" selected>(GMT+01:00) West Africa Time (Lagos)</option>
                                <option value="UTC">(UTC+00:00) Universal Coordinated Time</option>
                            </select>
                        </div>
                    </div>

                    <hr class="border-[#F3F7FC]" />

                    {{-- Maintenance Mode Toggle --}}
                    <div class="flex items-center justify-between p-4 rounded-xl bg-[#F7FAFD] border border-[#DBEBFB]">
                        <div class="space-y-0.5">
                            <span class="font-manrope font-bold text-sm text-[#171E26]">System Maintenance Mode</span>
                            <p class="font-inter text-xs text-[#171E26]/60">Temporarily restrict access for non-admin users across store fronts.</p>
                        </div>
                        <button @click="maintenanceMode = !maintenanceMode" type="button" 
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                :class="maintenanceMode ? 'bg-[#2775E4]' : 'bg-slate-300'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                  :class="maintenanceMode ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </div>
                </div>

                {{-- TAB 2: SECURITY & ACCESS --}}
                <div x-show="activeTab === 'security'" class="space-y-6">
                    <div class="border-b border-[#F3F7FC] pb-4">
                        <h3 class="font-manrope font-bold text-base md:text-lg text-[#171E26]">Security & Authentication</h3>
                        <p class="font-inter text-xs text-[#171E26]/50">Manage access controls, 2FA, and session timeouts.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-xl bg-[#F7FAFD] border border-[#DBEBFB]">
                            <div>
                                <span class="font-manrope font-bold text-sm text-[#171E26] block">Mandatory 2FA for Admins</span>
                                <span class="font-inter text-xs text-[#171E26]/60">Enforce Two-Factor Authentication for all SuperAdmin accounts.</span>
                            </div>
                            <button @click="require2fa = !require2fa" type="button" 
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="require2fa ? 'bg-[#2775E4]' : 'bg-slate-300'">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                      :class="require2fa ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="font-inter text-xs font-semibold text-[#171E26]">Session Timeout (Minutes)</label>
                                <input type="number" value="30" 
                                       class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-4 py-2.5 font-inter text-xs md:text-sm text-[#171E26] focus:outline-none focus:bg-white focus:border-[#2775E4]" />
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-inter text-xs font-semibold text-[#171E26]">Failed Login Lockout Threshold</label>
                                <select class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 font-inter text-xs md:text-sm text-[#171E26] focus:outline-none focus:bg-white focus:border-[#2775E4]">
                                    <option value="3">3 Attempts</option>
                                    <option value="5" selected>5 Attempts</option>
                                    <option value="10">10 Attempts</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: NOTIFICATIONS & ALERTS --}}
                <div x-show="activeTab === 'notifications'" class="space-y-6">
                    <div class="border-b border-[#F3F7FC] pb-4">
                        <h3 class="font-manrope font-bold text-base md:text-lg text-[#171E26]">Notifications & Alerts</h3>
                        <p class="font-inter text-xs text-[#171E26]/50">Choose system triggers for automated admin alerts.</p>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-start gap-3 p-3.5 rounded-xl border border-[#EAF1FB] hover:bg-[#F7FAFD] cursor-pointer transition">
                            <input type="checkbox" checked class="mt-0.5 rounded text-[#2775E4] focus:ring-[#2775E4]" />
                            <div>
                                <span class="font-manrope font-bold text-xs md:text-sm text-[#171E26] block">New Pharmacy Signups</span>
                                <span class="font-inter text-xs text-[#171E26]/60">Receive an instant email notification when a new pharmacy applies.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3.5 rounded-xl border border-[#EAF1FB] hover:bg-[#F7FAFD] cursor-pointer transition">
                            <input type="checkbox" checked class="mt-0.5 rounded text-[#2775E4] focus:ring-[#2775E4]" />
                            <div>
                                <span class="font-manrope font-bold text-xs md:text-sm text-[#171E26] block">High Value Orders Flag</span>
                                <span class="font-inter text-xs text-[#171E26]/60">Alert super admins for any customer order exceeding ₦1,000,000.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3.5 rounded-xl border border-[#EAF1FB] hover:bg-[#F7FAFD] cursor-pointer transition">
                            <input type="checkbox" class="mt-0.5 rounded text-[#2775E4] focus:ring-[#2775E4]" />
                            <div>
                                <span class="font-manrope font-bold text-xs md:text-sm text-[#171E26] block">Daily Financial Summary Digest</span>
                                <span class="font-inter text-xs text-[#171E26]/60">Send daily sales and payout breakdown report at 00:00 WAT.</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- TAB 4: COMMISSIONS & FEES --}}
                <div x-show="activeTab === 'commissions'" class="space-y-6">
                    <div class="border-b border-[#F3F7FC] pb-4">
                        <h3 class="font-manrope font-bold text-base md:text-lg text-[#171E26]">Platform Fees & Commissions</h3>
                        <p class="font-inter text-xs text-[#171E26]/50">Define default marketplace transaction rates and payout schedules.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="font-inter text-xs font-semibold text-[#171E26]">Default Commission Rate (%)</label>
                            <div class="relative">
                                <input type="text" value="5.0" 
                                       class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl pl-4 pr-8 py-2.5 font-inter text-xs md:text-sm text-[#171E26] focus:outline-none focus:bg-white focus:border-[#2775E4]" />
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 font-bold text-xs text-[#171E26]/40">%</span>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="font-inter text-xs font-semibold text-[#171E26]">Pharmacy Payout Schedule</label>
                            <select class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 font-inter text-xs md:text-sm text-[#171E26] focus:outline-none focus:bg-white focus:border-[#2775E4]">
                                <option value="daily">Daily Auto Payout</option>
                                <option value="weekly" selected>Weekly (Every Monday)</option>
                                <option value="biweekly">Bi-Weekly</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- TAB 5: API & GATEWAYS --}}
                <div x-show="activeTab === 'integrations'" class="space-y-6">
                    <div class="border-b border-[#F3F7FC] pb-4">
                        <h3 class="font-manrope font-bold text-base md:text-lg text-[#171E26]">Gateways & External APIs</h3>
                        <p class="font-inter text-xs text-[#171E26]/50">Manage Paystack, Termii SMS, and logistics integrations.</p>
                    </div>

                    <div class="space-y-3">
                        <div class="p-4 border border-[#EAF1FB] rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-blue-50 text-[#2775E4] font-bold text-xs flex items-center justify-center">PAY</div>
                                <div>
                                    <span class="font-manrope font-bold text-sm text-[#171E26] block">Paystack Payment Gateway</span>
                                    <span class="font-mono text-xs text-[#171E26]/50">pk_live_83a9...920f</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Connected
                            </span>
                        </div>

                        <div class="p-4 border border-[#EAF1FB] rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-teal-50 text-[#08AEBC] font-bold text-xs flex items-center justify-center">SMS</div>
                                <div>
                                    <span class="font-manrope font-bold text-sm text-[#171E26] block">Termii SMS Gateway</span>
                                    <span class="font-mono text-xs text-[#171E26]/50">t_api_99182...441a</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Connected
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-layouts.superAdmin>