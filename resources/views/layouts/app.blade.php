<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VaksinKu') }}</title>

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bg-subtle font-body-md text-on-surface antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: false }">
    
    <!-- Side Navigation Bar (Mobile Only) -->
    @include('components.sidebar')

    <!-- Top Navigation Bar -->
    @include('components.topbar')

    <!-- Main Content -->
    <main class="flex-grow pt-24 pb-12 px-gutter max-w-container-max mx-auto w-full transition-all duration-300">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-low border-t border-border-light py-8 px-gutter mt-12 w-full transition-all duration-300">
        <div class="max-w-container-max mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex flex-col items-center md:items-start">
                <p class="font-label-md font-bold text-primary mb-1">VaksinKu</p>
                <p class="text-body-sm text-on-surface-variant">© {{ date('Y') }} VaksinKu Healthcare. All rights reserved.</p>
            </div>
            <div class="flex gap-8">
                <a class="text-on-surface-variant hover:text-primary hover:underline transition-colors font-body-sm" href="#">Bantuan</a>
                <a class="text-on-surface-variant hover:text-primary hover:underline transition-colors font-body-sm" href="#">Privasi</a>
                <a class="text-on-surface-variant hover:text-primary hover:underline transition-colors font-body-sm" href="#">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>

    <!-- FAB for quick help -->
    <div class="fixed bottom-8 right-8 z-50">
        <button class="w-14 h-14 bg-primary text-white rounded-full shadow-lg flex items-center justify-center hover:scale-105 transition-transform group">
            <span class="material-symbols-outlined text-3xl">chat</span>
            <span class="absolute right-full mr-4 bg-on-background text-white px-4 py-2 rounded-lg text-label-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Hubungi Admin</span>
        </button>
    </div>

</body>
</html>
