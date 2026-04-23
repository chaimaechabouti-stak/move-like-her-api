<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            VilleSeeder::class,
            SalleSeeder::class,
            CoursSeeder::class,
            AbonnementSeeder::class,
            UserSeeder::class,
            CoachSeeder::class,
            SeanceSeeder::class,
        ]);
    }
}
