# HRMS + SPK AHP - Frontend

Sistem Manajemen Sumber Daya Manusia (HRMS) dengan Sistem Pendukung Keputusan menggunakan metode Analytical Hierarchy Process (AHP).

## Teknologi

- React.js 18
- Vite
- Ant Design
- Recharts
- Axios
- React Router DOM

## Instalasi

```bash
# Install dependencies
npm install

# Jalankan development server
npm run dev

# Build untuk production
npm run build
```

## Konfigurasi

Buat file `.env` dan sesuaikan dengan backend Laravel:

```
VITE_API_URL=http://localhost:8000/api
```

## Fitur

1. **Dashboard**
   - Statistik karyawan
   - Grafik performa
   - Grafik status karyawan

2. **Data Karyawan**
   - CRUD karyawan
   - Search & pagination
   - Detail karyawan

3. **Analisis AHP**
   - Stepper wizard untuk proses AHP
   - Input perbandingan berpasangan
   - Perhitungan otomatis
   - Validasi Consistency Ratio

4. **Hasil Keputusan**
   - Ranking karyawan
   - Rekomendasi
   - Export PDF

## Akun Default

```
Email: admin@hrms.com
Password: password123
```

## Struktur Folder

```
src/
├── components/          # Komponen reusable
│   ├── Layout/         # Layout components
│   └── ProtectedRoute.jsx
├── contexts/           # React contexts
│   └── AuthContext.jsx
├── pages/              # Halaman aplikasi
│   ├── Login.jsx
│   ├── Dashboard.jsx
│   ├── Employees.jsx
│   ├── AHP.jsx
│   └── Results.jsx
├── services/           # API services
│   ├── api.js
│   ├── authService.js
│   ├── employeeService.js
│   ├── ahpService.js
│   └── dashboardService.js
├── App.jsx
└── main.jsx
```

## Port

- Frontend: http://localhost:3000
- Backend API: http://localhost:8000/api
