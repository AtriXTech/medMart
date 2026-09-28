{{--
    Intended path: resources/views/errors/419.blade.php

    NOTES:
    - Same standalone structure as 404/403.blade.php.
    - FLAGGED ASSUMPTION: "Log In Again" points to "/" — I don't have your
      actual staff/customer login route names, swap in the real ones
      (they likely differ per app, so this page may need to know which
      app the visitor came from, or you point both at a shared login
      chooser — up to you).
    - Illustration/copy deliberately plays on your own expiring-batches
      concept: an "EXPIRED" ribbon across a pill bottle plus a clock,
      with the line "Sessions have a shelf life too" — same joke logic
      as a batch expiry, just for login sessions.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session expired — MedMart</title>
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
                    <rect x="98" y="70" width="56" height="100" rx="14" fill="url(#grad419)"/>
                    <rect x="112" y="52" width="28" height="22" rx="5" fill="#058A98"/>
                    <rect x="105" y="95" width="42" height="50" rx="6" fill="white" opacity="0.85"/>
                    <g transform="rotate(-18 126 118)">
                        <rect x="76" y="112" width="100" height="16" fill="#9C3A32"/>
                        <text x="126" y="124" text-anchor="middle" font-family="Inter, sans-serif" font-weight="700" font-size="10" fill="white" letter-spacing="1">EXPIRED</text>
                    </g>
                    <g transform="translate(190 150)">
                        <circle r="26" fill="white" stroke="#DBEBFB" stroke-width="3"/>
                        <circle r="26" fill="none" stroke="#B1D0FB" stroke-width="3"/>
                        <line x1="0" y1="0" x2="0" y2="-15" stroke="#171E26" stroke-width="3" stroke-linecap="round"/>
                        <line x1="0" y1="0" x2="10" y2="6" stroke="#171E26" stroke-width="3" stroke-linecap="round"/>
                    </g>
                    <defs>
                        <linearGradient id="grad419" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#2775E4"/>
                            <stop offset="1" stop-color="#08AEBC"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div class="text-center md:text-left">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#DBEBFB] text-[#2775E4] text-[11px] font-bold tracking-wide uppercase px-3 py-1 mb-4">Error 419</span>
                <h1 class="font-manrope font-extrabold text-[26px] md:text-[30px] text-[#171E26] mb-3">Your session has expired</h1>
                <p class="font-inter text-[14px] text-[#171E26]/60 leading-relaxed mb-1.5 max-w-[380px] mx-auto md:mx-0">
                    For your security, you were logged out after a period of inactivity. Log in again to continue.
                </p>
                <p class="font-inter text-[12.5px] text-[#171E26]/35 italic mb-7">Sessions have a shelf life too.</p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <a href="/" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13.5px] shadow-sm shadow-[#2775E4]/20 hover:opacity-95 transition">Log In Again</a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>