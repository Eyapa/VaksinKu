# VaksinKu - Vaccination Management Platform

## 📋 Overview

VaksinKu adalah platform manajemen vaksinasi berbasis Laravel 11 + Tailwind CSS dengan fitur:
- ✅ Autentikasi Email/Password (Session-based)
- ✅ Manajemen keluarga (multiple family members)
- ✅ Riwayat vaksinasi & sertifikat digital
- ✅ Pencarian faskes terdekat (via Maps)
- ✅ RBAC (Role-Based Access Control) dengan Spatie Permission
- ✅ Dashboard dengan statistik vaksinasi
- ✅ Integrasi dengan SATUSEHAT API (optional)

---

## 🎯 Phase Implementation Status

### Phase 1: Foundation ✓ (COMPLETE)
- [x] Laravel 11 project initialization
- [x] Database structure (SQLite for dev, MySQL for docker)
- [x] Authentication (Email/Password with Breeze)
- [x] RBAC setup (Spatie Permission)
- [x] Models & Migrations creation
- [x] Seeders with sample data
- [x] Basic testing & security audit

### Phase 2: Core Features (IN PROGRESS)
- [ ] Dashboard page (Logic & Data)
- [ ] Riwayat (History) page (Logic & Data)
- [ ] Keluarga (Family) management (Logic & Data)
- [x] Pengaturan (Settings) page
- [x] Layout components & sidebar

### Phase 3: Maps & APIs (PENDING)
- [ ] Maps integration (Leaflet.js)
- [ ] Faskes API endpoints
- [ ] Overpass API integration
- [ ] Real-time facility search

### Phase 4: Certificates (LOW PRIORITY)
- [ ] PDF generation (DomPDF)
- [ ] QR code generation
- [ ] Certificate download feature

### Phase 5: Reminders & Notifications (PENDING)
- [ ] Queue jobs
- [ ] Email notifications
- [ ] WhatsApp notifications (Fonnte)
- [ ] Scheduler for automatic reminders

### Phase 6: Testing & QA (ONGOING)
- [ ] Unit tests
- [ ] Feature tests
- [ ] Security audit
- [ ] Performance optimization

---

## 🚀 Quick Start

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite3 (for development)
- Docker & Docker Compose (for production)

### Setup Phase 1 Foundation

```bash
# Navigate to project
cd /workspaces/vaccination-laravel

# Run setup script
bash setup-phase1.sh
```

This will:
1. ✅ Create Laravel directory structure
2. ✅ Install Composer dependencies
3. ✅ Install Breeze (Email/Password auth)
4. ✅ Install Spatie Permission (RBAC)
5. ✅ Install NPM dependencies
6. ✅ Run migrations & seeders
7. ✅ Build frontend assets

### Development Server

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Watch frontend assets
npm run dev

# Access application
http://localhost:8000
```

### Docker (Production)

```bash
# Copy environment file
cp .env.example .env.docker

# Start containers
docker compose up -d

# Run migrations inside container
docker compose exec app php artisan migrate --seed

# Access application
http://localhost
```

---

## 📁 Project Structure

```
/workspaces/vaccination-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/          ← Authentication controllers
│   │   │   └── Api/           ← API controllers
│   │   ├── Middleware/        ← Custom middleware
│   │   └── Requests/          ← Form request validation
│   ├── Models/                ← Eloquent models
│   ├── Services/              ← Business logic services
│   ├── Jobs/                  ← Queued jobs
│   ├── Mail/                  ← Mailable classes
│   ├── Notifications/         ← Notification classes
│   └── Policies/              ← Authorization policies
├── database/
│   ├── migrations/            ← Database migrations
│   ├── seeders/               ← Data seeders
│   └── factories/             ← Factories for testing
├── resources/
│   ├── views/                 ← Blade templates
│   │   ├── layouts/           ← Master layouts
│   │   ├── auth/              ← Auth pages
│   │   ├── dashboard/         ← Dashboard pages
│   │   ├── components/        ← Reusable components
│   │   ├── riwayat/           ← History pages
│   │   ├── keluarga/          ← Family management
│   │   ├── cari/              ← Maps/search
│   │   └── pengaturan/        ← Settings
│   ├── css/                   ← Stylesheets
│   └── js/                    ← JavaScript
├── routes/
│   ├── web.php                ← Web routes
│   ├── api.php                ← API routes
│   └── auth.php               ← Auth routes (Breeze)
├── config/
│   ├── app.php
│   ├── database.php
│   ├── permission.php         ← Spatie Permission config
│   └── ...
├── tests/
│   ├── Feature/               ← Feature tests
│   └── Unit/                  ← Unit tests
├── docker/
│   ├── php/
│   │   ├── Dockerfile
│   │   └── local.ini
│   ├── nginx/
│   │   └── default.conf
│   └── mysql/
│       └── init/
├── storage/
│   ├── app/                   ← User uploaded files
│   ├── logs/                  ← Application logs
│   └── framework/             ← Framework caches
└── public/                    ← Web root

```

---

## 🗄️ Database Schema

### Core Tables

**users**
- id, name, email (unique), password, phone, nik, tanggal_lahir, jenis_kelamin
- alamat, rt, rw, kelurahan, kecamatan, kota, provinsi
- foto_profil, email_verified_at, timestamps

**anggota_keluarga**
- id, user_id (FK), nama, nik, hubungan, tanggal_lahir, jenis_kelamin
- no_kartu_vaksin, timestamps

**vaksin** (Master Data)
- id, nama, kode, jenis, dosis_total, interval_hari
- usia_minimal_bulan, usia_maksimal_tahun, deskripsi, produsen
- timestamps

**riwayat_vaksin**
- id, anggota_keluarga_id (FK), vaksin_id (FK), faskes_id (FK)
- nomor_dosis, tanggal_vaksin, nomor_batch, nama_tenaga_medis
- nomor_sertifikat, status (selesai|pending|batal), catatan
- timestamps

**jadwal_vaksin**
- id, anggota_keluarga_id (FK), vaksin_id (FK), faskes_id (FK)
- tanggal_jadwal, jam_mulai, jam_selesai, nomor_antrian
- status (terdaftar|konfirmasi|selesai|batal), reminder_sent_at
- timestamps

**fasilitas_kesehatan**
- id, osm_id, satusehat_id, nama, jenis, alamat, kelurahan
- kecamatan, kota, provinsi, latitude, longitude
- telepon, website, email, jam_buka, jam_tutup, hari_operasional
- layanan_vaksin (boolean), rating, foto_url, is_active
- timestamps

**sertifikat_vaksin**
- id, anggota_keluarga_id (FK), vaksin_id (FK), riwayat_vaksin_id (FK)
- nomor_sertifikat (unique), qr_code_data, tanggal_terbit
- pdf_path, timestamps

---

## 🔐 Authentication & Authorization

### Authentication
- **Type**: Session-based (Breeze)
- **Method**: Email & Password
- **Features**:
  - Email verification
  - Password reset
  - Remember me functionality

### Authorization (RBAC)
- **Package**: Spatie Laravel Permission
- **Roles**: admin, user, doctor, health_facility_staff
- **Permissions**: 
  - view_dashboard
  - manage_family
  - view_vaccination_history
  - download_certificate
  - manage_appointments
  - create_vaccination_record

---

## 🎨 Color Scheme

```
Primary Blue:        #1A56DB
Navy Dark (Sidebar): #1E3A5F
Success Green:       #0E9F6E
Warning Orange:      #FF5A1F
Danger Red:          #E02424
Background Light:    #F9FAFB
```

---

## 📦 Dependencies

### PHP Packages (Composer)
- Laravel Framework 11
- Laravel Breeze (Auth scaffold)
- Spatie Permission (RBAC)
- DomPDF (PDF generation)
- Simple QR Code (QR codes)
- Excel Export (Maatwebsite)
- Phone Number Utils (libphonenumber)
- HTTP Client (Guzzle)

### NPM Packages
- Tailwind CSS
- Leaflet.js (Maps)
- Leaflet MarkerCluster
- Chart.js (Graphs)
- Alpine.js (Reactive UI)
- Axios (HTTP)
- SweetAlert2 (Modals)
- Flatpickr (Date picker)

---

## 🧪 Testing

### Run Tests

```bash
# All tests
php artisan test

# Only feature tests
php artisan test --filter=Feature

# Only unit tests
php artisan test --filter=Unit

# With coverage
php artisan test --coverage
```

### Test Coverage Target: > 70%

---

## 🔒 Security Checklist

- [ ] Input validation on all forms
- [ ] CSRF token protection
- [ ] XSS prevention
- [ ] SQL injection prevention (use Eloquent)
- [ ] Rate limiting on auth endpoints
- [ ] Password hashing (bcrypt)
- [ ] Environment variables not exposed
- [ ] HTTPS enabled (production)
- [ ] Security headers configured

---

## 📝 Documentation

- `implementation.md` - Full implementation roadmap
- `task.md` - Detailed task breakdown
- `review.md` - QA & review checklist

---

## 🤝 Development Guidelines

### Code Style
- PSR-12 PHP Standard
- 4-space indentation
- Meaningful variable names

### Git Workflow
```bash
# Create feature branch
git checkout -b feature/task-name

# Commit with descriptive messages
git commit -m "feat: add user dashboard page"

# Push and create PR
git push origin feature/task-name
```

### Branch Naming
- `feature/` - New features
- `fix/` - Bug fixes
- `refactor/` - Code improvements
- `test/` - Test additions

---

## 📞 Support

For issues or questions:
1. Check `review.md` for known issues
2. Review `task.md` for current progress
3. Refer to `implementation.md` for architecture

---

## 📄 License

MIT License - See LICENSE file for details

---

**Last Updated**: 2026-06-05  
**Current Phase**: 1 - Foundation (IN PROGRESS)

