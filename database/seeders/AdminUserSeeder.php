<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// Panggil model User dan class Hash
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah user dengan email ini sudah ada atau belum
        // untuk mencegah duplikasi jika seeder dijalankan berkali-kali.
        User::firstOrCreate(
            ['email' => 'admin@aplikasi.com'], // Kunci untuk mencari
            [
                'name' => 'Admin Aplikasi',           // Ganti dengan nama yang Anda mau
                'password' => Hash::make('password123'), // GANTI DENGAN PASSWORD AMAN ANDA
            ]
        );
    }
}