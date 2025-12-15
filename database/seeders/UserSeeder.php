<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membuat akun anggota.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Rizka Khumaida',
            'email' => 'anggota@example.com',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
        ]);
    }
}
