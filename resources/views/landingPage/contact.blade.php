<x-layouts.landingpage>
    <div class="fixed top-0 left-0 right-0 z-40 h-[90px] md:h-[120px] backdrop-blur-xl bg-white/10 border-b border-white/20 pointer-events-none"></div>

    <nav class="mt-3 md:mt-5  fixed left-0 right-0 z-50 flex flex-wrap justify-between mx-4 md:mx-10 items-center bg-white px-4 py-2 rounded-2xl gap-4">
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
                    <a href="{{route('home')}}#business-benefit" class="block px-4 py-2 font-inter text-[16px] text-[#171E26] hover:bg-[#DBEBFB]/40 hover:text-[#2775E4]">Benefit</a>
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
        <a href="{{ route('contact') }}" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">Contact</a>
        <a href="{{route('home')}}#faq" onclick="closeMobileMenu()" class="font-inter text-[17px] font-medium text-[#171E26] hover:text-[#2775E4] px-2 py-3">FAQs</a>

        <div class="flex gap-3 px-2 pt-3 pb-2">
              <a href='{{ route('login') }}'><button class="flex-1 py-2.5 rounded-xl shadow-md text-[16px] font-inter bg-[#2775E4] text-white capitalize tracking-wider">login</button></a>
            <a href='{{ route('register') }}'><button class="flex-1 py-2.5 rounded-xl shadow-md text-[16px] font-inter bg-[#2775E4] text-white capitalize tracking-wider">signup</button></a>
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
            <a href='{{ route('register') }}'><button class="px-8 py-3.5 rounded-full bg-white text-[#2775E4] font-inter font-semibold text-lg tracking-wide hover:scale-[1.02] transition shadow-lg mt-8">
                Get Started
            </button></a>
        </div>
    </section>
</x-layouts.landingpage>