<header class="fixed top-0 left-0 w-full h-16 bg-surface border-b border-border-light z-40 flex justify-between items-center px-gutter">
    <div class="flex items-center gap-6">
        <!-- Hamburger Menu Button (Mobile Only) -->
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-on-surface-variant hover:bg-surface-container rounded-lg focus:outline-none">
            <span class="material-symbols-outlined">menu</span>
        </button>
        
        <!-- Logo (Visible on Desktop) -->
        <div class="hidden md:flex items-center gap-3">
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-sm" style="font-variation-settings: 'FILL' 1;">health_and_safety</span>
            </div>
            <span class="font-headline-sm text-lg font-bold text-primary">VaksinKu</span>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex gap-6 ml-4">
            <a class="{{ request()->routeIs('dashboard') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-all' }} font-bold font-label-md text-label-md" href="{{ route('dashboard') }}">Beranda</a>
            <a class="{{ request()->routeIs('cari') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-all' }} font-bold font-label-md text-label-md" href="{{ route('cari') }}">Cari Vaksin</a>
            <a class="{{ request()->routeIs('riwayat.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-all' }} font-bold font-label-md text-label-md" href="{{ route('riwayat.index') }}">Riwayat</a>
            <a class="{{ request()->routeIs('keluarga.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-all' }} font-bold font-label-md text-label-md" href="{{ route('keluarga.index') }}">Keluarga</a>
        </nav>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative flex items-center bg-surface-container rounded-full px-4 py-1.5 w-64">
            <span class="material-symbols-outlined text-outline text-sm">search</span>
            <input class="bg-transparent border-none focus:ring-0 text-body-sm w-full py-0" placeholder="Cari layanan..." type="text">
        </div>
        <button class="p-2 text-on-surface-variant hover:bg-surface-container rounded-full relative">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border border-surface"></span>
        </button>
        <div class="h-8 w-px bg-border-light mx-1"></div>
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <div class="flex items-center gap-3 pl-2 cursor-pointer" @click="open = !open">
                <div class="text-right hidden sm:block">
                    <p class="font-label-md text-label-md text-on-surface">{{ explode(' ', Auth::user()->name)[0] }}</p>
                    <p class="text-label-sm text-on-surface-variant">NIK: {{ substr(Auth::user()->nik ?? '3275000000', 0, 4) }}****</p>
                </div>
                <div class="w-10 h-10 rounded-full border-2 border-primary-container bg-primary flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="absolute right-0 mt-2 w-48 bg-surface border border-border-light rounded-md shadow-lg py-1 z-50" 
                 style="display: none;">
                
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-on-surface hover:bg-surface-container transition-colors">Profile</a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-danger-red hover:bg-error-container transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
