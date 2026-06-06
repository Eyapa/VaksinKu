<x-app-layout>
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <h2 class="font-headline-sm text-headline-sm text-primary">Cari Fasilitas Kesehatan</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-[70vh]">
        <div class="lg:col-span-4 flex flex-col gap-4">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input type="text" class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest border border-border-light rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-body-md" placeholder="Cari puskesmas, klinik...">
            </div>
            
            <div class="bg-surface-container-lowest rounded-xl border border-border-light flex-1 overflow-y-auto">
                <div class="p-4 border-b border-border-light hover:bg-surface-container cursor-pointer transition-colors">
                    <h3 class="font-headline-sm text-on-surface">Puskesmas Melati</h3>
                    <p class="text-body-sm text-on-surface-variant mb-2">Jl. Mawar No. 12, Desa Sukamaju</p>
                    <span class="inline-block px-2 py-1 bg-success-green/10 text-success-green text-[10px] font-bold rounded">Tersedia Vaksin COVID-19</span>
                </div>
                <div class="p-4 border-b border-border-light hover:bg-surface-container cursor-pointer transition-colors">
                    <h3 class="font-headline-sm text-on-surface">Klinik Sehat Selalu</h3>
                    <p class="text-body-sm text-on-surface-variant mb-2">Jl. Melati No. 4, Desa Sukamaju</p>
                    <span class="inline-block px-2 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded">Imunisasi Anak</span>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-8 bg-surface-container-lowest rounded-xl border border-border-light overflow-hidden relative">
            <div id="map" class="w-full h-full bg-surface-variant flex items-center justify-center">
                <p class="text-on-surface-variant flex items-center gap-2">
                    <span class="material-symbols-outlined">map</span> Peta Leaflet akan dimuat di sini...
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Placeholder map initialization (to be implemented with Leaflet)
        console.log('Leaflet Map will be initialized here.');
    </script>
    @endpush
</x-app-layout>
