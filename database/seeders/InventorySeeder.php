<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Tepung Terigu', 'type' => 'bahan_baku', 'unit' => 'pack', 'stock' => 50, 'unit_price' => 20000, 'description' => 'Tepung serbaguna untuk kue.'],
            ['name' => 'Gula Pasir', 'type' => 'bahan_baku', 'unit' => 'pack', 'stock' => 40, 'unit_price' => 15000, 'description' => 'Gula untuk pemanis.'],
            ['name' => 'Telur Ayam', 'type' => 'bahan_baku', 'unit' => 'pcs', 'stock' => 200, 'unit_price' => 1500, 'description' => 'Telur ayam lokal.'],
            ['name' => 'Mentega', 'type' => 'bahan_baku', 'unit' => 'pack', 'stock' => 20, 'unit_price' => 60000, 'description' => 'Mentega kualitas bagus.'],
        ];

        foreach ($items as $i) {
            Inventory::firstOrCreate(['name' => $i['name']], $i);
        }
    }
}
