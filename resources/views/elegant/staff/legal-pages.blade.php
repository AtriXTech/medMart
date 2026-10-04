<x-layouts.staff title="MedMart legal pages" active="Legal Pages">


{{-- <body class="bg-[#F8FBFF] text-[#171E26] font-inter antialiased"> --}}

    <!-- Page -->
    <main class="min-h-screen px-4 py-12 sm:px-6 lg:px-8">

        <!-- Content Container -->
        <div class="mx-auto max-w-[820px]">

            <!-- Header -->
            <header class="text-center">

                <!-- Small Label -->
                <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-[#E9F3FE] px-4 py-2">
                    <i class="ph ph-scales text-lg text-[#2775E4]"></i>

                    <span class="text-sm font-semibold text-[#2775E4]">
                        Legal & Policies
                    </span>
                </div>

                <!-- Heading -->
                <h1
                    class="font-manrope text-3xl font-extrabold tracking-tight text-[#171E26] sm:text-4xl lg:text-5xl"
                >
                    MedMart Legal
                </h1>

                <!-- Supporting Line -->
                <p
                    class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg"
                >
                    Important information about your privacy, payments,
                    and responsibilities when using MedMart.
                </p>

                <!-- Explanation -->
                <div class="mx-auto mt-7 max-w-3xl space-y-4 text-left text-sm leading-7 text-slate-600 sm:text-base">

                    <p>
                        These documents explain the rules, policies, and practices
                        that govern your use of MedMart. Reading them helps you
                        understand how information is handled, how payments and
                        subscriptions work, and what you can expect when using
                        the platform.
                    </p>

                    <p>
                        These documents provide important information about your
                        rights, responsibilities, payments, and use of the
                        platform.
                    </p>

                </div>

            </header>


            <!-- Legal Documents -->
            <section class="mt-10 space-y-5 sm:mt-12">

                <!-- Privacy & Data Processing -->
                <a
                    href="{{ route('privacyAndDataProcessing') }}"
                    class="group block rounded-2xl border border-[#DBEBFB] bg-white p-6 shadow-[0_4px_20px_rgba(39,117,228,0.05)] transition duration-200 hover:-translate-y-0.5 hover:border-[#2775E4] hover:shadow-[0_8px_30px_rgba(39,117,228,0.10)] sm:p-7"
                >

                    <div class="flex items-start gap-4">

                        <!-- Icon -->
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E9F3FE] text-[#2775E4]"
                        >
                            <i class="ph ph-shield-check text-2xl"></i>
                        </div>

                        <!-- Content -->
                        <div class="min-w-0 flex-1">

                            <div class="flex items-center justify-between gap-4">

                                <h2
                                    class="font-manrope text-lg font-bold text-[#171E26] sm:text-xl"
                                >
                                    Privacy & Data Processing
                                </h2>

                                <i
                                    class="ph ph-arrow-up-right shrink-0 text-xl text-slate-400 transition duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-[#2775E4]"
                                ></i>

                            </div>

                            <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                                Learn how MedMart collects, uses, stores, and protects
                                information when you use the platform. This includes
                                information provided by customers and pharmacies,
                                how data is processed, and the choices available to you.
                            </p>

                            <div class="mt-5 flex items-center gap-2 text-sm font-semibold text-[#2775E4]">
                                <span>Read Privacy & Data Processing</span>
                                <i class="ph ph-arrow-right transition duration-200 group-hover:translate-x-1"></i>
                            </div>

                        </div>

                    </div>

                </a>


                <!-- Subscription & Billing -->
                <a
                    href="{{ route('subscriptionAndBilling') }}"
                    class="group block rounded-2xl border border-[#DBEBFB] bg-white p-6 shadow-[0_4px_20px_rgba(39,117,228,0.05)] transition duration-200 hover:-translate-y-0.5 hover:border-[#2775E4] hover:shadow-[0_8px_30px_rgba(39,117,228,0.10)] sm:p-7"
                >

                    <div class="flex items-start gap-4">

                        <!-- Icon -->
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E9F3FE] text-[#2775E4]"
                        >
                            <i class="ph ph-credit-card text-2xl"></i>
                        </div>

                        <!-- Content -->
                        <div class="min-w-0 flex-1">

                            <div class="flex items-center justify-between gap-4">

                                <h2
                                    class="font-manrope text-lg font-bold text-[#171E26] sm:text-xl"
                                >
                                    Subscription & Billing
                                </h2>

                                <i
                                    class="ph ph-arrow-up-right shrink-0 text-xl text-slate-400 transition duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-[#2775E4]"
                                ></i>

                            </div>

                            <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                                Understand how MedMart subscriptions, billing, payments,
                                renewals, cancellations, and related charges work for
                                pharmacies using MedMart's subscription services.
                            </p>

                            <div class="mt-5 flex items-center gap-2 text-sm font-semibold text-[#2775E4]">
                                <span>Read Subscription & Billing</span>
                                <i class="ph ph-arrow-right transition duration-200 group-hover:translate-x-1"></i>
                            </div>

                        </div>

                    </div>

                </a>


                <!-- Terms & Conditions -->
                <a
                    href="{{ route('termsAndConditional') }}"
                    class="group block rounded-2xl border border-[#DBEBFB] bg-white p-6 shadow-[0_4px_20px_rgba(39,117,228,0.05)] transition duration-200 hover:-translate-y-0.5 hover:border-[#2775E4] hover:shadow-[0_8px_30px_rgba(39,117,228,0.10)] sm:p-7"
                >

                    <div class="flex items-start gap-4">

                        <!-- Icon -->
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E9F3FE] text-[#2775E4]"
                        >
                            <i class="ph ph-file-text text-2xl"></i>
                        </div>

                        <!-- Content -->
                        <div class="min-w-0 flex-1">

                            <div class="flex items-center justify-between gap-4">

                                <h2
                                    class="font-manrope text-lg font-bold text-[#171E26] sm:text-xl"
                                >
                                    Terms & Conditions
                                </h2>

                                <i
                                    class="ph ph-arrow-up-right shrink-0 text-xl text-slate-400 transition duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-[#2775E4]"
                                ></i>

                            </div>

                            <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                                Review the rules governing your use of MedMart,
                                including user responsibilities, acceptable use,
                                pharmacy obligations, orders, payments, service
                                limitations, and other important conditions that
                                apply when using the platform.
                            </p>

                            <div class="mt-5 flex items-center gap-2 text-sm font-semibold text-[#2775E4]">
                                <span>Read Terms & Conditions</span>
                                <i class="ph ph-arrow-right transition duration-200 group-hover:translate-x-1"></i>
                            </div>

                        </div>

                    </div>

                </a>

            </section>


            <!-- Bottom Note -->
            <div class="mt-8 text-center">

                <p class="text-xs leading-5 text-slate-500 sm:text-sm">
                    Please review the applicable documents before using
                    MedMart or its services.
                </p>

            </div>

        </div>

    </main>
</x-layouts.staff>