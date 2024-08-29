<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProfileSeeder::class,
            TypeOfPersonSeeder::class,
            StatusSeeder::class,
            UserSeeder::class,
            AbilitySeeder::class,
            ProfileAbilitySeeder::class,
            EstablishmentSeeder::class,
            EstablishmentUserSeeder::class,
            ServiceSeeder::class,
            ListSeeder::class,
        ]);
    }
}
