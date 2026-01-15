<?php

// File: database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin HRMS',
            'email' => 'admin@hrms.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'HR Manager',
            'email' => 'hr@hrms.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}