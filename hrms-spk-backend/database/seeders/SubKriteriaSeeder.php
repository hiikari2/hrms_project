<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubKriteria;
use App\Models\Kriteria;

class SubKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $subKriteriaData = [
            'K1' => [
                ['nama' => 'Sangat Baik', 'nilai' => 5.00],
                ['nama' => 'Baik', 'nilai' => 4.00],
                ['nama' => 'Cukup', 'nilai' => 3.00],
                ['nama' => 'Kurang', 'nilai' => 2.00],
                ['nama' => 'Sangat Kurang', 'nilai' => 1.00],
            ],
            'K2' => [
                ['nama' => '>95% Hadir', 'nilai' => 5.00],
                ['nama' => '90-95% Hadir', 'nilai' => 4.00],
                ['nama' => '85-89% Hadir', 'nilai' => 3.00],
                ['nama' => '80-84% Hadir', 'nilai' => 2.00],
                ['nama' => '<80% Hadir', 'nilai' => 1.00],
            ],
            'K3' => [
                ['nama' => 'Sangat Kooperatif', 'nilai' => 5.00],
                ['nama' => 'Kooperatif', 'nilai' => 4.00],
                ['nama' => 'Cukup Kooperatif', 'nilai' => 3.00],
                ['nama' => 'Kurang Kooperatif', 'nilai' => 2.00],
                ['nama' => 'Tidak Kooperatif', 'nilai' => 1.00],
            ],
            'K4' => [
                ['nama' => 'Sangat Proaktif', 'nilai' => 5.00],
                ['nama' => 'Proaktif', 'nilai' => 4.00],
                ['nama' => 'Cukup Proaktif', 'nilai' => 3.00],
                ['nama' => 'Kurang Proaktif', 'nilai' => 2.00],
                ['nama' => 'Tidak Proaktif', 'nilai' => 1.00],
            ],
            'K5' => [
                ['nama' => 'Expert', 'nilai' => 5.00],
                ['nama' => 'Advanced', 'nilai' => 4.00],
                ['nama' => 'Intermediate', 'nilai' => 3.00],
                ['nama' => 'Beginner', 'nilai' => 2.00],
                ['nama' => 'Novice', 'nilai' => 1.00],
            ],
        ];

        foreach ($subKriteriaData as $kodeKriteria => $subKriteriaList) {
            $kriteria = Kriteria::where('kode', $kodeKriteria)->first();
            
            if ($kriteria) {
                foreach ($subKriteriaList as $subKriteria) {
                    SubKriteria::create([
                        'kriteria_id' => $kriteria->id,
                        'nama' => $subKriteria['nama'],
                        'nilai' => $subKriteria['nilai'],
                        'deskripsi' => 'Sub kriteria untuk ' . $kriteria->nama
                    ]);
                }
            }
        }
    }
}