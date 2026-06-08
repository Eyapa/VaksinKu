# Phase 2 Progress Tracker

## Tahap 1: Manajemen Keluarga (Fondasi Data)
- [x] Buat/Update `KeluargaController` (index, create, store, edit, update, destroy)
- [x] Buat/Update `StoreAnggotaRequest` untuk validasi
- [x] Update view `keluarga/index.blade.php` dengan data dinamis
- [x] Buat view `keluarga/create.blade.php`
- [x] Tulis test `KeluargaTest.php`

## Tahap 2: Riwayat & Jadwal Vaksinasi
- [x] Buat/Update `RiwayatController` dan `JadwalController` (penggabungan data)
- [x] Update view `riwayat/index.blade.php` dengan timeline dinamis
- [x] Tulis test `RiwayatTest.php`

## Tahap 3: Agregasi Data Dashboard
- [x] Update `DashboardController` untuk kalkulasi metrik
- [x] Update view `dashboard/index.blade.php` dengan metrik dinamis
- [ ] Tulis test `DashboardTest.php`

## Tahap 4: Persiapan Main System (Phase 3)
- [x] Verifikasi skema `FasilitasKesehatan` (nullable relations) — model `app/Models/FasilitasKesehatan.php` ada
- [ ] Buat interface skeleton `OverpassMapService` — belum ada, perlu implementasi di `app/Services`
- [x] Siapkan stub `FaskesApiController` — endpoint pencarian sudah tersedia di `app/Http/Controllers/FaskesController.php`

---
**Catatan progres (2026-06-08):**
- Dashboard (controller + view) sudah diimplementasikan; masih perlu menambahkan `DashboardTest`
- Faskes: model dan controller API ada; layanan Overpass (external map sync) belum diimplementasikan
- Service `VaksinScheduleService` sudah ada dan digunakan oleh `DashboardController` (periksa import `Str` pada file service)
