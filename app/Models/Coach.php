<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $fillable = [
        'user_id', 'salle_id', 'specialite',
        'photo_url', 'bio', 'certifications',
        'cours_dispenses', 'experience_annees', 'active',
    ];

    protected function casts(): array
    {
        return [
            'certifications'   => 'array',
            'cours_dispenses'  => 'array',
            'active'           => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
