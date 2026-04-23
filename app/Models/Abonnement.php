<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    protected $fillable = [
        'nom', 'slug', 'prix_mensuel', 'prix_annuel',
        'fonctionnalites', 'populaire', 'couleur',
        'cta_texte', 'actif', 'ordre',
    ];

    protected function casts(): array
    {
        return [
            'fonctionnalites' => 'array',
            'populaire'       => 'boolean',
            'actif'           => 'boolean',
        ];
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }
}
