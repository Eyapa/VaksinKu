# VaksinKu — Blueprint Proyek Lengkap
**Platform Vaksinasi Berbasis Laravel + Tailwind CSS | Docker-Ready**

---

## 📐 1. Analisis Desain (Berdasarkan Gambar)

Desain memiliki 5 halaman utama dengan sidebar navigasi kiri:

| Halaman | Route | Deskripsi |
|---|---|---|
| Beranda | `/dashboard` | Greeting, pengumuman vaksin, status vaksinasi, anggota keluarga |
| Riwayat | `/riwayat` | Timeline sertifikat vaksin per jenis vaksin |
| Cari Vaksin | `/cari` | Peta + filter + daftar faskes terdekat |
| Keluarga | `/keluarga` | Manajemen anggota keluarga + cakupan vaksin |
| Pengaturan | `/pengaturan` | Profil pengguna |
| Landing | `/` | Halaman onboarding (WhatsApp login) |

**Palet Warna dari Desain:**
- Primary Blue: `#1A56DB` / `#1E429F`
- Success Green: `#0E9F6E`
- Warning Orange: `#FF5A1F`
- Danger Red: `#E02424`
- Background: `#F9FAFB`
- Sidebar: `#1E3A5F` (navy gelap)

---

## 🏗️ 2. Struktur Laravel (Folder & File Utama)

```
vaksinku/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── WhatsAppAuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── VaksinController.php
│   │   │   ├── RiwayatController.php
│   │   │   ├── FaskesController.php        ← Maps + Faskes
│   │   │   ├── KeluargaController.php
│   │   │   └── Api/
│   │   │       ├── FaskesApiController.php ← REST API untuk JS Maps
│   │   │       └── VaksinApiController.php
│   │   └── Middleware/
│   │       └── EnsureProfileComplete.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── AnggotaKeluarga.php
│   │   ├── Vaksin.php
│   │   ├── JadwalVaksin.php
│   │   ├── RiwayatVaksin.php
│   │   ├── FasilitasKesehatan.php
│   │   └── SertifikatVaksin.php
│   └── Services/
│       ├── SatuSehatService.php            ← Integrasi API Kemenkes
│       ├── OverpassMapService.php          ← Ambil faskes dari OSM
│       └── VaksinScheduleService.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── VaksinSeeder.php
│       ├── FaskesSeeder.php
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php               ← Layout utama + sidebar
│       │   └── auth.blade.php              ← Layout halaman login
│       ├── auth/
│       │   └── landing.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── riwayat/
│       │   └── index.blade.php
│       ├── cari/
│       │   └── index.blade.php             ← Halaman Maps
│       ├── keluarga/
│       │   └── index.blade.php
│       └── components/
│           ├── sidebar.blade.php
│           ├── vaksin-card.blade.php
│           ├── status-badge.blade.php
│           └── faskes-card.blade.php
└── routes/
    ├── web.php
    └── api.php
```

---

## 🗄️ 3. Migrasi Database

### users
```sql
id, name, phone (unique), nik, tanggal_lahir, jenis_kelamin,
alamat, rt, rw, kelurahan, kecamatan, kota, provinsi,
foto_profil, otp_code, otp_expires_at, phone_verified_at,
remember_token, created_at, updated_at
```

### anggota_keluargas
```sql
id, user_id (FK), nama, nik, hubungan (kepala_keluarga|istri|anak|orang_tua|lainnya),
tanggal_lahir, jenis_kelamin, no_kartu_vaksin, created_at, updated_at
```

### vaksin (Master Data)
```sql
id, nama, kode, jenis (covid|influenza|hepatitis|mmr|difteri|tetanus|polio|dll),
dosis_total, interval_hari, usia_minimal_bulan, usia_maksimal_tahun,
deskripsi, produsen, created_at, updated_at
```

### riwayat_vaksins
```sql
id, anggota_keluarga_id (FK), vaksin_id (FK), faskes_id (FK),
nomor_dosis, tanggal_vaksin, nomor_batch, nama_tenaga_medis,
nomor_sertifikat, status (selesai|pending|batal),
catatan, created_at, updated_at
```

### jadwal_vaksins
```sql
id, anggota_keluarga_id (FK), vaksin_id (FK), faskes_id (FK),
tanggal_jadwal, jam_mulai, jam_selesai, nomor_antrian,
status (terdaftar|konfirmasi|selesai|batal),
reminder_sent_at, created_at, updated_at
```

### fasilitas_kesehatan
```sql
id, osm_id (nullable), satusehat_id (nullable),
nama, jenis (puskesmas|rs_umum|rs_swasta|klinik|posyandu|apotek),
alamat, kelurahan, kecamatan, kota, provinsi,
latitude, longitude, telepon, website, email,
jam_buka, jam_tutup, hari_operasional,
layanan_vaksin (boolean), rating, foto_url,
is_active, created_at, updated_at
```

### sertifikat_vaksins
```sql
id, anggota_keluarga_id (FK), vaksin_id (FK),
nomor_sertifikat (unique), qr_code_data, tanggal_terbit,
pdf_path, created_at, updated_at
```

---

## 🌱 4. Seeder Data

### VaksinSeeder.php — Master Jadwal Imunisasi Nasional (IDAI 2023)

```php
$vaksinData = [
    // COVID-19
    ['nama' => 'COVID-19 (Primer)', 'kode' => 'COVID-P', 'jenis' => 'covid',
     'dosis_total' => 2, 'interval_hari' => 28, 'usia_minimal_bulan' => 216, // 18 tahun
     'produsen' => 'Sinovac/Pfizer/AstraZeneca'],
    ['nama' => 'COVID-19 (Booster 1)', 'kode' => 'COVID-B1', 'jenis' => 'covid',
     'dosis_total' => 1, 'interval_hari' => 180, 'usia_minimal_bulan' => 216],
    ['nama' => 'COVID-19 (Booster 2)', 'kode' => 'COVID-B2', 'jenis' => 'covid',
     'dosis_total' => 1, 'interval_hari' => 180, 'usia_minimal_bulan' => 216],

    // Influenza
    ['nama' => 'Influenza (Tahunan)', 'kode' => 'FLU', 'jenis' => 'influenza',
     'dosis_total' => 1, 'interval_hari' => 365, 'usia_minimal_bulan' => 6],

    // Hepatitis B
    ['nama' => 'Hepatitis B (Neonatus)', 'kode' => 'HBV-0', 'jenis' => 'hepatitis',
     'dosis_total' => 1, 'interval_hari' => 0, 'usia_minimal_bulan' => 0],

    // BCG
    ['nama' => 'BCG (Tuberkulosis)', 'kode' => 'BCG', 'jenis' => 'bcg',
     'dosis_total' => 1, 'interval_hari' => 0, 'usia_minimal_bulan' => 0,
     'usia_maksimal_tahun' => 0], // bayi baru lahir

    // Polio
    ['nama' => 'Polio Oral (OPV)', 'kode' => 'OPV', 'jenis' => 'polio',
     'dosis_total' => 4, 'interval_hari' => 56],
    ['nama' => 'Polio Inaktif (IPV)', 'kode' => 'IPV', 'jenis' => 'polio',
     'dosis_total' => 2, 'interval_hari' => 56],

    // DPT (Difteri, Pertusis, Tetanus)
    ['nama' => 'DPT-HB-HiB (Pentavalen)', 'kode' => 'DPT', 'jenis' => 'difteri',
     'dosis_total' => 3, 'interval_hari' => 56, 'usia_minimal_bulan' => 2],
    ['nama' => 'DT (Difteri Tetanus) Booster', 'kode' => 'DT', 'jenis' => 'difteri',
     'dosis_total' => 2, 'interval_hari' => 365],

    // MMR / MR
    ['nama' => 'MR (Measles-Rubella)', 'kode' => 'MR', 'jenis' => 'mmr',
     'dosis_total' => 2, 'interval_hari' => 180, 'usia_minimal_bulan' => 9],

    // Pneumococcal
    ['nama' => 'PCV (Pneumococcal)', 'kode' => 'PCV', 'jenis' => 'pneumococcal',
     'dosis_total' => 3, 'interval_hari' => 56, 'usia_minimal_bulan' => 2],

    // Rotavirus
    ['nama' => 'Rotavirus', 'kode' => 'RV', 'jenis' => 'rotavirus',
     'dosis_total' => 2, 'interval_hari' => 56, 'usia_minimal_bulan' => 2,
     'usia_maksimal_tahun' => 1],

    // HPV
    ['nama' => 'HPV (Human Papillomavirus)', 'kode' => 'HPV', 'jenis' => 'hpv',
     'dosis_total' => 2, 'interval_hari' => 180, 'usia_minimal_bulan' => 120, // 10 tahun
     'usia_maksimal_tahun' => 13],

    // Typhoid
    ['nama' => 'Typhoid (Tifus)', 'kode' => 'TYP', 'jenis' => 'typhoid',
     'dosis_total' => 1, 'interval_hari' => 1095, 'usia_minimal_bulan' => 24],
];
```

### FaskesSeeder.php — Data Contoh (Seed awal, API akan mengisi lebih)

```php
// Seed 50 faskes contoh lintas kota untuk development
// Data nyata diambil real-time dari Overpass API + SATUSEHAT API
$faskesContoh = [
    [
        'nama' => 'Puskesmas Kecamatan Tebet',
        'jenis' => 'puskesmas',
        'kota' => 'Jakarta Selatan',
        'provinsi' => 'DKI Jakarta',
        'latitude' => -6.2088, 'longitude' => 106.8456,
        'telepon' => '021-8291234',
        'layanan_vaksin' => true,
    ],
    // ... tambahkan 49 lainnya per kota
];
```

---

## 🗺️ 5. Integrasi Maps & API Faskes

### A. OpenStreetMap + Leaflet.js (GRATIS, Tidak perlu API Key)

**Library yang diinstall:**
```bash
npm install leaflet leaflet.markercluster
# atau via CDN di blade template
```

**Overpass API Query untuk Faskes Kesehatan Indonesia:**
```javascript
// resources/js/faskes-map.js

const OVERPASS_URL = 'https://overpass-api.de/api/interpreter';

async function fetchFaskesNearby(lat, lng, radiusKm = 5) {
    const radius = radiusKm * 1000; // meter
    const query = `
        [out:json][timeout:30];
        (
            node["amenity"="hospital"](around:${radius},${lat},${lng});
            node["amenity"="clinic"](around:${radius},${lat},${lng});
            node["healthcare"="centre"](around:${radius},${lat},${lng});
            node["healthcare"="clinic"](around:${radius},${lat},${lng});
            node["healthcare"="hospital"](around:${radius},${lat},${lng});
            node["amenity"="health_post"](around:${radius},${lat},${lng});
            node["name"~"[Pp]uskesmas"](around:${radius},${lat},${lng});
            node["name"~"[Pp]osyandu"](around:${radius},${lat},${lng});
            node["name"~"[Kk]linik"](around:${radius},${lat},${lng});
            way["amenity"="hospital"](around:${radius},${lat},${lng});
            way["healthcare"="hospital"](around:${radius},${lat},${lng});
            way["name"~"[Pp]uskesmas"](around:${radius},${lat},${lng});
        );
        out body center;
    `;

    const response = await fetch(OVERPASS_URL, {
        method: 'POST',
        body: query,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    return await response.json();
}

// Mapping tag OSM → jenis faskes VaksinKu
function detectJenis(tags) {
    const name = (tags.name || '').toLowerCase();
    if (name.includes('posyandu')) return 'posyandu';
    if (name.includes('puskesmas')) return 'puskesmas';
    if (tags.amenity === 'hospital' || tags.healthcare === 'hospital') return 'rs_umum';
    if (tags.amenity === 'clinic' || tags.healthcare === 'clinic') return 'klinik';
    if (tags.healthcare === 'centre') return 'puskesmas';
    if (name.includes('apotek') || name.includes('farmasi')) return 'apotek';
    return 'klinik';
}
```

**OSM Tags yang Relevan untuk Indonesia:**
| Tag OSM | Nilai | Keterangan |
|---|---|---|
| `amenity` | `hospital` | Rumah sakit |
| `amenity` | `clinic` | Klinik umum |
| `amenity` | `doctors` | Praktik dokter |
| `amenity` | `pharmacy` | Apotek |
| `healthcare` | `hospital` | RS (standar baru) |
| `healthcare` | `clinic` | Klinik (standar baru) |
| `healthcare` | `centre` | Pusat kesehatan (Puskesmas) |
| `healthcare` | `vaccination_centre` | Pos vaksin khusus |
| `name` | `~Puskesmas` | Filter nama |
| `name` | `~Posyandu` | Filter nama |
| `operator` | `Kementerian Kesehatan` | Operator pemerintah |
| `opening_hours` | `Mo-Fr 08:00-16:00` | Jam operasional |
| `phone` / `contact:phone` | | Telepon |
| `website` / `contact:website` | | Website |
| `addr:street`, `addr:city` | | Alamat |

### B. SATUSEHAT API Kemenkes (Opsional, untuk data resmi)

**Endpoint Autentikasi:**
```
Sandbox:    https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken
Production: https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken
Grant Type: client_credentials
```

**Endpoint Master Sarana Index (MSI):**
```
GET https://api-satusehat.kemkes.go.id/masterdata/v1/Organization
    ?partOf={parent_org_id}
    &type={kode_jenis_faskes}   // puskesmas, klinik, rs-umum, etc.
    &active=true
    &_count=100
    &_page=1
```

**SatuSehatService.php:**
```php
<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SatuSehatService
{
    private string $baseUrl;
    private string $authUrl;
    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $this->baseUrl     = config('satusehat.base_url');
        $this->authUrl     = config('satusehat.auth_url');
        $this->clientId    = config('satusehat.client_id');
        $this->clientSecret = config('satusehat.client_secret');
    }

    public function getAccessToken(): string
    {
        return Cache::remember('satusehat_token', 3500, function () {
            $response = Http::asForm()->post("{$this->authUrl}/accesstoken", [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type'    => 'client_credentials',
            ]);
            return $response->json('access_token');
        });
    }

    public function getFaskesByKota(string $kotaId, int $page = 1): array
    {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/masterdata/v1/Organization", [
                'active'  => 'true',
                '_count'  => 100,
                '_page'   => $page,
                'address-city' => $kotaId,
            ]);
        return $response->json('entry', []);
    }

    public function getImmunizationData(string $patientId): array
    {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/fhir/r4/Immunization", [
                'patient' => $patientId,
                '_count'  => 50,
            ]);
        return $response->json('entry', []);
    }
}
```

**config/satusehat.php:**
```php
return [
    'base_url'      => env('SATUSEHAT_BASE_URL', 'https://api-satusehat-stg.dto.kemkes.go.id'),
    'auth_url'      => env('SATUSEHAT_AUTH_URL', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1'),
    'client_id'     => env('SATUSEHAT_CLIENT_ID'),
    'client_secret' => env('SATUSEHAT_CLIENT_SECRET'),
    'org_id'        => env('SATUSEHAT_ORG_ID'),
];
```

### C. API Rumah Sakit Indonesia (api.co.id) — Alternatif Gratis

```
GET https://api.co.id/v1/hospitals?province={kode_prov}&city={kode_kota}
# 3000+ RS dari SIRS Kemenkes, gratis, tidak perlu OAuth
```

---

## 🎨 6. Frontend Stack & Library

### Composer (PHP)
```bash
composer require laravel/breeze        # Auth scaffolding
composer require spatie/laravel-permission  # Role & Permission
composer require barryvdh/laravel-dompdf    # Generate PDF Sertifikat
composer require simplesoftwareio/simple-qrcode  # QR Code sertifikat
composer require maatwebsite/excel      # Export data Excel
composer require libphonenumber/libphonenumber  # Validasi nomor HP
```

### NPM (Frontend)
```bash
npm install -D tailwindcss postcss autoprefixer
npm install leaflet                    # Maps
npm install leaflet.markercluster      # Clustering marker faskes
npm install chart.js                   # Grafik cakupan vaksinasi
npx tailwindcss init -p
npm install axios                      # HTTP requests
npm install alpinejs                   # Reactive UI ringan (alternatif Vue)
npm install sweetalert2                # Alert/Modal yang cantik
npm install flatpickr                  # Date picker untuk jadwal
```

### Struktur Layout Utama (Tailwind CSS)

```html
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VaksinKu — {{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
<div class="d-flex" style="min-height: 100vh">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        @yield('content')
    </main>
</div>
</body>
</html>
```

```html
<!-- resources/views/components/sidebar.blade.php -->
<nav class="sidebar bg-primary-dark d-flex flex-column p-3"
     style="width: 220px; min-height: 100vh; background: #1E3A5F;">

    <div class="brand text-white fw-bold fs-5 mb-4">
        <span class="text-info">💉</span> VaksinKu
    </div>

    <ul class="nav nav-pills flex-column gap-1">
        @foreach([
            ['route' => 'dashboard', 'icon' => '🏠', 'label' => 'Beranda'],
            ['route' => 'riwayat', 'icon' => '📋', 'label' => 'Riwayat'],
            ['route' => 'cari', 'icon' => '🔍', 'label' => 'Cari Vaksin'],
            ['route' => 'keluarga', 'icon' => '👨‍👩‍👧', 'label' => 'Keluarga'],
            ['route' => 'pengaturan', 'icon' => '⚙️', 'label' => 'Pengaturan'],
        ] as $item)
        <li class="nav-item">
            <a href="{{ route($item['route']) }}"
               class="nav-link text-white {{ request()->routeIs($item['route'].'*') ? 'active bg-info' : '' }}">
                {{ $item['icon'] }} {{ $item['label'] }}
            </a>
        </li>
        @endforeach
    </ul>

    <!-- User Info Bottom -->
    <div class="mt-auto text-white-50 small">
        <img src="{{ Auth::user()->foto_profil ?? '/img/avatar.png' }}"
             class="rounded-circle me-2" width="32" height="32" alt="">
        {{ Auth::user()->name }}
    </div>
</nav>
```

---

## 🤖 7. Prompt untuk Google Stitch / AI Design Tool

Gunakan prompt ini di **Google Stitch**, **v0.dev**, **Figma AI**, atau **Uizard**:

### Prompt Halaman Dashboard
```
Design a clean healthcare web dashboard page in Indonesian language called "VaksinKu".
Color scheme: sidebar navy dark (#1E3A5F), primary blue (#1A56DB), green (#0E9F6E).
Tailwind CSS layout. Left sidebar 220px with icons and navigation.
Main content shows:
1. Top greeting card "Selamat Pagi, [Name]" with blue gradient background
2. Vaccine announcement banner (blue card, rounded corners, with image placeholder)
3. Three quick-action cards in a row: "Daftar Vaksin", "Lokasi Terdekat", "Informasi Vaksin"
4. Vaccination status card (right side) with green checkmark circle
5. Family members table at bottom with columns: Avatar, Nama, Hubungan, Status Vaksinasi, Aksi
Style: Modern, trustworthy, government-adjacent but friendly. Rounded corners, subtle shadows.
```

### Prompt Halaman Cari Vaksin (Maps)
```
Design a vaccine location finder page for Indonesian healthcare app "VaksinKu".
Left panel (40%): Search filters (location input, radius dropdown, facility type checkboxes: Puskesmas, RS Umum, Klinik, Posyandu), results list with cards showing: facility name, distance, address, phone, green "Daftar Sekarang" button, orange "Hampir Penuh" badge.
Right panel (60%): Full-height interactive map with blue location pins clustered.
Top: breadcrumb "VaksinKu / Cari Jadwal Vaksinasi"
Tailwind CSS, Leaflet.js map placeholder.
```

### Prompt Halaman Riwayat Vaksinasi
```
Design a vaccination history page for Indonesian app "VaksinKu".
Show timeline/list of vaccines with:
- Progress indicator at top (85% completion ring chart)
- Each vaccine item: colored left border (green=done, orange=scheduled, red=overdue),
  vaccine name, dose number, date, location, doctor name, "Unduh Sertifikat" button (green)
- Reminder card: "Ingatkan Keluarga?" with blue background
- "Butuh Bantuan?" support contact section
Timeline style, Tailwind accordion or card list.
```

### Prompt Backend Logic (ChatGPT/Claude)
```
You are a senior Laravel developer. Generate a complete backend architecture for a 
vaccination management web app called VaksinKu for Indonesia. 

Requirements:
- Auth via WhatsApp OTP (using Twilio or local gateway)
- Family group management (1 account = multiple family members)
- Vaccine schedule tracking with automatic reminder logic
- Integration with OpenStreetMap Overpass API to find nearby health facilities
- PDF certificate generation using DomPDF
- QR code for vaccine certificates
- FHIR R4 data structure compatibility with SATUSEHAT Kemenkes API

Generate:
1. All migrations with proper foreign keys and indexes
2. Eloquent models with relationships
3. Service classes for external APIs
4. Controller methods for all CRUD operations
5. API endpoints for the map features
6. Queue jobs for reminders
```

---

## 🐳 8. Docker Compose

### docker-compose.yml
```yaml
version: '3.9'

services:
  # ── Aplikasi Laravel ──────────────────────────────────────────
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    image: vaksinku-app:latest
    container_name: vaksinku_app
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    environment:
      - APP_ENV=${APP_ENV:-local}
      - APP_KEY=${APP_KEY}
      - APP_URL=${APP_URL:-http://localhost}
    networks:
      - vaksinku_net
    depends_on:
      - mysql
      - redis

  # ── Nginx Web Server ──────────────────────────────────────────
  nginx:
    image: nginx:1.25-alpine
    container_name: vaksinku_nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
      - ./docker/nginx/ssl:/etc/nginx/ssl  # Untuk HTTPS
    networks:
      - vaksinku_net
    depends_on:
      - app

  # ── MySQL Database ────────────────────────────────────────────
  mysql:
    image: mysql:8.0
    container_name: vaksinku_mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ${DB_DATABASE:-vaksinku}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD:-secret_root}
      MYSQL_USER: ${DB_USERNAME:-vaksinku_user}
      MYSQL_PASSWORD: ${DB_PASSWORD:-secret}
    volumes:
      - mysql_data:/var/lib/mysql
      - ./docker/mysql/init.sql:/docker-entrypoint-initdb.d/init.sql
    ports:
      - "3306:3306"
    networks:
      - vaksinku_net
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 10s
      timeout: 5s
      retries: 5

  # ── Redis (Cache + Queue + Session) ──────────────────────────
  redis:
    image: redis:7-alpine
    container_name: vaksinku_redis
    restart: unless-stopped
    command: redis-server --requirepass ${REDIS_PASSWORD:-redis_secret}
    volumes:
      - redis_data:/data
    ports:
      - "6379:6379"
    networks:
      - vaksinku_net
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 3s
      retries: 5

  # ── Laravel Queue Worker ──────────────────────────────────────
  queue:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    container_name: vaksinku_queue
    restart: unless-stopped
    working_dir: /var/www/html
    command: php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
    volumes:
      - .:/var/www/html
    networks:
      - vaksinku_net
    depends_on:
      - mysql
      - redis

  # ── Laravel Scheduler ─────────────────────────────────────────
  scheduler:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    container_name: vaksinku_scheduler
    restart: unless-stopped
    working_dir: /var/www/html
    command: >
      sh -c "while true; do
        php artisan schedule:run --verbose --no-interaction &
        sleep 60
      done"
    volumes:
      - .:/var/www/html
    networks:
      - vaksinku_net
    depends_on:
      - mysql
      - redis

  # ── phpMyAdmin (dev only) ─────────────────────────────────────
  phpmyadmin:
    image: phpmyadmin:latest
    container_name: vaksinku_pma
    restart: unless-stopped
    ports:
      - "8080:80"
    environment:
      PMA_HOST: mysql
      PMA_USER: ${DB_USERNAME:-vaksinku_user}
      PMA_PASSWORD: ${DB_PASSWORD:-secret}
    networks:
      - vaksinku_net
    profiles:
      - dev   # hanya aktif dengan: docker compose --profile dev up

volumes:
  mysql_data:
    driver: local
  redis_data:
    driver: local

networks:
  vaksinku_net:
    driver: bridge
```

### docker/php/Dockerfile
```dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev \
    libxml2-dev zip unzip libzip-dev \
    libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd \
       --with-freetype --with-jpeg \
    && docker-php-ext-install \
       pdo_mysql mbstring exif pcntl \
       bcmath gd zip intl opcache

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node & NPM, build assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm ci && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# PHP config
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

EXPOSE 9000
CMD ["php-fpm"]
```

### docker/nginx/default.conf
```nginx
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css application/json
               application/javascript text/xml;
    gzip_min_length 1000;

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    client_max_body_size 20M;
}
```

### docker/php/local.ini
```ini
upload_max_filesize = 20M
post_max_size = 20M
memory_limit = 256M
max_execution_time = 300
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2
```

---

## 🔄 9. CI/CD — GitHub Actions

### .github/workflows/deploy.yml
```yaml
name: VaksinKu CI/CD

on:
  push:
    branches: [main, staging]
  pull_request:
    branches: [main]

env:
  REGISTRY: ghcr.io
  IMAGE_NAME: ${{ github.repository }}/vaksinku-app

jobs:
  # ── Test ──────────────────────────────────────────────────────
  test:
    name: Run Tests
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_DATABASE: vaksinku_test
          MYSQL_ROOT_PASSWORD: root
        ports: ["3306:3306"]
        options: --health-cmd="mysqladmin ping" --health-interval=10s

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP 8.2
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: pdo_mysql, mbstring, zip, gd, redis
          coverage: xdebug

      - name: Install Composer dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Setup test environment
        run: |
          cp .env.testing .env
          php artisan key:generate

      - name: Run migrations
        run: php artisan migrate --env=testing

      - name: Run tests
        run: php artisan test --coverage --min=70

  # ── Build & Push Image ────────────────────────────────────────
  build:
    name: Build Docker Image
    runs-on: ubuntu-latest
    needs: test
    if: github.ref == 'refs/heads/main' || github.ref == 'refs/heads/staging'

    steps:
      - uses: actions/checkout@v4

      - name: Login to GitHub Container Registry
        uses: docker/login-action@v3
        with:
          registry: ${{ env.REGISTRY }}
          username: ${{ github.actor }}
          password: ${{ secrets.GITHUB_TOKEN }}

      - name: Build and push Docker image
        uses: docker/build-push-action@v5
        with:
          context: .
          file: ./docker/php/Dockerfile
          push: true
          tags: |
            ${{ env.REGISTRY }}/${{ env.IMAGE_NAME }}:latest
            ${{ env.REGISTRY }}/${{ env.IMAGE_NAME }}:${{ github.sha }}
          cache-from: type=gha
          cache-to: type=gha,mode=max

  # ── Deploy ────────────────────────────────────────────────────
  deploy:
    name: Deploy to Production
    runs-on: ubuntu-latest
    needs: build
    if: github.ref == 'refs/heads/main'
    environment: production

    steps:
      - name: Deploy via SSH
        uses: appleboy/ssh-action@v1.0.0
        with:
          host: ${{ secrets.DEPLOY_HOST }}
          username: ${{ secrets.DEPLOY_USER }}
          key: ${{ secrets.DEPLOY_SSH_KEY }}
          script: |
            cd /opt/vaksinku
            docker compose pull
            docker compose up -d --force-recreate app nginx queue scheduler
            docker compose exec -T app php artisan migrate --force
            docker compose exec -T app php artisan config:cache
            docker compose exec -T app php artisan route:cache
            docker compose exec -T app php artisan view:cache
            docker image prune -f
            echo "✅ Deploy berhasil: $(date)"
```

---

## ⚙️ 10. Environment Variables (.env)

```dotenv
APP_NAME=VaksinKu
APP_ENV=local
APP_KEY=             # generate: php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=mysql        # nama service docker
DB_PORT=3306
DB_DATABASE=vaksinku
DB_USERNAME=vaksinku_user
DB_PASSWORD=secret
DB_ROOT_PASSWORD=secret_root

# Redis
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=redis_secret

# Cache & Queue
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# WhatsApp OTP (pilih salah satu)
WA_GATEWAY=fonnte       # fonnte | twilio | wablas
FONNTE_TOKEN=           # https://fonnte.com (murah, lokal)
TWILIO_SID=
TWILIO_TOKEN=
TWILIO_FROM=

# SATUSEHAT Kemenkes API
SATUSEHAT_BASE_URL=https://api-satusehat-stg.dto.kemkes.go.id
SATUSEHAT_AUTH_URL=https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1
SATUSEHAT_CLIENT_ID=
SATUSEHAT_CLIENT_SECRET=
SATUSEHAT_ORG_ID=

# Maps (OpenStreetMap gratis, tidak perlu key)
# Jika pakai Google Maps:
GOOGLE_MAPS_API_KEY=   # Opsional

# Mail (untuk notifikasi email)
MAIL_MAILER=smtp
MAIL_HOST=mailpit      # dev: mailpit, prod: smtp.gmail.com / ses
MAIL_PORT=1025

# PDF & QR
DOMPDF_ENABLE_PHP=false
QR_CODE_SIZE=300
```

---

## 🚀 11. Perintah Setup Awal

```bash
# 1. Clone & masuk folder
git clone https://github.com/username/vaksinku.git && cd vaksinku

# 2. Copy environment
cp .env.example .env

# 3. Jalankan semua container
docker compose --profile dev up -d

# 4. Install dependencies di dalam container
docker compose exec app composer install
docker compose exec app npm install && npm run build

# 5. Setup aplikasi
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link

# 6. Akses aplikasi
# Web:        http://localhost
# phpMyAdmin: http://localhost:8080
# Mailpit:    http://localhost:8025

# Untuk production (tanpa dev tools)
docker compose up -d
```

---

## 📌 12. Referensi API & Sumber Data

| Sumber | URL | Keterangan |
|---|---|---|
| SATUSEHAT Platform | https://satusehat.kemkes.go.id/platform | Registrasi + docs API Kemenkes |
| SATUSEHAT Docs | https://satusehat.kemkes.go.id/platform/docs | Dokumentasi FHIR R4 |
| API RS Indonesia | https://api.co.id/api-rumah-sakit-indonesia/ | 3000+ RS dari SIRS Kemenkes, gratis |
| Overpass API | https://overpass-api.de | Data OSM (puskesmas, klinik, dll) |
| Overpass Turbo | https://overpass-turbo.eu | Test query Overpass interaktif |
| Data Satu Indonesia | https://katalog.data.go.id | Dataset pemerintah open data |
| Leaflet.js | https://leafletjs.com | Library maps open source |
| Leaflet Cluster | https://github.com/Leaflet/Leaflet.markercluster | Clustering marker |
| IDAI Jadwal Imunisasi | https://www.idai.or.id/artikel/seputar-kesehatan-anak/jadwal-imunisasi-idai | Referensi jadwal vaksin anak |
| Fonnte (WA Gateway) | https://fonnte.com | Gateway WhatsApp murah lokal |
```
