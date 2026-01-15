# HRMS + SPK AHP System

Sistem Enterprise Human Resource Management System (HRMS) dengan Sistem Pendukung Keputusan menggunakan metode Analytical Hierarchy Process (AHP) untuk menentukan karyawan terbaik.

## 🎯 Fitur Utama

### 1. Manajemen Data Karyawan
- CRUD karyawan lengkap
- Search & pagination
- Detail informasi karyawan
- Status management (Aktif, Cuti, Non-Aktif)

### 2. Dashboard Eksekutif
- Statistik real-time
- Grafik performa karyawan
- Grafik status karyawan
- Overview sistem

### 3. Sistem Pendukung Keputusan (AHP)
- **Input Kriteria**: 5 kriteria penilaian
  - Kualitas Kerja
  - Kehadiran
  - Kerjasama Tim
  - Inisiatif
  - Pengetahuan Pekerjaan
- **Matriks Perbandingan Berpasangan**: Interface intuitif dengan skala AHP 1-9
- **Perhitungan Otomatis**:
  - Normalisasi matriks
  - Eigen vector (bobot prioritas)
  - Consistency Index (CI)
  - Consistency Ratio (CR)
- **Validasi**: CR < 0.1 untuk memastikan konsistensi
- **Hasil Ranking**: Otomatis mengurutkan karyawan berdasarkan nilai akhir

### 4. Laporan Hasil Keputusan
- Ranking karyawan
- Nilai preferensi
- Rekomendasi (Sangat Layak / Layak / Cukup Layak / Tidak Layak)
- Badge visual untuk setiap kategori
- Export PDF (coming soon)

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────┐
│         React.js Frontend           │
│  - Modern UI dengan Ant Design      │
│  - Responsive Design                │
│  - Real-time Charts                 │
└──────────────┬──────────────────────┘
               │ REST API
               │ (JSON)
┌──────────────▼──────────────────────┐
│         Laravel Backend             │
│  - REST API dengan Sanctum          │
│  - Service Layer untuk AHP          │
│  - Validation & Security            │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│         SQLite Database             │
│  - Employee Data                    │
│  - AHP Sessions & Results           │
│  - Audit Logs                       │
└─────────────────────────────────────┘
```

## 🛠️ Teknologi

### Backend
- **Laravel 12** - PHP Framework
- **Laravel Sanctum** - API Authentication
- **SQLite** - Database
- **PHP 8.2+**

### Frontend
- **React.js 18** - UI Framework
- **Vite** - Build Tool
- **Ant Design** - UI Components
- **Recharts** - Data Visualization
- **Axios** - HTTP Client
- **React Router** - Routing

## 🚀 Quick Start

### Menggunakan Script Otomatis

```bash
chmod +x start.sh
./start.sh
```

Script ini akan:
1. Setup backend (composer install, migrate, seed)
2. Setup frontend (npm install)
3. Menjalankan kedua server secara bersamaan

### Manual Setup

#### Backend Setup

```bash
cd hrms-spk-backend

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Jalankan server
php artisan serve
```

#### Frontend Setup

```bash
cd hrms-frontend

# Install dependencies
npm install

# Jalankan development server
npm run dev
```

## 🔐 Login

Setelah seeding, gunakan akun berikut:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@hrms.com | password123 |
| HR Manager | hr@hrms.com | password123 |

## 📊 Data Sample

Database sudah terisi dengan:
- ✅ 8 Karyawan sample dari berbagai departemen
- ✅ 5 Kriteria penilaian
- ✅ Sub-kriteria untuk setiap kriteria
- ✅ 2 User (Admin & HR Manager)

## 🌐 Akses Aplikasi

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **API Documentation**: http://localhost:8000/api/*

## 📖 Dokumentasi

- [Integration Guide](./INTEGRATION_GUIDE.md) - Panduan lengkap integrasi backend & frontend
- [Frontend README](./hrms-frontend/README.md) - Dokumentasi frontend
- [Backend README](./hrms-spk-backend/README.md) - Dokumentasi backend

## 🎨 Tampilan Aplikasi

### 1. Login Page
- Design modern dengan gradient background
- Form validation
- Responsive layout

### 2. Dashboard
- 4 Kartu statistik utama
- Bar chart performa karyawan
- Pie chart status karyawan
- Real-time data

### 3. Data Karyawan
- Tabel dengan pagination
- Search functionality
- Modal form untuk CRUD
- Detail view

### 4. Analisis AHP
- **Step 1**: Buat sesi AHP baru
- **Step 2**: Input matriks perbandingan berpasangan
- **Step 3**: Proses perhitungan
- **Step 4**: Lihat hasil & validasi CR

### 5. Hasil Keputusan
- Tabel ranking dengan trophy icons
- Filter berdasarkan sesi
- Badge rekomendasi berwarna
- Export PDF button

## 🔄 Alur Kerja Sistem

```
1. Login → Dashboard
   ↓
2. Tambah/Edit Data Karyawan
   ↓
3. Buat Sesi AHP Baru
   ↓
4. Input Perbandingan Berpasangan Kriteria
   ↓
5. Sistem Hitung Otomatis:
   - Normalisasi matriks
   - Bobot prioritas
   - Consistency Ratio
   ↓
6. Validasi CR < 0.1
   ↓
7. Lihat Hasil Ranking
   ↓
8. Export Laporan (PDF)
```

## 🧮 Metode AHP

Sistem menggunakan **Analytical Hierarchy Process (AHP)** dengan langkah:

1. **Penyusunan Hierarki**: Tujuan → Kriteria → Alternatif
2. **Perbandingan Berpasangan**: Skala 1-9 Saaty
3. **Normalisasi Matriks**: Setiap elemen / jumlah kolom
4. **Eigen Vector**: Rata-rata baris matriks ternormalisasi
5. **Consistency Check**:
   - λmax, CI, CR
   - Valid jika CR < 0.1

## 🔒 Keamanan

- ✅ JWT Authentication dengan Laravel Sanctum
- ✅ CSRF Protection
- ✅ SQL Injection Prevention
- ✅ XSS Protection
- ✅ Input Validation
- ✅ Audit Logging

## 📱 Responsive Design

- Desktop (≥1200px)
- Tablet (768px - 1199px)
- Mobile (< 768px)

## 🐛 Troubleshooting

### Backend tidak bisa diakses
```bash
# Pastikan server berjalan
php artisan serve

# Check port 8000 tidak digunakan
lsof -i :8000
```

### Frontend tidak bisa connect ke backend
```bash
# Check .env file
VITE_API_URL=http://localhost:8000/api

# Restart frontend
npm run dev
```

### Database error
```bash
# Reset database
php artisan migrate:fresh --seed
```

### CORS error
- Pastikan `config/cors.php` sudah dikonfigurasi dengan benar
- Check `SANCTUM_STATEFUL_DOMAINS` di `.env`

## 🚧 Development

### Backend

```bash
# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### Frontend

```bash
# Development
npm run dev

# Build production
npm run build

# Preview production build
npm run preview
```

## 📝 License

MIT License

## 👥 Contributors

Sistem HRMS + SPK AHP - Enterprise HR Management System

---

**Catatan**: Sistem ini dibuat untuk tujuan pembelajaran dan dapat dikembangkan lebih lanjut sesuai kebutuhan.