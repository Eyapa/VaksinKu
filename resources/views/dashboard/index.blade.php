<x-app-layout>
    <!-- Welcome Section -->
    <section class="mb-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Selamat Pagi, {{ explode(' ', auth()->user()->name)[0] }}</h2>
                <p class="text-body-lg text-on-surface-variant mt-1">Pantau kesehatan keluarga dan jadwal vaksinasi desa Anda di satu tempat.</p>
            </div>
            <div class="bg-white p-1 rounded-lg border border-border-light flex gap-1">
                <button class="px-4 py-2 bg-primary text-white rounded-md font-label-md text-label-md shadow-sm">Dashboard</button>
                <button style="display:none;" class="px-4 py-2 text-on-surface-variant hover:bg-surface-container rounded-md font-label-md text-label-md">Aktivitas</button>
            </div>
        </div>
    </section>

    <!-- Bento Grid Main Content -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
        
        <!-- Announcement Banner (Hero) -->
        @if($pengumuman)
        <div class="md:col-span-8 group relative overflow-hidden rounded-xl bg-gradient-to-br from-primary to-primary-container p-8 text-white shadow-lg border border-white/10 h-full flex flex-col justify-between">
            <div class="relative z-10">
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-label-sm font-bold mb-4">Pengumuman Terbaru</span>
                <h3 class="font-headline-md text-headline-md mb-4 max-w-md">{{ $pengumuman->judul }}</h3>
                <p class="text-primary-fixed opacity-90 text-body-md mb-6 max-w-sm leading-relaxed">
                    {{ $pengumuman->konten }}
                </p>
                <a href="{{ route('cari')}}" class="inline-flex bg-white text-primary px-6 py-2.5 rounded-lg font-bold hover:bg-surface-container transition-all items-center gap-2 group-hover:gap-3 w-max">
                    Daftar Sekarang <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
            <!-- Abstract patterns -->
            <div class="absolute right-[-40px] bottom-[-40px] opacity-10 pointer-events-none transform rotate-12">
                <span class="material-symbols-outlined text-[300px]" style="font-variation-settings: 'FILL' 1;">medical_services</span>
            </div>
        </div>
        @else
        <div class="md:col-span-8 group relative overflow-hidden rounded-xl bg-gradient-to-br from-primary to-primary-container p-8 text-white shadow-lg border border-white/10 h-full flex flex-col justify-between">
            <div class="relative z-10">
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-label-sm font-bold mb-4">Info</span>
                <h3 class="font-headline-md text-headline-md mb-4 max-w-md">Belum Ada Pengumuman Baru</h3>
                <p class="text-primary-fixed opacity-90 text-body-md mb-6 max-w-sm leading-relaxed">
                    Pantau terus halaman ini untuk mendapatkan informasi terbaru seputar layanan kesehatan dan jadwal vaksinasi di balai desa.
                </p>
            </div>
            <div class="absolute right-[-40px] bottom-[-40px] opacity-10 pointer-events-none transform rotate-12">
                <span class="material-symbols-outlined text-[300px]" style="font-variation-settings: 'FILL' 1;">health_and_safety</span>
            </div>
        </div>
        @endif

        @php
            $mainRiwayatTerbaru = $mainProfile ? $mainProfile->jadwalVaksin->where('status', 'selesai')->sortByDesc('tanggal_jadwal')->first() : null;
            $mainJadwalTerbaru = $mainProfile ? $mainProfile->jadwalVaksin->where('tanggal_jadwal', '>=', now()->toDateString())->whereIn('status', ['terdaftar', 'konfirmasi'])->sortBy('tanggal_jadwal')->first() : null;
        @endphp
        <!-- Status Vaksinasi Card -->
        <div class="md:col-span-4 flex flex-col gap-gutter">
            <div class="bg-white p-6 rounded-xl border border-border-light shadow-sm flex-1 flex flex-col">
                <div class="flex justify-between items-start mb-6">
                    @if($mainRiwayatTerbaru && $mainRiwayatTerbaru->file_sertifikat_url)
                    <div class="w-12 h-12 bg-success-green/10 text-success-green rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">verified</span>
                    </div>
                    <span class="text-label-sm font-bold text-success-green bg-success-green/10 px-2 py-1 rounded">Terverifikasi</span>
                    @else
                    <div class="w-12 h-12 bg-warning-orange/10 text-warning-orange rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">pending</span>
                    </div>
                    <span class="text-label-sm font-bold text-warning-orange bg-warning-orange/10 px-2 py-1 rounded">Belum Terverifikasi</span>
                    @endif
                </div>
                <h4 class="font-headline-sm text-headline-sm text-on-surface mb-2">Status Vaksinasi</h4>
                <div class="mt-4 space-y-4">
                    @if($mainJadwalTerbaru)
                    <div class="flex items-center gap-4 p-4 bg-warning-orange/5 rounded-lg border border-warning-orange/20">
                        <span class="material-symbols-outlined text-warning-orange">schedule</span>
                        <div>
                            <p class="font-label-md text-label-md text-on-surface">Jadwal: {{ $mainJadwalTerbaru->vaksin->nama }}</p>
                            <p class="text-label-sm text-on-surface-variant">{{ \Carbon\Carbon::parse($mainJadwalTerbaru->tanggal_jadwal)->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    @elseif($mainRiwayatTerbaru)
                    <div class="flex items-center gap-4 p-4 bg-bg-subtle rounded-lg border border-border-light">
                        <span class="material-symbols-outlined text-primary">vaccines</span>
                        <div>
                            <p class="font-label-md text-label-md text-on-surface">Selesai: {{ $mainRiwayatTerbaru->vaksin->nama }}</p>
                            <p class="text-label-sm text-on-surface-variant">{{ \Carbon\Carbon::parse($mainRiwayatTerbaru->tanggal_vaksin)->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-4 p-4 bg-bg-subtle rounded-lg border border-border-light">
                        <span class="material-symbols-outlined text-on-surface-variant">help</span>
                        <div>
                            <p class="font-label-md text-label-md text-on-surface">Belum Divaksin</p>
                            <p class="text-label-sm text-on-surface-variant">-</p>
                        </div>
                    </div>
                    @endif

                    @if($mainRiwayatTerbaru)
                    <button @click="$dispatch('open-certificate', { url: '{{ $mainRiwayatTerbaru->file_sertifikat_url}} ' })"  class="w-full py-3 border-2 border-primary text-primary rounded-lg font-bold hover:bg-primary-container/5 transition-colors flex items-center justify-center gap-2 mt-4">
                        <span class="material-symbols-outlined text-lg">download</span>
                        Lihat Sertifikat
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Action Cards -->
        <a href="{{ route('keluarga.index') }}" class="md:col-span-4 bg-white p-6 rounded-xl border border-border-light shadow-sm hover:border-primary transition-colors cursor-pointer group text-decoration-none">
            <div class="w-12 h-12 bg-surface-container text-primary rounded-lg flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl">family_restroom</span>
            </div>
            <h5 class="font-headline-sm text-[18px] mb-2 text-on-surface">Manajemen Keluarga</h5>
            <p class="text-body-sm text-on-surface-variant">Kelola profil anggota keluarga Anda untuk keperluan vaksinasi.</p>
        </a>

        <a href="{{ route('cari') }}" class="md:col-span-4 bg-white p-6 rounded-xl border border-border-light shadow-sm hover:border-primary transition-colors cursor-pointer group text-decoration-none">
            <div class="w-12 h-12 bg-surface-container text-primary rounded-lg flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl">map</span>
            </div>
            <h5 class="font-headline-sm text-[18px] mb-2 text-on-surface">Lokasi Terdekat</h5>
            <p class="text-body-sm text-on-surface-variant">Cari puskesmas atau balai desa terdekat untuk vaksinasi.</p>
        </a>

        <a href="{{ route('riwayat.index') }}" class="md:col-span-4 bg-white p-6 rounded-xl border border-border-light shadow-sm hover:border-primary transition-colors cursor-pointer group text-decoration-none">
            <div class="w-12 h-12 bg-surface-container text-primary rounded-lg flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl">info</span>
            </div>
            <h5 class="font-headline-sm text-[18px] mb-2 text-on-surface">Informasi Vaksin</h5>
            <p class="text-body-sm text-on-surface-variant">Lihat riwayat, edukasi jenis vaksin dan efek sampingnya.</p>
        </a>

        <!-- Family Table Section -->
        <div class="md:col-span-12">
            <div class="bg-white rounded-xl border border-border-light shadow-sm overflow-hidden mt-6">
                <div class="p-6 border-b border-border-light flex justify-between items-center">
                    <h4 class="font-headline-sm text-headline-sm">Anggota Keluarga</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-bg-subtle text-on-surface-variant border-b border-border-light">
                                <th class="px-6 py-4 font-label-md text-label-md">Nama</th>
                                <th class="px-6 py-4 font-label-md text-label-md">Hubungan</th>
                                <th class="px-6 py-4 font-label-md text-label-md">Status Terakhir</th>
                                <th class="px-6 py-4 font-label-md text-label-md text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light">
                            @forelse($keluargaLain as $anggota)
                            <tr class="hover:bg-bg-subtle transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-primary-container/20 text-primary rounded-full flex items-center justify-center font-bold">
                                            {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                                        </div>
                                        <p class="font-label-md text-on-surface">{{ $anggota->nama }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-on-surface-variant font-body-sm">{{ ucfirst($anggota->hubungan) }}</td>
                                <td class="px-6 py-5">
                                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md text-label-sm font-bold bg-{{ explode('-', $anggota->status_terakhir_type)[0] }}/10 text-{{ $anggota->status_terakhir_type }}">
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">{{ $anggota->status_terakhir_icon }}</span>
                                        {{ $anggota->status_terakhir_text }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('riwayat.index', ['tab' => 'keluarga', 'anggota_id' => $anggota->id]) }}" class="text-primary hover:text-primary-container font-bold text-label-md">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-on-surface-variant">
                                    Belum ada data anggota keluarga.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
