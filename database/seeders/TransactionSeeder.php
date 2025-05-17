<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product; 

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstProduct = DB::table('products')->first();
        $secondProduct = DB::table('products')->skip(1)->first();

        if ($firstProduct) {
            DB::table('transactions')->insert([
                'product_id' => $firstProduct->id,
                'quantity' => 2,
                'total_price' => $firstProduct->price * 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($secondProduct) {
            DB::table('transactions')->insert([
                'product_id' => $secondProduct->id,
                'quantity' => 1,
                'total_price' => $secondProduct->price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}