<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    protected $fillable = [
        'nom', 'slug', 'description', 'duree',
        'niveau', 'calories', 'image_url', 'couleur', 'actif',
    ];

    protected function casts(): array
    {
        return ['actif' => 'boolean'];
    }

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
