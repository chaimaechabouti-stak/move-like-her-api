<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@movelikeher.ma'],
            [
                'name'     => 'Admin',
                'prenom'   => 'Move Like Her',
                'role'     => 'admin',
                'password' => Hash::make('Admin@2026!'),
            ]
        );

        // Coaches
        $coaches = [
            ['name' => 'Amrani',   'prenom' => 'Sara',    'email' => 'sara.amrani@movelikeher.ma'],
            ['name' => 'Benali',   'prenom' => 'Khadija', 'email' => 'khadija.benali@movelikeher.ma'],
            ['name' => 'El Fassi', 'prenom' => 'Nadia',   'email' => 'nadia.elfassi@movelikeher.ma'],
            ['name' => 'Tahiri',   'prenom' => 'Yasmine', 'email' => 'yasmine.tahiri@movelikeher.ma'],
            ['name' => 'Mansouri', 'prenom' => 'Imane',   'email' => 'imane.mansouri@movelikeher.ma'],
            ['name' => 'Chraibi',  'prenom' => 'Rania',   'email' => 'rania.chraibi@movelikeher.ma'],
            ['name' => 'Idrissi',  'prenom' => 'Salma',   'email' => 'salma.idrissi@movelikeher.ma'],
        ];

        foreach ($coaches as $coach) {
            User::firstOrCreate(
                ['email' => $coach['email']],
                array_merge($coach, [
                    'role'     => 'coach',
                    'password' => Hash::make('Coach@2026!'),
                ])
            );
        }

        // Membres test
        User::firstOrCreate(
            ['email' => 'membre@movelikeher.ma'],
            [
                'name'     => 'Alaoui',
                'prenom'   => 'Fatima',
                'role'     => 'membre',
                'password' => Hash::make('Membre@2026!'),
            ]
        );
    }
}
