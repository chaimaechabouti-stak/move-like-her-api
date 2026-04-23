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
        $salleCasa = Salle::where('slug', 'casablanca-centre')->first();
        $salleRabat = Salle::where('slug', 'rabat-agdal')->first();

        $coaches = [
            [
                'email'            => 'sara.amrani@movelikeher.ma',
                'salle_slug'       => 'casablanca-centre',
                'specialite'       => 'Yoga & Pilates',
                'experience_annees'=> 6,
                'bio'              => 'Passionnée de yoga depuis 10 ans, Sara a obtenu sa certification RYT-500 à Bali. Elle transmet sa sérénité et son expertise à chacune de ses élèves.',
                'certifications'   => ['RYT-500 Yoga Alliance', 'Pilates Mat & Reformer', 'Méditation pleine conscience'],
                'cours_dispenses'  => ['yoga', 'etirements'],
                'photo_url'        => 'https://images.unsplash.com/photo-1594381898411-846e7d193883?w=400&q=80&fit=crop&auto=format',
            ],
            [
                'email'            => 'khadija.benali@movelikeher.ma',
                'salle_slug'       => 'casablanca-centre',
                'specialite'       => 'HIIT & Cross Training',
                'experience_annees'=> 5,
                'bio'              => 'Ex-athlète nationale, Khadija combine puissance et technique pour des séances intenses et efficaces. Ses cours HIIT sont parmi les plus demandés du club.',
                'certifications'   => ['CrossFit Level 2', 'HIIT Specialist', 'Nutrition Sportive'],
                'cours_dispenses'  => ['hiit', 'cross-training', 'full-body'],
                'photo_url'        => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&q=80&fit=crop&auto=format',
            ],
            [
                'email'            => 'nadia.elfassi@movelikeher.ma',
                'salle_slug'       => 'rabat-agdal',
                'specialite'       => 'Cardio & Danse Fitness',
                'experience_annees'=> 4,
                'bio'              => 'Nadia transforme chaque séance en moment de joie. Spécialisée en Zumba et Cardio Boxe, elle booste la motivation de ses membres avec son énergie contagieuse.',
                'certifications'   => ['Zumba Instructor B1', 'Cardio Boxe Pro', 'FFA Coaching'],
                'cours_dispenses'  => ['cardio-boxe', 'zumba', 'step'],
                'photo_url'        => 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=400&q=80&fit=crop&auto=format',
            ],
            [
                'email'            => 'yasmine.tahiri@movelikeher.ma',
                'salle_slug'       => 'rabat-agdal',
                'specialite'       => 'Musculation & Remise en forme',
                'experience_annees'=> 7,
                'bio'              => 'Coach personnelle certifiée, Yasmine accompagne chaque membre vers ses objectifs avec un programme sur mesure et un suivi rigoureux.',
                'certifications'   => ['BPJEPS APT', 'Personal Trainer NASM', 'Nutrition & Diététique sportive'],
                'cours_dispenses'  => ['caf', 'abdos', 'full-body'],
                'photo_url'        => 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=400&q=80&fit=crop&auto=format',
            ],
        ];

        foreach ($coaches as $data) {
            $user = User::where('email', $data['email'])->first();
            $salle = Salle::where('slug', $data['salle_slug'])->first();
            if (!$user || !$salle) continue;

            Coach::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'salle_id'          => $salle->id,
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
