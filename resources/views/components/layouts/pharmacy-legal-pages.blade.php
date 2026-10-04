<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Disclaimer | MedMart</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.2"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

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
<body  class="bg-white">

    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-5 sm:px-6 lg:px-8">

            <!-- Logo -->
          <img src="{{asset('images/logo.png')}}" alt="logo" class="h-[70px] w-[100px] md:h-[60px] md:w-[110px]">

            <!-- Back -->
            <a
                href="{{ route('legalPages') }}"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-medmart-hero hover:text-medmart-blue"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>

                Back to MedMart
            </a>

        </div>
    </header>

{{ $slot }}   

<footer class="border-t border-slate-200 bg-slate-50">

        <div class="mx-auto max-w-7xl px-5 py-5 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <img src="{{asset('images/logo.png')}}" alt="logo" class="h-[70px] w-[100px] md:h-[60px] md:w-[110px]">


                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-slate-500">

                    <a
                        href="{{ route('subscriptionAndBilling') }}"
                        class="font-semibold "
                    >
                       Subscription & Billing
                    </a>

                    <a
                        href="{{ route('termsAndConditional') }}"
                        class="transition hover:text-medmart-blue"
                    >
                        Terms & Conditions
                    </a>

                    <a
                        href="{{ route('privacyAndDataProcessing') }}"
                        class="transition hover:text-medmart-blue"
                    >
                        Privacy & Data Processing
                    </a>
                </div>


                <p class="text-xs text-slate-400">
                   &copy; <?php echo date("Y"); ?> MedMart. All rights reserved.
                </p>

            </div>

        </div>

    </footer>
</body>
</html>