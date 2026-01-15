<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'nip' => 'EMP001',
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@company.com',
                'jabatan' => 'Software Engineer',
                'departemen' => 'IT',
                'tanggal_masuk' => '2022-01-15',
                'status' => 'aktif'
            ],
            [
                'nip' => 'EMP002',
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@company.com',
                'jabatan' => 'Marketing Manager',
                'departemen' => 'Marketing',
                'tanggal_masuk' => '2021-06-10',
                'status' => 'aktif'
            ],
            [
                'nip' => 'EMP003',
                'nama' => 'Ahmad Ridwan',
                'email' => 'ahmad.ridwan@company.com',
                'jabatan' => 'Finance Staff',
                'departemen' => 'Finance',
                'tanggal_masuk' => '2023-03-20',
                'status' => 'aktif'
            ],
            [
                'nip' => 'EMP004',
                'nama' => 'Dewi Lestari',
                'email' => 'dewi.lestari@company.com',
                'jabatan' => 'HR Officer',
                'departemen' => 'HR',
                'tanggal_masuk' => '2022-08-05',
                'status' => 'aktif'
            ],
            [
                'nip' => 'EMP005',
                'nama' => 'Rudi Hartono',
                'email' => 'rudi.hartono@company.com',
                'jabatan' => 'Sales Executive',
                'departemen' => 'Sales',
                'tanggal_masuk' => '2021-11-12',
                'status' => 'aktif'
            ],
            [
                'nip' => 'EMP006',
                'nama' => 'Maya Sari',
                'email' => 'maya.sari@company.com',
                'jabatan' => 'Product Designer',
                'departemen' => 'Design',
                'tanggal_masuk' => '2023-01-08',
                'status' => 'aktif'
            ],
            [
                'nip' => 'EMP007',
                'nama' => 'Andi Wijaya',
                'email' => 'andi.wijaya@company.com',
                'jabatan' => 'Data Analyst',
                'departemen' => 'IT',
                'tanggal_masuk' => '2022-04-18',
                'status' => 'aktif'
            ],
            [
                'nip' => 'EMP008',
                'nama' => 'Rina Kusuma',
                'email' => 'rina.kusuma@company.com',
                'jabatan' => 'Customer Service',
                'departemen' => 'Customer Support',
                'tanggal_masuk' => '2021-09-25',
                'status' => 'aktif'
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}

