# AGENT.md — VaksinKu

> **Model**: Gemini (via Antigravity IDE)  
> **Proyek**: VaksinKu — Platform Vaksinasi Web untuk Indonesia  
> **Stack**: Laravel 11 · Tailwind CSS · MySQL 8 · Redis · Leaflet.js · Docker  
> **MCP**: Figma Developer Mode  
> **Agent Root**: `.agents/`

---

## 🧭 Siapa Kamu

Kamu adalah agen pengembang **full-stack senior** yang ditugaskan eksklusif untuk proyek **VaksinKu**. Platform ini membantu masyarakat Indonesia memantau jadwal vaksinasi keluarga, menemukan fasilitas kesehatan terdekat lewat peta, dan menyimpan sertifikat vaksin digital.

Tugasmu adalah **mengeksekusi — bukan menjelaskan**. Tulis kode yang langsung bisa dipakai.

---

## 📁 Peta Proyek

```
vaksinku/                          ← Root Laravel
├── AGENT.md                       ← File ini (dibaca otomatis Antigravity)
├── .agents/                       ← Konfigurasi agent
│   ├── agentconfig.json           ← Settings Antigravity
│   ├── instructions/
│   │   └── system-prompt.md       ← Instruksi detail agent
│   └── skills/                    ← Referensi teknis per domain
│       ├── laravel-vaksinku.md
│       ├── blade-tailwind.md
│       ├── maps-faskes.md
│       ├── figma-developer.md
│       ├── docker-ops.md
│       ├── whatsapp-auth.md
│       ├── sertifikat-pdf.md
│       ├── testing.md
│       └── satusehat-api.md
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Services/
├── database/{migrations,seeders,factories}
├── resources/views/{layouts,components}
├── docker/
└── docker-compose.yml
```

---

## ⚙️ Stack Teknis

| Layer | Teknologi |
|---|---|
| Backend | PHP 8.2 + Laravel 11 |
| Frontend | Tailwind CSS + Alpine.js + Vite |
| Peta | Leaflet.js + OpenStreetMap |
| Database | MySQL 8 + Redis 7 |
| Auth | WhatsApp OTP (Fonnte) |
| PDF | DomPDF + SimpleQRCode |
| Infra | Docker + GitHub Actions |
| Design | Figma (MCP Developer Mode) |
| API Eks | SATUSEHAT Kemenkes · Overpass OSM · Nominatim |

---

## 🎨 Design Tokens CSS

Ini adalah token warna resmi proyek — gunakan **selalu**, jangan hardcode hex:

```css
:root {
  --vk-primary:        #1A56DB;
  --vk-primary-dark:   #1E3A5F;
  --vk-primary-light:  #EBF5FF;
  --vk-success:        #0E9F6E;
  --vk-success-light:  #ECFDF5;
  --vk-warning:        #FF5A1F;
  --vk-warning-light:  #FFF3ED;
  --vk-danger:         #E02424;
  --vk-danger-light:   #FEF2F2;
  --vk-bg:             #F9FAFB;
  --vk-surface:        #FFFFFF;
  --vk-border:         #E5E7EB;
  --vk-text:           #111928;
  --vk-text-muted:     #6B7280;
  --vk-sidebar:        #1E3A5F;
  --vk-radius:         0.625rem;
  --vk-radius-lg:      1rem;
  --vk-shadow:         0 1px 3px rgba(0,0,0,0.08);
  --vk-shadow-md:      0 4px 6px -1px rgba(0,0,0,0.1);
  --vk-sidebar-w:      220px;
}
```

---

## 🗺️ Halaman & Routes

| Halaman | Route | View |
|---|---|---|
| Landing / Login | `/` | `auth/landing.blade.php` |
| OTP Verify | `/otp/verify` | `auth/otp.blade.php` |
| Dashboard | `/dashboard` | `dashboard/index.blade.php` |
| Riwayat Vaksin | `/riwayat` | `riwayat/index.blade.php` |
| Cari Vaksin (Peta) | `/cari` | `cari/index.blade.php` |
| Manajemen Keluarga | `/keluarga` | `keluarga/index.blade.php` |
| Pengaturan | `/pengaturan` | `pengaturan/index.blade.php` |
| Verifikasi Sertifikat | `/verify/{nomor}` | `sertifikat/verify.blade.php` |

---

## 🗄️ Model & Relasi Utama

```
User
 └── hasMany → AnggotaKeluarga
                └── hasMany → RiwayatVaksin → belongsTo Vaksin
                └── hasMany → JadwalVaksin  → belongsTo Vaksin
                └── hasMany → JadwalVaksin  → belongsTo FasilitasKesehatan
                └── hasMany → SertifikatVaksin

Vaksin (master data — seed dari IDAI 2023)
FasilitasKesehatan (cache dari Overpass OSM + SATUSEHAT sync)
```

---

## 📏 Konvensi Wajib

### PHP
- PSR-12 · typed properties · return types selalu ada
- Logika bisnis → **Service class**, bukan Controller
- Validasi → **FormRequest**, bukan Controller
- Tidak ada `dd()`, `var_dump()`, credentials hardcode

### Database
- Tabel: `snake_case plural`
- FK: `{model}_id` + `->constrained()->cascadeOnDelete()`
- Selalu: `timestamps()` + `softDeletes()` pada tabel utama

### Frontend
- Teks UI: **Bahasa Indonesia**
- Variabel kode: **English**
- Tailwind CSS utility-first, CSS custom hanya jika utility tidak cukup
- Format tanggal: `translatedFormat('d F Y')` dengan locale `id`

### API Response (selalu format ini)
```json
{ "success": true, "data": {}, "message": "OK" }
{ "success": false, "error": "Pesan error", "code": 422 }
```

---

## 🗓️ Jadwal Vaksin Nasional (Referensi Cepat)

| Kode | Vaksin | Dosis | Usia Mulai |
|---|---|---|---|
| BCG | BCG | 1 | Lahir |
| HBV-0 | Hepatitis B | 1 | Lahir |
| OPV | Polio Oral | 4 | 0 bln |
| DPT | DPT-HB-HiB | 3 | 2 bln |
| MR | Measles-Rubella | 2 | 9 bln |
| PCV | Pneumococcal | 3 | 2 bln |
| RV | Rotavirus | 2 | 2 bln |
| HPV | HPV | 2 | 10 thn |
| TYP | Typhoid | 1 | 2 thn |
| FLU | Influenza | 1/thn | 6 bln |
| COVID-P | COVID Primer | 2 | 18 thn |
| COVID-B1 | COVID Booster | 1 | 18 thn |

---

## 🔐 Environment Variables Wajib

```dotenv
APP_KEY=                          # php artisan key:generate
APP_URL=http://localhost

DB_HOST=mysql                     # nama service docker
DB_DATABASE=vaksinku
DB_USERNAME=vaksinku_user
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PASSWORD=redis_secret

# WhatsApp (gunakan 'log' untuk dev)
WA_GATEWAY=log                    # log | fonnte | twilio
FONNTE_TOKEN=

# SATUSEHAT Kemenkes
SATUSEHAT_BASE_URL=https://api-satusehat-stg.dto.kemkes.go.id
SATUSEHAT_CLIENT_ID=
SATUSEHAT_CLIENT_SECRET=

# Figma MCP
FIGMA_ACCESS_TOKEN=
FIGMA_FILE_KEY=
```

---

## ⚡ Quick Commands

```bash
# Setup awal
docker compose --profile dev up -d
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link

# Development harian
docker compose exec app php artisan <command>
docker compose exec app npm run dev

# Test
docker compose exec app php artisan test --coverage --min=70

# Sync faskes dari SATUSEHAT
docker compose exec app php artisan vaksinku:sync-faskes --kota=Jakarta
```

---

## 🗂️ Skills yang Tersedia

Referensi teknis detail ada di `.agents/skills/`:

| Domain | File Skill |
|---|---|
| Backend Laravel | `laravel-vaksinku.md` |
| Views Blade + Tailwind CSS | `blade-tailwind.md` |
| Peta & Faskes (Leaflet + OSM) | `maps-faskes.md` |
| Figma MCP Developer Mode | `figma-developer.md` |
| Docker & Deployment | `docker-ops.md` |
| WhatsApp OTP Auth | `whatsapp-auth.md` |
| Sertifikat PDF & QR | `sertifikat-pdf.md` |
| Testing PHPUnit | `testing.md` |
| SATUSEHAT API Kemenkes | `satusehat-api.md` |

Baca skill yang relevan **sebelum** mengerjakan task di domain tersebut.
