<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Cake', 'slug' => 'cake'],
            ['name' => 'Bread', 'slug' => 'bread'],
            ['name' => 'Cookies', 'slug' => 'cookies'],
            ['name' => 'Beverages', 'slug' => 'beverages'],
        ];

        foreach ($categories as $c) {
            Category::firstOrCreate(['slug' => $c['slug']], $c);
        }
    }
}
