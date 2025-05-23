<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Quique',
            'email' => 'admin@admin.com',
            'password' => Hash::make('AFasfasasf255581'),
            //            'verified_at' => Carbon::now(),
        ]);
    }
}
