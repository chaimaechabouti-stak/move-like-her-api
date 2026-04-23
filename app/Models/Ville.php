<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    protected $fillable = ['nom', 'slug'];

    public function salles()
    {
        return $this->hasMany(Salle::class);
    }
}
