<x-app-layout>
    <div class="mb-8">
        <a href="{{ route('keluarga.index') }}" class="text-on-surface-variant hover:text-primary flex items-center gap-2 mb-4 font-label-md">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Daftar Keluarga
        </a>
        <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Edit Anggota Keluarga</h2>
        <p class="text-body-sm text-on-surface-variant mt-1">Perbarui data {{ $keluarga->nama }}.</p>
    </div>

    <div class="max-w-2xl bg-surface-container-lowest p-8 rounded-xl border border-border-light shadow-sm">
        <form action="{{ route('keluarga.update', $keluarga) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="nama" value="Nama Lengkap" />
                <x-text-input id="nama" name="nama" type="text" :value="old('nama', $keluarga->nama)" required autofocus />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="nik" value="Nomor Induk Kependudukan (NIK)" />
                <x-text-input id="nik" name="nik" type="text" :value="old('nik', $keluarga->nik)" required />
                <p class="text-xs text-on-surface-variant mt-1">Sesuai dengan KTP/KIA.</p>
                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                    <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" :value="old('tanggal_lahir', $keluarga->tanggal_lahir->format('Y-m-d'))" required />
                    <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="jenis_kelamin" value="Jenis Kelamin" />
                    <select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-4 py-3 bg-surface-bright border border-border-light rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-body-md text-on-surface shadow-sm" required>
                        <option value="" disabled>Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin', $keluarga->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $keluarga->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="hubungan" value="Hubungan Keluarga" />
                <select id="hubungan" name="hubungan" class="w-full px-4 py-3 bg-surface-bright border border-border-light rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-body-md text-on-surface shadow-sm" required>
                    <option value="" disabled>Pilih Hubungan</option>
                    <option value="kepala_keluarga" {{ old('hubungan', $keluarga->hubungan) == 'kepala_keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                    <option value="istri" {{ old('hubungan', $keluarga->hubungan) == 'istri' ? 'selected' : '' }}>Istri</option>
                    <option value="anak" {{ old('hubungan', $keluarga->hubungan) == 'anak' ? 'selected' : '' }}>Anak</option>
                    <option value="orang_tua" {{ old('hubungan', $keluarga->hubungan) == 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                    <option value="lainnya" {{ old('hubungan', $keluarga->hubungan) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                <x-input-error :messages="$errors->get('hubungan')" class="mt-2" />
            </div>

            <div class="pt-4 flex items-center justify-end gap-4">
                <a href="{{ route('keluarga.index') }}" class="px-6 py-3 text-on-surface-variant hover:text-primary font-label-md transition-colors">Batal</a>
                <x-primary-button>
                    Simpan Perubahan
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
