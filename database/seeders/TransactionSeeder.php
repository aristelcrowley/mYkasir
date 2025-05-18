<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();
        $users = User::all();

        if ($products->count() === 0 || $users->count() === 0) {
            $this->command->error('No products or users found. Please run the ProductSeeder and UserSeeder first.');
            return;
        }

        $firstUser = $users->first();
        foreach ($products as $product) {
            Transaction::create([
                'product_id' => $product->id,
                'user_id' => $firstUser->id,
                'quantity' => 1,
                'total_price' => $product->price,
            ]);
        }
    }
}