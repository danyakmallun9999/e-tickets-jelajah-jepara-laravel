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
        $admin = User::create([
            'name' => 'Admin Pariwisata',
            'email' => 'admin@jepara.go.id',
            'password' => Hash::make('adminwisata'),
            'email_verified_at' => now(),
            'is_admin' => true, // Keep for backward compatibility
        ]);

        // Assign super_admin role
        $admin->assignRole('super_admin');

        $this->command->info('Super Admin created: admin@jepara.go.id');
    }
}
