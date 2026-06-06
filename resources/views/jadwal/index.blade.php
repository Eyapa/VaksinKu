<x-app-layout>
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <h2 class="font-headline-sm text-headline-sm text-primary">Jadwal Imunisasi</h2>
        </div>
        <button class="bg-primary text-white px-4 py-2 rounded-lg font-bold hover:bg-primary-container transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">edit_calendar</span> Tambah Jadwal
        </button>
    </div>

    <div class="bg-surface-container-lowest p-6 rounded-xl border border-border-light shadow-sm">
        @forelse($jadwals ?? [] as $jadwal)
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center py-4 {{ !$loop->last ? 'border-b border-border-light' : '' }}">
            <div class="flex gap-4">
                <div class="w-12 h-12 bg-primary/10 text-primary rounded-lg flex flex-col items-center justify-center font-bold">
                    <span class="text-xs">{{ $jadwal->tanggal_jadwal->format('M') }}</span>
                    <span class="text-lg leading-none">{{ $jadwal->tanggal_jadwal->format('d') }}</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-on-surface">{{ $jadwal->vaksin->nama ?? 'Vaksin' }}</h3>
                    <p class="text-body-sm text-on-surface-variant">Untuk: {{ $jadwal->anggotaKeluarga->nama ?? 'Anggota Keluarga' }}</p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-4">
                <x-status-badge :status="$jadwal->status" />
                <button class="text-primary hover:bg-primary/10 p-2 rounded-full transition-colors" title="Edit Jadwal">
                    <span class="material-symbols-outlined text-sm">edit</span>
                </button>
            </div>
        </div>
        @empty
        <x-empty-state icon="calendar_today" title="Tidak ada jadwal" message="Belum ada jadwal imunisasi yang akan datang." />
        @endforelse

        @if(isset($jadwals) && $jadwals->hasPages())
            <div class="mt-6">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
