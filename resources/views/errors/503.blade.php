{{--
    Intended path: resources/views/errors/503.blade.php

    NOTES:
    - Same standalone structure as the others in this set.
    - No "Go to Dashboard" CTA on this one deliberately — if the whole
      app is down for maintenance, that link would just 503 again. Only
      a "Refresh" action is offered.
    - Illustration: a shop door with a hanging "CLOSED" sign and a
      wrench, reading as "temporarily closed for maintenance" rather
      than "broken."
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temporarily unavailable — MedMart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/light/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, .font-manrope { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-[#E9F3FE] text-[#171E26] antialiased">

    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-[420px] h-[420px] rounded-full bg-[#B1D0FB] opacity-40 blur-3xl"></div>
        <div class="absolute -bottom-32 -right-16 w-[380px] h-[380px] rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] opacity-20 blur-3xl"></div>
    </div>

    <div class="absolute top-6 left-6 md:top-8 md:left-10 flex items-center gap-2">
        <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
            <i class="ph-fill ph-plus-circle text-white text-[16px]"></i>
        </div>
        <span class="font-manrope font-extrabold text-[16px] text-[#171E26]">MedMart</span>
    </div>

    <main class="min-h-screen flex items-center justify-center px-6 py-24">
        <div class="max-w-[880px] w-full grid md:grid-cols-2 gap-10 md:gap-16 items-center">

            <div class="flex justify-center md:justify-end">
                <svg viewBox="0 0 260 220" class="w-[220px] md:w-[260px] h-auto" xmlns="http://www.w3.org/2000/svg">
                    <rect x="60" y="30" width="140" height="160" rx="10" fill="#DBEBFB"/>
                    <rect x="76" y="46" width="108" height="90" rx="6" fill="white" opacity="0.7"/>
                    <circle cx="168" cy="120" r="4" fill="#B1D0FB"/>
                    <line x1="130" y1="80" x2="130" y2="95" stroke="#171E26" stroke-width="2"/>
                    <g transform="rotate(-6 130 118)">
                        <rect x="92" y="98" width="76" height="34" rx="6" fill="url(#grad503)"/>
                        <text x="130" y="120" text-anchor="middle" font-family="Manrope, sans-serif" font-weight="800" font-size="12" fill="white" letter-spacing="1">CLOSED</text>
                    </g>
                    <g transform="translate(45 175) rotate(-25)">
                        <rect x="-3" y="-22" width="6" height="34" rx="3" fill="#058A98"/>
                        <circle cx="0" cy="-24" r="8" fill="none" stroke="#058A98" stroke-width="4"/>
                    </g>
                    <defs>
                        <linearGradient id="grad503" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#2775E4"/>
                            <stop offset="1" stop-color="#08AEBC"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div class="text-center md:text-left">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#DBEBFB] text-[#2775E4] text-[11px] font-bold tracking-wide uppercase px-3 py-1 mb-4">Error 503</span>
                <h1 class="font-manrope font-extrabold text-[26px] md:text-[30px] text-[#171E26] mb-3">MedMart is temporarily unavailable</h1>
                <p class="font-inter text-[14px] text-[#171E26]/60 leading-relaxed mb-7 max-w-[380px] mx-auto md:mx-0">
                    We're carrying out scheduled maintenance. We'll be back shortly — thanks for your patience.
                </p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <a href="javascript:location.reload()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13.5px] shadow-sm shadow-[#2775E4]/20 hover:opacity-95 transition">Refresh</a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>