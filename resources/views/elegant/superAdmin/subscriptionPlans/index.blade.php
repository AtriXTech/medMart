<x-layouts.superadmin title="Subscription Plans Management" active="subscriptions-plans">

    <div class="space-y-6">

        {{-- PAGE HEADER & GLOBAL ACTIONS --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                    Subscription Plans
                </h1>
                <p class="font-inter text-xs md:text-sm text-[#171E26]/60 mt-0.5">
                    Manage the subscription plans available to MedMart pharmacies.
                </p>
            </div>

            {{-- Create Plan CTA Button --}}
            <button type="button" onclick="openCreateModal()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white px-4 py-2.5 rounded-xl font-inter text-xs font-semibold shadow-sm hover:opacity-95 active:scale-95 transition">
                <i class="ph ph-plus-circle text-base"></i>
                <span>Create Plan</span>
            </button>
        </div>

        {{-- KPI SECTION (3 COMPACT CARDS) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
            
            {{-- 1. Total Plans --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Total Plans</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1">3</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#2775E4] flex items-center justify-center">
                    <i class="ph-fill ph-[#2775E4] ph-squares-four text-xl"></i>
                </div>
            </div>

            {{-- 2. Active Plans --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Active Plans</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1">2</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                </div>
            </div>

            {{-- 3. Pharmacies Subscribed --}}
            <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-inter text-xs font-medium text-[#171E26]/60 block">Pharmacies Subscribed</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#2775E4] mt-1">184</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-[#E9F3FE] text-[#08AEBC] flex items-center justify-center">
                    <i class="ph-fill ph-storefront text-xl"></i>
                </div>
            </div>

        </div>

        {{-- SUBSCRIPTION PLAN CARDS MANAGEMENT GRID --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-manrope font-bold text-base text-[#171E26]">Available Subscription Tiers</h2>
                <span class="font-inter text-xs text-[#171E26]/50">Platform Admin Configured</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- PLAN CARD 1: STANDARD (ACTIVE) --}}
                <div class="bg-white border-2 border-[#2775E4]/30 rounded-2xl p-5 shadow-sm flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-gradient-to-l from-[#2775E4]/10 to-transparent w-24 h-24 pointer-events-none"></div>

                    <div class="space-y-4">
                        {{-- Header & Status --}}
                        <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3">
                            <div>
                                <span class="font-inter text-[11px] font-bold text-[#2775E4] tracking-wider uppercase">Tier 01</span>
                                <h3 class="font-manrope font-extrabold text-xl text-[#171E26]">Standard</h3>
                            </div>
                            <span class="inline-flex items-center gap-1 font-inter text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        </div>

                        {{-- Pricing --}}
                        <div>
                            <div class="flex items-baseline gap-1">
                                <span class="font-manrope font-extrabold text-2xl text-[#171E26]">₦9,500</span>
                                <span class="font-inter text-xs text-[#171E26]/60">/ month</span>
                            </div>
                            <p class="font-inter text-xs text-[#171E26]/70 mt-2 leading-relaxed">
                                A complete digital pharmacy management package for operational retail stores.
                            </p>
                        </div>

                        {{-- Feature List --}}
                        <div class="space-y-2 pt-2 border-t border-[#F3F7FC]">
                            <span class="font-inter text-[11px] font-bold uppercase tracking-wider text-[#171E26]/50 block">Included Features</span>
                            <ul class="space-y-1.5 font-inter text-xs text-[#171E26]/80">
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Customer ordering</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Pharmacy dashboard</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Point of Sale (POS)</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Sales & order management</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Customer management</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Card Footer & Actions --}}
                    <div class="pt-5 mt-4 border-t border-[#F3F7FC] space-y-3">
                        <div class="flex items-center justify-between font-inter text-xs">
                            <span class="text-[#171E26]/60">Current Adoption</span>
                            <span class="font-manrope font-bold text-[#2775E4] bg-[#E9F3FE] px-2.5 py-0.5 rounded-lg">126 Pharmacies</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1 font-inter text-xs font-semibold">
                            <button type="button" onclick="openEditModal('Standard', '9500', 'Monthly', 'A complete digital pharmacy management package.', ['Customer ordering', 'Pharmacy dashboard', 'POS', 'Sales & order management', 'Customer management'])" class="w-full border border-[#DBEBFB] bg-white text-[#171E26] hover:bg-[#F7FAFD] py-2 rounded-xl text-center transition shadow-sm">
                                Edit Plan
                            </button>
                            <button type="button" onclick="openDrawer('Standard')" class="w-full bg-[#E9F3FE] text-[#2775E4] hover:bg-[#2775E4] hover:text-white py-2 rounded-xl text-center transition">
                                Performance
                            </button>
                        </div>
                    </div>
                </div>

                {{-- PLAN CARD 2: PREMIUM (ACTIVE) --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm flex flex-col justify-between relative">
                    <div class="space-y-4">
                        {{-- Header & Status --}}
                        <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3">
                            <div>
                                <span class="font-inter text-[11px] font-bold text-[#08AEBC] tracking-wider uppercase">Tier 02</span>
                                <h3 class="font-manrope font-extrabold text-xl text-[#171E26]">Premium</h3>
                            </div>
                            <span class="inline-flex items-center gap-1 font-inter text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        </div>

                        {{-- Pricing --}}
                        <div>
                            <div class="flex items-baseline gap-1">
                                <span class="font-manrope font-extrabold text-2xl text-[#171E26]">₦15,000</span>
                                <span class="font-inter text-xs text-[#171E26]/60">/ month</span>
                            </div>
                            <p class="font-inter text-xs text-[#171E26]/70 mt-2 leading-relaxed">
                                Extended features for growing multi-location pharmacies and enterprise hubs.
                            </p>
                        </div>

                        {{-- Feature List --}}
                        <div class="space-y-2 pt-2 border-t border-[#F3F7FC]">
                            <span class="font-inter text-[11px] font-bold uppercase tracking-wider text-[#171E26]/50 block">Included Features</span>
                            <ul class="space-y-1.5 font-inter text-xs text-[#171E26]/80">
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Everything in Standard</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Multi-branch management</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Priority marketplace listing</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> Advanced financial reporting</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-emerald-600"></i> 24/7 Priority support</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Card Footer & Actions --}}
                    <div class="pt-5 mt-4 border-t border-[#F3F7FC] space-y-3">
                        <div class="flex items-center justify-between font-inter text-xs">
                            <span class="text-[#171E26]/60">Current Adoption</span>
                            <span class="font-manrope font-bold text-[#08AEBC] bg-[#E9F3FE] px-2.5 py-0.5 rounded-lg">58 Pharmacies</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1 font-inter text-xs font-semibold">
                            <button type="button" onclick="openEditModal('Premium', '15000', 'Monthly', 'Extended features for growing pharmacies.', ['Everything in Standard', 'Multi-branch management', 'Priority marketplace listing', 'Advanced financial reporting', '24/7 Priority support'])" class="w-full border border-[#DBEBFB] bg-white text-[#171E26] hover:bg-[#F7FAFD] py-2 rounded-xl text-center transition shadow-sm">
                                Edit Plan
                            </button>
                            <button type="button" onclick="openDrawer('Premium')" class="w-full bg-[#E9F3FE] text-[#2775E4] hover:bg-[#2775E4] hover:text-white py-2 rounded-xl text-center transition">
                                Performance
                            </button>
                        </div>
                    </div>
                </div>

                {{-- PLAN CARD 3: BASIC (INACTIVE) --}}
                <div class="bg-[#F7FAFD]/70 border border-[#EAF1FB] rounded-2xl p-5 shadow-sm flex flex-col justify-between relative opacity-85">
                    <div class="space-y-4">
                        {{-- Header & Status --}}
                        <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3">
                            <div>
                                <span class="font-inter text-[11px] font-bold text-[#171E26]/40 tracking-wider uppercase">Tier 00</span>
                                <h3 class="font-manrope font-extrabold text-xl text-[#171E26]/70">Basic</h3>
                            </div>
                            <span class="inline-flex items-center gap-1 font-inter text-xs font-semibold text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-0.5 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Inactive
                            </span>
                        </div>

                        {{-- Pricing --}}
                        <div>
                            <div class="flex items-baseline gap-1">
                                <span class="font-manrope font-extrabold text-2xl text-[#171E26]/70">₦5,000</span>
                                <span class="font-inter text-xs text-[#171E26]/50">/ month</span>
                            </div>
                            <p class="font-inter text-xs text-[#171E26]/60 mt-2 leading-relaxed">
                                Legacy baseline plan. Deprecated for new onboarding pharmacies.
                            </p>
                        </div>

                        {{-- Feature List --}}
                        <div class="space-y-2 pt-2 border-t border-[#F3F7FC]">
                            <span class="font-inter text-[11px] font-bold uppercase tracking-wider text-[#171E26]/40 block">Included Features</span>
                            <ul class="space-y-1.5 font-inter text-xs text-[#171E26]/60">
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-slate-400"></i> Basic POS terminal</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-slate-400"></i> Digital order view</li>
                                <li class="flex items-center gap-2"><i class="ph-bold ph-check text-slate-400"></i> Email support</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Card Footer & Actions --}}
                    <div class="pt-5 mt-4 border-t border-[#F3F7FC] space-y-3">
                        <div class="flex items-center justify-between font-inter text-xs">
                            <span class="text-[#171E26]/50">Current Adoption</span>
                            <span class="font-manrope font-bold text-[#171E26]/50 bg-slate-100 px-2.5 py-0.5 rounded-lg">0 Pharmacies</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1 font-inter text-xs font-semibold">
                            <button type="button" onclick="openEditModal('Basic', '5000', 'Monthly', 'Legacy baseline plan.', ['Basic POS terminal', 'Digital order view', 'Email support'], false)" class="w-full border border-[#DBEBFB] bg-white text-[#171E26] hover:bg-[#F7FAFD] py-2 rounded-xl text-center transition shadow-sm">
                                Edit Plan
                            </button>
                            <button type="button" onclick="openDrawer('Basic')" class="w-full bg-[#E9F3FE] text-[#2775E4] hover:bg-[#2775E4] hover:text-white py-2 rounded-xl text-center transition">
                                Performance
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- CREATE / EDIT PLAN FORM MODAL (VANILLA JS OPERATED) --}}
    <div id="plan-modal-backdrop" onclick="closePlanModal()" class="fixed inset-0 bg-[#171E26]/40 backdrop-blur-sm z-40 hidden transition-opacity"></div>

    <div id="plan-modal" class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-2xl z-50 hidden transition-all max-h-[90vh] overflow-y-auto">
        <div class="p-5 md:p-6 space-y-5">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-3">
                <h3 class="font-manrope font-extrabold text-lg text-[#171E26]" id="modal-title">Create Subscription Plan</h3>
                <button type="button" onclick="closePlanModal()" class="h-8 w-8 rounded-full bg-[#F7FAFD] flex items-center justify-center text-[#171E26]/60 hover:text-[#171E26] transition">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>

            {{-- Form Inputs --}}
            <form id="plan-form" onsubmit="event.preventDefault(); savePlan();" class="space-y-4 font-inter text-xs">
                
                {{-- Plan Name --}}
                <div>
                    <label class="block font-semibold text-[#171E26] mb-1">Plan Name</label>
                    <input type="text" id="form-plan-name" placeholder="e.g. Enterprise Tier" required class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 text-[#171E26] focus:outline-none focus:border-[#2775E4]" />
                </div>

                {{-- Price & Billing Interval Grid --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-[#171E26] mb-1">Price (₦)</label>
                        <input type="number" id="form-plan-price" placeholder="9500" required class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 text-[#171E26] focus:outline-none focus:border-[#2775E4]" />
                    </div>

                    <div>
                        <label class="block font-semibold text-[#171E26] mb-1">Billing Interval</label>
                        <select id="form-plan-interval" class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 text-[#171E26] focus:outline-none focus:border-[#2775E4] cursor-pointer">
                            <option value="Monthly">Monthly</option>
                            <option value="Quarterly">Quarterly</option>
                            <option value="Annual">Annual</option>
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block font-semibold text-[#171E26] mb-1">Description</label>
                    <textarea id="form-plan-description" rows="2" placeholder="Brief explanation of target pharmacy tier..." required class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3.5 py-2.5 text-[#171E26] focus:outline-none focus:border-[#2775E4]"></textarea>
                </div>

                {{-- Feature List Dynamic Input Container --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block font-semibold text-[#171E26]">Included Features</label>
                        <button type="button" onclick="addFeatureInput()" class="text-xs font-semibold text-[#2775E4] hover:underline flex items-center gap-1">
                            <i class="ph ph-plus text-xs"></i> Add Feature
                        </button>
                    </div>

                    <div id="features-container" class="space-y-2">
                        {{-- Feature Row template appended here dynamically via Vanilla JS --}}
                    </div>
                </div>

                {{-- Active Status Checkbox --}}
                <div class="pt-2 flex items-center gap-2">
                    <input type="checkbox" id="form-plan-active" class="h-4 w-4 rounded border-[#DBEBFB] text-[#2775E4] focus:ring-0 cursor-pointer" checked />
                    <label for="form-plan-active" class="font-semibold text-[#171E26] cursor-pointer">Plan is active and available to pharmacies</label>
                </div>

                {{-- Form Actions --}}
                <div class="pt-4 border-t border-[#F3F7FC] flex items-center justify-end gap-2">
                    <button type="button" onclick="closePlanModal()" class="px-4 py-2 rounded-xl border border-[#DBEBFB] bg-white text-[#171E26] font-semibold hover:bg-[#F7FAFD] transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-semibold shadow-sm hover:opacity-95 transition">
                        Save Plan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- PLAN PERFORMANCE RESPONSIVE SLIDE-OVER DRAWER --}}
    <div id="drawer-backdrop" onclick="closeDrawer()" class="fixed inset-0 bg-[#171E26]/40 backdrop-blur-sm z-40 hidden transition-opacity"></div>

    <div id="plan-drawer" class="fixed right-0 top-0 bottom-0 w-full max-w-lg bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">
        <div class="p-5 md:p-6 space-y-6">
            
            {{-- Drawer Header --}}
            <div class="flex items-center justify-between border-b border-[#F3F7FC] pb-4">
                <div>
                    <span class="font-inter text-xs text-[#171E26]/50">Plan Performance</span>
                    <h2 class="font-manrope font-extrabold text-xl text-[#171E26]" id="drawer-plan-title">Standard Plan</h2>
                </div>
                <button type="button" onclick="closeDrawer()" class="h-8 w-8 rounded-full bg-[#F7FAFD] flex items-center justify-center text-[#171E26]/60 hover:text-[#171E26] transition">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>

            {{-- Plan Performance KPI Summary Grid --}}
            <div class="grid grid-cols-3 gap-2 bg-[#E9F3FE]/60 border border-[#DBEBFB] rounded-2xl p-4 text-center font-inter">
                <div>
                    <span class="text-[11px] text-[#171E26]/60 block">Subscribers</span>
                    <p class="font-manrope font-extrabold text-lg text-[#171E26]" id="drawer-subscribers">126</p>
                </div>
                <div class="border-x border-[#DBEBFB]">
                    <span class="text-[11px] text-[#171E26]/60 block">Monthly Revenue</span>
                    <p class="font-manrope font-extrabold text-lg text-[#2775E4]" id="drawer-revenue">₦1,197,000</p>
                </div>
                <div>
                    <span class="text-[11px] text-[#171E26]/60 block">Renewals</span>
                    <p class="font-manrope font-extrabold text-lg text-emerald-700" id="drawer-renewals">118</p>
                </div>
            </div>

            {{-- Plan Summary Description --}}
            <div class="space-y-1.5 font-inter text-xs">
                <span class="text-[#171E26]/50 font-semibold uppercase tracking-wider text-[10px]">Description</span>
                <p class="text-[#171E26]/80 leading-relaxed" id="drawer-description">A complete digital pharmacy management package.</p>
            </div>

            {{-- Plan Feature Checklist --}}
            <div class="space-y-2 pt-2 border-t border-[#F3F7FC]">
                <span class="font-inter text-xs font-bold text-[#171E26] block">Configured Plan Features</span>
                <ul class="space-y-1.5 font-inter text-xs text-[#171E26]/80" id="drawer-features-list">
                    {{-- Populated via Vanilla JS --}}
                </ul>
            </div>

            {{-- Recent Subscription Activity --}}
            <div class="space-y-3 pt-2 border-t border-[#F3F7FC]">
                <div class="flex items-center justify-between">
                    <span class="font-manrope font-bold text-sm text-[#171E26]">Recent Subscription Activity</span>
                    <span class="font-inter text-[11px] text-[#2775E4] font-semibold">Real-time Backend Stream</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left font-inter text-xs">
                        <thead>
                            <tr class="bg-[#F7FAFD] border-b border-[#EAF1FB] text-[10px] font-bold text-[#171E26]/50 uppercase">
                                <th class="py-2 px-3">Pharmacy</th>
                                <th class="py-2 px-3">Status</th>
                                <th class="py-2 px-3 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F3F7FC]" id="drawer-activity-list">
                            {{-- Dynamic Rows via JS --}}
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- View Full Subscriber Statement Link --}}
            <div class="pt-3 border-t border-[#F3F7FC]">
                <a href="{{ route('subscriptionPlanShow', 'standard') }}" id="drawer-view-full" class="w-full flex items-center justify-center gap-1.5 bg-[#2775E4] hover:bg-[#2775E4]/90 text-white py-2.5 rounded-xl font-inter text-xs font-semibold shadow-sm transition">
                    View Full Plan Analytics <i class="ph ph-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>

    {{-- VANILLA JAVASCRIPT CONTROLLERS --}}
    <script>
        // Mock dataset for pure Vanilla JS modal and drawer state binding
        const planPerformanceData = {
            'Standard': {
                title: 'Standard Plan',
                subscribers: '126',
                revenue: '₦1,197,000',
                renewals: '118',
                description: 'A complete digital pharmacy management package for operational retail stores.',
                features: ['Customer ordering', 'Pharmacy dashboard', 'POS', 'Sales & order management', 'Customer management'],
                activity: [
                    { pharmacy: 'Triple B Pharmacy', status: 'Active', date: 'Sep 8, 2026' },
                    { pharmacy: 'MedMart Yaba Branch', status: 'Active', date: 'Sep 7, 2026' },
                    { pharmacy: 'Kano Med Centre', status: 'Renewed', date: 'Sep 5, 2026' }
                ]
            },
            'Premium': {
                title: 'Premium Plan',
                subscribers: '58',
                revenue: '₦870,000',
                renewals: '54',
                description: 'Extended features for growing multi-location pharmacies and enterprise hubs.',
                features: ['Everything in Standard', 'Multi-branch management', 'Priority marketplace listing', 'Advanced financial reporting', '24/7 Priority support'],
                activity: [
                    { pharmacy: 'Apex Care Pharmacy', status: 'Active', date: 'Sep 8, 2026' },
                    { pharmacy: 'Victoria Island Hub', status: 'Active', date: 'Sep 6, 2026' }
                ]
            },
            'Basic': {
                title: 'Basic Plan',
                subscribers: '0',
                revenue: '₦0',
                renewals: '0',
                description: 'Legacy baseline plan. Deprecated for new onboarding pharmacies.',
                features: ['Basic POS terminal', 'Digital order view', 'Email support'],
                activity: []
            }
        };

        function openDrawer(planKey) {
            const data = planPerformanceData[planKey];
            if (!data) return;

            document.getElementById('drawer-plan-title').innerText = data.title;
            document.getElementById('drawer-subscribers').innerText = data.subscribers;
            document.getElementById('drawer-revenue').innerText = data.revenue;
            document.getElementById('drawer-renewals').innerText = data.renewals;
            document.getElementById('drawer-description').innerText = data.description;

            // Features list
            const featuresContainer = document.getElementById('drawer-features-list');
            featuresContainer.innerHTML = '';
            data.features.forEach(feat => {
                const li = document.createElement('li');
                li.className = 'flex items-center gap-2';
                li.innerHTML = `<i class="ph-bold ph-check text-emerald-600"></i> ${feat}`;
                featuresContainer.appendChild(li);
            });

            // Activity list
            const activityContainer = document.getElementById('drawer-activity-list');
            activityContainer.innerHTML = '';
            if (data.activity.length === 0) {
                activityContainer.innerHTML = `<tr><td colspan="3" class="py-3 text-center text-[#171E26]/40">No recent activity recorded</td></tr>`;
            } else {
                data.activity.forEach(act => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="py-2.5 px-3 font-semibold text-[#171E26]">${act.pharmacy}</td>
                        <td class="py-2.5 px-3"><span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-semibold text-[10px]">${act.status}</span></td>
                        <td class="py-2.5 px-3 text-right text-[#171E26]/60">${act.date}</td>
                    `;
                    activityContainer.appendChild(tr);
                });
            }

            // Reveal Drawer
            document.getElementById('drawer-backdrop').classList.remove('hidden');
            document.getElementById('plan-drawer').classList.remove('translate-x-full');
        }

        function closeDrawer() {
            document.getElementById('plan-drawer').classList.add('translate-x-full');
            document.getElementById('drawer-backdrop').classList.add('hidden');
        }

        function openCreateModal() {
            document.getElementById('modal-title').innerText = 'Create Subscription Plan';
            document.getElementById('form-plan-name').value = '';
            document.getElementById('form-plan-price').value = '';
            document.getElementById('form-plan-interval').value = 'Monthly';
            document.getElementById('form-plan-description').value = '';
            document.getElementById('form-plan-active').checked = true;

            const container = document.getElementById('features-container');
            container.innerHTML = '';
            addFeatureInput('Customer ordering');
            addFeatureInput('Pharmacy dashboard');

            document.getElementById('plan-modal-backdrop').classList.remove('hidden');
            document.getElementById('plan-modal').classList.remove('hidden');
        }

        function openEditModal(name, price, interval, description, features, active = true) {
            document.getElementById('modal-title').innerText = `Edit ${name} Plan`;
            document.getElementById('form-plan-name').value = name;
            document.getElementById('form-plan-price').value = price;
            document.getElementById('form-plan-interval').value = interval;
            document.getElementById('form-plan-description').value = description;
            document.getElementById('form-plan-active').checked = active;

            const container = document.getElementById('features-container');
            container.innerHTML = '';
            features.forEach(feat => addFeatureInput(feat));

            document.getElementById('plan-modal-backdrop').classList.remove('hidden');
            document.getElementById('plan-modal').classList.remove('hidden');
        }

        function closePlanModal() {
            document.getElementById('plan-modal').classList.add('hidden');
            document.getElementById('plan-modal-backdrop').classList.add('hidden');
        }

        function addFeatureInput(val = '') {
            const container = document.getElementById('features-container');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.innerHTML = `
                <input type="text" value="${val}" placeholder="Feature description..." required class="w-full bg-[#F7FAFD] border border-[#DBEBFB] rounded-xl px-3 py-2 text-[#171E26] focus:outline-none focus:border-[#2775E4]" />
                <button type="button" onclick="this.parentElement.remove()" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="ph ph-trash text-sm"></i></button>
            `;
            container.appendChild(row);
        }

        function savePlan() {
            // Simulated form submission with modal closing
            closePlanModal();
        }
    </script>

</x-layouts.superadmin>