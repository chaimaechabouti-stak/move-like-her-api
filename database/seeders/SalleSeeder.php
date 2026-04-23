<?php

namespace Database\Seeders;

use App\Models\Salle;
use App\Models\Ville;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    public function run(): void
    {
        $salles = [
            [
                'ville' => 'casablanca',
                'nom'   => 'Move Like Her Casablanca - Centre',
                'slug'  => 'casablanca-centre',
                'adresse' => '12 Bd Mohammed V, Casablanca 20000',
                'telephone' => '+212 522 00 00 01',
                'email'   => 'casa@movelikeher.ma',
                'horaires' => '6h - 22h',
                'image_url' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&q=75&fit=crop&auto=format',
                'description' => 'Notre club phare au cœur de Casablanca. Équipements premium, coaches certifiées et ambiance 100% féminine.',
            ],
            [
                'ville' => 'rabat',
                'nom'   => 'Move Like Her Rabat - Agdal',
                'slug'  => 'rabat-agdal',
                'adresse' => '5 Avenue Fal Ould Oumeir, Agdal, Rabat',
                'telephone' => '+212 537 00 00 02',
                'email'   => 'rabat@movelikeher.ma',
                'horaires' => '6h - 22h',
                'image_url' => 'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=800&q=75&fit=crop&auto=format',
                'description' => 'Votre espace bien-être à Agdal. Un cadre moderne et bienveillant pour atteindre vos objectifs.',
            ],
            [
                'ville' => 'marrakech',
                'nom'   => 'Move Like Her Marrakech',
                'slug'  => 'marrakech',
                'adresse' => 'Gueliz, Avenue Mohammed VI, Marrakech',
                'telephone' => '+212 524 00 00 03',
                'email'   => 'marrakech@movelikeher.ma',
                'horaires' => '7h - 21h',
                'image_url' => 'https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=800&q=75&fit=crop&auto=format',
                'description' => 'Au cœur de Gueliz, votre salle de sport féminine dans la ville ocre.',
            ],
            [
                'ville' => 'fes',
                'nom'   => 'Move Like Her Fès',
                'slug'  => 'fes',
                'adresse' => 'Route de Sefrou, Fès',
                'telephone' => '+212 535 00 00 04',
                'email'   => 'fes@movelikeher.ma',
                'horaires' => '7h - 21h',
                'image_url' => 'https://images.unsplash.com/photo-1593079831268-3381b0db4a77?w=800&q=75&fit=crop&auto=format',
                'description' => 'Un espace moderne et sécurisé pour les femmes de Fès et sa région.',
            ],
            [
                'ville' => 'tanger',
                'nom'   => 'Move Like Her Tanger',
                'slug'  => 'tanger',
                'adresse' => 'Avenue Med Tazi, Tanger',
                'telephone' => '+212 539 00 00 05',
                'email'   => 'tanger@movelikeher.ma',
                'horaires' => '6h30 - 22h',
                'image_url' => 'https://images.unsplash.com/photo-1558611848-73f7eb4001a1?w=800&q=75&fit=crop&auto=format',
                'description' => 'Move Like Her Tanger, votre refuge fitness avec vue sur la ville du détroit.',
            ],
            [
                'ville' => 'agadir',
                'nom'   => 'Move Like Her Agadir',
                'slug'  => 'agadir',
                'adresse' => 'Quartier Talborjt, Agadir',
                'telephone' => '+212 528 00 00 06',
                'email'   => 'agadir@movelikeher.ma',
                'horaires' => '7h - 21h30',
                'image_url' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=800&q=75&fit=crop&auto=format',
                'description' => 'Fitness et bien-être au soleil d\'Agadir. Rejoignez notre communauté du sud.',
            ],
        ];

        foreach ($salles as $data) {
            $ville = Ville::where('slug', $data['ville'])->first();
            unset($data['ville']);
            Salle::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['ville_id' => $ville->id])
            );
        }
    }
}
