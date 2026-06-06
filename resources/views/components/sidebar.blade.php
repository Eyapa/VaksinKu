<!-- Backdrop for mobile -->
<div x-show="sidebarOpen" 
     class="fixed inset-0 bg-on-surface/50 z-40 md:hidden backdrop-blur-sm transition-opacity" 
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"></div>

<aside class="fixed left-0 top-0 h-full w-[240px] border-r border-border-light bg-surface-container-lowest flex flex-col py-6 px-4 z-50 transform transition-transform duration-300 ease-in-out -translate-x-full md:hidden"
       :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
    <div class="mb-10 flex items-center gap-3 px-2">
        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">health_and_safety</span>
        </div>
        <div>
            <h1 class="font-headline-sm text-headline-sm font-bold text-primary leading-tight">VaksinKu</h1>
            <p class="text-label-sm text-on-surface-variant">Layanan Kesehatan Desa</p>
        </div>
    </div>
    <nav class="flex-1 space-y-1">
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-primary-container text-white rounded-lg font-bold transition-all duration-200 active:scale-[0.98]' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-lg transition-colors duration-200' }}" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home</span>
            <span class="font-label-md text-label-md">Beranda</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('cari') ? 'bg-primary-container text-white rounded-lg font-bold transition-all duration-200 active:scale-[0.98]' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-lg transition-colors duration-200' }}" href="{{ route('cari') }}">
            <span class="material-symbols-outlined">search</span>
            <span class="font-label-md text-label-md">Cari Vaksin</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('riwayat.*') ? 'bg-primary-container text-white rounded-lg font-bold transition-all duration-200 active:scale-[0.98]' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-lg transition-colors duration-200' }}" href="{{ route('riwayat.index') }}">
            <span class="material-symbols-outlined">history</span>
            <span class="font-label-md text-label-md">Riwayat</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('keluarga.*') ? 'bg-primary-container text-white rounded-lg font-bold transition-all duration-200 active:scale-[0.98]' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-lg transition-colors duration-200' }}" href="{{ route('keluarga.index') }}">
            <span class="material-symbols-outlined">group</span>
            <span class="font-label-md text-label-md">Keluarga</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-lg transition-colors duration-200" href="#">
            <span class="material-symbols-outlined">campaign</span>
            <span class="font-label-md text-label-md">Pengumuman</span>
        </a>
    </nav>
    <div class="mt-auto space-y-1 border-t border-border-light pt-6">
        <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-primary transition-colors duration-200" href="{{ route('profile.edit') }}">
            <span class="material-symbols-outlined">person</span>
            <span class="font-label-md text-label-md">Profil</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" x-data>
            @csrf
            <a class="flex items-center gap-3 px-4 py-3 text-danger-red hover:bg-error-container/20 rounded-lg transition-colors duration-200 cursor-pointer" 
               @click.prevent="$root.submit();">
                <span class="material-symbols-outlined">logout</span>
                <span class="font-label-md text-label-md">Keluar</span>
            </a>
        </form>
    </div>
</aside>
