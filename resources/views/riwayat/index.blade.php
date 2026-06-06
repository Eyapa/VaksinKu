<x-app-layout>
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <h2 class="font-headline-sm text-headline-sm text-primary">Riwayat Vaksinasi</h2>
        </div>
        <button class="bg-primary text-white px-4 py-2 rounded-lg font-bold hover:bg-primary-container transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span> Tambah Riwayat
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Timeline -->
        <div class="lg:col-span-8 space-y-6">
            @forelse($riwayats ?? [] as $riwayat)
            <div class="relative pl-12 pb-2">
                <div class="absolute left-4 top-0 bottom-0 w-px bg-border-light"></div>
                <div class="absolute left-0 top-0 w-8 h-8 rounded-full {{ $riwayat->status === 'Selesai' ? 'bg-primary-container/40' : 'bg-warning-orange/20' }} flex items-center justify-center border-4 border-surface z-10">
                    <span class="material-symbols-outlined text-sm {{ $riwayat->status === 'Selesai' ? 'text-on-primary-container' : 'text-warning-orange' }}" style="font-variation-settings: 'FILL' 1;">
                        {{ $riwayat->status === 'Selesai' ? 'check_circle' : 'schedule' }}
                    </span>
                </div>
                
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-border-light shadow-sm status-stripe-{{ $riwayat->status === 'Selesai' ? 'success' : 'warning' }} transition-all hover:shadow-md">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full {{ $riwayat->status === 'Selesai' ? 'bg-success-green/10 text-success-green' : 'bg-warning-orange/10 text-warning-orange' }} text-label-sm font-bold mb-2">
                                {{ $riwayat->status }}
                            </span>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface">{{ $riwayat->vaksin->nama ?? 'Vaksin' }}</h3>
                            <p class="text-body-sm text-outline">{{ $riwayat->faskes->nama ?? 'Faskes' }} • {{ $riwayat->tanggal_vaksin->translatedFormat('d F Y') }}</p>
                        </div>
                        @if($riwayat->status === 'Selesai')
                        <button class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-label-md transition-all active:scale-95">
                            <span class="material-symbols-outlined text-sm">download</span> Unduh
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <x-empty-state icon="history" title="Belum ada riwayat" message="Anda belum memiliki data riwayat vaksinasi." />
            @endforelse
            
            @if(isset($riwayats) && $riwayats->hasPages())
                <div class="mt-6">
                    {{ $riwayats->links() }}
                </div>
            @endif
        </div>

        <!-- Right Column: Status & Cards -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-surface-container-lowest p-6 rounded-xl border border-border-light shadow-sm text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16"></div>
                <h4 class="font-headline-sm text-headline-sm text-on-surface mb-6">Status Kesehatan</h4>
                <div class="relative inline-flex items-center justify-center mb-6">
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle class="text-surface-container-high" cx="64" cy="64" fill="transparent" r="58" stroke="currentColor" stroke-width="8"></circle>
                        <circle class="text-success-green" cx="64" cy="64" fill="transparent" r="58" stroke="currentColor" stroke-dasharray="364.4" stroke-dashoffset="54.6" stroke-linecap="round" stroke-width="8"></circle>
                    </svg>
                    <span class="absolute text-headline-md font-bold text-on-surface">85%</span>
                </div>
                <p class="text-body-sm text-on-surface-variant mb-6 px-4">Profil kesehatan Anda tergolong lengkap dan aman.</p>
                <div class="flex justify-between items-center py-3 border-t border-border-light">
                    <span class="text-label-md text-outline">Total Vaksin</span>
                    <span class="text-headline-sm text-primary font-bold">{{ $riwayats ? $riwayats->total() : 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
