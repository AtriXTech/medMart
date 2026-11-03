<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedMart</title>
     @vite('resources/css/app.css')
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
            /* --PrimaryBlue:#2775E4;
            --HealthcareTeal:; */

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
<body class="font-inter text-color">
   <section id="hero" class=" bg-gradient-to-br
                from-[#E9F3FE]
                via-[#DBEBFB]
                to-[#B1D0FB] py-5 h-screen ">
     <div class="fixed top-0 left-0 right-0 z-40 h-[90px] md:h-[120px] backdrop-blur-xl bg-white/10 border-b border-white/20 pointer-events-none"></div>
     
     <nav class="fixed left-0 right-0 z-50 flex flex-wrap justify-between mx-4 md:mx-10 items-center bg-white px-4 py-2 rounded-2xl gap-4">
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
                    <a href="{{route('home') }}#problem" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Problem</a>
                    <a href="{{route('home')}}#solution" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Solution</a>
                    <a href="{{route('home')}}#business-benefit" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Benefit</a>
                    <a href="{{route('home')}}#how-it-works" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">How It Works</a>
                    
                </div>
            </div>
        </div>
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="{{route('pricing') }}">Pricing</a></h1>
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="{{ route('contact') }}">contact</a></h1>
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="{{route('home')}}#faq">FAQs</a></h1>
    </div>

    <!-- Desktop auth buttons -->
    <div class="hidden md:flex gap-3 md:gap-5 items-center">
        <a href='{{route('login')}}'><button class="py-2 px-3 md:px-4 rounded-2xl hover:scale-[1.01] cursor-pointer shadow-md text-[16px] md:text-[20px] hover:bg-[white] hover:text-[#2775E4] hover:border-1 hover:border-[#2775E4] rounded-xl font-inter bg-[#2775E4]  text-white capitalize text-center tracking-wider">login</button></a>
        <a href="{{ route('register') }}"><button class="py-2 px-3 md:px-4 rounded-2xl hover:scale-[1.01] cursor-pointer shadow-md text-[16px] md:text-[20px] hover:bg-[white] hover:text-[#2775E4] hover:border-1 hover:border-[#2775E4] rounded-xl font-inter bg-[#2775E4]  text-white capitalize text-center tracking-wider">signup</button></a>
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
        <a href="{{route('contact')}}" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">Contact</a>
        <a href="{{route('home')}}#faq" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">FAQs</a>

      <div class="flex w-full gap-3 px-4 pt-3 pb-2">
    <a href="{{ route('login') }}" class="flex-1">
        <button class="w-full py-2.5 rounded-xl shadow-md text-[16px] font-inter bg-[#2775E4] text-white capitalize tracking-wider">
            Login
        </button>
    </a>

    <a href="{{ route('register') }}" class="flex-1">
        <button class="w-full py-2.5 rounded-xl shadow-md text-[16px] font-inter bg-[#2775E4] text-white capitalize tracking-wider">
            Signup
        </button>
    </a>
</div>
    </div>
</nav>

 {{ $slot }}

<footer class="bg-[#171E26] px-4 md:px-10 pt-16 pb-8">
     <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between gap-12">
 
        <!-- Logo + tagline -->
        <div class="md:max-w-xs">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-[70px] w-[100px] md:h-[80px] md:w-[110px] -ml-2">
            <p class="font-inter text-white/60 text-sm mt-2">
                The digital platform for modern pharmacies.
            </p>
        </div>
 
        <!-- Link columns -->
        <div class="flex flex-wrap gap-10 md:gap-16">
 
            <div>
                <p class="font-manrope text-white font-bold text-sm mb-4">Platform</p>
                <ul class="space-y-3">
                    <li><a href="{{route('home')}}#features" class="font-inter text-white/60 text-sm hover:text-white transition">Features</a></li>
                    <li><a href="{{route('home')}}#how-it-works" class="font-inter text-white/60 text-sm hover:text-white transition">How It Works</a></li>
                    <li><a href="{{route('pricing')}}#pricing" class="font-inter text-white/60 text-sm hover:text-white transition">Pricing</a></li>
                    <li><a href="{{route('home')}}#faq" class="font-inter text-white/60 text-sm hover:text-white transition">FAQs</a></li>
                </ul>
            </div>
 
            <div>
                <p class="font-manrope text-white font-bold text-sm mb-4">For Pharmacies</p>
                <ul class="space-y-3">
                    <li><a href="{{route('register')}}" class="font-inter text-white/60 text-sm hover:text-white transition">Get Started</a></li>
                    <li><a href="{{route('login')}}" class="font-inter text-white/60 text-sm hover:text-white transition">Login</a></li>
                    <li><a href="{{route('contact')}}" class="font-inter text-white/60 text-sm hover:text-white transition">Contact Support</a></li>
                </ul>
            </div>
 
            <div>
                <p class="font-manrope text-white font-bold text-sm mb-4">Company</p>
                <ul class="space-y-3">
                    {{-- <li><a href="#" class="font-inter text-white/60 text-sm hover:text-white transition">About</a></li> --}}
                    <li><a href="{{route('home')}}" class="font-inter text-white/60 text-sm hover:text-white transition">Privacy</a></li>
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