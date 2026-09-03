<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@store.pk'],
            [
                'name' => 'Store Admin',
                'phone' => '+92 300 1234567',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Ahmed Khan',
                'phone' => '+92 301 7654321',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'city' => 'Lahore',
                'address' => 'House 12, Street 5, Model Town',
                'email_verified_at' => now(),
            ]
        );
    }
}
