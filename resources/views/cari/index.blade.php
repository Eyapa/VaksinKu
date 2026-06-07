<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="font-headline-sm text-headline-sm text-primary mb-1">Cari Fasilitas Kesehatan</h2>
            <p class="text-body-sm text-on-surface-variant">Temukan Puskesmas atau RS terdekat untuk jadwal vaksinasi keluarga Anda.</p>
        </div>
    </div>

    <!-- Main Content Layout (Flex) -->
    <div class="flex flex-col md:flex-row w-full gap-4 md:gap-0 md:border md:border-border-light md:rounded-xl md:overflow-hidden md:bg-surface-container-lowest md:shadow-sm" style="height: 80vh; min-height: 600px;">
        
        <!-- Left Panel: Filter & Results -->
        <div class="w-full md:w-[40%] flex-1 md:h-full flex flex-col border border-border-light md:border-0 md:border-r md:border-border-light rounded-xl md:rounded-none overflow-hidden bg-surface-bright relative z-20 shadow-sm md:shadow-none" style="min-height: 0;">
            <div class="p-4 md:p-6 border-b border-border-light flex-shrink-0">
                <!-- Filter Section -->
                <div class="grid grid-cols-1 gap-4">
                    <div class="space-y-1">
                        <label class="text-label-sm text-on-surface-variant ml-1">Lokasi atau Nama Faskes</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">location_on</span>
                            <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-primary text-body-sm" placeholder="Cari puskesmas, rumah sakit...">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-label-sm text-on-surface-variant ml-1">Jenis Vaksin</label>
                            <select id="vaksinSelect" class="w-full px-3 py-2 bg-white border border-border-light rounded-lg focus:ring-primary text-body-sm">
                                <option value="">Semua Jenis</option>
                                @foreach($vaksins as $v)
                                    <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-label-sm text-on-surface-variant ml-1">Jarak Maksimum</label>
                            <select id="radiusSelect" class="w-full px-3 py-2 bg-white border border-border-light rounded-lg focus:ring-primary text-body-sm">
                                <option value="">Tanpa Batas Jarak</option>
                                <option value="5">5 km</option>
                                <option value="10">10 km</option>
                                <option value="20">20 km</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-[11px] text-on-surface-variant mt-1 italic hidden md:block">* Klik di atas peta untuk menentukan titik pencarian radius jarak.</p>
                </div>
            </div>
            
            <!-- Results List -->
            <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 custom-scrollbar min-h-0" id="faskes-list">
                <div class="flex justify-center items-center h-32">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel: Map Interface -->
        <div class="w-full md:w-[60%] flex-1 md:h-full relative border border-border-light md:border-0 rounded-xl md:rounded-none overflow-hidden bg-surface-variant z-10 shadow-sm md:shadow-none" style="min-height: 0;">
            <!-- Explicit absolute positioning for Leaflet container to guarantee size -->
            <div id="map" class="absolute inset-0 z-0"></div>

            <!-- Bottom Map Controls -->
            <div class="absolute bottom-6 left-6 right-6 flex justify-between items-end z-[400] pointer-events-none">
                <div class="bg-white/90 backdrop-blur-md p-4 rounded-xl shadow-lg border border-white/20 max-w-xs pointer-events-auto">
                    <h5 class="text-label-md font-bold text-on-surface mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                        Panduan Cepat
                    </h5>
                    <ul class="text-[12px] space-y-2 text-on-surface-variant">
                        <li class="flex gap-2">
                            <span class="font-bold text-primary">1.</span>
                            Gunakan Peta: Klik sembarang lokasi untuk mencari faskes dengan radius tertentu.
                        </li>
                        <li class="flex gap-2">
                            <span class="font-bold text-primary">2.</span>
                            Pilih faskes terdekat yang memiliki vaksin tersedia.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        window.faskesData = {};
        document.addEventListener('DOMContentLoaded', function() {
            // Default center (Indonesia)
            const map = L.map('map').setView([-2.5489, 118.0149], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let markers = [];
            let originMarker = null;
            let radiusCircle = null;
            let currentLat = null;
            let currentLng = null;

            const searchInput = document.getElementById('searchInput');
            const vaksinSelect = document.getElementById('vaksinSelect');
            const radiusSelect = document.getElementById('radiusSelect');

            // Coba dapatkan lokasi user
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    currentLat = position.coords.latitude;
                    currentLng = position.coords.longitude;
                    map.setView([currentLat, currentLng], 12);
                    setOriginPoint(currentLat, currentLng, false);
                    fetchFaskes();
                }, function() {
                    fetchFaskes();
                });
            } else {
                fetchFaskes();
            }

            // Event Listeners for Filters
            vaksinSelect.addEventListener('change', fetchFaskes);
            radiusSelect.addEventListener('change', () => {
                if (radiusSelect.value && !currentLat) {
                    alert("Silakan klik titik di peta terlebih dahulu untuk menentukan pusat radius pencarian.");
                    radiusSelect.value = '';
                    return;
                }
                if (currentLat && currentLng) {
                    setOriginPoint(currentLat, currentLng, false);
                }
                fetchFaskes();
            });

            // Local text search
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                document.querySelectorAll('.faskes-card').forEach(card => {
                    const nama = card.dataset.nama;
                    const kota = card.dataset.kota;
                    if (nama.includes(term) || kota.includes(term)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Map Click Event for Radius
            map.on('click', function(e) {
                currentLat = e.latlng.lat;
                currentLng = e.latlng.lng;
                
                // Set default radius if user clicked but radius not set
                if (!radiusSelect.value) {
                    radiusSelect.value = "10";
                }
                
                setOriginPoint(currentLat, currentLng, true);
                fetchFaskes();
            });

            function setOriginPoint(lat, lng, panMap = true) {
                if (originMarker) map.removeLayer(originMarker);
                if (radiusCircle) map.removeLayer(radiusCircle);

                originMarker = L.marker([lat, lng], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(map).bindPopup("<b>Titik Pencarian Radius</b>").openPopup();

                if (radiusSelect.value) {
                    const radiusMeters = parseInt(radiusSelect.value) * 1000;
                    radiusCircle = L.circle([lat, lng], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.1,
                        radius: radiusMeters
                    }).addTo(map);

                    if (panMap) {
                        map.fitBounds(radiusCircle.getBounds());
                    }
                } else if (panMap) {
                    map.setView([lat, lng], 13);
                }
            }

            function fetchFaskes() {
                let url = new URL('/api/v1/faskes', window.location.origin);
                if (vaksinSelect.value) url.searchParams.append('vaksin_id', vaksinSelect.value);
                if (radiusSelect.value && currentLat && currentLng) {
                    url.searchParams.append('lat', currentLat);
                    url.searchParams.append('lng', currentLng);
                    url.searchParams.append('radius', radiusSelect.value);
                }

                document.getElementById('faskes-list').innerHTML = '<div class="flex justify-center items-center h-32"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';

                fetch(url.toString())
                    .then(res => res.json())
                    .then(response => {
                        if (response.success && response.data) {
                            window.faskesData = {};
                            response.data.forEach(f => window.faskesData[f.id] = f);
                            renderFaskes(response.data);
                            searchInput.dispatchEvent(new Event('input')); // trigger local filter
                        }
                    })
                    .catch(err => {
                        console.error("Gagal memuat data faskes", err);
                        document.getElementById('faskes-list').innerHTML = '<p class="text-error">Gagal memuat data.</p>';
                    });
            }

            function renderFaskes(faskesArray) {
                const container = document.getElementById('faskes-list');
                container.innerHTML = '';
                
                markers.forEach(m => map.removeLayer(m));
                markers = [];

                if(faskesArray.length === 0) {
                    container.innerHTML = '<p class="text-on-surface-variant text-center py-4">Tidak ada faskes ditemukan pada area ini.</p>';
                    return;
                }

                faskesArray.forEach(faskes => {
                    const isTersedia = faskes.layanan_vaksin && faskes.is_active;
                    const borderLeft = isTersedia ? 'border-l-success-green' : 'border-l-border-light';

                    // Parse Vaksins
                    let vaksinsHtml = '';
                    if (faskes.vaksins && faskes.vaksins.length > 0) {
                        vaksinsHtml = '<div class="flex flex-wrap gap-1 mt-3">';
                        faskes.vaksins.forEach(v => {
                            let badgeColor = 'bg-surface-variant text-on-surface-variant';
                            if (v.pivot.status === 'Tersedia') badgeColor = 'bg-success-green/10 text-success-green border border-success-green/20';
                            else if (v.pivot.status === 'Hampir Penuh') badgeColor = 'bg-warning-orange/10 text-warning-orange border border-warning-orange/20';
                            
                            vaksinsHtml += `<span class="px-2 py-0.5 rounded-full text-[10px] font-medium ${badgeColor}">${v.nama} (${v.pivot.status})</span>`;
                        });
                        vaksinsHtml += '</div>';
                    }

                    const card = document.createElement('div');
                    card.className = `faskes-card bg-white border-l-4 ${borderLeft} rounded-xl border border-border-light p-4 hover:shadow-md transition-shadow cursor-pointer group mb-4`;
                    card.dataset.nama = faskes.nama.toLowerCase();
                    card.dataset.kota = faskes.kota ? faskes.kota.toLowerCase() : '';
                    
                    card.innerHTML = `
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">medical_services</span>
                                <div>
                                    <h4 class="font-label-md text-on-surface group-hover:text-primary transition-colors">${faskes.nama}</h4>
                                    ${faskes.distance ? `<p class="text-[10px] text-primary font-bold">Jarak: ${parseFloat(faskes.distance).toFixed(2)} km</p>` : ''}
                                </div>
                            </div>
                        </div>
                        <p class="text-body-sm text-on-surface-variant mb-1">${faskes.alamat || faskes.kota}</p>
                        <div class="flex items-center gap-4 text-label-sm text-on-surface-variant">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">phone</span>
                                ${faskes.telepon || '-'}
                            </div>
                        </div>
                        ${vaksinsHtml}
                        
                        <div class="mt-4 pt-4 border-t border-border-light" onclick="event.stopPropagation();">
                            <button onclick="window.openRegistrationModal({
                                actionUrl: '{{ route('jadwal.store') }}',
                                method: 'POST',
                                faskesNama: '${faskes.nama.replace(/'/g, `\\'`)}',
                                faskesAlamat: '${(faskes.alamat || faskes.kota).replace(/'/g, `\\'`)}',
                                faskesId: ${faskes.id},
                                vaksins: window.faskesData[${faskes.id}] ? window.faskesData[${faskes.id}].vaksins : []
                            })" class="w-full py-2 border border-primary text-primary rounded-lg font-label-md hover:bg-primary hover:text-white transition-colors">
                                Daftar Sekarang
                            </button>
                        </div>
                    `;
                    
                    card.addEventListener('click', () => {
                        if(faskes.latitude && faskes.longitude) {
                            map.setView([faskes.latitude, faskes.longitude], 15);
                        }
                    });
                    container.appendChild(card);

                    // Add Marker to Map
                    if (faskes.latitude && faskes.longitude) {
                        const popupHtml = `
                            <div class="font-sans min-w-[200px]">
                                <h4 class="font-bold text-sm mb-1">${faskes.nama}</h4>
                                ${faskes.distance ? `<p class="text-xs font-bold text-primary mb-1">Jarak: ${parseFloat(faskes.distance).toFixed(2)} km</p>` : ''}
                                <p class="text-xs text-gray-600 mb-1">${faskes.alamat || faskes.kota}</p>
                            </div>
                        `;
                        const markerColor = isTersedia ? '#0E9F6E' : '#737686';
                        
                        const customIcon = L.divIcon({
                            className: 'custom-div-icon',
                            html: `<div style="background-color:${markerColor}; width:14px; height:14px; border-radius:50%; border:2px solid white; box-shadow:0 2px 4px rgba(0,0,0,0.3);"></div>`,
                            iconSize: [14, 14],
                            iconAnchor: [7, 7]
                        });

                        const m = L.marker([faskes.latitude, faskes.longitude], {icon: customIcon})
                            .addTo(map)
                            .bindPopup(popupHtml);
                        markers.push(m);
                    }
                });
            }
            
            // Initial Fetch
            fetchFaskes();
        });
    </script>
    @endpush

    <x-registration-modal :anggotaKeluargas="$anggotaKeluargas" />
</x-app-layout>
