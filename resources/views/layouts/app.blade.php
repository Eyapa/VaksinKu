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
    @stack('styles')
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

    <!-- Certificate Modal -->
    <div x-data="{ show: false, url: '' }" 
         x-init="$watch('show', val => { if (!val) setTimeout(() => url = '', 300) })"
         @open-certificate.window="url = $event.detail.url; show = true;"
         x-show="show" 
         class="fixed inset-0 flex items-center justify-center" 
         style="display: none; z-index: 9999;">
        
        <!-- Backdrop -->
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-inverse-surface/60 backdrop-blur-sm"
             @click="show = false"></div>
             
        <!-- Modal Content -->
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="relative w-full max-w-[800px] p-4 z-[101]">
             
            <div class="bg-surface-container-lowest rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center p-6 border-b border-border-light">
                    <h3 class="text-headline-sm font-headline-sm text-on-surface">Sertifikat Vaksin</h3>
                    <button class="text-on-surface-variant hover:text-primary transition-colors" @click="show = false">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <div class="flex-1 p-6 bg-surface-container-low">
                    <div class="w-full h-[500px] bg-white border border-border-light rounded-lg flex items-center justify-center overflow-hidden">
                        <template x-if="url">
                            <object :data="url" type="application/pdf" class="w-full h-full border-0" x-on:error="window.open(url, '_blank'); show = false;">
                                <iframe :src="url" x-on:error="window.open(url, '_blank'); show = false;" class="w-full h-full border-0"></iframe>
                            </object>
                        </template>
                        <template x-if="!url">
                            <div class="text-center">
                                <span class="material-symbols-outlined text-[64px] text-outline mb-4">picture_as_pdf</span>
                                <p class="text-body-md text-on-surface-variant">Sertifikat Digital tidak tersedia</p>
                            </div>
                        </template>
                    </div>
                </div>
                
                <div class="p-6 border-t border-border-light flex justify-end gap-4">
                    <button class="px-6 py-2 text-primary font-label-md hover:bg-primary/5 rounded-lg transition-colors" @click="show = false">Tutup</button>
                    <a :href="url" download target="_blank" rel="noopener noreferrer" class="px-6 py-2 bg-primary text-on-primary font-label-md rounded-lg hover:shadow-md transition-all active:scale-95 flex items-center gap-2" x-show="url">
                        <span class="material-symbols-outlined text-sm">download</span>
                        Unduh PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
