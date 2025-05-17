<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // Import the Product model

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Laptop',
            'price' => 800.00,
            'stock' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Product::create([
            'name' => 'Mouse',
            'price' => 25.00,
            'stock' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Product::create([
            'name' => 'Keyboard',
            'price' => 75.00,
            'stock' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}