<x-app-layout>
    <!-- Page Header with Action -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Manajemen Keluarga</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Kelola data vaksinasi dan profil anggota keluarga Anda dalam satu tempat.</p>
        </div>
        <a href="{{ route('keluarga.create') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-lg font-bold hover:bg-primary-container hover:text-on-primary-container transition-all shadow-sm active:scale-95">
            <span class="material-symbols-outlined">person_add</span>
            <span>Tambah Anggota Keluarga</span>
        </a>
    </div>

    <!-- Stats Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-border-light flex items-center gap-5">
            <div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[32px]">group</span>
            </div>
            <div>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider">Total Anggota</p>
                <p class="text-headline-md font-bold">{{ $stats['total'] }}</p>
                <p class="text-success-green text-label-sm flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                    Semua Terdaftar
                </p>
            </div>
        </div>
        
        <div class="md:col-span-2 bg-primary-container text-on-primary-container p-6 rounded-xl flex items-center justify-between relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-label-md font-bold mb-1">Cakupan Vaksinasi Keluarga</p>
                <p class="text-body-sm opacity-90">{{ $stats['selesai'] }} dari {{ $stats['total'] }} anggota keluarga sudah melengkapi vaksin.</p>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                @php
                    $percentage = $stats['total'] > 0 ? round(($stats['selesai'] / $stats['total']) * 100) : 0;
                    $dasharray = 175.9;
                    $dashoffset = $dasharray - ($dasharray * $percentage / 100);
                @endphp
                <div class="relative w-16 h-16 flex items-center justify-center">
                    <svg class="w-full h-full -rotate-90">
                        <circle cx="32" cy="32" fill="transparent" r="28" stroke="currentColor" stroke-opacity="0.2" stroke-width="6"></circle>
                        <circle cx="32" cy="32" fill="transparent" r="28" stroke="currentColor" stroke-dasharray="{{ $dasharray }}" stroke-dashoffset="{{ $dashoffset }}" stroke-width="6"></circle>
                    </svg>
                    <span class="absolute text-label-md font-bold">{{ $percentage }}%</span>
                </div>
            </div>
            <!-- Background Pattern -->
            <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
                <span class="material-symbols-outlined text-[120px] translate-x-1/4 -translate-y-1/4">vaccines</span>
            </div>
        </div>
    </div>

    <!-- Family Members Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($keluarga ?? [] as $anggota)
        <div class="bg-surface-container-lowest rounded-xl border border-border-light shadow-sm hover:shadow-md transition-shadow overflow-hidden group status-stripe-{{ str_replace('outline', 'gray-400', $anggota->status_color) }}" style="{{ $anggota->status_color === 'outline' ? 'border-left: 4px solid #c3c5d7;' : '' }}">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex gap-4">
                        <div class="w-14 h-14 rounded-full bg-primary-container/20 text-primary flex items-center justify-center text-xl font-bold">
                            {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface">{{ $anggota->nama }}</h3>
                            <p class="text-label-sm text-on-surface-variant font-medium">NIK: {{ $anggota->nik ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-{{ $anggota->status_color }}/10 text-{{ $anggota->status_color }} text-label-sm rounded-full font-bold">
                            {{ $anggota->status_vaksin }}
                        </span>
                        
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="text-outline hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('keluarga.edit', $anggota)">
                                    Edit
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('keluarga.destroy', $anggota) }}" id="delete-form-{{ $anggota->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="block w-full px-4 py-2 text-start text-body-md text-danger-red hover:bg-error-container hover:text-on-error-container focus:outline-none transition duration-150 ease-in-out"
                                            onclick="
                                            Swal.fire({
                                                title: 'Hapus Anggota Keluarga?',
                                                text: 'Data yang dihapus tidak dapat dikembalikan!',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#E02424',
                                                cancelButtonColor: '#1E3A5F',
                                                confirmButtonText: 'Ya, hapus!',
                                                cancelButtonText: 'Batal',
                                                background: '#F9FAFB',
                                                color: '#111928'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('delete-form-{{ $anggota->id }}').submit();
                                                }
                                            });">
                                        Hapus
                                    </button>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 py-4 border-y border-border-light/50 mb-4">
                    <div>
                        <p class="text-[10px] text-on-surface-variant uppercase font-bold mb-1">Hubungan</p>
                        <p class="text-label-md">{{ ucwords(str_replace('_', ' ', $anggota->hubungan)) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-on-surface-variant uppercase font-bold mb-1">Vaksin Terakhir</p>
                        <p class="text-label-md">{{ $anggota->vaksin_terakhir }}</p>
                    </div>
                </div>

                <a href="{{ route('riwayat.index', ['tab' => 'keluarga', 'anggota_id' => $anggota->id]) }}" class="w-full py-2 bg-surface-container text-primary rounded-lg font-bold text-label-md hover:bg-primary/5 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">history</span>
                    Lihat Riwayat
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <x-empty-state icon="family_restroom" title="Belum ada data keluarga" message="Tambahkan anggota keluarga Anda untuk memantau jadwal vaksinasinya." />
        </div>
        @endforelse
    </div>

    <!-- Protective Footer Card -->
    <div class="mt-12 bg-primary-container p-10 rounded-2xl relative overflow-hidden text-on-primary-container flex flex-col items-center text-center">
        <div class="max-w-2xl relative z-10">
            <h2 class="font-headline-lg text-headline-lg mb-4">Lindungi Keluarga, Lindungi Masa Depan</h2>
            <p class="font-body-md text-body-md opacity-90 mb-8">Pastikan seluruh anggota keluarga mendapatkan dosis vaksinasi lengkap sesuai jadwal untuk menjaga imunitas kelompok di lingkungan Anda.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('riwayat.index') }}" class="px-8 py-3 bg-surface-container-lowest text-primary rounded-full font-bold hover:bg-surface transition-all">Jadwal Vaksinasi</a>
            </div>
        </div>
        <!-- Decorative Element -->
        <span class="material-symbols-outlined absolute -bottom-10 -right-10 text-[240px] opacity-10 rotate-12">medical_services</span>
        <span class="material-symbols-outlined absolute -top-10 -left-10 text-[180px] opacity-10 -rotate-12">health_and_safety</span>
    </div>
</x-app-layout>
