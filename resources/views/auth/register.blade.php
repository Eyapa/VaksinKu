<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar Akun - {{ config('app.name', 'VaksinKu') }}</title>

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <style>
        .status-stripe {
            border-left: 4px solid #1A56DB;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bg-subtle font-body-md text-on-surface antialiased min-h-screen flex flex-col">
    
    <!-- TopAppBar -->
    <header class="w-full top-0 sticky bg-surface border-b border-border-light z-50">
        <div class="flex justify-between items-center px-6 py-4 max-w-container-max mx-auto">
            <a href="/" class="font-headline-md text-[24px] text-primary font-bold cursor-pointer hover:opacity-80 transition-opacity">
                VaksinKu
            </a>
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="text-primary font-bold hover:text-primary-dark transition-colors cursor-pointer font-label-md">
                    Masuk
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-12">
        <div class="max-w-container-max w-full px-6 flex flex-col lg:flex-row gap-8 items-stretch">
            
            <!-- Visual Section (Banner) -->
            <div class="hidden lg:flex lg:w-1/2 flex-col justify-center p-12 bg-primary text-white rounded-xl relative overflow-hidden shadow-lg">
                <div class="relative z-10">
                    <h1 class="font-headline-lg text-[32px] font-bold mb-6 leading-tight">Wujudkan Keluarga Sehat dengan VaksinKu</h1>
                    <p class="font-body-lg opacity-90 mb-10">Platform terpadu untuk manajemen imunisasi keluarga yang aman, terpercaya, dan profesional. Daftarkan diri Anda sekarang untuk kemudahan akses kesehatan.</p>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined p-3 bg-white/20 rounded-lg text-[28px]">verified_user</span>
                            <span class="font-label-md font-bold text-[16px]">Data Terverifikasi NIK Nasional</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined p-3 bg-white/20 rounded-lg text-[28px]">notifications_active</span>
                            <span class="font-label-md font-bold text-[16px]">Pengingat Jadwal Vaksin Otomatis</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined p-3 bg-white/20 rounded-lg text-[28px]">history_edu</span>
                            <span class="font-label-md font-bold text-[16px]">Riwayat Imunisasi Digital Terpusat</span>
                        </div>
                    </div>
                </div>
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-20 pointer-events-none">
                    <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHbmT3su58ZrC3EYoQO9rVvEXb6xPWuMIFoVbC0Ub5b55JngKCUwF06DfB8XboaaMst11MHW1rsMJjM_NX1_FgQJKPauUQCU0dcGivyLF74Fd9CiMfRRACazaYhzGPUNL0__pIYRIHhiXWjslHdTNkYKi0Dq9nLtUbKzYneDTDLuLfDS_wZ-vqtc_sixhVq50k9RvJ1XHqq5FzprMW0PvXkZrvTP4T1ERAvwo3Npxrp6xO8srrHU7hWnrqvo8TplKnXLBBWOyXX25N" alt="Hospital Background">
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-primary/90 to-transparent"></div>
            </div>

            <!-- Form Section -->
            <div class="w-full lg:w-1/2 bg-surface-container-lowest p-8 md:p-10 rounded-xl shadow-sm border border-border-light status-stripe">
                <div class="mb-8">
                    <h2 class="font-headline-md text-[24px] font-bold text-on-surface">Daftar Akun</h2>
                    <p class="font-body-sm text-on-surface-variant mt-2">Lengkapi data diri sesuai KTP untuk memulai pendaftaran.</p>
                </div>
                
                <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ role: '{{ old('hubungan', 'Kepala Keluarga') }}' }">
                    @csrf
                    
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block font-label-md font-bold text-on-surface-variant mb-2" for="name">Nama Lengkap</label>
                        <input class="w-full px-4 py-3 rounded-lg border border-border-light focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md bg-white" id="name" name="name" placeholder="Masukkan nama lengkap sesuai KTP" type="text" value="{{ old('name') }}" required autofocus autocomplete="name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    
                    <!-- NIK -->
                    <div>
                        <label class="block font-label-md font-bold text-on-surface-variant mb-2" for="nik">NIK (16 Digit)</label>
                        <input class="w-full px-4 py-3 rounded-lg border border-border-light focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md bg-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" id="nik" name="nik" placeholder="Contoh: 3201234567890001" type="number" value="{{ old('nik') }}" required>
                        <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block font-label-md font-bold text-on-surface-variant mb-2" for="tanggal_lahir">Tanggal Lahir</label>
                            <input class="w-full px-4 py-3 rounded-lg border border-border-light focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md bg-white" id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir') }}" required>
                            <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                        </div>
                        
                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block font-label-md font-bold text-on-surface-variant mb-2" for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-4 py-3 rounded-lg border border-border-light focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md bg-white" required>
                                <option disabled value="" {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih jenis kelamin...</option>
                                <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Peran dalam Keluarga -->
                    <div>
                        <label class="block font-label-md font-bold text-on-surface-variant mb-2" for="hubungan">Peran dalam Keluarga</label>
                        <select x-model="role" class="w-full px-4 py-3 rounded-lg border border-border-light focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md bg-white" id="hubungan" name="hubungan" required>
                            <option disabled value="">Pilih peran...</option>
                            <option value="Kepala Keluarga">Kepala Keluarga</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Orang Tua">Orang Tua</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <x-input-error :messages="$errors->get('hubungan')" class="mt-2" />
                    </div>

                    <!-- Additional Field for 'Lainnya' -->
                    <div x-show="role === 'Lainnya'" x-transition class="mt-2">
                        <label class="block font-label-md font-bold text-on-surface-variant mb-2" for="other_role">Sebutkan Peran Lainnya</label>
                        <input class="w-full px-4 py-3 rounded-lg border border-border-light focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md bg-white" id="other_role" name="hubungan_lainnya" placeholder="Contoh: Wali" type="text" value="{{ old('hubungan_lainnya') }}">
                        <x-input-error :messages="$errors->get('hubungan_lainnya')" class="mt-2" />
                    </div>
                    
                    <!-- Akun Login Info -->
                    <div class="pt-5 mt-5 border-t border-border-light">
                        <h3 class="font-label-md font-bold text-on-surface mb-4">Informasi Akun (Untuk Login)</h3>
                        
                        <div class="space-y-5">
                            <!-- Email -->
                            <div>
                                <label class="block font-label-md font-bold text-on-surface-variant mb-2" for="email">Email</label>
                                <input class="w-full px-4 py-3 rounded-lg border border-border-light focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md bg-white" id="email" name="email" placeholder="nama@email.com" type="email" value="{{ old('email') }}" required autocomplete="username">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Password -->
                                <div>
                                    <label class="block font-label-md font-bold text-on-surface-variant mb-2" for="password">Kata Sandi</label>
                                    <input class="w-full px-4 py-3 rounded-lg border border-border-light focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md bg-white" id="password" name="password" type="password" required autocomplete="new-password">
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                
                                <!-- Confirm Password -->
                                <div>
                                    <label class="block font-label-md font-bold text-on-surface-variant mb-2" for="password_confirmation">Konfirmasi Sandi</label>
                                    <input class="w-full px-4 py-3 rounded-lg border border-border-light focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md bg-white" id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button class="w-full py-3.5 bg-primary text-white font-bold rounded-lg hover:bg-primary-dark active:scale-95 transition-all shadow-md" type="submit">
                            Daftar Sekarang
                        </button>
                        <div class="text-center mt-6">
                            <span class="text-on-surface-variant font-body-sm">Sudah punya akun? </span>
                            <a class="text-primary font-bold font-label-md hover:underline" href="{{ route('login') }}">Masuk</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>
</html>
