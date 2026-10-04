<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedMart</title>
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
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/bold/style.css">
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
    </style>
    <script>
        tailwind.config ={
            theme:{
                extend:{
                    colors:{
                        color:'var(--color)',
                        PrimaryBlue:'var(--PrimaryBlue)',
                        HealthcareTeal:'var(--HealthcareTeal)'


                    }
                }
            }
        }
    </script>
</head>
<body class="font-inter text-color antialiased">
   <section id="hero" class=" bg-gradient-to-br
                from-[#E9F3FE]
                via-[#DBEBFB]
                to-[#B1D0FB] py-5 h-screen ">
     <div class="fixed top-0 left-0 right-0 z-40 h-[90px] md:h-[120px] backdrop-blur-xl bg-white/10 border-b border-white/20 pointer-events-none"></div>
     
     <nav class="fixed left-0 right-0 z-50 flex flex-wrap justify-between mx-4 md:mx-10 items-center bg-white px-4 py-2.5 rounded-2xl gap-4 shadow-sm">
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
                    <a href="{{route('home') }}#problem" class="block px-4 py-2 font-inter text-sm text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Problem</a>
                    <a href="{{route('home')}}#solution" class="block px-4 py-2 font-inter text-sm text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Solution</a>
                    <a href="{{route('home')}}#business-benefit" class="block px-4 py-2 font-inter text-sm text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Benefit</a>
                    <a href="{{route('home')}}#how-it-works" class="block px-4 py-2 font-inter text-sm text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">How It Works</a>
                </div>
            </div>
        </div>
        <h1 class="font-inter text-sm lg:text-base font-medium bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:opacity-80 transition"><a href="{{route('pricing') }}">Pricing</a></h1>
        <h1 class="font-inter text-sm lg:text-base font-medium bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:opacity-80 transition"><a href="{{ route('contact') }}">contact</a></h1>
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
                <a href="#problem" onclick="closeMobileMenu()" class="font-inter text-sm text-[#171E26]/70 hover:text-[#2775E4] py-1.5">Problem</a>
                <a href="#solution" onclick="closeMobileMenu()" class="font-inter text-sm text-[#171E26]/70 hover:text-[#2775E4] py-1.5">Solution</a>
                <a href="#business-benefit" onclick="closeMobileMenu()" class="font-inter text-sm text-[#171E26]/70 hover:text-[#2775E4] py-1.5">Benefit</a>
                <a href="#how-it-works" onclick="closeMobileMenu()" class="font-inter text-sm text-[#171E26]/70 hover:text-[#2775E4] py-1.5">How It Works</a>
            </div>
        </details>

        <a href="{{ route('pricing') }}" onclick="closeMobileMenu()" class="font-inter text-sm font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-2.5">Pricing</a>
        <a href="route('contact')" onclick="closeMobileMenu()" class="font-inter text-sm font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-2.5">Contact</a>
        <a href="#faq" onclick="closeMobileMenu()" class="font-inter text-sm font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-2.5">FAQs</a>

        <div class="flex gap-3 px-2 pt-3 pb-2">
            <a href='{{ route('login') }}' class="flex-1"><button class="w-full py-2 rounded-xl shadow-sm text-sm font-medium font-inter bg-[#2775E4] text-white capitalize tracking-wide">login</button></a>
            <a href='{{ route('register') }}' class="flex-1"><button class="w-full py-2 rounded-xl shadow-sm text-sm font-medium font-inter bg-[#2775E4] text-white capitalize tracking-wide">signup</button></a>
        </div>
    </div>
</nav>

   <div class="flex flex-col md:flex-row justify-between items-center text-center md:text-left m-4 md:m-10 mt-[110px] md:mt-[150px] bg-white/30 backdrop-blur-xl border border-white/40 rounded-3xl p-6 md:p-10">
  <!-- Text column -->
  <div class="w-full md:w-1/2 flex-shrink-0 flex flex-col items-center md:items-start">
    <h1 class="text-[#171E26] font-manrope text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight">
      Give Your Customers a Better Way to Order.
    </h1>
    <p class="text-[#171E26]/80 font-inter font-normal text-sm sm:text-base md:text-lg mt-4 leading-relaxed">
MedMart lets your customers order from your pharmacy from anywhere, while giving you the tools to manage products, inventory, payments, and orders — all from one platform.    </p>
    <a href='{{ route('register') }}'><button class="px-6 py-3 md:px-8 md:py-3.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-medium shadow-md shadow-[#2775E4]/20 hover:scale-[1.02] transition capitalize tracking-wide text-sm md:text-base my-4 cursor-pointer">
      Get Started
    </button></a>
    <p class="font-inter text-sm md:text-base capitalize">
      Already have an account?
      <a href="{{route('login') }}" class="font-medium bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]">login</a>
    </p>
  </div>

  <!-- Image column -->
  <div class="w-full md:w-1/2 flex-shrink-0 flex justify-center md:justify-end mt-8 md:mt-0">
    <img src="{{ asset('images/newHeroImg.png') }}" class="w-full max-w-[500px] h-auto rounded-2xl object-cover" alt="Hero">
  </div>
</div>
   </section>

    <section id="problem" class="py-16 md:py-24 px-4 md:px-10 scroll-mt-[110px] md:scroll-mt-[140px]">
     <div class="max-w-3xl mx-auto text-center reveal">
        <span class="font-inter text-xs md:text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent">The Problem</span>
        <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-[#171E26] mt-2">
            Your customers shouldn't have to visit just to place an order.
        </h2>
     </div>
 
     <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl mx-auto mt-12 reveal-group">
 
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                <i class="ph-bold ph-phone-call text-white text-2xl"></i>
            </div>
            <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26] mt-4">Endless calls & messages</h3>
            <p class="font-inter text-sm text-[#171E26]/70 mt-2 leading-relaxed">
                Customers constantly ask: "Do you have this medicine?", "How much is it?", "Is my order ready?"
            </p>
        </div>
 
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                <i class="ph-bold ph-note-pencil text-white text-2xl"></i>
            </div>
            <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26] mt-4">Manual order processing</h3>
            <p class="font-inter text-sm text-[#171E26]/70 mt-2 leading-relaxed">
                Orders are written manually, invoices calculated manually, and mistakes can happen.
            </p>
        </div>
 
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                <i class="ph-bold ph-clock text-white text-2xl"></i>
            </div>
            <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26] mt-4">Customers waste time</h3>
            <p class="font-inter text-sm text-[#171E26]/70 mt-2 leading-relaxed">
                Customers have to travel to the pharmacy just to place an order and wait for it to be prepared.
            </p>
        </div>
 
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                <i class="ph-bold ph-trend-down text-white text-2xl"></i>
            </div>
            <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26] mt-4">Limited digital presence</h3>
            <p class="font-inter text-sm text-[#171E26]/70 mt-2 leading-relaxed">
                Your pharmacy may have loyal customers, but there's no simple digital storefront for them to order from.
            </p>
        </div>
 
     </div>
 
     <p class="font-manrope text-2xl md:text-3xl font-bold text-center bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent mt-12 md:mt-16">
        There's a better way.......
     </p>
   </section>

   <section id="solution" class="py-16 md:py-24 px-4 md:px-10 bg-[#DBEBFB]/40 scroll-mt-[110px] md:scroll-mt-[140px]">
     <div class="max-w-3xl mx-auto text-center reveal">
        <span class="font-inter text-xs md:text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent">The Solution</span>
        <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-[#171E26] mt-2">
            One platform. Two experiences.
        </h2>
     </div>
 
     <div class="flex flex-col md:flex-row items-stretch justify-center gap-6 max-w-4xl mx-auto mt-12 reveal-group">
 
        <!-- For Customers -->
        <div class="w-full md:w-1/2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="h-12 w-12 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                <i class="ph ph-users text-[#2775E4] text-2xl"></i>
            </div>
            <h3 class="font-manrope text-xl font-bold text-[#171E26] mt-4">For Your Customers</h3>
            <p class="font-inter text-sm text-[#171E26]/70 mt-2 leading-relaxed">
                A simple way to shop from home, without stepping into the pharmacy.
            </p>
            <ul class="mt-5 space-y-3">
                <li class="flex items-center gap-3 font-inter text-sm text-[#171E26]">
                    <i class="ph ph-magnifying-glass text-[#2775E4] text-base"></i>
                    Browse available products
                </li>
                <li class="flex items-center gap-3 font-inter text-sm text-[#171E26]">
                    <i class="ph ph-house text-[#2775E4] text-base"></i>
                    Place orders from home
                </li>
                <li class="flex items-center gap-3 font-inter text-sm text-[#171E26]">
                    <i class="ph ph-lock-key text-[#2775E4] text-base"></i>
                    Make secure payments
                </li>
                <li class="flex items-center gap-3 font-inter text-sm text-[#171E26]">
                    <i class="ph ph-map-pin-line text-[#2775E4] text-base"></i>
                    Track orders until pickup
                </li>
            </ul>
        </div>
 
        <!-- For Pharmacy Owner -->
        <div class="w-full md:w-1/2 rounded-2xl shadow-md p-6 md:p-8 bg-gradient-to-br from-[#2775E4] to-[#08AEBC]">
            <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="ph ph-storefront text-white text-2xl"></i>
            </div>
            <h3 class="font-manrope text-xl font-bold text-white mt-4">For You, the Pharmacy Owner</h3>
            <p class="font-inter text-sm text-white/90 mt-2 leading-relaxed">
                One platform to manage your pharmacy digitally, start to finish.
            </p>
            <ul class="mt-5 space-y-3">
                <li class="flex items-center gap-3 font-inter text-sm text-white">
                    <i class="ph ph-package text-white text-base"></i>
                    Update products, prices & availability
                </li>
                <li class="flex items-center gap-3 font-inter text-sm text-white">
                    <i class="ph ph-tray-arrow-down text-white text-base"></i>
                    Receive customer orders
                </li>
                <li class="flex items-center gap-3 font-inter text-sm text-white">
                    <i class="ph ph-check-circle text-white text-base"></i>
                    Process orders with ease
                </li>
                <li class="flex items-center gap-3 font-inter text-sm text-white">
                    <i class="ph ph-chart-line-up text-white text-base"></i>
                    Track everything from one dashboard
                </li>
            </ul>
        </div>
 
     </div>
   </section>
   
   <section id="how-it-works" class="py-16 md:py-24 px-4 md:px-10 scroll-mt-[110px] md:scroll-mt-[140px]">
     <div class="max-w-3xl mx-auto text-center reveal">
        <span class="font-inter text-xs md:text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent">How It Works</span>
        <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-[#171E26] mt-2">
            The pharmacy owner's workflow.
        </h2>
     </div>
 
     <div class="max-w-2xl mx-auto mt-12 reveal-group">
 
        <!-- Step 01 -->
        <div class="relative flex gap-5 pb-8">
            <div class="absolute left-5 top-10 bottom-0 w-0.5 bg-[#DBEBFB]"></div>
            <div class="relative z-10 flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center font-manrope font-bold text-sm text-white">01</div>
            <div>
                <h3 class="font-manrope text-base md:text-lg font-bold text-[#171E26]">Set up your pharmacy</h3>
                <p class="font-inter text-sm text-[#171E26]/70 mt-0.5">Add your pharmacy information and configure your account.</p>
            </div>
        </div>
 
        <!-- Step 02 -->
        <div class="relative flex gap-5 pb-8">
            <div class="absolute left-5 top-10 bottom-0 w-0.5 bg-[#DBEBFB]"></div>
            <div class="relative z-10 flex-shrink-0 h-10 w-10 rounded-full bg-white border-2 border-[#DBEBFB] flex items-center justify-center font-manrope font-bold text-sm text-[#171E26]">02</div>
            <div>
                <h3 class="font-manrope text-base md:text-lg font-bold text-[#171E26]">Add your products</h3>
                <p class="font-inter text-sm text-[#171E26]/70 mt-0.5">Add medicines, healthcare products, prices and availability.</p>
            </div>
        </div>
 
        <!-- Step 03 -->
        <div class="relative flex gap-5 pb-8">
            <div class="absolute left-5 top-10 bottom-0 w-0.5 bg-[#DBEBFB]"></div>
            <div class="relative z-10 flex-shrink-0 h-10 w-10 rounded-full bg-white border-2 border-[#DBEBFB] flex items-center justify-center font-manrope font-bold text-sm text-[#171E26]">03</div>
            <div>
                <h3 class="font-manrope text-base md:text-lg font-bold text-[#171E26]">Customers start ordering</h3>
                <p class="font-inter text-sm text-[#171E26]/70 mt-0.5">Your customers browse your pharmacy and place orders from home.</p>
            </div>
        </div>
 
        <!-- Step 04 -->
        <div class="relative flex gap-5 pb-8">
            <div class="absolute left-5 top-10 bottom-0 w-0.5 bg-[#DBEBFB]"></div>
            <div class="relative z-10 flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center font-manrope font-bold text-sm text-white">04</div>
            <div>
                <h3 class="font-manrope text-base md:text-lg font-bold text-[#171E26]">You receive the order</h3>
                <p class="font-inter text-sm text-[#171E26]/70 mt-0.5">The pharmacy dashboard shows new orders.</p>
            </div>
        </div>
 
        <!-- Step 05 -->
        <div class="relative flex gap-5 pb-8">
            <div class="absolute left-5 top-10 bottom-0 w-0.5 bg-[#DBEBFB]"></div>
            <div class="relative z-10 flex-shrink-0 h-10 w-10 rounded-full bg-white border-2 border-[#DBEBFB] flex items-center justify-center font-manrope font-bold text-sm text-[#171E26]">05</div>
            <div>
                <h3 class="font-manrope text-base md:text-lg font-bold text-[#171E26]">Prepare the order</h3>
                <p class="font-inter text-sm text-[#171E26]/70 mt-0.5">Your staff processes and prepares it.</p>
            </div>
        </div>
 
        <!-- Step 06 -->
        <div class="relative flex gap-5">
            <div class="relative z-10 flex-shrink-0 h-10 w-10 rounded-full bg-white border-2 border-[#DBEBFB] flex items-center justify-center font-manrope font-bold text-sm text-[#171E26]">06</div>
            <div>
                <h3 class="font-manrope text-base md:text-lg font-bold text-[#171E26]">Customer gets notified</h3>
                <p class="font-inter text-sm text-[#171E26]/70 mt-0.5">Once ready, the customer knows when to come and pick it up.</p>
            </div>
        </div>
 
     </div>
 
     <p class="font-manrope text-xl md:text-2xl font-bold text-center bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent mt-12 md:mt-16">
        That's the entire MedMart value proposition in six steps.
     </p>
   </section>
   
   <section id="features" class="py-16 md:py-24 px-4 md:px-10 scroll-mt-[110px] md:scroll-mt-[140px]">
     <div class="max-w-3xl mx-auto text-center reveal">
        <span class="font-inter text-xs md:text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent"> Features</span>
        <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-[#171E26] mt-2">
            Everything you need to run your pharmacy online.
        </h2>
     </div>
 
     <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center mt-12">
 
        <!-- Left: feature list -->
        <div class="reveal-group">
            <div class="flex items-start gap-4 py-3.5 border-t border-[#DBEBFB]">
                <i class="ph ph-package text-[#2775E4] text-lg mt-0.5"></i>
                <div>
                    <p class="font-manrope text-base font-bold text-[#171E26]">Order Management</p>
                    <p class="font-inter text-xs md:text-sm text-[#171E26]/70 mt-0.5">Receive and process customer orders from one dashboard.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 py-3.5 border-t border-[#DBEBFB]">
                <i class="ph ph-tag text-[#2775E4] text-lg mt-0.5"></i>
                <div>
                    <p class="font-manrope text-base font-bold text-[#171E26]">Product Management</p>
                    <p class="font-inter text-xs md:text-sm text-[#171E26]/70 mt-0.5">Add, edit and manage your pharmacy's products and prices.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 py-3.5 border-t border-[#DBEBFB]">
                <i class="ph ph-credit-card text-[#2775E4] text-lg mt-0.5"></i>
                <div>
                    <p class="font-manrope text-base font-bold text-[#171E26]">Payment Management</p>
                    <p class="font-inter text-xs md:text-sm text-[#171E26]/70 mt-0.5">Receive and track payments associated with customer orders.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 py-3.5 border-t border-[#DBEBFB]">
                <i class="ph ph-users text-[#2775E4] text-lg mt-0.5"></i>
                <div>
                    <p class="font-manrope text-base font-bold text-[#171E26]">Customer Management</p>
                    <p class="font-inter text-xs md:text-sm text-[#171E26]/70 mt-0.5">Keep track of your customers and their order history.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 py-3.5 border-t border-[#DBEBFB]">
                <i class="ph ph-bell text-[#2775E4] text-lg mt-0.5"></i>
                <div>
                    <p class="font-manrope text-base font-bold text-[#171E26]">Order Updates</p>
                    <p class="font-inter text-xs md:text-sm text-[#171E26]/70 mt-0.5">Keep customers informed as their orders move through the process.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 py-3.5 border-t border-b border-[#DBEBFB]">
                <i class="ph ph-receipt text-[#2775E4] text-lg mt-0.5"></i>
                <div>
                    <p class="font-manrope text-base font-bold text-[#171E26]">Invoice Management</p>
                    <p class="font-inter text-xs md:text-sm text-[#171E26]/70 mt-0.5">Generate and manage order invoices.</p>
                </div>
            </div>
        </div>
 
        <!-- Right: dashboard mockup -->
        <div class="bg-[#DBEBFB]/40 rounded-2xl p-4 md:p-6 reveal">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="font-inter text-xs font-semibold uppercase tracking-wider text-[#171E26]/50 mb-3">Dashboard Overview</p>
 
              <img src="{{ asset('images/dashboard.png') }}" class="rounded-lg w-full">
            </div>
        </div>
 
     </div>
   </section>

   <section id="business-benefit" class="py-16 md:py-24 px-4 md:px-10 bg-[#DBEBFB]/40 scroll-mt-[110px] md:scroll-mt-[140px]">
     <div class="max-w-3xl mx-auto text-center reveal">
        <span class="font-inter text-xs md:text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent"> The Business Benefit</span>
        <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-[#171E26] mt-2">
            More convenience for your customers.<br class="hidden md:block"> Less work for your staff.
        </h2>
     </div>
 
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-12 items-stretch reveal-group">
 
        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
            <div class="h-12 w-12 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                <i class="ph ph-timer text-[#2775E4] text-2xl"></i>
            </div>
            <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26] mt-4">Save time</h3>
            <p class="font-inter text-sm text-[#171E26]/70 mt-2 leading-relaxed">
                Reduce unnecessary queues and repetitive customer interactions.
            </p>
        </div>
 
        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
            <div class="h-12 w-12 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                <i class="ph ph-trend-up text-[#2775E4] text-2xl"></i>
            </div>
            <h3 class="font-manrope text-lg md:text-xl font-bold text-[#171E26] mt-4">Serve more customers</h3>
            <p class="font-inter text-sm text-[#171E26]/70 mt-2 leading-relaxed">
                Allow customers to place orders even when they aren't physically in the pharmacy.
            </p>
        </div>
 
        <div class="rounded-2xl p-6 md:p-8 bg-gradient-to-br from-[#2775E4] to-[#08AEBC] shadow-md">
            <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="ph ph-handshake text-white text-2xl"></i>
            </div>
            <h3 class="font-manrope text-lg md:text-xl font-bold text-white mt-4">Keep your customers</h3>
            <p class="font-inter text-sm text-white/90 mt-2 leading-relaxed">
                Give your existing customers a convenient digital experience without sending them to another marketplace.
            </p>
        </div>
 
     </div>
 
     <p class="font-manrope text-xl md:text-2xl font-bold text-center bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent mt-12 md:mt-16 max-w-2xl mx-auto">
        Your pharmacy now has its own digital ordering channel.
     </p>
   </section>

     <section id="faq" class="py-16 md:py-24 px-4 md:px-10 scroll-mt-[110px] md:scroll-mt-[140px]">
     <div class="max-w-3xl mx-auto text-center reveal">
        <span class="font-inter text-xs md:text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent"> FAQ</span>
        <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-[#171E26] mt-2">
            Questions pharmacy owners ask us.
        </h2>
     </div>
 
     <div class="max-w-2xl mx-auto mt-10 space-y-3 reveal-group">
 
        <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 open:shadow-md transition">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4">
                <span class="font-manrope font-bold text-[#171E26] text-sm md:text-base">What is MedMart?</span>
                <i class="ph ph-plus text-[#2775E4] text-lg flex-shrink-0 group-open:hidden"></i>
                <i class="ph ph-minus text-[#2775E4] text-lg flex-shrink-0 hidden group-open:block"></i>
            </summary>
            <p class="font-inter text-xs md:text-sm text-[#171E26]/70 px-5 md:px-6 pb-4 leading-relaxed">
                MedMart is a platform that gives your pharmacy its own digital storefront, so customers can browse, order and pay online while you manage products, prices and orders from one dashboard.
            </p>
        </details>
 
        <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 open:shadow-md transition">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4">
                <span class="font-manrope font-bold text-[#171E26] text-sm md:text-base">How does my pharmacy get started?</span>
                <i class="ph ph-plus text-[#2775E4] text-lg flex-shrink-0 group-open:hidden"></i>
                <i class="ph ph-minus text-[#2775E4] text-lg flex-shrink-0 hidden group-open:block"></i>
            </summary>
            <p class="font-inter text-xs md:text-sm text-[#171E26]/70 px-5 md:px-6 pb-4 leading-relaxed">
                You sign up, set up your pharmacy profile, and add your products, prices and availability. Once that's done, your digital storefront is ready for customers.
            </p>
        </details>

        <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 open:shadow-md transition">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4">
                <span class="font-manrope font-bold text-[#171E26] text-sm md:text-base">Can my customers order from home?</span>
                <i class="ph ph-plus text-[#2775E4] text-lg flex-shrink-0 group-open:hidden"></i>
                <i class="ph ph-minus text-[#2775E4] text-lg flex-shrink-0 hidden group-open:block"></i>
            </summary>
            <p class="font-inter text-xs md:text-sm text-[#171E26]/70 px-5 md:px-6 pb-4 leading-relaxed">
                Yes. Customers can browse your available products, place an order and pay, all without visiting the pharmacy in person.
            </p>
        </details>
 
        <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 open:shadow-md transition">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4">
                <span class="font-manrope font-bold text-[#171E26] text-sm md:text-base">Do I manage my own products and prices?</span>
                <i class="ph ph-plus text-[#2775E4] text-lg flex-shrink-0 group-open:hidden"></i>
                <i class="ph ph-minus text-[#2775E4] text-lg flex-shrink-0 hidden group-open:block"></i>
            </summary>
            <p class="font-inter text-xs md:text-sm text-[#171E26]/70 px-5 md:px-6 pb-4 leading-relaxed">
                Yes. You're always in control of your product list, pricing and availability, and can update them at any time.
            </p>
        </details>
 
        <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 open:shadow-md transition">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4">
                <span class="font-manrope font-bold text-[#171E26] text-sm md:text-base">How do I receive customer orders?</span>
                <i class="ph ph-plus text-[#2775E4] text-lg flex-shrink-0 group-open:hidden"></i>
                <i class="ph ph-minus text-[#2775E4] text-lg flex-shrink-0 hidden group-open:block"></i>
            </summary>
            <p class="font-inter text-xs md:text-sm text-[#171E26]/70 px-5 md:px-6 pb-4 leading-relaxed">
                New orders appear directly on your pharmacy dashboard as they come in, so you can review and start processing them right away.
            </p>
        </details>
 
        <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 open:shadow-md transition">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4">
                <span class="font-manrope font-bold text-[#171E26] text-sm md:text-base">Can customers track their orders?</span>
                <i class="ph ph-plus text-[#2775E4] text-lg flex-shrink-0 group-open:hidden"></i>
                <i class="ph ph-minus text-[#2775E4] text-lg flex-shrink-0 hidden group-open:block"></i>
            </summary>
            <p class="font-inter text-xs md:text-sm text-[#171E26]/70 px-5 md:px-6 pb-4 leading-relaxed">
                Yes. Customers can follow their order from placed, to payment confirmed, to preparing, to ready for pickup.
            </p>
        </details>
 
        <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 open:shadow-md transition">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4">
                <span class="font-manrope font-bold text-[#171E26] text-sm md:text-base">Can I manage multiple staff members?</span>
                <i class="ph ph-plus text-[#2775E4] text-lg flex-shrink-0 group-open:hidden"></i>
                <i class="ph ph-minus text-[#2775E4] text-lg flex-shrink-0 hidden group-open:block"></i>
            </summary>
            <p class="font-inter text-xs md:text-sm text-[#171E26]/70 px-5 md:px-6 pb-4 leading-relaxed">
                MedMart is built around your pharmacy account, so your team can help process and prepare orders from the same dashboard as they come in.
            </p>
        </details>
 
        <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 open:shadow-md transition">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4">
                <span class="font-manrope font-bold text-[#171E26] text-sm md:text-base">How are payments handled?</span>
                <i class="ph ph-plus text-[#2775E4] text-lg flex-shrink-0 group-open:hidden"></i>
                <i class="ph ph-minus text-[#2775E4] text-lg flex-shrink-0 hidden group-open:block"></i>
            </summary>
            <p class="font-inter text-xs md:text-sm text-[#171E26]/70 px-5 md:px-6 pb-4 leading-relaxed">
                Customers pay securely online when placing an order, and payments are tracked against each order on your dashboard.
            </p>
        </details>
 
        <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 open:shadow-md transition">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 md:px-6 py-4">
                <span class="font-manrope font-bold text-[#171E26] text-sm md:text-base">Can I still serve customers physically?</span>
                <i class="ph ph-plus text-[#2775E4] text-lg flex-shrink-0 group-open:hidden"></i>
                <i class="ph ph-minus text-[#2775E4] text-lg flex-shrink-0 hidden group-open:block"></i>
            </summary>
            <div class="px-5 md:px-6 pb-4">
                <p class="font-inter text-xs md:text-sm text-[#171E26]/70 leading-relaxed">
                    Absolutely. MedMart doesn't replace your physical pharmacy, it simply gives it a digital ordering channel alongside it.
                </p>
                <p class="font-inter text-xs md:text-sm font-semibold bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent mt-2">
                    Your pharmacy. Your customers. Your brand.
                </p>
            </div>
        </details>
 
     </div>
   </section>

     <section id="final-cta" class="py-16 md:py-24 px-4 md:px-10 bg-gradient-to-br from-[#2775E4] to-[#08AEBC] text-center scroll-mt-[110px] md:scroll-mt-[140px]">
     <div class="max-w-2xl mx-auto reveal">
        <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white">
            Ready to take your pharmacy online?
        </h2>
        <p class="font-inter text-white/90 text-base md:text-lg mt-4 max-w-xl mx-auto">
            Give your customers a simpler way to order from your pharmacy — without changing the way your pharmacy operates.
        </p>
 
        <a href="{{route('register') }}"><button class="px-6 py-3 md:px-8 md:py-3.5 rounded-xl bg-white text-[#2775E4] font-inter font-medium text-sm md:text-base tracking-wide hover:scale-[1.02] active:scale-[0.98] transition shadow-md mt-6 cursor-pointer">
            Get Started
        </button></a>
 
        <p class="font-inter text-white/90 text-sm mt-6">
            Already using MedMart?
           <a href="{{ route('login') }}"><button class="font-inter font-medium text-white underline underline-offset-4 hover:text-white/80 transition ml-1 cursor-pointer">
                Login
            </button></a>
        </p>
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