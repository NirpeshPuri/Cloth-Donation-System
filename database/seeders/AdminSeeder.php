<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'name' => 'Thamel Cloth Donation Bank',
                'email' => 'thamel.donation@gmail.com',
                'password' => Hash::make('123456'),
                'phone' => '9800000001',
                'latitude' => 27.7172,
                'longitude' => 85.3240,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Baneshwor Cloth Donation Bank',
                'email' => 'baneshwor.donation@gmail.com',
                'password' => Hash::make('123456'),
                'phone' => '9800000002',
                'latitude' => 27.6880,
                'longitude' => 85.3350,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kalanki Cloth Donation Bank',
                'email' => 'kalanki.donation@gmail.com',
                'password' => Hash::make('123456'),
                'phone' => '9800000003',
                'latitude' => 27.6933,
                'longitude' => 85.2810,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bouddha Cloth Donation Bank',
                'email' => 'bouddha.donation@gmail.com',
                'password' => Hash::make('123456'),
                'phone' => '9800000004',
                'latitude' => 27.7215,
                'longitude' => 85.3616,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Koteshwor Cloth Donation Bank',
                'email' => 'koteshwor.donation@gmail.com',
                'password' => Hash::make('123456'),
                'phone' => '9800000005',
                'latitude' => 27.6780,
                'longitude' => 85.3498,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
