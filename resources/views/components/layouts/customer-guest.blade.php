@props(['title' => 'MedMart'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} · MedMart</title>
    <link rel="stylesheet" href="{{ asset('assets/minimal/css/app.css') }}">
</head>
<body>
    <div class="guest-wrap">
        <div class="guest-card">
            <h1>{{ $title }}</h1>
            {{ $subtitle ?? '' }}
            {{ $slot }}
        </div>
    </div>
    <script src="{{ asset('assets/minimal/js/customer/api.js') }}"></script>
    <script src="{{ asset('assets/minimal/js/customer/auth.js') }}"></script>
    {{ $scripts ?? '' }}
</body>
</html>