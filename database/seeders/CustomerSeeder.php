<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat beberapa pelanggan demo. Jika ada User pelanggan, tautkan.
        $user = User::firstWhere('email', 'pelanggan@example.com');

        $customers = [
            [
                'name' => 'Budi Santoso',
                'address' => 'Jl. Melati No.1, Jakarta',
                'phone' => '081200000001',
                'detail_address' => 'Perumahan Melati Blok A',
            ],
            [
                'name' => 'Siti Aminah',
                'address' => 'Jl. Mawar No.2, Bandung',
                'phone' => '081200000002',
                'detail_address' => 'Apartemen Mawar Lt.3',
            ],
        ];

        foreach ($customers as $c) {
            $attrs = $c;
            if ($user) {
                $attrs['user_id'] = $user->id;
            }
            Customer::firstOrCreate([
                'phone' => $c['phone']
            ], $attrs);
        }
    }
}
