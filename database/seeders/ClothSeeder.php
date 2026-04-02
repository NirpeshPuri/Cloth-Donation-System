<?php

namespace Database\Seeders;

use App\Models\Cloth;
use Illuminate\Database\Seeder;

class ClothSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cloth::create([
            'donor_id' => 1,
            'brand_id' => 1,
            'cloth_type_id' => 1,
            'size' => 'M',
            'image_path' => 'clothes/shirt.jpg',
            'quantity' => 5,
            'quality' => 'Good',
            'status' => 'available',
        ]);
    }
}
