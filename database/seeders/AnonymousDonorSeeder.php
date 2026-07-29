<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AnonymousDonorSeeder extends Seeder
{
    public function run()
    {
        // Create anonymous donor if it doesn't exist
        User::firstOrCreate(
            ['email' => 'anonymous@donation.com'],
            [
                'name' => 'Anonymous Donor',
                'password' => Hash::make('anonymous@123'),
                'phone' => 'N/A',
                'address' => 'N/A',
                'profile_photo' => null,
            ]
        );
    }
}
