<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\Cours;
use App\Models\Salle;
use App\Models\Seance;
use App\Models\User;
use Illuminate\Database\Seeder;

class SeanceSeeder extends Seeder
{
    public function run(): void
    {
        $salle = Salle::where('slug', 'casablanca-centre')->first();
        if (!$salle) return;

        $planning = [
            ['jour' => 'Lundi',    'cours' => 'yoga',          'debut' => '07:00', 'fin' => '07:45', 'coach_email' => 'sara.amrani@movelikeher.ma'],
            ['jour' => 'Lundi',    'cours' => 'hiit',           'debut' => '09:00', 'fin' => '09:30', 'coach_email' => 'khadija.benali@movelikeher.ma'],
            ['jour' => 'Lundi',    'cours' => 'full-body',      'debut' => '18:30', 'fin' => '19:15', 'coach_email' => 'khadija.benali@movelikeher.ma'],
            ['jour' => 'Mardi',    'cours' => 'cardio-boxe',    'debut' => '07:30', 'fin' => '08:15', 'coach_email' => 'nadia.elfassi@movelikeher.ma'],
            ['jour' => 'Mardi',    'cours' => 'caf',            'debut' => '10:00', 'fin' => '10:45', 'coach_email' => 'yasmine.tahiri@movelikeher.ma'],
            ['jour' => 'Mardi',    'cours' => 'zumba',          'debut' => '19:00', 'fin' => '20:00', 'coach_email' => 'nadia.elfassi@movelikeher.ma'],
            ['jour' => 'Mercredi', 'cours' => 'cross-training', 'debut' => '08:00', 'fin' => '08:45', 'coach_email' => 'khadija.benali@movelikeher.ma'],
            ['jour' => 'Mercredi', 'cours' => 'yoga',           'debut' => '17:30', 'fin' => '18:15', 'coach_email' => 'sara.amrani@movelikeher.ma'],
            ['jour' => 'Jeudi',    'cours' => 'bikin',          'debut' => '07:00', 'fin' => '07:45', 'coach_email' => 'nadia.elfassi@movelikeher.ma'],
            ['jour' => 'Jeudi',    'cours' => 'abdos',          'debut' => '09:30', 'fin' => '09:50', 'coach_email' => 'yasmine.tahiri@movelikeher.ma'],
            ['jour' => 'Jeudi',    'cours' => 'hiit',           'debut' => '18:30', 'fin' => '19:00', 'coach_email' => 'khadija.benali@movelikeher.ma'],
            ['jour' => 'Vendredi', 'cours' => 'step',           'debut' => '08:00', 'fin' => '08:30', 'coach_email' => 'nadia.elfassi@movelikeher.ma'],
            ['jour' => 'Vendredi', 'cours' => 'etirements',     'debut' => '10:30', 'fin' => '11:00', 'coach_email' => 'sara.amrani@movelikeher.ma'],
            ['jour' => 'Vendredi', 'cours' => 'full-body',      'debut' => '19:00', 'fin' => '19:45', 'coach_email' => 'khadija.benali@movelikeher.ma'],
            ['jour' => 'Samedi',   'cours' => 'yoga',           'debut' => '09:00', 'fin' => '09:45', 'coach_email' => 'sara.amrani@movelikeher.ma'],
            ['jour' => 'Samedi',   'cours' => 'zumba',          'debut' => '10:30', 'fin' => '11:30', 'coach_email' => 'nadia.elfassi@movelikeher.ma'],
            ['jour' => 'Samedi',   'cours' => 'cardio-training','debut' => '16:00', 'fin' => '16:45', 'coach_email' => 'khadija.benali@movelikeher.ma'],
        ];

        foreach ($planning as $s) {
            $cours = Cours::where('slug', $s['cours'])->first();
            $coach = null;
            if ($s['coach_email']) {
                $user = User::where('email', $s['coach_email'])->first();
                $coach = $user ? Coach::where('user_id', $user->id)->first() : null;
            }

            if (!$cours) continue;

            Seance::firstOrCreate(
                [
                    'cours_id' => $cours->id,
                    'salle_id' => $salle->id,
                    'jour'     => $s['jour'],
                    'heure_debut' => $s['debut'],
                ],
                [
                    'coach_id'   => $coach?->id,
                    'heure_fin'  => $s['fin'],
                    'places_max' => 20,
                ]
            );
        }
    }
}
