<?php

namespace Database\Seeders;

use App\Models\Abonnement;
use Illuminate\Database\Seeder;

class AbonnementSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'nom'            => 'Découverte',
                'slug'           => 'decouverte',
                'prix_mensuel'   => 500,
                'prix_annuel'    => 400,
                'populaire'      => false,
                'couleur'        => '#f48fb1',
                'cta_texte'      => 'Commencer',
                'ordre'          => 1,
                'fonctionnalites' => [
                    'Accès 1 salle',
                    '2 cours collectifs / semaine',
                    'Vestiaires & douches',
                    'Application Move Like Her',
                ],
            ],
            [
                'nom'            => 'Premium',
                'slug'           => 'premium',
                'prix_mensuel'   => 750,
                'prix_annuel'    => 600,
                'populaire'      => true,
                'couleur'        => '#e91e8c',
                'cta_texte'      => 'Choisir Premium',
                'ordre'          => 2,
                'fonctionnalites' => [
                    'Accès toutes les salles',
                    'Cours collectifs illimités',
                    'Vestiaires & douches premium',
                    '1 séance coaching / mois',
                    'Application Move Like Her',
                    'Accès zone cardio & muscu',
                ],
            ],
            [
                'nom'            => 'Elite',
                'slug'           => 'elite',
                'prix_mensuel'   => 1100,
                'prix_annuel'    => 880,
                'populaire'      => false,
                'couleur'        => '#c2185b',
                'cta_texte'      => "Rejoindre l'Elite",
                'ordre'          => 3,
                'fonctionnalites' => [
                    'Accès toutes les salles 24h/24',
                    'Cours collectifs illimités',
                    'Vestiaires VIP',
                    '4 séances coaching / mois',
                    'Programme nutrition personnalisé',
                    'Application Move Like Her Premium',
                    'Accès spa & relaxation',
                ],
            ],
        ];

        foreach ($plans as $p) {
            Abonnement::firstOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
