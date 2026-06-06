<x-app-layout>
    <!-- Page Header with Action -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Riwayat Vaksinasi</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Pantau jadwal dan riwayat vaksinasi seluruh anggota keluarga Anda.</p>
        </div>
        <button class="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-lg font-bold hover:bg-primary-container hover:text-on-primary-container transition-all shadow-sm active:scale-95">
            <span class="material-symbols-outlined">add</span>
            <span>Tambah Riwayat</span>
        </button>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex gap-8 border-b border-border-light mb-8">
        <a href="{{ route('riwayat.index', ['tab' => 'saya']) }}" class="pb-3 {{ $tab === 'saya' ? 'border-b-2 border-primary text-primary font-bold' : 'text-on-surface-variant hover:text-primary transition-colors font-medium' }}">Riwayat Saya</a>
        <a href="{{ route('riwayat.index', ['tab' => 'keluarga']) }}" class="pb-3 {{ $tab === 'keluarga' ? 'border-b-2 border-primary text-primary font-bold' : 'text-on-surface-variant hover:text-primary transition-colors font-medium' }}">Riwayat Keluarga</a>
    </div>

    @if($tab === 'keluarga')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #D1D5DB;
        }
    </style>
    <!-- Member Selector -->
    <div class="flex items-center justify-between gap-2 md:gap-4 mb-8 w-full min-w-0 overflow-hidden">
        <div class="relative flex-1 min-w-0 w-full overflow-hidden">
            <!-- Scrollable Area -->
            <div class="flex items-center gap-4 overflow-x-auto pb-6 pt-2 px-2 custom-scrollbar w-full snap-x snap-mandatory"
                 x-data
                 x-init="$nextTick(() => {
                    let selected = document.getElementById('selected-member');
                    if (selected && window.innerWidth < 768) {
                        selected.scrollIntoView({behavior: 'smooth', inline: 'center', block: 'nearest'});
                    }
                 })">
                @foreach($anggotaKeluargaList as $anggota)
                    @if($selectedAnggota && $selectedAnggota->id === $anggota->id)
                        <button id="selected-member" class="snap-center flex items-center gap-3 px-6 py-3 bg-primary text-white rounded-2xl shadow-md transition-all whitespace-nowrap min-w-max border border-primary relative z-10">
                            <div class="w-12 h-12 shrink-0 rounded-full border border-white text-white flex items-center justify-center font-bold text-headline-sm">
                                {{ substr($anggota->nama, 0, 1) }}
                            </div>
                            <div class="text-left pr-4">
                                <span class="font-label-lg block">{{ $anggota->nama }}</span>
                                <span class="text-xs font-medium opacity-90">{{ ucwords(str_replace('_', ' ', $anggota->hubungan)) }}</span>
                            </div>
                        </button>
                    @else
                        <a href="{{ route('riwayat.index', ['tab' => 'keluarga', 'anggota_id' => $anggota->id]) }}" class="snap-center flex items-center gap-3 px-6 py-3 bg-white border border-border-light rounded-2xl hover:bg-surface-container-lowest transition-all whitespace-nowrap min-w-max relative z-10 shadow-sm">
                            <div class="w-12 h-12 shrink-0 rounded-full bg-[#f3f4f6] text-[#374151] flex items-center justify-center font-bold text-headline-sm">
                                {{ substr($anggota->nama, 0, 1) }}
                            </div>
                            <div class="text-left pr-4">
                                <span class="font-label-lg text-on-surface block">{{ $anggota->nama }}</span>
                                <span class="text-xs font-medium text-[#4B5563]">{{ ucwords(str_replace('_', ' ', $anggota->hubungan)) }}</span>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
            
            <!-- Blur / Shadow Edges -->
            <div class="absolute top-0 bottom-6 left-0 w-6 bg-gradient-to-r from-[#F9FAFB] to-transparent pointer-events-none z-20"></div>
            <div class="absolute top-0 bottom-6 right-0 w-12 bg-gradient-to-l from-[#F9FAFB] to-transparent pointer-events-none z-20"></div>
        </div>
        
        <div class="shrink-0 pb-6 pt-2">
            <a href="{{ route('keluarga.index') }}" class="w-14 h-14 rounded-full border border-border-light bg-white shadow-sm flex items-center justify-center text-[#374151] hover:bg-surface-container-lowest transition-all">
                <span class="material-symbols-outlined text-[28px]">add</span>
            </a>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-12">
        
        <!-- Left: Summary Card (Bento Style) -->
        <div class="lg:col-span-4 space-y-6">
            @php
                $totalSelesai = collect($timeline)->where('status', 'Selesai')->count();
            @endphp
            @if($targetAnggota)
            <div class="bg-surface-container-lowest border border-border-light rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface flex items-center gap-3">
                            {{ $targetAnggota->nama }}
                            @if(isset($targetAnggota->status_vaksin))
                            <span class="px-3 py-1 bg-{{ $targetAnggota->status_color }}/10 text-{{ $targetAnggota->status_color }} text-[10px] font-bold rounded-full uppercase">
                                {{ $targetAnggota->status_vaksin }}
                            </span>
                            @endif
                        </h2>
                        <p class="text-body-sm text-on-surface-variant font-mono mt-1">NIK: {{ $targetAnggota->nik ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="flex flex-col items-center py-4">
                    <!-- Progress Ring -->
                    <div class="relative w-32 h-32 mx-auto flex items-center justify-center">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 128 128">
                            <circle class="text-surface-container" cx="64" cy="64" fill="transparent" r="56" stroke="currentColor" stroke-width="8"></circle>
                            <circle class="text-primary" cx="64" cy="64" fill="transparent" r="56" stroke="currentColor" stroke-dasharray="351.8" stroke-dashoffset="{{ 351.8 - (351.8 * ($persentaseCakupan ?? 0) / 100) }}" stroke-linecap="round" stroke-width="8"></circle>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-headline-md font-bold text-on-surface leading-none">{{ $persentaseCakupan ?? 0 }}%</span>
                            <span class="text-[10px] text-on-surface-variant mt-1">Selesai</span>
                        </div>
                    </div>
                    <p class="mt-4 text-center text-body-sm text-on-surface-variant px-4">Telah menyelesaikan {{ $totalSelesai }} dosis vaksinasi.</p>
                </div>
                
                <div class="mt-6 pt-6 border-t border-border-light space-y-3">
                    <div class="flex justify-between text-body-sm">
                        <span class="text-on-surface-variant">Total Vaksin</span>
                        <span class="font-bold">{{ $totalSelesai }} Dosis</span>
                    </div>
                </div>
            </div>
            @endif


            <div class="bg-primary-container p-6 rounded-xl text-on-primary-container flex flex-col gap-4 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="font-bold text-label-lg mb-2">Lindungi Keluarga, Lindungi Masa Depan</p>
                    <p class="text-xs opacity-90 mb-4">Pastikan seluruh anggota keluarga mendapatkan dosis vaksinasi lengkap sesuai jadwal.</p>
                    <a href="{{ route('keluarga.index') }}" class="inline-block px-4 py-2 bg-surface-container-lowest text-primary rounded-lg font-bold hover:bg-surface transition-all text-sm text-center w-full">Manajemen Keluarga</a>
                </div>
                <span class="material-symbols-outlined absolute -bottom-4 -right-4 text-[80px] opacity-10 rotate-12">medical_services</span>
            </div>
        </div>

        <!-- Right: Timeline -->
        <div class="lg:col-span-8 space-y-6 relative">
            @forelse($timeline ?? [] as $item)
            @php
                $isJadwal = $item->type === 'jadwal';
                $status = $item->status;
                
                // Determine colors and icons based on status
                if ($isJadwal || $status === 'Terdaftar' || $status === 'Dijadwalkan') {
                    $statusColor = 'warning-orange';
                    $statusBg = 'bg-warning-orange/20';
                    $icon = 'schedule';
                    $stripe = 'status-stripe-warning';
                } elseif ($status === 'Selesai') {
                    $statusColor = 'success-green';
                    $statusBg = 'bg-success-green/20';
                    $icon = 'check_circle';
                    $stripe = 'status-stripe-success';
                } elseif ($status === 'Memproses') {
                    $statusColor = 'info-cyan';
                    $statusBg = 'bg-info-cyan/20';
                    $icon = 'sync';
                    $stripe = 'status-stripe-info';
                } else {
                    // Ditolak
                    $statusColor = 'danger-red';
                    $statusBg = 'bg-danger-red/20';
                    $icon = 'cancel';
                    $stripe = 'status-stripe-danger';
                }
            @endphp
            <div class="flex flex-row mb-6">
                <!-- Kolom 1 (Circle & Garis vertical) -->
                <div class="flex flex-col items-center mr-4 w-10 shrink-0">
                    <div class="w-10 h-10 rounded-full {{ $statusBg }} flex items-center justify-center text-{{ $statusColor }} shrink-0 z-10">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">
                            {{ $icon }}
                        </span>
                    </div>
                    @if(!$loop->last)
                        <div class="w-px h-full bg-border-light my-2"></div>
                    @else
                        <div class="w-px h-full bg-transparent my-2"></div>
                    @endif
                </div>
                
                <!-- Kolom 2 (Card) -->
                <div class="flex-1 min-w-0">
                    <div class="bg-surface-container-lowest border border-border-light rounded-xl p-6 md:p-8 shadow-sm {{ $stripe }} transition-all hover:shadow-md" style="{{ $stripe === 'status-stripe-info' ? 'border-left: 4px solid #3ABFF8;' : ($stripe === 'status-stripe-danger' ? 'border-left: 4px solid #E02424;' : '') }}">
                        <div class="flex flex-col md:flex-row justify-between gap-6">
                            <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-headline-sm text-[18px] text-on-surface">{{ $item->vaksin->nama ?? 'Vaksin' }}</h3>
                                <span class="px-2 py-0.5 bg-{{ $statusColor }}/10 text-{{ $statusColor }} text-[10px] font-bold rounded-full uppercase">{{ $status }}</span>
                            </div>
                            <p class="text-body-sm text-on-surface-variant flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">calendar_today</span> {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') : '-' }}
                            </p>
                            <p class="text-body-sm text-on-surface-variant mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">location_on</span> {{ $item->faskes->nama ?? 'Faskes' }}
                            </p>
                        </div>
                        
                            <div class="flex flex-wrap gap-3 items-center self-start md:mt-2">
                                @if($isJadwal)
                                    <button class="px-6 py-2.5 border-2 border-border-light text-primary hover:bg-primary-container hover:text-primary-dark rounded-xl font-bold text-sm transition-all active:scale-95 whitespace-nowrap">
                                        Ubah Jadwal
                                    </button>
                                @elseif($status === 'Memproses')
                                    <button class="px-6 py-2.5 border-2 border-border-light text-primary hover:bg-primary-container hover:text-primary-dark rounded-xl font-bold text-sm transition-all active:scale-95 whitespace-nowrap">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('riwayat.destroy', $item->model_id) }}" id="cancel-riwayat-{{ $item->model_id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="px-6 py-2.5 border border-danger-red text-danger-red hover:bg-danger-red/10 rounded-xl font-bold text-sm transition-all active:scale-95 whitespace-nowrap"
                                                onclick="
                                                Swal.fire({
                                                    title: 'Batalkan Pengajuan?',
                                                    text: 'Pengajuan riwayat vaksin ini akan dibatalkan!',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#E02424',
                                                    cancelButtonColor: '#1E3A5F',
                                                    confirmButtonText: 'Ya, batalkan!',
                                                    cancelButtonText: 'Kembali'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById('cancel-riwayat-{{ $item->model_id }}').submit();
                                                    }
                                                });">
                                            Batalkan
                                        </button>
                                    </form>
                                @elseif($status === 'Selesai')
                                    <button @click="$dispatch('open-certificate', { url: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf' })" class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm transition-all hover:bg-primary-dark active:scale-95 whitespace-nowrap">
                                        <span class="material-symbols-outlined text-[18px]">download</span> Lihat Sertifikat
                                    </button>
                                    
                                    <!-- Dropdown Hapus -->
                                    <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="p-2 text-outline hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined">more_vert</span>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <form method="POST" action="{{ route('riwayat.destroy', $item->model_id) }}" id="delete-riwayat-{{ $item->model_id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="block w-full px-4 py-2 text-start text-body-md text-danger-red hover:bg-error-container hover:text-on-error-container focus:outline-none transition"
                                                    onclick="
                                                    Swal.fire({
                                                        title: 'Hapus Riwayat?',
                                                        text: 'Riwayat ini akan dihapus permanen!',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#E02424',
                                                        cancelButtonColor: '#1E3A5F',
                                                        confirmButtonText: 'Ya, hapus!',
                                                        cancelButtonText: 'Batal'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            document.getElementById('delete-riwayat-{{ $item->model_id }}').submit();
                                                        }
                                                    });">
                                                Hapus
                                            </button>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @elseif($status === 'Ditolak')
                                <!-- Ditolak dll -->
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="p-2 text-outline hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined">more_vert</span>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <form method="POST" action="{{ route('riwayat.destroy', $item->model_id) }}" id="delete-riwayat-{{ $item->model_id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="block w-full px-4 py-2 text-start text-body-md text-danger-red hover:bg-error-container hover:text-on-error-container focus:outline-none transition"
                                                    onclick="
                                                    Swal.fire({
                                                        title: 'Hapus Riwayat?',
                                                        text: 'Riwayat ini akan dihapus permanen!',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#E02424',
                                                        cancelButtonColor: '#1E3A5F',
                                                        confirmButtonText: 'Ya, hapus!',
                                                        cancelButtonText: 'Batal'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            document.getElementById('delete-riwayat-{{ $item->model_id }}').submit();
                                                        }
                                                    });">
                                                Hapus
                                            </button>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <x-empty-state icon="history" title="Belum ada riwayat" message="Tidak ada data riwayat vaksinasi." />
            @endforelse
        </div>
    </div>
</x-app-layout>

