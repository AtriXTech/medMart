<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | MedMart</title>
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

        .field-input {
            width: 100%;
            background: #fff;
            border: 1px solid #DBEBFB;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            color: #171E26;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .field-input:focus {
            outline: none;
            border-color: #2775E4;
            box-shadow: 0 0 0 3px rgba(39, 117, 228, 0.15);
        }
        .field-label {
            display: block;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #171E26;
            margin-bottom: 0.375rem;
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

    <nav class="mt-3 md:mt-5  fixed left-0 right-0 z-50 flex flex-wrap justify-between mx-4 md:mx-10 items-center bg-white px-4 py-2 rounded-2xl gap-4">
    <img src="./image/logo.png" alt="logo" class="h-[70px] w-[100px] md:h-[100px] md:w-[140px]">

    <!-- Desktop nav links -->
    <div class="hidden md:flex gap-6 lg:gap-10 ">
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="home.html#features">Features</a></h1>
        <div class="relative group">
            <h1 class="font-inter text-[18px] lg:text-[20px] font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4] cursor-pointer flex items-center gap-1">
                Why MedMart
                <i class="ph ph-caret-down text-[#2775E4] text-sm"></i>
            </h1>
            <div class="absolute left-0 top-full pt-3 hidden group-hover:block z-50">
                <div class="bg-white rounded-xl shadow-lg py-2 min-w-[160px]">
                    <a href="home.html#problem" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Problem</a>
                    <a href="home.html#solution" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Solution</a>
                    <a href="home.html#business-benefit" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Benefit</a>
                    <a href="home.html#how-it-works" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">How It Works</a>
                    
                </div>
            </div>
        </div>
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="pricing.html">Pricing</a></h1>
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="contact.html">contact</a></h1>
        <h1 class="font-inter text-[18px] lg:text-[20px]  font-thin bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent hover:text-[#2775E4]"><a href="home.html#faq">FAQs</a></h1>
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
        <a href="home.html#features" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">Features</a>

        <details class="group px-2">
            <summary class="flex items-center justify-between cursor-pointer list-none py-3 font-inter text-[17px] font-medium text-[#171E26]">
                Why MedMart
                <i class="ph ph-caret-down text-[#2775E4] text-sm group-open:rotate-180 transition-transform"></i>
            </summary>
            <div class="flex flex-col pl-3 pb-2">
                <a href="home.html#problem" onclick="closeMobileMenu()" class="font-inter text-[15px] text-[#171E26]/70 hover:text-[#2775E4] py-2">Problem</a>
                <a href="home.html#solution" onclick="closeMobileMenu()" class="font-inter text-[15px] text-[#171E26]/70 hover:text-[#2775E4] py-2">Solution</a>
                <a href="home.html#business-benefit" onclick="closeMobileMenu()" class="font-inter text-[15px] text-[#171E26]/70 hover:text-[#2775E4] py-2">Benefit</a>
                <a href="home.html#how-it-works" onclick="closeMobileMenu()" class="font-inter text-[15px] text-[#171E26]/70 hover:text-[#2775E4] py-2">How It Works</a>
            </div>
        </details>

        <a href="pricing.html" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">Pricing</a>
        <a href="contact.html" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">Contact</a>
        <a href="home.html#faq" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">FAQs</a>

        <div class="flex gap-3 px-2 pt-3 pb-2">
            <button class="flex-1 py-2.5 rounded-xl shadow-md text-[16px] font-inter bg-[#2775E4] text-white capitalize tracking-wider">login</button>
            <button class="flex-1 py-2.5 rounded-xl shadow-md text-[16px] font-inter bg-[#2775E4] text-white capitalize tracking-wider">signup</button>
        </div>
    </div>
</nav>
    <!-- 1. HERO -->
    <section class="bg-gradient-to-br from-[#E9F3FE] via-[#DBEBFB] to-[#B1D0FB] pt-[130px] pb-16 md:pt-[170px] md:pb-24 px-4 md:px-10">
        <div class="max-w-3xl mx-auto text-center bg-white/30 backdrop-blur-xl border border-white/40 rounded-3xl p-6 md:p-10 reveal">
                        <span class="font-inter text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent">Contact Us</span>
            <h1 class="font-manrope text-3xl sm:text-4xl md:text-6xl font-extrabold text-[#171E26]">
                Let's talk about your pharmacy.
            </h1>
            <p class="font-inter text-[#171E26]/70 text-base sm:text-lg md:text-xl mt-4 max-w-2xl mx-auto">
                Have questions about MedMart, need help getting started, or interested in bringing your pharmacy online? Our team is here to help.
            </p>
        </div>
    </section>

    <!-- 2. CONTACT OPTIONS -->
    <section class="py-16 md:py-24 px-4 md:px-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto reveal-group">

            <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
                <div class="h-12 w-12 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                    <i class="ph-light ph-chat-circle-text text-[#2775E4] text-2xl"></i>
                </div>
                <h3 class="font-manrope text-xl font-bold text-[#171E26] mt-4">General Enquiries</h3>
                <p class="font-inter text-[#171E26]/70 mt-2 text-sm">
                    Questions about MedMart, features, pricing, or how the platform works.
                </p>
            </div>

            <div class="rounded-2xl shadow-lg p-6 md:p-8 bg-gradient-to-br from-[#2775E4] to-[#08AEBC]">
                <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="ph-light ph-handshake text-white text-2xl"></i>
                </div>
                <h3 class="font-manrope text-xl font-bold text-white mt-4">Partner With Us</h3>
                <p class="font-inter text-white/85 mt-2 text-sm">
                    Own a pharmacy and want to bring it online? Let's get you set up on MedMart.
                </p>
                <a href="#contact-form" class="font-inter font-semibold text-white mt-4 inline-flex items-center gap-1.5 hover:gap-2.5 transition-all">
                    Get Started <i class="ph-light ph-arrow-right"></i>
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
                <div class="h-12 w-12 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                    <i class="ph-light ph-wrench text-[#2775E4] text-2xl"></i>
                </div>
                <h3 class="font-manrope text-xl font-bold text-[#171E26] mt-4">Technical Support</h3>
                <p class="font-inter text-[#171E26]/70 mt-2 text-sm">
                    Already using MedMart and running into a technical issue? We'll help you sort it out.
                </p>
            </div>

        </div>
    </section>

    <!-- 3. MAIN CONTACT FORM -->
    <section id="contact-form" class="py-16 md:py-24 px-4 md:px-10 bg-[#DBEBFB]/40">
        <div class="max-w-3xl mx-auto text-center reveal">
            <span class="font-inter text-sm font-semibold tracking-widest uppercase bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent">Get In Touch</span>
            <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#171E26] mt-3">
                Send us a message.
            </h2>
        </div>

        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-14 mt-14 items-start reveal-group">

            <!-- Left: info -->
            <div>
                <h3 class="font-manrope text-2xl md:text-3xl font-bold text-[#171E26]">We're here to help.</h3>
                <p class="font-inter text-[#171E26]/70 mt-3">
                    Whether you're exploring MedMart for your pharmacy, already using the platform, or simply have a question, send us a message and we'll get back to you.
                </p>

                <div class="mt-8 space-y-5">
                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                            <i class="ph-light ph-envelope-simple text-[#2775E4] text-xl"></i>
                        </div>
                        <div>
                            <p class="font-inter text-xs text-[#171E26]/50">Email</p>
                            <p class="font-inter text-[#171E26] font-medium">hello@medmart.com</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                            <i class="ph-light ph-phone text-[#2775E4] text-xl"></i>
                        </div>
                        <div>
                            <p class="font-inter text-xs text-[#171E26]/50">Phone</p>
                            <p class="font-inter text-[#171E26] font-medium">+234 800 000 0000</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                            <i class="ph-light ph-map-pin text-[#2775E4] text-xl"></i>
                        </div>
                        <div>
                            <p class="font-inter text-xs text-[#171E26]/50">Office</p>
                            <p class="font-inter text-[#171E26] font-medium">Ibadan, Nigeria</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: form -->
            <form class="bg-white/40 backdrop-blur-xl border border-white/50 rounded-3xl p-6 md:p-8 shadow-md">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label">Full Name</label>
                        <input type="text" placeholder="Your full name" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Pharmacy Name</label>
                        <input type="text" placeholder="Your pharmacy's name" class="field-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="field-label">Email Address</label>
                        <input type="email" placeholder="you@example.com" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Phone Number</label>
                        <input type="tel" placeholder="+234 000 000 0000" class="field-input">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="field-label">What can we help you with?</label>
                    <select class="field-input">
                        <option>General enquiry</option>
                        <option>Interested in MedMart</option>
                        <option>Pharmacy partnership</option>
                        <option>Technical support</option>
                        <option>Payment issue</option>
                        <option>Account issue</option>
                        <option>Other</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label class="field-label">Message</label>
                    <textarea rows="4" placeholder="Tell us a bit more..." class="field-input resize-none"></textarea>
                </div>

                <button type="submit" class="w-full mt-6 px-7 py-3.5 rounded-full bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[17px] shadow-lg shadow-[#2775E4]/20 hover:scale-[1.01] transition tracking-wide">
                    Send Message
                </button>
            </form>

        </div>
    </section>

    <!-- 4. FAQ MINI SECTION -->
    <section class="py-16 md:py-20 px-4 md:px-10">
        <div class="max-w-2xl mx-auto text-center reveal">
            <h2 class="font-manrope text-2xl md:text-3xl font-bold text-[#171E26]">
                Looking for a quick answer?
            </h2>
            <p class="font-inter text-[#171E26]/70 mt-3">
                You might find what you're looking for in our frequently asked questions.
            </p>
            <a href="home.html#faq" class="font-inter font-semibold bg-gradient-to-br from-[#2775E4] to-[#08AEBC] bg-clip-text text-transparent mt-4 inline-flex items-center gap-1.5 hover:gap-2.5 transition-all">
                Visit FAQs <i class="ph-light ph-arrow-right text-[#2775E4]"></i>
            </a>
        </div>
    </section>

    <!-- 5. FINAL CTA -->
    <section class="py-20 md:py-28 px-4 md:px-10 bg-gradient-to-br from-[#2775E4] to-[#08AEBC] text-center">
        <div class="max-w-2xl mx-auto reveal">
            <h2 class="font-manrope text-3xl sm:text-4xl md:text-5xl font-extrabold text-white">
                Ready to bring your pharmacy online?
            </h2>
            <p class="font-inter text-white/85 text-lg md:text-xl mt-5">
                Give your customers a simpler way to order while you manage your pharmacy from one place.
            </p>
            <button class="px-8 py-3.5 rounded-full bg-white text-[#2775E4] font-inter font-semibold text-lg tracking-wide hover:scale-[1.02] transition shadow-lg mt-8">
                Get Started
            </button>
        </div>
    </section>

    <footer class="bg-[#171E26] px-4 md:px-10 pt-16 pb-8">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between gap-12">

            <div class="md:max-w-xs">
                <img src="./image/logo.png" alt="logo" class="h-[70px] w-[100px] md:h-[80px] md:w-[110px] -ml-2">
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
                        <li><a href="index.html#pricing" class="font-inter text-white/60 text-sm hover:text-white transition">Pricing</a></li>
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