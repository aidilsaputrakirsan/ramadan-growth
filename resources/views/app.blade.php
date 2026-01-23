<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Ramadan Growth') }}</title>
    <meta name="description" content="Aplikasi tracking ibadah Ramadan - Catat shalat, puasa, tarawih, tilawah Al-Quran, dan amal sunnah lainnya.">

    <!-- Open Graph / WhatsApp / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="{{ config('app.name', 'Ramadan Growth') }} - Tracking Ibadah Ramadan">
    <meta property="og:description" content="Catat dan pantau ibadah harian Ramadan. Shalat 5 waktu, puasa, tarawih, tilawah Al-Quran, sedekah, dan amal sunnah lainnya.">
    <meta property="og:image" content="{{ config('app.url') }}/images/og-image.png">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('app.name', 'Ramadan Growth') }} - Tracking Ibadah Ramadan">
    <meta name="twitter:description" content="Catat dan pantau ibadah harian Ramadan. Shalat, puasa, tarawih, tilawah, dan amal sunnah lainnya.">
    <meta name="twitter:image" content="{{ config('app.url') }}/images/og-image.png">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" href="/favicon.ico">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Lordicon Animated Icons -->
    <script src="https://cdn.lordicon.com/lordicon.js"></script>

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>