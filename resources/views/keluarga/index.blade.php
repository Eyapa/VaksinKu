<x-app-layout>
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <h2 class="font-headline-sm text-headline-sm text-primary">Anggota Keluarga</h2>
        </div>
        <button class="bg-primary text-white px-4 py-2 rounded-lg font-bold hover:bg-primary-container transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">person_add</span> Tambah Anggota
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($keluarga ?? [] as $anggota)
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-border-light shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-primary-container/20 text-primary rounded-full flex items-center justify-center text-xl font-bold">
                        {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-headline-sm text-on-surface">{{ $anggota->nama }}</h3>
                        <p class="text-body-sm text-on-surface-variant">{{ ucfirst($anggota->hubungan) }}</p>
                    </div>
                </div>
                <button class="text-outline hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">more_vert</span>
                </button>
            </div>
            
            <div class="space-y-2 mt-6">
                <div class="flex justify-between text-body-sm">
                    <span class="text-outline">NIK</span>
                    <span class="text-on-surface font-medium">{{ $anggota->nik }}</span>
                </div>
                <div class="flex justify-between text-body-sm">
                    <span class="text-outline">Tanggal Lahir</span>
                    <span class="text-on-surface font-medium">{{ $anggota->tanggal_lahir->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-border-light flex justify-end">
                <button class="text-primary hover:text-primary-container font-bold text-label-md flex items-center gap-1">
                    Lihat Rekam Medis <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <x-empty-state icon="family_restroom" title="Belum ada data keluarga" message="Tambahkan anggota keluarga Anda untuk memantau jadwal vaksinasinya." />
        </div>
        @endforelse
    </div>
</x-app-layout>
