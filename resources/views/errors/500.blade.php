{{--
    Intended path: resources/views/errors/500.blade.php

    NOTES:
    - Same standalone structure as the others in this set.
    - FLAGGED ASSUMPTION: "Go to Dashboard" points to "/" — same caveat as
      404/403.
    - Illustration: a cracked mortar and pestle with a couple of spilled
      pills — "something broke while mixing," staying in the same
      pharmacy visual family rather than a generic broken-server icon.
    - "Try Again" just does location.reload() — no backend retry logic
      exists to call, so this is the honest client-side option.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Something went wrong — MedMart</title>
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
                    <ellipse cx="130" cy="185" rx="90" ry="10" fill="#DBEBFB"/>
                    <path d="M70 130 Q130 195 190 130 L182 150 Q130 185 78 150 Z" fill="url(#grad500)"/>
                    <path d="M70 130 Q130 165 190 130" fill="none" stroke="#058A98" stroke-width="4" stroke-linecap="round"/>
                    <path d="M120 132 l8 10 l-6 6 l10 8" fill="none" stroke="#171E26" stroke-width="2" opacity="0.35" stroke-linecap="round" stroke-linejoin="round"/>
                    <g transform="rotate(-30 150 100)">
                        <rect x="144" y="55" width="14" height="70" rx="7" fill="#B1D0FB"/>
                        <ellipse cx="151" cy="55" rx="12" ry="9" fill="#B1D0FB"/>
                    </g>
                    <circle cx="60" cy="150" r="6" fill="#08AEBC"/>
                    <circle cx="205" cy="155" r="5" fill="#2775E4"/>
                    <circle cx="220" cy="140" r="4" fill="#B1D0FB"/>
                    <defs>
                        <linearGradient id="grad500" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#2775E4"/>
                            <stop offset="1" stop-color="#08AEBC"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div class="text-center md:text-left">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#DBEBFB] text-[#2775E4] text-[11px] font-bold tracking-wide uppercase px-3 py-1 mb-4">Error 500</span>
                <h1 class="font-manrope font-extrabold text-[26px] md:text-[30px] text-[#171E26] mb-3">Something went wrong on our end</h1>
                <p class="font-inter text-[14px] text-[#171E26]/60 leading-relaxed mb-7 max-w-[380px] mx-auto md:mx-0">
                    An unexpected error occurred while processing your request. Try again, and reach out to support if it keeps happening.
                </p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <a href="javascript:location.reload()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13.5px] shadow-sm shadow-[#2775E4]/20 hover:opacity-95 transition">Try Again</a>
                    <a href="/" class="px-5 py-2.5 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[13.5px] text-[#171E26] hover:bg-white transition">Go to Dashboard</a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>