<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Donor User',
            'email' => 'donor@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'donor',
        ]);

        User::create([
            'name' => 'Receiver User',
            'email' => 'receiver@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'receiver',
        ]);
    }
}
