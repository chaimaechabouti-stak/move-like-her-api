<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $fillable = [
        'cours_id', 'coach_id', 'salle_id',
        'jour', 'heure_debut', 'heure_fin',
        'places_max', 'active',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}
