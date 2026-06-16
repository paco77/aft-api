<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
        ['email' => 'admin@aftraining.com'],
        [
            'username' => 'admin',
            'name' => 'AF Training Admin',
            'password' => Hash::make('password123'),
            'role' => 'coach',
        ]
        );
    }
}
