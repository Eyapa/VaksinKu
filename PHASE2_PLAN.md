# Implementation Plan: Phase 2 (Core Features)

Dokumen ini adalah rancangan sistematis untuk pengerjaan **Phase 2 (Core Features)**. Implementasi akan berfokus sepenuhnya pada logika backend dan integrasi data untuk fitur inti. Desain arsitektur juga diatur agar menjadi fondasi yang kuat bagi integrasi API di Phase 3.

## 🏗️ Struktur Eksekusi Sistematis

### Tahap 1: Manajemen Keluarga (Fondasi Data)
*Karena fitur Dashboard dan Riwayat bergantung pada keberadaan data anggota keluarga, fitur ini dikerjakan pertama.*
- **Controller (`KeluargaController.php`)**: Mengelola CRUD `AnggotaKeluarga`.
- **Request (`StoreAnggotaRequest.php`)**: Validasi NIK dan kelengkapan data.
- **View (`keluarga/index.blade.php`)**: Menampilkan data anggota.
- **Test**: `tests/Feature/KeluargaTest.php` untuk validasi NIK dan sekuritas kepemilikan.

### Tahap 2: Riwayat & Jadwal Vaksinasi
- **Controller (`RiwayatController.php`)**: Gabungan (Merge) data riwayat masa lalu dan jadwal mendatang.
- **View (`riwayat/index.blade.php`)**: Visualisasi timeline dinamis.
- **Test**: `tests/Feature/RiwayatTest.php`.

### Tahap 3: Agregasi Data Dashboard
- **Controller (`DashboardController.php`)**: Menghitung statistik keluarga, dosis selesai, dan jadwal terdekat.
- **View (`dashboard/index.blade.php`)**: Integrasi metrik pada UI Card.
- **Test**: `tests/Feature/DashboardTest.php`.

### Tahap 4: Persiapan Main System untuk Phase 3
- Memastikan relasi Faskes bersifat *nullable* di Phase 2.
- Interface skeleton di `App\Services\OverpassMapService`.

*(Catatan: Pertanyaan mengenai desain UI untuk input Riwayat Masa Lalu akan ditunda/diselesaikan saat pengerjaan spesifik tahap 2 dengan meminta design Stitch)*
