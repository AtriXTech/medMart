{{--
    Intended path: resources/views/errors/404.blade.php

    NOTES:
    - Standalone page — does not extend x-layouts.staff or x-layouts.customer,
      since a broken link can be hit by either app (or a signed-out visitor).
      Self-contained <html> with the same Tailwind v4 CDN / Manrope+Inter /
      Phosphor stack used everywhere else in the app.
    - FLAGGED ASSUMPTION: the "Go to Dashboard" link points to "/" — I don't
      have your actual named dashboard route (it differs for staff vs
      customer). Swap in {{ route('...') }} once you tell me the right one.
    - Design ties every error page in this set to a pharmacy-shelf visual
      motif (see 403/419/500/503) rather than a generic "broken robot" —
      here: an empty spot on the shelf where the page should be, and a
      tipped-over "?" bottle nearby.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page not found — MedMart</title>
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
                    <rect x="30" y="150" width="200" height="12" rx="6" fill="#B1D0FB"/>
                    <g opacity="0.55">
                        <rect x="55" y="90" width="34" height="58" rx="8" fill="none" stroke="#B1D0FB" stroke-width="2.5" stroke-dasharray="5 6"/>
                        <rect x="64" y="80" width="16" height="14" rx="3" fill="none" stroke="#B1D0FB" stroke-width="2.5" stroke-dasharray="5 6"/>
                    </g>
                    <g transform="rotate(18 165 130)">
                        <rect x="150" y="95" width="34" height="58" rx="8" fill="url(#grad404)"/>
                        <rect x="159" y="85" width="16" height="14" rx="3" fill="#058A98"/>
                        <text x="167" y="128" text-anchor="middle" font-family="Manrope, sans-serif" font-weight="800" font-size="15" fill="white">?</text>
                    </g>
                    <defs>
                        <linearGradient id="grad404" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#2775E4"/>
                            <stop offset="1" stop-color="#08AEBC"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div class="text-center md:text-left">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#DBEBFB] text-[#2775E4] text-[11px] font-bold tracking-wide uppercase px-3 py-1 mb-4">Error 404</span>
                <h1 class="font-manrope font-extrabold text-[26px] md:text-[30px] text-[#171E26] mb-3">This page doesn't exist</h1>
                <p class="font-inter text-[14px] text-[#171E26]/60 leading-relaxed mb-7 max-w-[380px] mx-auto md:mx-0">
                    The link may be broken, or the page may have moved. Check the address, or head back to what you were doing.
                </p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <a href="/" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13.5px] shadow-sm shadow-[#2775E4]/20 hover:opacity-95 transition">Go to Dashboard</a>
                    <a href="javascript:history.back()" class="px-5 py-2.5 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[13.5px] text-[#171E26] hover:bg-white transition">Go back</a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>