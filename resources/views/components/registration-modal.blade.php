@props(['anggotaKeluargas' => []])

<div class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300" id="registration-modal">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeRegistrationModal()"></div>
    <form id="registration-modal-form" action="" method="POST" class="relative w-full max-w-md bg-surface-container-lowest rounded-xl shadow-xl overflow-hidden transform transition-transform duration-300 scale-95 z-10 mx-4 flex flex-col max-h-[90vh]">
        @csrf
        <div id="registration-modal-method"></div>
        <input type="hidden" name="faskes_id" id="modal-faskes-id">
        
        <div class="p-6 border-b border-border-light flex justify-between items-center shrink-0">
            <h3 class="text-headline-sm font-headline-sm text-primary" id="modal-title">Pendaftaran Jadwal Vaksinasi</h3>
            <button type="button" class="text-on-surface-variant hover:text-on-surface" onclick="closeRegistrationModal()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-6 overflow-y-auto custom-scrollbar">
            <div class="bg-primary/5 p-4 rounded-lg border border-primary/10">
                <p class="font-label-md text-primary" id="modal-faskes-name">Nama Faskes</p>
                <p class="text-body-sm text-on-surface-variant" id="modal-faskes-address">Alamat</p>
            </div>
            
            <div class="space-y-2" id="modal-anggota-container">
                <label class="text-label-sm text-on-surface-variant font-bold">Pilih Anggota Keluarga</label>
                <div class="grid grid-cols-2 gap-3 max-h-32 overflow-y-auto custom-scrollbar" id="modal-anggota-list">
                    @if(auth()->check() && count($anggotaKeluargas) > 0)
                        @foreach($anggotaKeluargas as $anggota)
                        <label class="anggota-item flex items-center gap-3 p-3 border border-border-light rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors" data-id="{{ $anggota->id }}" onclick="document.querySelectorAll('.anggota-item').forEach(r => r.className = 'anggota-item flex items-center gap-3 p-3 border border-border-light rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors'); this.className = 'anggota-item flex items-center gap-3 p-3 border border-primary bg-primary/5 rounded-lg cursor-pointer transition-colors'; document.getElementById('radio-anggota-{{ $anggota->id }}').checked = true;">
                            <input id="radio-anggota-{{ $anggota->id }}" class="text-primary focus:ring-primary hidden" name="anggota_keluarga_id" type="radio" value="{{ $anggota->id }}" required/>
                            <div class="flex flex-col pointer-events-none">
                                <span class="text-label-md font-bold">{{ $anggota->nama }}</span>
                                <span class="text-[10px] text-on-surface-variant">{{ $anggota->peran_label }}</span>
                            </div>
                        </label>
                        @endforeach
                    @else
                        <p class="text-sm text-error col-span-2">Silakan login dan pastikan Anda memiliki data anggota keluarga.</p>
                    @endif
                </div>
            </div>
            
            <div class="space-y-2">
                <label class="text-label-sm text-on-surface-variant font-bold">Jenis Vaksin</label>
                <select id="modal-vaksin-select" name="vaksin_id" required class="w-full px-3 py-2.5 bg-surface-container-lowest border border-border-light rounded-lg focus:ring-primary focus:border-primary text-body-sm outline-none">
                    <option value="">Pilih Vaksin</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-label-sm text-on-surface-variant font-bold">Tanggal</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">calendar_today</span>
                        <input id="modal-tanggal" class="w-full pl-10 pr-4 py-2.5 bg-surface-container-lowest border border-border-light rounded-lg focus:ring-primary focus:border-primary text-body-sm outline-none" type="date" name="tanggal_jadwal" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required/>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-label-sm text-on-surface-variant font-bold">Jam Mulai</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">schedule</span>
                        <input id="modal-jam" class="w-full pl-10 pr-4 py-2.5 bg-surface-container-lowest border border-border-light rounded-lg focus:ring-primary focus:border-primary text-body-sm outline-none" type="time" name="jam_mulai" value="08:00" required/>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6 bg-surface-container-low flex gap-3 border-t border-border-light shrink-0">
            <button type="button" class="flex-1 py-2.5 border border-border-light bg-surface-container-lowest text-on-surface-variant rounded-lg font-label-md hover:bg-surface-container transition-colors" onclick="closeRegistrationModal()">Batal</button>
            <button type="submit" id="modal-submit-btn" class="flex-1 py-2.5 bg-primary text-white rounded-lg font-label-md hover:bg-opacity-90 transition-all active:scale-[0.98]">Konfirmasi Pendaftaran</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function closeRegistrationModal() {
        const modal = document.getElementById('registration-modal');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.querySelector('.transform').classList.add('scale-95');
    }

    window.openRegistrationModal = function(config) {
        const modal = document.getElementById('registration-modal');
        
        // Setup Form
        const form = document.getElementById('registration-modal-form');
        form.action = config.actionUrl;
        
        const methodDiv = document.getElementById('registration-modal-method');
        if (config.method && config.method.toUpperCase() === 'PUT') {
            methodDiv.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('modal-title').innerText = 'Ubah Jadwal Vaksinasi';
            document.getElementById('modal-submit-btn').innerText = 'Simpan Perubahan';
        } else {
            methodDiv.innerHTML = '';
            document.getElementById('modal-title').innerText = 'Pendaftaran Jadwal Vaksinasi';
            document.getElementById('modal-submit-btn').innerText = 'Konfirmasi Pendaftaran';
        }

        // Setup Faskes Info
        document.getElementById('modal-faskes-name').innerText = config.faskesNama;
        document.getElementById('modal-faskes-address').innerText = config.faskesAlamat;
        document.getElementById('modal-faskes-id').value = config.faskesId;
        
        // Setup Vaksin Select
        const vaksinSelect = document.getElementById('modal-vaksin-select');
        vaksinSelect.innerHTML = '<option value="">Pilih Vaksin</option>';
        if (config.vaksins) {
            config.vaksins.forEach(v => {
                const statusText = v.pivot && v.pivot.status ? ` (${v.pivot.status})` : '';
                const selected = (config.vaksinId && config.vaksinId == v.id) ? 'selected' : '';
                vaksinSelect.innerHTML += `<option value="${v.id}" ${selected}>${v.nama}${statusText}</option>`;
            });
        }
        
        // Setup Anggota Keluarga List
        const anggotaContainer = document.getElementById('modal-anggota-container');
        document.querySelectorAll('.anggota-item').forEach(item => {
            if (config.anggotaId) {
                // Mode Update: Hide the container since it's already selected
                anggotaContainer.style.display = 'none';
                if (item.getAttribute('data-id') == config.anggotaId) {
                    item.click(); // Select it in the background
                }
            } else {
                // Mode Create: Show all and show container
                anggotaContainer.style.display = 'block';
                item.style.display = 'flex';
                // Reset selection to first item
                const firstItem = document.querySelector('.anggota-item');
                if(firstItem) firstItem.click();
            }
        });
        
        // Setup Tanggal & Jam
        if (config.tanggal) document.getElementById('modal-tanggal').value = config.tanggal;
        if (config.jam) document.getElementById('modal-jam').value = config.jam;

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.querySelector('.transform').classList.remove('scale-95');
    };
</script>
@endpush
