<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Desa',
            'email' => 'admin@mayonglor.id',
            'password' => Hash::make('adminmayong'),
            'email_verified_at' => now(),
        ]);
    }
}
