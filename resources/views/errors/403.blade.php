{{--
    Intended path: resources/views/errors/403.blade.php

    NOTES:
    - Same standalone structure as 404.blade.php — self-contained <html>,
      same Tailwind v4 CDN / Manrope+Inter / Phosphor stack, same
      background/blob/brand-mark chrome, for visual consistency across
      the whole error-page set.
    - FLAGGED ASSUMPTION: "Go to Dashboard" points to "/" — same caveat
      as 404, swap in your real named route.
    - Illustration: a locked medicine cabinet — reads clearly as
      "restricted access" while staying inside the same pharmacy-shelf
      visual family as the other error pages.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access denied — MedMart</title>
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
                    <rect x="55" y="30" width="150" height="160" rx="16" fill="#DBEBFB"/>
                    <line x1="130" y1="30" x2="130" y2="190" stroke="#B1D0FB" stroke-width="3"/>
                    <circle cx="118" cy="110" r="4" fill="#058A98"/>
                    <circle cx="142" cy="110" r="4" fill="#058A98"/>
                    <g transform="translate(130 108)">
                        <rect x="-26" y="-6" width="52" height="42" rx="10" fill="url(#grad403)"/>
                        <path d="M-16 -6 v-14 a16 16 0 0 1 32 0 v14" fill="none" stroke="url(#grad403)" stroke-width="8" stroke-linecap="round"/>
                        <circle cx="0" cy="16" r="5" fill="white"/>
                        <rect x="-2.5" y="18" width="5" height="10" rx="2.5" fill="white"/>
                    </g>
                    <defs>
                        <linearGradient id="grad403" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#2775E4"/>
                            <stop offset="1" stop-color="#08AEBC"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div class="text-center md:text-left">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#DBEBFB] text-[#2775E4] text-[11px] font-bold tracking-wide uppercase px-3 py-1 mb-4">Error 403</span>
                <h1 class="font-manrope font-extrabold text-[26px] md:text-[30px] text-[#171E26] mb-3">You don't have access to this page</h1>
                <p class="font-inter text-[14px] text-[#171E26]/60 leading-relaxed mb-7 max-w-[380px] mx-auto md:mx-0">
                    Your account role doesn't include this permission. If you think this is a mistake, contact your pharmacy owner or admin.
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