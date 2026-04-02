<?php

namespace Database\Seeders;

use App\Models\ClothType;
use Illuminate\Database\Seeder;

class ClothTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClothType::insert([
            ['name' => 'Shirt'],
            ['name' => 'Pant'],
            ['name' => 'Jacket'],
        ]);
    }
}
