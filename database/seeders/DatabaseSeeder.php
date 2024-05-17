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
            TypeOfUserSeeder::class,
            StatusSeeder::class,
            ServiceSeeder::class,
            UserSeeder::class,
            AbilitySeeder::class,
            ProfileAbilitySeeder::class,
            EstablishmentSeeder::class,
            EstablishmentUserSeeder::class,
            EstablishmentServiceSeeder::class,
            ListSeeder::class,
        ]);
    }
}
