 <x-filament-widgets::widget>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">
    <style>
        .font-manrope { font-family: 'Manrope', sans-serif; }
        .font-inter { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
   
    <div class="bg-white border border-[#EAF1FB] rounded-2xl p-5 md:p-6 shadow-sm">
        
        <div>
            <h3 class="font-manrope font-bold text-[17px] text-[#171E26]">
                Orders by Status
            </h3>

            <p class="font-inter text-[12px] text-[#171E26]/50 mt-0.5">
                Distribution across backend lifecycle
            </p>

            <div class="mt-5 space-y-3 font-inter">

                @foreach ($orders as $order)

                    <div>
                        <div class="flex justify-between text-[12px] mb-1">
                            <span class="font-medium text-[#171E26]">
                                {{ $order['label'] }}
                            </span>

                            <span class="font-semibold text-[#171E26]">
                                {{ number_format($order['count']) }}
                            </span>
                        </div>

                        <div class="h-2 w-full bg-[#EAF1FB] rounded-full overflow-hidden">
                            <div
                                class="h-full rounded-full transition-all duration-500"
                                style="
                                    width: {{ $order['percentage'] }}%;
                                    background-color: {{ $order['color'] }};
                                "
                            ></div>
                        </div>
                    </div>

                @endforeach

            </div>
        </div>

    </div>
    

</body></html>

</x-filament-widgets::widget>