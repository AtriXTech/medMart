<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing | MedMart</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css">
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">
  <style>
        .font-manrope {
            font-family: 'Manrope', sans-serif;
        }

        .font-inter {
            font-family: 'Inter', sans-serif;
        }
        :root{
            --color:#171E26;
        }

        /* Scroll-reveal */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-group > * {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal-group.is-visible > * {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-group.is-visible > *:nth-child(2) { transition-delay: 90ms; }
        .reveal-group.is-visible > *:nth-child(3) { transition-delay: 180ms; }
        .reveal-group.is-visible > *:nth-child(4) { transition-delay: 270ms; }
        .reveal-group.is-visible > *:nth-child(5) { transition-delay: 360ms; }
        .reveal-group.is-visible > *:nth-child(6) { transition-delay: 450ms; }
        .reveal-group.is-visible > *:nth-child(n+7) { transition-delay: 540ms; }

        @media (prefers-reduced-motion: reduce) {
            .reveal, .reveal-group > * {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }

        section[id] {
            scroll-margin-top: 110px;
        }
        @media (min-width: 768px) {
            section[id] {
                scroll-margin-top: 140px;
            }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        color: 'var(--color)',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-inter text-color bg-white">

    <div class="fixed top-0 left-0 right-0 z-40 h-[90px] md:h-[120px] backdrop-blur-xl bg-white/10 border-b border-white/20 pointer-events-none"></div>


    <nav class="mt-3 md:mt-5 fixed left-0 right-0 z-50 flex flex-wrap justify-between mx-4 md:mx-10 items-center bg-white px-4 py-2 rounded-2xl gap-4">
    <img src="{{asset('images/logo.png')}}" alt="logo" class="h-[70px] w-[100px] md:h-[100px] md:w-[140px]">

    <!-- Desktop nav links -->
    <div class="hidden md:flex gap-6 lg:gap-10 ">
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="{{route('home')}}#features">Features</a></h1>
        <div class="relative group">
            <h1 class="font-inter text-[18px] lg:text-[20px] font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4] cursor-pointer flex items-center gap-1">
                Why MedMart
                <i class="ph ph-caret-down text-[#2775E4] text-sm"></i>
            </h1>
            <div class="absolute left-0 top-full pt-3 hidden group-hover:block z-50">
                <div class="bg-white rounded-xl shadow-lg py-2 min-w-[160px]">
                    <a href="{{route('home')}}#problem" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Problem</a>
                    <a href="{{route('home')}}#solution" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Solution</a>
                    <a href="{{route('home')}}#how-it-works" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">How It Works</a>
                    
                </div>
            </div>
        </div>
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="{{route('pricing') }}">Pricing</a></h1>
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="{{route('contact') }}">contact</a></h1>
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="{{route('home')}}#faq">FAQs</a></h1>
    </div>

    <!-- Desktop auth buttons -->
    <div class="hidden md:flex gap-3 md:gap-5 items-center">
        <button class="py-2 px-3 md:px-4 rounded-2xl hover:scale-[1.01] cursor-pointer shadow-md text-[16px] md:text-[20px] hover:bg-[white] hover:text-[#2775E4] hover:border-1 hover:border-[#2775E4] rounded-xl font-inter bg-[#2775E4]  text-white capitalize text-center tracking-wider">login</button>
        <button class="py-2 px-3 md:px-4 rounded-2xl hover:scale-[1.01] cursor-pointer shadow-md text-[16px] md:text-[20px] hover:bg-[white] hover:text-[#2775E4] hover:border-1 hover:border-[#2775E4] rounded-xl font-inter bg-[#2775E4]  text-white capitalize text-center tracking-wider">signup</button>
    </div>

    <!-- Hamburger button (mobile only) -->
    <button id="navToggle" aria-label="Toggle menu" aria-expanded="false"
        onclick="
            document.getElementById('mobileMenu').classList.toggle('hidden');
            document.getElementById('navIconOpen').classList.toggle('hidden');
            document.getElementById('navIconClose').classList.toggle('hidden');
            this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
        "
        class="md:hidden flex items-center justify-center h-10 w-10 text-[#2775E4] text-3xl cursor-pointer">
        <i id="navIconOpen" class="ph ph-list"></i>
        <i id="navIconClose" class="ph ph-x hidden"></i>
    </button>

    <!-- Mobile dropdown panel -->
    <div id="mobileMenu" class="hidden md:hidden w-full order-3 flex flex-col gap-1 pt-2 border-t border-[#DBEBFB]">
        <a href="{{route('home')}}#features" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">Features</a>

        <details class="group px-2">
            <summary class="flex items-center justify-between cursor-pointer list-none py-3 font-inter text-[17px] font-medium text-[#171E26]">
                Why MedMart
                <i class="ph ph-caret-down text-[#2775E4] text-sm group-open:rotate-180 transition-transform"></i>
            </summary>
            <div class="flex flex-col pl-3 pb-2">
                <a href="{{route('home')}}#problem" onclick="closeMobileMenu()" class="font-inter text-[15px] text-[#171E26]/70 hover:text-[#2775E4] py-2">Problem</a>
                <a href="{{route('home')}}#solution" onclick="closeMobileMenu()" class="font-inter text-[15px] text-[#171E26]/70 hover:text-[#2775E4] py-2">Solution</a>
                <a href="{{route('home')}}#business-benefit" onclick="closeMobileMenu()" class="font-inter text-[15px] text-[#171E26]/70 hover:text-[#2775E4] py-2">Benefit</a>
                <a href="{{route('home')}}#how-it-works" onclick="closeMobileMenu()" class="font-inter text-[15px] text-[#171E26]/70 hover:text-[#2775E4] py-2">How It Works</a>
            </div>
        </details>

        <a href="{{ route('pricing') }}" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">Pricing</a>
        <a href="{{ route('contact') }}" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">Contact</a>
        <a href="{{route('home')}}#faq" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">FAQs</a>

        <div class="flex gap-3 px-2 pt-3 pb-2">
            <button class="flex-1 py-2.5 rounded-xl shadow-md text-[16px] font-inter bg-[#2775E4] text-white capitalize tracking-wider">login</button>
            <button class="flex-1 py-2.5 rounded-xl shadow-md text-[16px] font-inter bg-[#2775E4] text-white capitalize tracking-wider">signup</button>
        </div>
    </div>
</nav>

    <!-- 2. PRICING HERO -->
    <section class="bg-gradient-to-br from-[#E9F3FE] via-[#DBEBFB] to-[#B1D0FB] pt-[130px] pb-16 md:pt-[170px] md:pb-24 px-4 md:px-10">
        <div class="max-w-2xl mx-auto text-center reveal">
            <span class="font-inter text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent">Pricing</span>
            <h1 class="font-manrope text-3xl sm:text-4xl md:text-6xl font-extrabold text-[#171E26] mt-3">
                Simple pricing. Everything your pharmacy needs.
            </h1>
            <p class="font-inter text-[#171E26]/70 text-base sm:text-lg md:text-xl mt-4">
                Bring your pharmacy online, let customers order from home, and manage your products, orders, inventory, and customers from one place.
            </p>
            <p class="font-inter font-semibold text-[#2775E4] mt-4">
                One plan. No complicated tiers.
            </p>
        </div>
    </section>

    <!-- 3. MAIN PRICING CARD -->
    <section class="px-4 md:px-10 -mt-10 md:-mt-14 relative z-10">
        <div class="max-w-lg mx-auto reveal">
            <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-3xl shadow-xl p-8 md:p-10 text-center">
                <span class="font-inter text-xs font-semibold tracking-widest uppercase text-[#2775E4] bg-[#DBEBFB] px-3 py-1 rounded-full">MedMart Plan</span>
                <h2 class="font-manrope text-2xl md:text-3xl font-bold text-[#171E26] mt-4">
                    Everything your pharmacy needs to go digital.
                </h2>

                <div class="mt-6 flex items-end justify-center gap-2">
                    <span class="font-manrope text-5xl md:text-6xl font-extrabold text-[#171E26]">₦9,500</span>
                    <span class="font-inter text-[#171E26]/60 text-lg mb-1.5">/ month</span>
                </div>

                <button class="w-full mt-8 px-7 py-3.5 rounded-full bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[18px] shadow-lg shadow-[#2775E4]/20 hover:scale-[1.01] transition tracking-wide">
                    Get Started
                </button>
                <p class="font-inter text-[#171E26]/50 text-xs mt-4">
                    Full access to the MedMart platform — customer ordering, pharmacy management, and everything in between.
                </p>
            </div>
        </div>
    </section>

    <!-- 4. INCLUDED FEATURES -->
    <section class="py-20 md:py-28 px-4 md:px-10">
        <div class="max-w-3xl mx-auto text-center reveal">
            <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#171E26]">
                Everything included.
            </h2>
            <p class="font-inter text-[#171E26]/70 mt-3">
                One subscription gives your pharmacy the tools to manage its digital operations and serve customers online.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto mt-14 reveal-group">

            <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                    <i class="ph-light ph-storefront text-white text-2xl"></i>
                </div>
                <h3 class="font-manrope text-xl font-bold text-[#171E26] mt-4">Customer Ordering</h3>
                <ul class="mt-4 space-y-2.5">
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Online pharmacy storefront</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Product browsing</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Product search</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Product availability and pricing</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Shopping cart</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Customer accounts</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Online ordering</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Order history</li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                    <i class="ph-light ph-package text-white text-2xl"></i>
                </div>
                <h3 class="font-manrope text-xl font-bold text-[#171E26] mt-4">Pharmacy Management</h3>
                <ul class="mt-4 space-y-2.5">
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Product management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Inventory management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Price management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Availability management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Customer management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Customer verification</li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                    <i class="ph-light ph-receipt text-white text-2xl"></i>
                </div>
                <h3 class="font-manrope text-xl font-bold text-[#171E26] mt-4">Order &amp; Payment Management</h3>
                <ul class="mt-4 space-y-2.5">
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Incoming order management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Order processing</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Order status updates</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Customer order tracking</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Payment verification</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Invoice generation/printing</li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                    <i class="ph-light ph-chart-line-up text-white text-2xl"></i>
                </div>
                <h3 class="font-manrope text-xl font-bold text-[#171E26] mt-4">Dashboard &amp; Support</h3>
                <ul class="mt-4 space-y-2.5">
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Pharmacy dashboard</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Order overview</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Sales/revenue overview</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Inventory overview</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Customer overview</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Technical support</li>
                </ul>
            </div>

        </div>
    </section>

    <!-- 5. HOW THE SUBSCRIPTION WORKS -->
    <section class="py-16 md:py-24 px-4 md:px-10 bg-[#DBEBFB]/40">
        <div class="max-w-3xl mx-auto text-center reveal">
            <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#171E26]">
                One pharmacy. One plan. No confusion.
            </h2>
            <p class="font-inter text-[#171E26]/70 mt-3">
                Your monthly subscription gives your pharmacy access to the MedMart platform and its management tools.
            </p>
        </div>

        <div class="max-w-2xl mx-auto mt-14 reveal-group">

            <div class="relative flex gap-6 pb-10">
                <div class="absolute left-6 top-12 bottom-0 w-0.5 bg-white"></div>
                <div class="relative z-10 flex-shrink-0 h-12 w-12 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center font-manrope font-bold text-white">01</div>
                <div>
                    <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26]">Subscribe</h3>
                    <p class="font-inter text-[#171E26]/70 mt-1">Start your MedMart subscription for ₦9,500/month.</p>
                </div>
            </div>

            <div class="relative flex gap-6 pb-10">
                <div class="absolute left-6 top-12 bottom-0 w-0.5 bg-white"></div>
                <div class="relative z-10 flex-shrink-0 h-12 w-12 rounded-full bg-white border-2 border-[#B1D0FB] flex items-center justify-center font-manrope font-bold text-[#171E26]">02</div>
                <div>
                    <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26]">Set up your pharmacy</h3>
                    <p class="font-inter text-[#171E26]/70 mt-1">Add your pharmacy details and configure your account.</p>
                </div>
            </div>

            <div class="relative flex gap-6 pb-10">
                <div class="absolute left-6 top-12 bottom-0 w-0.5 bg-white"></div>
                <div class="relative z-10 flex-shrink-0 h-12 w-12 rounded-full bg-white border-2 border-[#B1D0FB] flex items-center justify-center font-manrope font-bold text-[#171E26]">03</div>
                <div>
                    <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26]">Add your products</h3>
                    <p class="font-inter text-[#171E26]/70 mt-1">List your medicines and healthcare products with prices and availability.</p>
                </div>
            </div>

            <div class="relative flex gap-6 pb-10">
                <div class="absolute left-6 top-12 bottom-0 w-0.5 bg-white"></div>
                <div class="relative z-10 flex-shrink-0 h-12 w-12 rounded-full bg-white border-2 border-[#B1D0FB] flex items-center justify-center font-manrope font-bold text-[#171E26]">04</div>
                <div>
                    <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26]">Invite your customers</h3>
                    <p class="font-inter text-[#171E26]/70 mt-1">Let your existing customers know they can now order online.</p>
                </div>
            </div>

            <div class="relative flex gap-6">
                <div class="relative z-10 flex-shrink-0 h-12 w-12 rounded-full bg-white border-2 border-[#B1D0FB] flex items-center justify-center font-manrope font-bold text-[#171E26]">05</div>
                <div>
                    <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26]">Start receiving orders</h3>
                    <p class="font-inter text-[#171E26]/70 mt-1">Orders begin flowing into your dashboard, ready to process.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- 6. VALUE SECTION -->
    <section class="py-16 md:py-24 px-4 md:px-10">
        <div class="max-w-3xl mx-auto text-center reveal">
            <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#171E26]">
                More than an online store.
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-14 items-stretch reveal-group">

            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-md">
                <div class="h-12 w-12 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                    <i class="ph-light ph-house-line text-[#2775E4] text-2xl"></i>
                </div>
                <h3 class="font-manrope text-xl font-bold text-[#171E26] mt-4">Serve customers remotely</h3>
                <p class="font-inter text-[#171E26]/70 mt-2">
                    Let customers browse and order without having to visit your pharmacy just to place an order.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-md">
                <div class="h-12 w-12 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                    <i class="ph-light ph-squares-four text-[#2775E4] text-2xl"></i>
                </div>
                <h3 class="font-manrope text-xl font-bold text-[#171E26] mt-4">Manage your pharmacy digitally</h3>
                <p class="font-inter text-[#171E26]/70 mt-2">
                    Keep your products, prices, inventory, customers and orders organized in one place.
                </p>
            </div>

            <div class="rounded-2xl p-6 md:p-8 bg-gradient-to-br from-[#2775E4] to-[#08AEBC] shadow-lg">
                <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="ph-light ph-timer text-white text-2xl"></i>
                </div>
                <h3 class="font-manrope text-xl font-bold text-white mt-4">Save time</h3>
                <p class="font-inter text-white/85 mt-2">
                    Spend less time handling repetitive ordering tasks and more time serving your customers.
                </p>
            </div>

        </div>
    </section>

    <!-- 7. CUSTOMER EXPERIENCE SECTION -->
    <section class="py-16 md:py-24 px-4 md:px-10 bg-[#DBEBFB]/40">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center reveal-group">

            <div>
                <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#171E26]">
                    Your customers get a better way to order.
                </h2>
                <p class="font-inter text-[#171E26]/70 mt-4">
                    Give your customers the convenience of ordering from home while keeping your pharmacy at the center of the experience.
                </p>
            </div>

            <div class="bg-white/50 backdrop-blur-xl border border-white/60 rounded-3xl shadow-md p-6 md:p-8">
                <div class="flex flex-col">

                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                            <i class="ph-light ph-magnifying-glass text-white text-xl"></i>
                        </div>
                        <p class="font-inter font-medium text-[#171E26]">Browse products</p>
                    </div>
                    <div class="h-6 w-0.5 bg-[#B1D0FB] ml-[22px]"></div>

                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                            <i class="ph-light ph-shopping-cart-simple text-white text-xl"></i>
                        </div>
                        <p class="font-inter font-medium text-[#171E26]">Add to cart</p>
                    </div>
                    <div class="h-6 w-0.5 bg-[#B1D0FB] ml-[22px]"></div>

                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                            <i class="ph-light ph-clipboard-text text-white text-xl"></i>
                        </div>
                        <p class="font-inter font-medium text-[#171E26]">Place order</p>
                    </div>
                    <div class="h-6 w-0.5 bg-[#B1D0FB] ml-[22px]"></div>

                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                            <i class="ph-light ph-lock-key text-white text-xl"></i>
                        </div>
                        <p class="font-inter font-medium text-[#171E26]">Make payment</p>
                    </div>
                    <div class="h-6 w-0.5 bg-[#B1D0FB] ml-[22px]"></div>

                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                            <i class="ph-light ph-map-pin-line text-white text-xl"></i>
                        </div>
                        <p class="font-inter font-medium text-[#171E26]">Track order</p>
                    </div>
                    <div class="h-6 w-0.5 bg-[#B1D0FB] ml-[22px]"></div>

                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                            <i class="ph-light ph-check-circle text-white text-xl"></i>
                        </div>
                        <p class="font-inter font-medium text-[#171E26]">Pick up when ready</p>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- 8. FAQ SECTION -->
    <section class="py-16 md:py-24 px-4 md:px-10">
        <div class="max-w-3xl mx-auto text-center reveal">
            <span class="font-inter text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent"> FAQ</span>
            <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#171E26] mt-3">
                Questions about pricing?
            </h2>
        </div>

        <div class="max-w-2xl mx-auto mt-12 space-y-3 reveal-group">

            <details class="group bg-white rounded-2xl shadow-md open:shadow-lg transition">
                <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4 md:py-5">
                    <span class="font-manrope font-bold text-[#171E26] text-base md:text-lg">What does the ₦9,500 subscription include?</span>
                    <i class="ph-light ph-plus text-[#2775E4] text-xl flex-shrink-0 group-open:hidden"></i>
                    <i class="ph-light ph-minus text-[#2775E4] text-xl flex-shrink-0 hidden group-open:block"></i>
                </summary>
                <p class="font-inter text-[#171E26]/70 px-5 md:px-6 pb-5">
                    Your subscription includes full access to the MedMart platform — customer ordering, pharmacy management, order and payment management, and your dashboard, as listed above.
                </p>
            </details>

            <details class="group bg-white rounded-2xl shadow-md open:shadow-lg transition">
                <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4 md:py-5">
                    <span class="font-manrope font-bold text-[#171E26] text-base md:text-lg">Is the subscription billed monthly?</span>
                    <i class="ph-light ph-plus text-[#2775E4] text-xl flex-shrink-0 group-open:hidden"></i>
                    <i class="ph-light ph-minus text-[#2775E4] text-xl flex-shrink-0 hidden group-open:block"></i>
                </summary>
                <p class="font-inter text-[#171E26]/70 px-5 md:px-6 pb-5">
                    Yes. The MedMart plan is billed monthly.
                </p>
            </details>

            <details class="group bg-white rounded-2xl shadow-md open:shadow-lg transition">
                <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4 md:py-5">
                    <span class="font-manrope font-bold text-[#171E26] text-base md:text-lg">Are payment processing fees included?</span>
                    <i class="ph-light ph-plus text-[#2775E4] text-xl flex-shrink-0 group-open:hidden"></i>
                    <i class="ph-light ph-minus text-[#2775E4] text-xl flex-shrink-0 hidden group-open:block"></i>
                </summary>
                <p class="font-inter text-[#171E26]/70 px-5 md:px-6 pb-5">
                   No, payment processing fees are not included .
                </p>
            </details>

            <details class="group bg-white rounded-2xl shadow-md open:shadow-lg transition">
                <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4 md:py-5">
                    <span class="font-manrope font-bold text-[#171E26] text-base md:text-lg">Can I cancel my subscription?</span>
                    <i class="ph-light ph-plus text-[#2775E4] text-xl flex-shrink-0 group-open:hidden"></i>
                    <i class="ph-light ph-minus text-[#2775E4] text-xl flex-shrink-0 hidden group-open:block"></i>
                </summary>
                <p class="font-inter text-[#171E26]/70 px-5 md:px-6 pb-5">
                    Yes ,you can cancel subscription anytime
                </p>
            </details>

            <details class="group bg-white rounded-2xl shadow-md open:shadow-lg transition">
                <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4 md:py-5">
                    <span class="font-manrope font-bold text-[#171E26] text-base md:text-lg">Can I add more pharmacies to one account?</span>
                    <i class="ph-light ph-plus text-[#2775E4] text-xl flex-shrink-0 group-open:hidden"></i>
                    <i class="ph-light ph-minus text-[#2775E4] text-xl flex-shrink-0 hidden group-open:block"></i>
                </summary>
                <p class="font-inter text-[#171E26]/70 px-5 md:px-6 pb-5">
                    No, you can't add more than one pharmacies to an account.
                </p>
            </details>

        </div>
    </section>

    <!-- 9. FINAL CTA -->
    <section class="py-20 md:py-28 px-4 md:px-10 bg-gradient-to-br from-[#2775E4] to-[#08AEBC] text-center">
        <div class="max-w-2xl mx-auto reveal">
            <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-extrabold text-white">
                Ready to take your pharmacy online?
            </h2>
            <p class="font-inter text-white/85 text-lg md:text-xl mt-5">
                Start giving your customers a simpler way to order while managing your pharmacy from one powerful platform.
            </p>

            <button class="px-8 py-3.5 rounded-full bg-white text-[#2775E4] font-inter font-semibold text-lg tracking-wide hover:scale-[1.02] transition shadow-lg mt-8">
                Get Started
            </button>

            <p class="font-inter text-white/80 text-sm mt-6">
                Have questions?
                <a href="contact.html" class="font-inter font-semibold text-white underline underline-offset-4 hover:text-white/90 transition ml-1">
                    Contact us →
                </a>
            </p>
        </div>
    </section>

    <footer class="bg-[#171E26] px-4 md:px-10 pt-16 pb-8">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between gap-12">

            <div class="md:max-w-xs">
                <img src="{{asset('images/logo.png')}}" alt="logo" class="h-[70px] w-[100px] md:h-[80px] md:w-[110px] -ml-2">
                <p class="font-inter text-white/60 text-sm mt-2">
                    The digital platform for modern pharmacies.
                </p>
            </div>

            <div class="flex flex-wrap gap-10 md:gap-16">

                <div>
                    <p class="font-manrope text-white font-bold text-sm mb-4">Platform</p>
                    <ul class="space-y-3">
                        <li><a href="index.html#features" class="font-inter text-white/60 text-sm hover:text-white transition">Features</a></li>
                        <li><a href="index.html#how-it-works" class="font-inter text-white/60 text-sm hover:text-white transition">How It Works</a></li>
                        <li><a href="pricing.html" class="font-inter text-white/60 text-sm hover:text-white transition">Pricing</a></li>
                        <li><a href="index.html#faq" class="font-inter text-white/60 text-sm hover:text-white transition">FAQs</a></li>
                    </ul>
                </div>

                <div>
                    <p class="font-manrope text-white font-bold text-sm mb-4">For Pharmacies</p>
                    <ul class="space-y-3">
                        <li><a href="#" class="font-inter text-white/60 text-sm hover:text-white transition">Get Started</a></li>
                        <li><a href="#" class="font-inter text-white/60 text-sm hover:text-white transition">Login</a></li>
                        <li><a href="contact.html" class="font-inter text-white/60 text-sm hover:text-white transition">Contact Support</a></li>
                    </ul>
                </div>

                <div>
                    <p class="font-manrope text-white font-bold text-sm mb-4">Company</p>
                    <ul class="space-y-3">
                        <li><a href="#" class="font-inter text-white/60 text-sm hover:text-white transition">About</a></li>
                        <li><a href="#" class="font-inter text-white/60 text-sm hover:text-white transition">Privacy</a></li>
                        <li><a href="#" class="font-inter text-white/60 text-sm hover:text-white transition">Terms</a></li>
                    </ul>
                </div>

            </div>

        </div>

        <div class="max-w-6xl mx-auto border-t border-white/10 mt-12 pt-6">
            <p class="font-inter text-white/40 text-xs text-center">© 2026 MedMart</p>
        </div>
    </footer>

    <button onclick="window.scrollTo({top:0, behavior:'smooth'})" aria-label="Scroll to top" class="fixed bottom-6 right-6 md:bottom-8 md:right-8 z-50 h-11 w-11 md:h-12 md:w-12 rounded-full bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white shadow-lg shadow-[#2775E4]/30 hover:scale-[1.05] active:scale-95 transition cursor-pointer flex items-center justify-center">
        <i class="ph ph-arrow-line-up text-xl md:text-2xl"></i>
    </button>

    <script>
        function closeMobileMenu() {
            document.getElementById('mobileMenu').classList.add('hidden');
            document.getElementById('navIconOpen').classList.remove('hidden');
            document.getElementById('navIconClose').classList.add('hidden');
            document.getElementById('navToggle').setAttribute('aria-expanded', 'false');
        }

        // Scroll-reveal animations
        (function () {
            var targets = document.querySelectorAll('.reveal, .reveal-group');
            if (!('IntersectionObserver' in window) || targets.length === 0) {
                targets.forEach(function (el) { el.classList.add('is-visible'); });
                return;
            }
            var observer = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
            targets.forEach(function (el) { observer.observe(el); });
        })();
    </script>
</body>
</html>