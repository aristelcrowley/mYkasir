<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        if ($users->count() === 0) {
            $this->command->error('No users found. Please run the UserSeeder first.');
            return;
        }

        $firstUser = $users->first();

        Product::create([
            'name' => "Laptop",
            'price' => 12000000,
            'stock' => 10,
            'user_id' => $firstUser->id,
        ]);

        Product::create([
            'name' => "Mouse",
            'price' => 300000,
            'stock' => 50,
            'user_id' => $firstUser->id,
        ]);

        Product::create([
            'name' => "Keyboard",
            'price' => 800000,
            'stock' => 30,
            'user_id' => $firstUser->id,
        ]);
    }
}
