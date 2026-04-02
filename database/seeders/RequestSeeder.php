<?php

namespace Database\Seeders;

use App\Models\ClothRequest;
use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClothRequest::create([
            'receiver_id' => 2,
            'cloth_id' => 1,
            'quantity' => 2,
            'status' => 'pending',
        ]);
    }
}
