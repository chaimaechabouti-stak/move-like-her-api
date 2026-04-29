<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Database\Seeder;

class CoachSeeder extends Seeder
{
    public function run(): void
    {
        $coaches = [
            [
                'email'            => 'sara.amrani@movelikeher.ma',
                'salle_slug'       => 'casablanca-centre',
                'ville'            => 'Casablanca',
                'specialite'       => 'Yoga & Pilates',
                'experience_annees'=> 6,
                'bio'              => 'Passionnée de yoga depuis 10 ans, Sara a obtenu sa certification RYT-500 à Bali. Elle transmet sa sérénité et son expertise à chacune de ses élèves.',
                'certifications'   => ['RYT-500 Yoga Alliance', 'Pilates Mat & Reformer', 'Méditation pleine conscience'],
                'cours_dispenses'  => ['yoga', 'etirements'],
                'photo_url'        => 'https://tse3.mm.bing.net/th/id/OIP.pu4NAq0QbIu--Hx6KKuGcgHaE8?pid=Api&h=220&P=0',
            ],
            [
                'email'            => 'khadija.benali@movelikeher.ma',
                'salle_slug'       => 'casablanca-centre',
                'ville'            => 'Rabat',
                'specialite'       => 'HIIT & Cross Training',
                'experience_annees'=> 5,
                'bio'              => 'Ex-athlète nationale, Khadija combine puissance et technique pour des séances intenses et efficaces. Ses cours HIIT sont parmi les plus demandés du club.',
                'certifications'   => ['CrossFit Level 2', 'HIIT Specialist', 'Nutrition Sportive'],
                'cours_dispenses'  => ['hiit', 'cross-training', 'full-body'],
                'photo_url'        => 'https://tse2.mm.bing.net/th/id/OIP.NSnk2rAT7hITm2pcCegqYgHaHa?pid=Api&h=220&P=0',
            ],
            [
                'email'            => 'nadia.elfassi@movelikeher.ma',
                'salle_slug'       => 'rabat-agdal',
                'ville'            => 'Marrakech',
                'specialite'       => 'Cardio & Danse Fitness',
                'experience_annees'=> 4,
                'bio'              => 'Nadia transforme chaque séance en moment de joie. Spécialisée en Zumba et Cardio Boxe, elle booste la motivation de ses membres avec son énergie contagieuse.',
                'certifications'   => ['Zumba Instructor B1', 'Cardio Boxe Pro', 'FFA Coaching'],
                'cours_dispenses'  => ['cardio-boxe', 'zumba', 'step'],
                'photo_url'        => 'https://tse1.mm.bing.net/th/id/OIP.6AEanFzonJiLfMWg1ygwKgHaF7?pid=Api&h=220&P=0',
            ],
            [
                'email'            => 'yasmine.tahiri@movelikeher.ma',
                'salle_slug'       => 'rabat-agdal',
                'ville'            => 'Agadir',
                'specialite'       => 'Musculation & Remise en forme',
                'experience_annees'=> 7,
                'bio'              => 'Coach personnelle certifiée, Yasmine accompagne chaque membre vers ses objectifs avec un programme sur mesure et un suivi rigoureux.',
                'certifications'   => ['BPJEPS APT', 'Personal Trainer NASM', 'Nutrition & Diététique sportive'],
                'cours_dispenses'  => ['caf', 'abdos', 'full-body'],
                'photo_url'        => 'https://tse3.mm.bing.net/th/id/OIP.9qF1U6-yNZFoYfEWPHCWVAHaE7?pid=Api&h=220&P=0',
            ],
            [
                'email'            => 'imane.mansouri@movelikeher.ma',
                'salle_slug'       => 'casablanca-centre',
                'ville'            => 'Fès',
                'specialite'       => 'Step & Danse',
                'experience_annees'=> 4,
                'bio'              => 'Imane transforme chaque cours de Step en une véritable fête. Sa bonne humeur et son dynamisme sont contagieux.',
                'certifications'   => ['Zumba Instructor B1', 'Step Aerobic Pro', 'BPJEPS AGFF'],
                'cours_dispenses'  => ['step', 'zumba', 'cardio-training'],
                'photo_url'        => 'https://tse1.mm.bing.net/th/id/OIP.ynjMj3cu68ia2qfJ8i7XXAHaEo?pid=Api&h=220&P=0',
            ],
            [
                'email'            => 'rania.chraibi@movelikeher.ma',
                'salle_slug'       => 'casablanca-centre',
                'ville'            => 'Tanger',
                'specialite'       => 'CAF & Renforcement musculaire',
                'experience_annees'=> 5,
                'bio'              => 'Rania croit en la force de chaque femme. Spécialisée en renforcement musculaire, elle sculpte les corps avec bienveillance et exigence.',
                'certifications'   => ['Diplômée STAPS', 'CAF Expert', 'Personal Trainer'],
                'cours_dispenses'  => ['caf', 'abdos', 'full-body'],
                'photo_url'        => 'https://tse3.mm.bing.net/th/id/OIP.dxvWVIaZPONkUhbmpdUKgwHaFb?pid=Api&h=220&P=0',
            ],
            [
                'email'            => 'salma.idrissi@movelikeher.ma',
                'salle_slug'       => 'rabat-agdal',
                'ville'            => 'Tanger',
                'specialite'       => 'Bien-être & Étirements',
                'experience_annees'=> 3,
                'bio'              => 'Salma guide ses élèves vers une récupération optimale. Elle enseigne que le repos et l\'étirement sont aussi importants que l\'effort.',
                'certifications'   => ['Certifiée Pilates', 'Yoga Yin RYT-200', 'Stretching Pro'],
                'cours_dispenses'  => ['etirements', 'yoga'],
                'photo_url'        => 'https://tse1.mm.bing.net/th/id/OIP.gC3nYzdgMWu3TcqVnYdMngHaE8?pid=Api&h=220&P=0',
            ],
        ];

        foreach ($coaches as $data) {
            $user = User::where('email', $data['email'])->first();
            $salle = Salle::where('slug', $data['salle_slug'])->first();
            if (!$user || !$salle) continue;

            Coach::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'salle_id'          => $salle->id,
                    'ville'             => $data['ville'],
                    'specialite'        => $data['specialite'],
                    'experience_annees' => $data['experience_annees'],
                    'bio'               => $data['bio'],
                    'certifications'    => $data['certifications'],
                    'cours_dispenses'   => $data['cours_dispenses'],
                    'photo_url'         => $data['photo_url'],
                ]
            );
        }
    }
}
