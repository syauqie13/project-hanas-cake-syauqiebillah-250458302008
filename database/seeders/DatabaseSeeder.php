<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin - Full Access
        User::create([
            'name' => 'Admin Hana\'s Cake',
            'email' => 'admin@hanascake.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Akun Karyawan - POS & Manajemen Produk
        User::create([
            'name' => 'Kasir Hana\'s Cake',
            'email' => 'kasir@hanascake.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'phone' => '081234567891',
        ]);

        // Akun Pelanggan Demo (opsional)
        User::create([
            'name' => 'Pelanggan Demo',
            'email' => 'pelanggan@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'phone' => '081234567892',
            'address' => 'Jl. No. 123, Jakarta',
        ]);

    }
}
