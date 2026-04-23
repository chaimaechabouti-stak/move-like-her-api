<?php

namespace Database\Seeders;

use App\Models\Ville;
use Illuminate\Database\Seeder;

class VilleSeeder extends Seeder
{
    public function run(): void
    {
        $villes = [
            ['nom' => 'Casablanca',  'slug' => 'casablanca'],
            ['nom' => 'Rabat',       'slug' => 'rabat'],
            ['nom' => 'Marrakech',   'slug' => 'marrakech'],
            ['nom' => 'Fès',         'slug' => 'fes'],
            ['nom' => 'Tanger',      'slug' => 'tanger'],
            ['nom' => 'Agadir',      'slug' => 'agadir'],
        ];

        foreach ($villes as $v) {
            Ville::firstOrCreate(['slug' => $v['slug']], $v);
        }
    }
}
