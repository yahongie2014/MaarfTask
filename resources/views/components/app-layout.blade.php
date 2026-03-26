<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('YouTube Course Scraper') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', 'Cairo', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    @livewireStyles
</head>

<body class="bg-[#0f172a] text-white antialiased">
    <nav class="border-b border-white/5 bg-[#1e293b]/50 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span class="text-red-500 font-bold text-xl mr-2 ml-2">YT</span>
                    <span class="font-semibold text-lg hidden sm:block">{{ __('YouTube Course Scraper') }}</span>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="?lang={{ app()->getLocale() == 'ar' ? 'en' : 'ar' }}"
                        style="background: rgba(255, 255, 255, 0.1); color: rgb(255, 255, 255); border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; text-decoration: none;"><svg
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-globe" aria-hidden="true"
                            style="vertical-align: middle; margin: 0px 5px;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                            <path d="M2 12h20"></path>
                        </svg>{{ app()->getLocale() == 'ar' ? 'EN' : 'AR' }}</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="min-h-screen">
        {{ $slot }}
    </div>
    @livewireScripts
</body>

</html>