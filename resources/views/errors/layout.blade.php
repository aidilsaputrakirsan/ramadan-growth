<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Ramadan Tracker</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            950: '#022c22',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }

        .error-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="antialiased h-screen overflow-hidden bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900">
    <div class="relative min-h-screen flex flex-col items-center justify-center p-6 sm:p-12">

        <!-- Decorative Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-lg text-center">
            <!-- Logo Section -->
            <div class="mb-8 flex justify-center">
                <div
                    class="w-24 h-24 bg-slate-900 rounded-full flex items-center justify-center border-2 border-emerald-500/30">
                    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" class="w-16 h-16">
                        <defs>
                            <linearGradient id="domeGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#34d399" />
                                <stop offset="100%" style="stop-color:#059669" />
                            </linearGradient>
                        </defs>
                        <circle cx="32" cy="32" r="30" fill="url(#domeGradient)" opacity="0.1" />
                        <rect x="16" y="40" width="32" height="12" rx="2" fill="url(#domeGradient)" />
                        <ellipse cx="32" cy="40" rx="14" ry="12" fill="url(#domeGradient)" />
                        <rect x="12" y="32" width="5" height="20" rx="1" fill="url(#domeGradient)" />
                        <circle cx="49.5" cy="32" r="3" fill="url(#domeGradient)" />
                        <rect x="28" y="44" width="8" height="8" rx="4" ry="4" fill="#0f172a" />
                    </svg>
                </div>
            </div>

            <div class="error-card p-10 rounded-3xl shadow-2xl">
                <h1 class="text-7xl font-black text-emerald-400 mb-4 tracking-tight">@yield('code')</h1>
                <h2 class="text-2xl font-bold text-white mb-6">@yield('message')</h2>
                <p class="text-emerald-300/60 text-base leading-relaxed mb-10">
                    @yield('description')
                </p>

                <a href="/"
                    class="inline-flex items-center justify-center px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-2xl transition-all active:scale-[0.98] shadow-lg shadow-emerald-500/30">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <p class="mt-8 text-emerald-300/40 text-xs">
                &copy; {{ date('Y') }} Ramadan Tracker. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>