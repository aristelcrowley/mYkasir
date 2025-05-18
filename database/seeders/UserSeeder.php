<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Aristel',
            'email' => 'aristel@gmail.com',
            'password' => Hash::make('aristel23'),
        ]);

        User::create([
            'name' => 'Crowley',
            'email' => 'crowley@gmail.com',
            'password' => Hash::make('crowley123'),
        ]);
    }
}