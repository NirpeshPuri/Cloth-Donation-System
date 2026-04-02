<?php

namespace Database\Seeders;

use App\Models\Donation;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Donation::create([
            'donor_id' => 1,
            'receiver_id' => 2,
            'cloth_id' => 1,
            'quantity' => 2,
            'status' => 'completed',
            'date_of_donation' => now(),
        ]);
    }
}
