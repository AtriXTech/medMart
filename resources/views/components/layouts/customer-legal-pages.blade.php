<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title }}. | MedMart</title>

    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.2"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        medmart: {
                            blue: '#2775E4',
                            teal: '#08AEBC',
                            darkTeal: '#058A98',
                            hero: '#E9F3FE',
                            shape: '#B1D0FB',
                            light: '#DBEBFB',
                            text: '#171E26',
                        }
                    },
                    fontFamily: {
                        manrope: ['Manrope', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #171E26;
        }

        .font-manrope {
            font-family: 'Manrope', sans-serif;
        }

        .legal-content h2 {
            font-family: 'Manrope', sans-serif;
            font-size: 1.35rem;
            line-height: 1.4;
            font-weight: 700;
            color: #171E26;
            margin-top: 2.75rem;
            margin-bottom: 1rem;
            scroll-margin-top: 100px;
        }

        .legal-content h3 {
            font-family: 'Manrope', sans-serif;
            font-size: 1.05rem;
            line-height: 1.5;
            font-weight: 700;
            color: #171E26;
            margin-top: 1.75rem;
            margin-bottom: 0.65rem;
            scroll-margin-top: 100px;
        }

        .legal-content p {
            font-size: 0.95rem;
            line-height: 1.8;
            color: #475569;
            margin-bottom: 1rem;
        }

        .legal-content ul {
            margin: 1rem 0 1.25rem 1.25rem;
            list-style-type: disc;
        }

        .legal-content li {
            font-size: 0.95rem;
            line-height: 1.8;
            color: #475569;
            padding-left: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .legal-content strong {
            color: #334155;
            font-weight: 600;
        }

        .legal-content a {
            color: #2775E4;
            font-weight: 500;
        }

        .legal-content a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="bg-white">

    <!-- Header -->
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-5 sm:px-6 lg:px-8">

            <!-- Logo -->
            <a href="{{ route('extra') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="MedMart logo" class="h-[48px] w-auto">
            </a>

            <!-- Back -->
            <a href="{{ route('extra') }}"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-[#2775E4]">
                <i class="ph ph-arrow-left text-base"></i>
                <span class="hidden sm:inline">Back to MedMart</span>
                <span class="sm:hidden">Back</span>
            </a>

        </div>
    </header>

    {{ $slot }}


    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white">

        <div class="mx-auto max-w-7xl px-5 py-8 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <img src="{{ asset('images/logo.png') }}" alt="MedMart logo" class="h-[48px] w-auto">
                    <p class="mt-2 text-xs text-slate-500">
                        Your pharmacy, just a few taps away.
                    </p>
                </div>


                <nav class="flex flex-wrap gap-x-5 gap-y-2 text-xs font-medium sm:max-w-md sm:justify-end">

                    <a href="{{ route('privacyPolicy') }}"
                        class="transition hover:text-[#2775E4] {{ request()->routeIs('privacyPolicy') ? 'font-semibold text-[#2775E4]' : 'text-slate-500' }}">
                        Privacy Policy
                    </a>

                    <a href="{{ route('termsAndCondition') }}"
                        class="transition hover:text-[#2775E4] {{ request()->routeIs('termsAndCondition') ? 'font-semibold text-[#2775E4]' : 'text-slate-500' }}">
                        Terms &amp; Conditions
                    </a>

                    <a href="{{ route('refundAndCancellationPolicy') }}"
                        class="transition hover:text-[#2775E4] {{ request()->routeIs('refundAndCancellationPolicy') ? 'font-semibold text-[#2775E4]' : 'text-slate-500' }}">
                        Refund And Cancellation Policy
                    </a>

                    <a href="{{ route('cookiePolicy') }}"
                        class="transition hover:text-[#2775E4] {{ request()->routeIs('cookiePolicy') ? 'font-semibold text-[#2775E4]' : 'text-slate-500' }}">
                        Cookie Policy
                    </a>

                    <a href="{{ route('disclaimer') }}"
                        class="transition hover:text-[#2775E4] {{ request()->routeIs('disclaimer') ? 'font-semibold text-[#2775E4]' : 'text-slate-500' }}">
                        Disclaimer
                    </a>

                </nav>

            </div>


            <div class="mt-6 border-t border-slate-100 pt-5">

                <p class="text-xs text-slate-400">
                    © 2026 MedMart. All rights reserved.
                </p>

            </div>

        </div>
    </footer>

</body>

</html>
