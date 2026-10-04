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
<body class="font-inter text-color bg-white antialiased">

    <div class="fixed top-0 left-0 right-0 z-40 h-[90px] md:h-[120px] backdrop-blur-xl bg-white/10 border-b border-white/20 pointer-events-none"></div>

    <nav class="mt-3 md:mt-5 fixed left-0 right-0 z-50 flex flex-wrap justify-between mx-4 md:mx-10 items-center bg-white px-4 py-2.5 rounded-2xl gap-4 shadow-sm">
    <img src="{{asset('images/logo.png')}}" alt="logo" class="h-[60px] w-[90px] md:h-[80px] md:w-[110px] object-contain">

    <!-- Desktop nav links -->
    <div class="hidden md:flex gap-6 lg:gap-8 items-center">
        <h1 class="font-inter text-sm lg:text-base font-medium bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:opacity-80 transition"><a href="{{route('home')}}#features">Features</a></h1>
        <div class="relative group">
            <h1 class="font-inter text-sm lg:text-base font-medium bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:opacity-80 transition cursor-pointer flex items-center gap-1">
                Why MedMart
                <i class="ph ph-caret-down text-[#2775E4] text-xs"></i>
            </h1>
            <div class="absolute left-0 top-full pt-3 hidden group-hover:block z-50">
                <div class="bg-white rounded-xl shadow-lg py-2 min-w-[160px] border border-gray-100">
                    <a href="{{route('home')}}#problem" class="block px-4 py-2 font-inter text-sm text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Problem</a>
                    <a href="{{route('home')}}#solution" class="block px-4 py-2 font-inter text-sm text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Solution</a>
                    <a href="{{route('home')}}#how-it-works" class="block px-4 py-2 font-inter text-sm text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">How It Works</a>
                </div>
            </div>
        </div>
        <h1 class="font-inter text-sm lg:text-base font-medium bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:opacity-80 transition"><a href="{{route('pricing') }}">Pricing</a></h1>
        <h1 class="font-inter text-sm lg:text-base font-medium bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:opacity-80 transition"><a href="{{route('contact') }}">contact</a></h1>
        <h1 class="font-inter text-sm lg:text-base font-medium bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:opacity-80 transition"><a href="{{route('home')}}#faq">FAQs</a></h1>
    </div>

    <!-- Desktop auth buttons -->
    <div class="hidden md:flex gap-3 items-center">
        <a href='{{route('login')}}'><button class="py-2 px-4 rounded-xl hover:scale-[1.02] cursor-pointer text-sm font-medium hover:bg-gray-50 hover:text-[#2775E4] border border-transparent hover:border-[#2775E4] font-inter bg-[#2775E4] text-white capitalize text-center tracking-wide transition-all shadow-sm">login</button></a>
        <a href="{{ route('register') }}"><button class="py-2 px-4 rounded-xl hover:scale-[1.02] cursor-pointer text-sm font-medium hover:bg-gray-50 hover:text-[#2775E4] border border-transparent hover:border-[#2775E4] font-inter bg-[#2775E4] text-white capitalize text-center tracking-wide transition-all shadow-sm">signup</button></a>
    </div>

    <!-- Hamburger button (mobile only) -->
    <button id="navToggle" aria-label="Toggle menu" aria-expanded="false"
        onclick="
            document.getElementById('mobileMenu').classList.toggle('hidden');
            document.getElementById('navIconOpen').classList.toggle('hidden');
            document.getElementById('navIconClose').classList.toggle('hidden');
            this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
        "
        class="md:hidden flex items-center justify-center h-10 w-10 text-[#2775E4] text-2xl cursor-pointer">
        <i id="navIconOpen" class="ph ph-list"></i>
        <i id="navIconClose" class="ph ph-x hidden"></i>
    </button>

    <!-- Mobile dropdown panel -->
    <div id="mobileMenu" class="hidden md:hidden w-full order-3 flex flex-col gap-1 pt-2 border-t border-[#DBEBFB]">
        <a href="{{route('home')}}#features" onclick="closeMobileMenu()" class="font-inter text-sm font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-2.5">Features</a>

        <details class="group px-2">
            <summary class="flex items-center justify-between cursor-pointer list-none py-2.5 font-inter text-sm font-medium text-[#171E26]">
                Why MedMart
                <i class="ph ph-caret-down text-[#2775E4] text-xs group-open:rotate-180 transition-transform"></i>
            </summary>
            <div class="flex flex-col pl-3 pb-2">
                <a href="{{route('home')}}#problem" onclick="closeMobileMenu()" class="font-inter text-sm text-[#171E26]/70 hover:text-[#2775E4] py-1.5">Problem</a>
                <a href="{{route('home')}}#solution" onclick="closeMobileMenu()" class="font-inter text-sm text-[#171E26]/70 hover:text-[#2775E4] py-1.5">Solution</a>
                <a href="{{route('home')}}#business-benefit" onclick="closeMobileMenu()" class="font-inter text-sm text-[#171E26]/70 hover:text-[#2775E4] py-1.5">Benefit</a>
                <a href="{{route('home')}}#how-it-works" onclick="closeMobileMenu()" class="font-inter text-sm text-[#171E26]/70 hover:text-[#2775E4] py-1.5">How It Works</a>
            </div>
        </details>

        <a href="{{ route('pricing') }}" onclick="closeMobileMenu()" class="font-inter text-sm font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-2.5">Pricing</a>
        <a href="{{ route('contact') }}" onclick="closeMobileMenu()" class="font-inter text-sm font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-2.5">Contact</a>
        <a href="{{route('home')}}#faq" onclick="closeMobileMenu()" class="font-inter text-sm font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-2.5">FAQs</a>

        <div class="flex gap-3 px-2 pt-3 pb-2">
            <a href='{{ route('login') }}' class="flex-1"><button class="w-full py-2 rounded-xl shadow-sm text-sm font-medium font-inter bg-[#2775E4] text-white capitalize tracking-wide">login</button></a>
            <a href='{{ route('register') }}' class="flex-1"><button class="w-full py-2 rounded-xl shadow-sm text-sm font-medium font-inter bg-[#2775E4] text-white capitalize tracking-wide">signup</button></a>
        </div>
    </div>
</nav>

    <!-- 2. PRICING HERO -->
    <section class="bg-gradient-to-br from-[#E9F3FE] via-[#DBEBFB] to-[#B1D0FB] pt-[130px] pb-16 md:pt-[170px] md:pb-24 px-4 md:px-10">
        <div class="max-w-2xl mx-auto text-center reveal">
            <span class="font-inter text-xs md:text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent">Pricing</span>
            <h1 class="font-manrope text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-[#171E26] mt-2">
                Simple pricing. Everything your pharmacy needs.
            </h1>
            <p class="font-inter text-[#171E26]/75 text-sm sm:text-base md:text-lg mt-4 leading-relaxed">
                Bring your pharmacy online, let customers order from home, and manage your products, orders, inventory, and customers from one place.
            </p>
            <p class="font-inter font-semibold text-sm md:text-base text-[#2775E4] mt-3">
                One plan. No complicated tiers.
            </p>
        </div>
    </section>

    <!-- 3. MAIN PRICING CARD -->
    <section class="px-4 md:px-10 -mt-10 md:-mt-14 relative z-10">
        <div class="max-w-lg mx-auto reveal">
            <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-lg p-6 md:p-10 text-center">
                <span class="font-inter text-xs font-semibold tracking-widest uppercase text-[#2775E4] bg-[#DBEBFB] px-3 py-1 rounded-full">MedMart Plan</span>
                <h2 class="font-manrope text-xl md:text-2xl font-bold text-[#171E26] mt-4">
                    Everything your pharmacy needs to go digital.
                </h2>

                <div class="mt-6 flex items-end justify-center gap-1.5">
                    <span class="font-manrope text-4xl md:text-5xl lg:text-6xl font-extrabold text-[#171E26]">₦9,500</span>
                    <span class="font-inter text-[#171E26]/60 text-sm md:text-base mb-1">/ month</span>
                </div>

                <button class="w-full mt-6 px-6 py-3 md:py-3.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-medium text-sm md:text-base shadow-md shadow-[#2775E4]/20 hover:scale-[1.01] transition active:scale-[0.99] tracking-wide cursor-pointer">
                    Get Started
                </button>
                <p class="font-inter text-[#171E26]/50 text-xs mt-3 leading-relaxed">
                    Full access to the MedMart platform — customer ordering, pharmacy management, and everything in between.
                </p>
            </div>
        </div>
    </section>

    <!-- 4. INCLUDED FEATURES -->
    <section class="py-16 md:py-24 px-4 md:px-10">
        <div class="max-w-3xl mx-auto text-center reveal">
            <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-[#171E26]">
                Everything included.
            </h2>
            <p class="font-inter text-sm md:text-base text-[#171E26]/70 mt-2">
                One subscription gives your pharmacy the tools to manage its digital operations and serve customers online.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto mt-12 reveal-group">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                    <i class="ph-light ph-storefront text-white text-2xl"></i>
                </div>
                <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26] mt-4">Customer Ordering</h3>
                <ul class="mt-4 space-y-2">
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Online pharmacy storefront</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Product browsing</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Product search</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Product availability and pricing</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Shopping cart</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Customer accounts</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Online ordering</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Order history</li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                    <i class="ph-light ph-package text-white text-2xl"></i>
                </div>
                <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26] mt-4">Pharmacy Management</h3>
                <ul class="mt-4 space-y-2">
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Product management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Inventory management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Price management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Availability management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Customer management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Customer verification</li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                    <i class="ph-light ph-receipt text-white text-2xl"></i>
                </div>
                <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26] mt-4">Order &amp; Payment Management</h3>
                <ul class="mt-4 space-y-2">
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Incoming order management</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Order processing</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Order status updates</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Customer order tracking</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Payment verification</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Invoice generation/printing</li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                    <i class="ph-light ph-chart-line-up text-white text-2xl"></i>
                </div>
                <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26] mt-4">Dashboard &amp; Support</h3>
                <ul class="mt-4 space-y-2">
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Pharmacy dashboard</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Order overview</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Sales/revenue overview</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Inventory overview</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Customer overview</li>
                    <li class="flex items-center gap-2.5 font-inter text-[#171E26]/80 text-xs md:text-sm"><i class="ph-light ph-check-circle text-[#2775E4] text-base"></i>Technical support</li>
                </ul>
            </div>

        </div>
    </section>

    <!-- 5. HOW THE SUBSCRIPTION WORKS -->
    <section class="py-16 md:py-24 px-4 md:px-10 bg-[#DBEBFB]/40">
        <div class="max-w-3xl mx-auto text-center reveal">
            <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-[#171E26]">
                One pharmacy. One plan. No confusion.
            </h2>
            <p class="font-inter text-sm md:text-base text-[#171E26]/70 mt-2">
                Your monthly subscription gives your pharmacy access to the MedMart platform and its management tools.
            </p>
        </div>

        <div class="max-w-2xl mx-auto mt-12 reveal-group">

            <div class="relative flex gap-5 pb-8">
                <div class="absolute left-5 top-10 bottom-0 w-0.5 bg-white"></div>
                <div class="relative z-10 flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center font-manrope font-bold text-sm text-white">01</div>
                <div>
                    <h3 class="font-manrope text-base md:text-lg font-bold text-[#171E26]">Subscribe</h3>
                    <p class="font-inter text-sm text-[#171E26]/70 mt-0.5">Start your MedMart subscription for ₦9,500/month.</p>
                </div>
            </div>

            <div class="relative flex gap-5 pb-8">
                <div class="absolute left-5 top-10 bottom-0 w-0.5 bg-white"></div>
                <div class="relative z-10 flex-shrink-0 h-10 w-10 rounded-full bg-white border-2 border-[#B1D0FB] flex items-center justify-center font-manrope font-bold text-sm text-[#171E26]">02</div>
                <div>
                    <h3 class="font-manrope text-base md:text-lg font-bold text-[#171E26]">Set up your pharmacy</h3>
                    <p class="font-inter text-sm text-[#171E26]/70 mt-0.5">Add your pharmacy details and configure your account.</p>
                </div>
            </div>

            <div class="relative flex gap-5 pb-8">
                <div class="absolute left-5 top-10 bottom-0 w-0.5 bg-white"></div>
                <div class="relative z-10 flex-shrink-0 h-10 w-10 rounded-full bg-white border-2 border-[#B1D0FB] flex items-center justify-center font-manrope font-bold text-sm text-[#171E26]">03</div>
                <div>
                    <h3 class="font-manrope text-base md:text-lg font-bold text-[#171E26]">Start selling online</h3>
                    <p class="font-inter text-sm text-[#171E26]/70 mt-0.5">Receive and manage orders from your customers seamlessly.</p>
                </div>
            </div>

        </div>
    </section>

    <footer class="bg-[#171E26] px-4 md:px-10 pt-16 pb-8">
     <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between gap-12">
 
        <!-- Logo + tagline -->
        <div class="md:max-w-xs">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-[60px] w-[90px] md:h-[70px] md:w-[100px] -ml-2 object-contain">
            <p class="font-inter text-white/60 text-xs md:text-sm mt-2">
                The digital platform for modern pharmacies.
            </p>
        </div>
 
        <!-- Link columns -->
        <div class="flex flex-wrap gap-10 md:gap-16">
 
            <div>
                <p class="font-manrope text-white font-semibold text-sm mb-4">Platform</p>
                <ul class="space-y-2.5">
                    <li><a href="#features" class="font-inter text-white/60 text-xs md:text-sm hover:text-white transition">Features</a></li>
                    <li><a href="#how-it-works" class="font-inter text-white/60 text-xs md:text-sm hover:text-white transition">How It Works</a></li>
                    <li><a href="{{route('pricing')}}#pricing" class="font-inter text-white/60 text-xs md:text-sm hover:text-white transition">Pricing</a></li>
                    <li><a href="#faq" class="font-inter text-white/60 text-xs md:text-sm hover:text-white transition">FAQs</a></li>
                </ul>
            </div>
 
            <div>
                <p class="font-manrope text-white font-semibold text-sm mb-4">For Pharmacies</p>
                <ul class="space-y-2.5">
                    <li><a href="{{route('register')}}" class="font-inter text-white/60 text-xs md:text-sm hover:text-white transition">Get Started</a></li>
                    <li><a href="{{route('login')}}" class="font-inter text-white/60 text-xs md:text-sm hover:text-white transition">Login</a></li>
                    <li><a href="{{route('contact')}}" class="font-inter text-white/60 text-xs md:text-sm hover:text-white transition">Contact Support</a></li>
                </ul>
            </div>
 
            <div>
                <p class="font-manrope text-white font-semibold text-sm mb-4">Company</p>
                <ul class="space-y-2.5">
                    <li><a href="#" class="font-inter text-white/60 text-xs md:text-sm hover:text-white transition">About</a></li>
                    <li><a href="#" class="font-inter text-white/60 text-xs md:text-sm hover:text-white transition">Privacy</a></li>
                    <li><a href="#" class="font-inter text-white/60 text-xs md:text-sm hover:text-white transition">Terms</a></li>
                </ul>
            </div>
 
        </div>
 
     </div>
 
     <div class="max-w-6xl mx-auto border-t border-white/10 mt-12 pt-6">
        <p class="font-inter text-white/40 text-xs text-center">&copy; <?php echo date("Y"); ?> MedMart</p>
     </div>
   </footer>

    <button onclick="window.scrollTo({top:0, behavior:'smooth'})" aria-label="Scroll to top" class="fixed bottom-6 right-6 md:bottom-8 md:right-8 z-50 h-10 w-10 md:h-11 md:w-11 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white shadow-md shadow-[#2775E4]/20 hover:scale-[1.05] active:scale-95 transition cursor-pointer flex items-center justify-center">
        <i class="ph ph-arrow-line-up text-lg md:text-xl"></i>
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