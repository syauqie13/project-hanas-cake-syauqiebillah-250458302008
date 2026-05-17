<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cakes = [
            [
                'name' => 'Chocolate Cake',
                'price' => 120000,
                'stock' => 10,
                'discount' => 10,
                'description' => 'Rich chocolate sponge with ganache',
                'flavors' => ['chocolate'],
                'portions' => ['small', 'medium', 'large'],
            ],
            [
                'name' => 'Vanilla Butter Cake',
                'price' => 90000,
                'stock' => 15,
                'discount' => 0,
                'description' => 'Classic vanilla butter cake',
                'flavors' => ['vanilla'],
                'portions' => ['small', 'medium'],
            ],
        ];

        $category = Category::firstWhere('slug', 'cake');
        foreach ($cakes as $c) {
            Product::firstOrCreate(
                ['slug' => Str::slug($c['name'])],
                array_merge($c, [
                    'category_id' => $category ? $category->id : null,
                    'image' => null,
                    'is_po' => false,
                ])
            );
        }
    }
}
