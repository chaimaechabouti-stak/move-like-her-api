<?php

namespace Database\Seeders;

use App\Models\Cours;
use Illuminate\Database\Seeder;

class CoursSeeder extends Seeder
{
    public function run(): void
    {
        $cours = [
            ['nom' => 'Yoga',               'slug' => 'yoga',             'duree' => '45 min', 'niveau' => 'Tous niveaux',   'calories' => '200-350', 'couleur' => '#f48fb1', 'description' => 'Renforcement musculaire par les postures. Améliore souplesse, force et sérénité.', 'image_url' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'HIIT',               'slug' => 'hiit',             'duree' => '30 min', 'niveau' => 'Intermédiaire',  'calories' => '400-600', 'couleur' => '#e91e8c', 'description' => 'High Intensity Interval Training — cardio intense pour brûler et sculpter.', 'image_url' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'Cardio Boxe',        'slug' => 'cardio-boxe',      'duree' => '45 min', 'niveau' => 'Tous niveaux',   'calories' => '450-600', 'couleur' => '#c2185b', 'description' => 'Inspiré de la boxe pour brûler des calories et améliorer l\'endurance.', 'image_url' => 'https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'Cross Training',     'slug' => 'cross-training',   'duree' => '45 min', 'niveau' => 'Intermédiaire',  'calories' => '400-550', 'couleur' => '#d81b60', 'description' => 'Entraînement complet et fonctionnel pour un corps athlétique et équilibré.', 'image_url' => 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'Cuisses Abdos Fessiers', 'slug' => 'caf',          'duree' => '45 min', 'niveau' => 'Tous niveaux',   'calories' => '300-400', 'couleur' => '#f06292', 'description' => 'Tonification ciblée des cuisses, abdominaux et fessiers.', 'image_url' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'Full Body',          'slug' => 'full-body',        'duree' => '45 min', 'niveau' => 'Tous niveaux',   'calories' => '350-500', 'couleur' => '#e91e8c', 'description' => 'Entraînement complet pour travailler tout le corps en une seule session.', 'image_url' => 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=600&q=75&fit=crop&auto=format'],
            ['nom' => "Bik'in",             'slug' => 'bikin',            'duree' => '45 min', 'niveau' => 'Tous niveaux',   'calories' => '400-600', 'couleur' => '#f48fb1', 'description' => 'Vélo en musique. Cardio intense dans une ambiance festive.', 'image_url' => 'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'Step',               'slug' => 'step',             'duree' => '30 min', 'niveau' => 'Tous niveaux',   'calories' => '300-450', 'couleur' => '#c2185b', 'description' => 'Cours chorégraphié en musique sur step. Coordination et cardio.', 'image_url' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'Abdos',              'slug' => 'abdos',            'duree' => '20 min', 'niveau' => 'Tous niveaux',   'calories' => '150-200', 'couleur' => '#d81b60', 'description' => 'Travail de la sangle abdominale pour un ventre tonique et une meilleure posture.', 'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'Étirements',         'slug' => 'etirements',       'duree' => '30 min', 'niveau' => 'Tous niveaux',   'calories' => '80-120',  'couleur' => '#f48fb1', 'description' => 'Améliorez votre souplesse et récupérez mieux après chaque séance.', 'image_url' => 'https://images.unsplash.com/photo-1552196563-55cd4e45efb3?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'Run',                'slug' => 'run',              'duree' => '45 min', 'niveau' => 'Intermédiaire',  'calories' => '350-500', 'couleur' => '#e91e8c', 'description' => 'Course en groupe encadrée par un coach. Dépassez vos limites en plein air.', 'image_url' => 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'Cardio Training',    'slug' => 'cardio-training',  'duree' => '45 min', 'niveau' => 'Intermédiaire',  'calories' => '400-550', 'couleur' => '#c2185b', 'description' => 'Entraînement intense pour brûler des calories et améliorer l\'endurance cardiovasculaire.', 'image_url' => 'https://images.unsplash.com/photo-1538805060514-97d9cc17730c?w=600&q=75&fit=crop&auto=format'],
            ['nom' => 'Zumba',              'slug' => 'zumba',            'duree' => '60 min', 'niveau' => 'Tous niveaux',   'calories' => '400-500', 'couleur' => '#f06292', 'description' => 'Danse fitness latine explosive. Brûle des calories en s\'amusant !', 'image_url' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600&q=75&fit=crop&auto=format'],
        ];

        foreach ($cours as $c) {
            Cours::firstOrCreate(['slug' => $c['slug']], $c);
        }
    }
}
