<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder user kalau ada
        // $this->call(UserSeeder::class);

        // Jalankan seeder partnership
        $this->call(PartnershipProposalSeeder::class);

        $this->call([
        UserSeeder::class,
    ]);

    }

}

