<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Wisata - Tourism content manager
        $adminWisata = User::create([
            'name' => 'Admin Wisata',
            'email' => 'wisata@jepara.go.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);
        $adminWisata->assignRole('admin_wisata');
        $this->command->info('Admin Wisata created: wisata@jepara.go.id / password');

        // Admin Berita - News and events manager
        $adminBerita = User::create([
            'name' => 'Admin Berita',
            'email' => 'berita@jepara.go.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);
        $adminBerita->assignRole('admin_berita');
        $this->command->info('Admin Berita created: berita@jepara.go.id / password');
    }
}
