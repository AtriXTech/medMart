@props(['title' => 'MedMart'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{ $title }} · MedMart Staff</title> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/minimal/css/app.css') }}"> --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
<body  class="font-inter text-[#171E26] bg-[#E9F3FE] min-h-screen">
    <div class="guest-wrap">
        <div class="guest-card">
            {{-- <h1>{{ $title }}</h1>
            {{ $subtitle ?? '' }} --}}
            {{ $slot }}
        </div>
    </div>
    <script src="{{ asset('assets/minimal/js/api.js') }}"></script>
    <script src="{{ asset('assets/minimal/js/auth.js') }}"></script>
    {{ $scripts ?? '' }}
</body>
</html>