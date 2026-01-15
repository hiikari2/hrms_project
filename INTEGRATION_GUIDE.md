# Panduan Integrasi Backend & Frontend HRMS + SPK AHP

## Arsitektur Sistem

```
┌─────────────────┐         ┌──────────────────┐
│   React.js      │   API   │   Laravel        │
│   (Frontend)    │ ◄─────► │   (Backend)      │
│   Port: 3000    │         │   Port: 8000     │
└─────────────────┘         └──────────────────┘
                                     │
                                     ▼
                            ┌──────────────────┐
                            │   SQLite         │
                            │   (Database)     │
                            └──────────────────┘
```

## Setup Backend (Laravel)

### 1. Install Dependencies

```bash
cd hrms-spk-backend
composer install
```

### 2. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database

```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed
```

### 4. Jalankan Server

```bash
php artisan serve
# Server berjalan di http://localhost:8000
```

## Setup Frontend (React.js)

### 1. Install Dependencies

```bash
cd hrms-frontend
npm install
```

### 2. Konfigurasi Environment

File `.env` sudah dibuat dengan konfigurasi:
```
VITE_API_URL=http://localhost:8000/api
```

### 3. Jalankan Development Server

```bash
npm run dev
# Server berjalan di http://localhost:3000
```

## API Endpoints

### Authentication

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/login` | Login user |
| POST | `/api/register` | Register user baru |
| POST | `/api/logout` | Logout user |
| GET | `/api/me` | Get user info |

### Dashboard

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/dashboard/stats` | Get statistik dashboard |
| GET | `/api/dashboard/charts` | Get data chart |

### Employees

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/employees` | Get semua karyawan |
| POST | `/api/employees` | Tambah karyawan |
| GET | `/api/employees/{id}` | Get detail karyawan |
| PUT | `/api/employees/{id}` | Update karyawan |
| DELETE | `/api/employees/{id}` | Hapus karyawan |

### AHP

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/ahp/kriteria` | Get semua kriteria |
| GET | `/api/ahp/sessions` | Get semua sesi AHP |
| POST | `/api/ahp/sessions` | Buat sesi AHP baru |
| POST | `/api/ahp/sessions/{id}/comparisons` | Simpan perbandingan |
| POST | `/api/ahp/sessions/{id}/calculate` | Hitung AHP |
| GET | `/api/ahp/sessions/{id}/results` | Get hasil AHP |

## Format Request & Response

### Login Request

```json
{
  "email": "admin@hrms.com",
  "password": "password123"
}
```

### Login Response

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin HRMS",
      "email": "admin@hrms.com"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

### Get Employees Response

```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "nip": "EMP001",
        "nama": "Budi Santoso",
        "email": "budi@company.com",
        "jabatan": "Software Engineer",
        "departemen": "IT",
        "tanggal_masuk": "2022-01-15",
        "status": "aktif"
      }
    ],
    "total": 8
  }
}
```

### AHP Calculation Response

```json
{
  "success": true,
  "data": {
    "weights": [0.3, 0.25, 0.2, 0.15, 0.1],
    "consistency_ratio": 0.05,
    "is_consistent": true
  }
}
```

## Alur Proses AHP

1. **Buat Sesi AHP**
   - Frontend: Form input nama sesi, deskripsi, periode
   - Backend: Simpan ke `ahp_sessions` table

2. **Input Perbandingan Berpasangan**
   - Frontend: Matriks perbandingan dengan dropdown skala AHP
   - Backend: Simpan ke `pairwise_comparisons` table

3. **Perhitungan AHP**
   - Frontend: Trigger calculation
   - Backend:
     - Build matriks perbandingan
     - Normalisasi matriks
     - Hitung eigen vector (bobot prioritas)
     - Hitung Consistency Ratio
     - Validasi CR < 0.1

4. **Hasil Ranking**
   - Frontend: Tampilkan ranking karyawan
   - Backend: Return dari `ahp_results` table

## Troubleshooting

### CORS Error

Pastikan CORS sudah dikonfigurasi di Laravel:
```php
// config/cors.php
'paths' => ['api/*'],
'allowed_origins' => ['*'],
```

### Authentication Error

Pastikan token disimpan di localStorage dan dikirim di header:
```javascript
Authorization: Bearer {token}
```

### Database Error

```bash
php artisan migrate:fresh --seed
```

## Testing

### Test Backend API

```bash
# Test login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@hrms.com","password":"password123"}'
```

### Test Frontend

1. Buka http://localhost:3000
2. Login dengan admin@hrms.com / password123
3. Test semua fitur

## Akun Default

Setelah seeding, akun berikut tersedia:

| Email | Password | Role |
|-------|----------|------|
| admin@hrms.com | password123 | Admin |
| hr@hrms.com | password123 | HR Manager |

## Data Seeder

- 8 Karyawan sample
- 5 Kriteria (Kualitas Kerja, Kehadiran, Kerjasama Tim, Inisiatif, Pengetahuan)
- Sub-kriteria untuk setiap kriteria

## Production Deployment

### Backend

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Frontend

```bash
npm run build
# Deploy folder 'dist' ke hosting
```

## Support

Jika ada masalah, pastikan:
- ✅ Backend berjalan di port 8000
- ✅ Frontend berjalan di port 3000
- ✅ Database sudah dimigrate dan seeded
- ✅ CORS dikonfigurasi dengan benar
- ✅ Token authentication berfungsi
