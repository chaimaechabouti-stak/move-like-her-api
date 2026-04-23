<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = [
        'nom', 'slug', 'ville_id', 'adresse',
        'telephone', 'email', 'horaires',
        'image_url', 'description', 'active',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function coaches()
    {
        return $this->hasMany(Coach::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }
}
