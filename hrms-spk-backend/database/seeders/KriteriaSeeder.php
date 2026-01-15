<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $kriteriaList = [
            [
                'kode' => 'K1',
                'nama' => 'Kualitas Kerja',
                'deskripsi' => 'Penilaian terhadap hasil kerja dan pencapaian target',
                'urutan' => 1,
                'is_active' => true
            ],
            [
                'kode' => 'K2',
                'nama' => 'Kehadiran',
                'deskripsi' => 'Tingkat kehadiran dan disiplin waktu karyawan',
                'urutan' => 2,
                'is_active' => true
            ],
            [
                'kode' => 'K3',
                'nama' => 'Kerjasama Tim',
                'deskripsi' => 'Kemampuan bekerja sama dengan rekan kerja',
                'urutan' => 3,
                'is_active' => true
            ],
            [
                'kode' => 'K4',
                'nama' => 'Inisiatif',
                'deskripsi' => 'Proaktif dalam menyelesaikan masalah dan mengambil tindakan',
                'urutan' => 4,
                'is_active' => true
            ],
            [
                'kode' => 'K5',
                'nama' => 'Pengetahuan Pekerjaan',
                'deskripsi' => 'Pemahaman dan keahlian teknis dalam bidang pekerjaan',
                'urutan' => 5,
                'is_active' => true
            ]
        ];

        foreach ($kriteriaList as $kriteria) {
            Kriteria::create($kriteria);
        }
    }
}
