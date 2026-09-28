<x-layouts.superadmin title="Transaction Details - MM-92831" active="finance-transactions">

    <div class="space-y-6">

        {{-- NAVIGATION & BACK HEADER --}}
        <div class="space-y-3">
            <a href="{{ route('transactions') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2775E4] hover:underline">
                <i class="ph ph-arrow-left"></i> Back to Transactions
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="font-manrope font-extrabold text-2xl md:text-[26px] text-[#171E26] tracking-tight">
                            MM-92831
                        </h1>
                        <span class="inline-flex items-center gap-1 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Transaction Successful
                        </span>
                    </div>
                    <p class="font-inter text-xs text-[#171E26]/60 mt-0.5">
                        Processed via Paystack Gateway · Sep 07, 2026 at 14:32:05 WAT
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" class="flex items-center gap-1.5 px-3 py-2 rounded-xl border border-[#DBEBFB] bg-white font-inter text-xs font-semibold text-[#171E26] shadow-sm hover:bg-[#F7FAFD] transition">
                        <i class="ph ph-printer text-sm text-[#2775E4]"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>

        {{-- SUMMARY OVERVIEW CARD --}}
        <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 shadow-sm bg-gradient-to-r from-white via-white to-[#E9F3FE]/30">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 font-inter text-xs">
                <div>
                    <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Gross Amount</span>
                    <p class="font-manrope font-extrabold text-2xl md:text-3xl text-[#171E26] mt-1">₦25,000.00</p>
                </div>
                <div>
                    <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Transaction Type</span>
                    <p class="font-manrope font-bold text-base text-[#2775E4] mt-1">Customer Order Payment</p>
                </div>
                <div>
                    <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Payment Status</span>
                    <p class="font-manrope font-bold text-base text-emerald-600 mt-1">Successful</p>
                </div>
                <div>
                    <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Completed Date</span>
                    <p class="font-manrope font-bold text-base text-[#171E26] mt-1">Sep 07, 2026</p>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN: TRANSACTION INFORMATION --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Detailed Fields Card --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] border-b border-[#F3F7FC] pb-3">Transaction Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 font-inter text-xs md:text-sm">
                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Platform Reference</span>
                            <p class="font-mono font-bold text-[#171E26] mt-0.5">MM-92831</p>
                        </div>

                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Gateway Reference</span>
                            <p class="font-mono text-[#171E26] mt-0.5">PSTK_98120381920381</p>
                        </div>

                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Payment Method</span>
                            <p class="font-medium text-[#171E26] mt-0.5">Debit Card (Mastercard ending in 4092)</p>
                        </div>

                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Associated Pharmacy</span>
                            <p class="font-manrope font-bold text-[#2775E4] mt-0.5">Triple B Pharmacy</p>
                        </div>

                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Created Date & Time</span>
                            <p class="font-medium text-[#171E26] mt-0.5">Sep 07, 2026 · 14:30:12 WAT</p>
                        </div>

                        <div>
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Completed Date & Time</span>
                            <p class="font-medium text-[#171E26] mt-0.5">Sep 07, 2026 · 14:32:05 WAT</p>
                        </div>

                        <div class="md:col-span-2 pt-2 border-t border-[#F3F7FC]">
                            <span class="text-[#171E26]/50 block text-[11px] uppercase tracking-wider font-semibold">Related Platform Entity</span>
                            <p class="font-mono text-xs text-[#2775E4] mt-0.5">Order Ref: #ORD-2026-8849</p>
                        </div>
                    </div>
                </div>

                {{-- FAILED STATE DEMONSTRATION CALLOUT (Conditional rendering on backend) --}}
                @if(false)
                <div class="bg-rose-50 border border-rose-200/80 rounded-2xl p-4 md:p-5 shadow-sm space-y-2">
                    <div class="flex items-center gap-2 text-rose-700">
                        <i class="ph ph-warning text-lg"></i>
                        <h4 class="font-manrope font-bold text-sm">Payment Failed</h4>
                    </div>
                    <p class="font-inter text-xs text-rose-900/80">
                        Failure Reason: Insufficient funds or card authorization timeout from issuing bank.
                    </p>
                </div>
                @endif

            </div>

            {{-- RIGHT COLUMN: FINANCIAL BREAKDOWN & SETTLEMENT STATUS --}}
            <div class="space-y-6">

                {{-- Financial Breakdown (Backend Provided Data) --}}
                <div class="bg-white border border-[#EAF1FB] rounded-2xl p-4 md:p-5 shadow-sm space-y-4">
                    <h3 class="font-manrope font-bold text-base text-[#171E26] border-b border-[#F3F7FC] pb-3">Financial Breakdown</h3>

                    <div class="space-y-3 font-inter text-xs">
                        <div class="flex items-center justify-between py-1">
                            <span class="text-[#171E26]/70">Gross Transaction Amount</span>
                            <span class="font-manrope font-bold text-[#171E26]">₦25,000.00</span>
                        </div>

                        <div class="flex items-center justify-between py-1 text-emerald-600">
                            <span>MedMart Platform Fee (2%)</span>
                            <span class="font-manrope font-bold">+₦500.00</span>
                        </div>

                        <div class="pt-3 border-t border-[#F3F7FC] flex items-center justify-between font-semibold">
                            <span class="text-[#171E26]">Pharmacy Net Amount</span>
                            <span class="font-manrope font-extrabold text-[#2775E4] text-sm">₦24,500.00</span>
                        </div>
                    </div>
                </div>

                {{-- Important Concept Visual Separation Card --}}
                <div class="bg-[#F7FAFD] border border-[#DBEBFB] rounded-2xl p-4 shadow-sm space-y-2.5">
                    <div class="flex items-center gap-1.5 font-manrope font-bold text-xs text-[#171E26]">
                        <i class="ph ph-arrows-merge text-[#2775E4] text-sm"></i>
                        <span>Settlement Lifecycle</span>
                    </div>

                    <p class="font-inter text-[11px] text-[#171E26]/60 leading-relaxed">
                        This customer payment has succeeded. Settlement funds are queued for weekly batch payout to Triple B Pharmacy.
                    </p>

                    <div class="pt-2 border-t border-[#DBEBFB]/60 flex items-center justify-between font-inter text-[11px]">
                        <span class="text-[#171E26]/50">Settlement Batch:</span>
                        <span class="font-mono font-semibold text-[#2775E4]">#SET-2026-W36</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-layouts.superadmin>